<?php

namespace App\Http\Controllers\BackEnd\Event;

use App\Exports\BookingExport;
use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Earning;
use App\Models\Event;
use App\Models\Event\Booking;
use App\Models\Event\EventContent;
use App\Models\Event\Ticket;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventBookingController extends Controller
{
  public function index(Request $request)
  {
    $bookingId = $paymentStatus = null;
    $eventIds = [];
    if ($request->filled('booking_id')) {
      $bookingId = $request['booking_id'];
    }

    if ($request->filled('event_title')) {
      $event_contents = EventContent::where('title', 'like', '%' . $request->event_title . '%')->get();
      foreach ($event_contents as $event_content) {
        if (!in_array($event_content->event_id, $eventIds)) {
          array_push($eventIds, $event_content->event_id);
        }
      }
    }

    if ($request->filled('status')) {
      $paymentStatus = $request['status'];
    }


    $bookings = Booking::when($bookingId, function ($query) use ($bookingId) {
      return $query->where('booking_id', 'like', '%' . $bookingId . '%');
    })->when($paymentStatus, function ($query, $paymentStatus) {
      return $query->where('paymentStatus', '=', $paymentStatus);
    })
      ->when($eventIds, function ($query) use ($eventIds) {
        return $query->whereIn('event_id', $eventIds);
      })
      ->orderByDesc('id')
      ->paginate(10);

    return view('backend.event.booking.index', compact('bookings'));
  }
  //updatePaymentStatus
  public function updatePaymentStatus(Request $request, $id)
  {
    $booking = Booking::where('id', $id)->first();

    if ($request['payment_status'] == 'completed') {
      $booking->update([
        'paymentStatus' => 'completed'
      ]);

      $earning = Earning::first();
      $earning->total_revenue = $earning->total_revenue + ($booking->price + $booking->tax);

      if ($booking->organizer_id != null) {
        $earning->total_earning = $earning->total_earning + ($booking->tax + $booking->commission);
      } else {
        $earning->total_earning = $earning->total_earning + $booking['price'] + $booking->tax;
      }

      $earning->save();

      $invoice = $this->generateInvoice($booking);

      $booking->update([
        'invoice' => $invoice
      ]);

      $bookingInfo = $booking;

      //storeTransaction
      $bookingInfo['paymentStatus'] = 1;
      $bookingInfo['transcation_type'] = 1;

      storeTranscation($bookingInfo);

      //store amount to organizer
      $organizerData['organizer_id'] = $booking->organizer_id;
      $organizerData['price'] = $booking->price;
      $organizerData['commission'] = $booking->commission;
      $organizerData['organizer_id'] = $booking->organizer_id;
      storeOrganizer($organizerData);

      //unlink qr code
      @unlink(public_path('assets/admin/qrcodes/') . $booking->booking_id . '.svg');
      //end unlink qr code

      $this->sendMail($request, $booking, 'Booking approved');
    } else if ($request['payment_status'] == 'pending') {
      $booking->update([
        'paymentStatus' => 'pending'
      ]);
    } else {
      // dd($booking->event_id);
      $event = Event::where('id', $booking->event_id)->first();
      if ($event) {
        if ($event->event_type == 'online') {
          $ticket = Ticket::where('event_id', $event->id)->first();
          if ($ticket) {
            if ($ticket->ticket_available_type == 'limited') {
              $ticket->ticket_available = $ticket->ticket_available + $booking->quantity;
              $ticket->save();
            }
          }
        } else {
          $variations = json_decode($booking->variation, true);
          if ($variations) {
            foreach ($variations as $variation) {

              $ticket = Ticket::where('id', $variation['ticket_id'])->first();
              if ($ticket) {
                if ($ticket->pricing_type == 'variation') {

                  $ticket_variations =  json_decode($ticket->variations, true);
                  $update_variation = [];
                  foreach ($ticket_variations as $ticket_variation) {
                    if ($ticket_variation['name']  == $variation['name']) {

                      if ($ticket_variation['ticket_available_type'] == 'limited') {
                        $ticket_available = intval($ticket_variation['ticket_available']) + intval($variation['qty']);
                      } else {
                        $ticket_available = $ticket_variation['ticket_available'];
                      }
                      $update_variation[] = [
                        'name' => $ticket_variation['name'],
                        'price' => round($ticket_variation['price'], 2),
                        'ticket_available_type' => $ticket_variation['ticket_available_type'],
                        'ticket_available' => $ticket_available,
                        'max_ticket_buy_type' => $ticket_variation['max_ticket_buy_type'],
                        'v_max_ticket_buy' => $ticket_variation['v_max_ticket_buy'],
                      ];
                    } else {
                      $update_variation[] = [
                        'name' => $ticket_variation['name'],
                        'price' => round($ticket_variation['price'], 2),
                        'ticket_available_type' => $ticket_variation['ticket_available_type'],
                        'ticket_available' => $ticket_variation['ticket_available'],
                        'max_ticket_buy_type' => $ticket_variation['max_ticket_buy_type'],
                        'v_max_ticket_buy' => $ticket_variation['v_max_ticket_buy'],
                      ];
                    }
                  }
                  $ticket->variations = json_encode($update_variation, true);

                  $ticket->save();
                } else {
                  if ($ticket->ticket_available_type == 'limited') {
                    $ticket->ticket_available = $ticket->ticket_available + $variation['qty'];
                    $ticket->save();
                  }
                }
              }
            }
          }
        }
      }
      $booking->update([
        'paymentStatus' => 'rejected'
      ]);

      //unlink qr code
      @unlink(public_path('assets/admin/qrcodes/') . $booking->booking_id . '.svg');
      //end unlink qr code

      $this->sendMail($request, $booking, 'Booking rejected');

      //status change on transaction
      $transaction = Transaction::where([['booking_id', $id], ['transcation_type', 1]])->first();
      if ($transaction) {
        $transaction->update([
          'payment_status' => 0
        ]);
      }
    }

    return redirect()->back();
  }

  public function generateInvoice($bookingInfo)
  {
    $fileName = $bookingInfo->booking_id . '.pdf';
    $directory = public_path('assets/admin/file/invoices/');

    @mkdir($directory, 0775, true);
    @mkdir(public_path('assets/admin/qrcodes/'), 0775, true);

    $fileLocated = $directory . $fileName;

    //generate qr code
    QrCode::size(200)->generate($bookingInfo->booking_id, public_path('assets/admin/qrcodes/') . $bookingInfo->booking_id . '.svg');

    // get event title
    $language = $this->getLanguage();

    $eventInfo = EventContent::where('event_id', $bookingInfo->event_id)->where('language_id', $language->id)->first();

    $width = "50%";
    $float = "right";
    $mb = "35px";
    $ml = "18px";

    PDF::loadView('frontend.event.invoice', compact('bookingInfo', 'eventInfo', 'width', 'float', 'mb', 'ml'))->save($fileLocated);

    return $fileName;
  }

  public function sendMail($request, $booking, $mailFor)
  {
    // first get the mail template info from db
    if ($mailFor == 'Booking approved') {
      $mailTemplate = MailTemplate::where('mail_type', 'event_booking_approved')->first();
    } else {
      $mailTemplate = MailTemplate::where('mail_type', 'event_booking_rejected')->first();
    }

    $mailSubject = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // second get the website title & mail's smtp info from db
    $info = DB::table('basic_settings')
      ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();

    $customerName = $booking->fname . ' ' . $booking->lname;
    $booking_id = $booking->booking_id;

    $language = $this->getLanguage();
    $event = Event::where('id', $booking->event_id)->firstOrFail();
    $eventInfo = EventContent::where('event_id', $event->id)->where('language_id', $language->id)->firstOrFail();
    $eventTitle = $eventInfo->title;

    $websiteTitle = $info->website_title;

    $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
    $mailBody = str_replace('{order_id}', $booking_id, $mailBody);
    $mailBody = str_replace('{title}', '<a href="' . route('event.details', ['slug' => $eventInfo->slug, 'id' => $event->id]) . '">' . $eventTitle . '</a>', $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);

    // initialize a new mail
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // if smtp status == 1, then set some value for PHPMailer
    if ($info->smtp_status == 1) {
      $mail->isSMTP();
      $mail->Host       = $info->smtp_host;
      $mail->SMTPAuth   = true;
      $mail->Username   = $info->smtp_username;
      $mail->Password   = $info->smtp_password;

      if ($info->encryption == 'TLS') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      }

      $mail->Port       = $info->smtp_port;
    }

    // finally add other informations and send the mail
    try {
      // Recipients
      $mail->setFrom($info->from_mail, $info->from_name);
      $mail->addAddress($booking->email);

      // Attachments (Invoice)
      if (!is_null($booking->invoice)) {
        $mail->addAttachment(public_path('assets/admin/file/invoices/') . $booking->invoice);
      }

      // Content
      $mail->isHTML(true);
      $mail->Subject = $mailSubject;
      $mail->Body = $mailBody;

      $mail->send();

      Session::flash('success', 'Updated Successfully!');
    } catch (Exception $e) {
      Session::flash('warning', 'Mail could not be sent. Mailer Error: ' . $mail->ErrorInfo);
    }
    return;
  }
  //show
  public function show($id)
  {
    $booking = Booking::findOrFail($id);

    // get course title
    $language = $this->getLanguage();

    return view('backend.event.booking.details', compact('booking'));
  }

  public function destroy($id)
  {
    $Booking = Booking::find($id);

    // first, delete the attachment
    @unlink(public_path('assets/admin/file/attachments/') . $Booking->attachment);

    // second, delete the invoice
    @unlink(public_path('assets/admin/file/invoices/') . $Booking->invoice);

    $Booking->delete();

    return redirect()->back()->with('success', 'Booking deleted successfully!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $booking = Booking::find($id);

      // first, delete the attachment
      @unlink(public_path('assets/admin/file/attachments/') . $booking->attachment);

      // second, delete the invoice
      @unlink(public_path('assets/admin/file/invoices/') . $booking->invoice);

      $booking->delete();
    }

    Session::flash('success', 'Deleted Successfully');

    return response()->json(['status' => 'success'], 200);
  }

  public function report(Request $request)
  {

    $language = $this->getLanguage();

    $fromDate = $request->from_date;
    $toDate = $request->to_date;
    $paymentStatus = $request->payment_status;
    $paymentMethod = $request->payment_method;

    if (!empty($fromDate) && !empty($toDate)) {
      $bookings = Booking::join('event_contents', 'event_contents.event_id', 'bookings.event_id')
        ->join('customers', 'customers.id', 'bookings.customer_id')
        ->where('event_contents.language_id', $language->id)
        ->when($fromDate, function ($query, $fromDate) {
          return $query->whereDate('bookings.created_at', '>=', Carbon::parse($fromDate));
        })->when($toDate, function ($query, $toDate) {
          return $query->whereDate('bookings.created_at', '<=', Carbon::parse($toDate));
        })->when($paymentMethod, function ($query, $paymentMethod) {
          return $query->where('bookings.paymentMethod', $paymentMethod);
        })->when($paymentStatus, function ($query, $paymentStatus) {
          return $query->where('bookings.paymentStatus', '=', $paymentStatus);
        })
        ->select('event_contents.title', 'customers.fname as customerfname', 'customers.lname as customerlname', 'event_contents.slug', 'bookings.*')
        ->orderByDesc('id');

      Session::put('booking_report', $bookings->get());
      $data['bookings'] = $bookings->paginate(10);
    } else {
      Session::put('booking_report', []);
      $data['bookings'] = [];
    }


    $data['onPms'] = OnlineGateway::where('status', 1)->get();
    $data['offPms'] = OfflineGateway::where('status', 1)->get();
    $data['deLang'] = $language;
    $data['abs'] = Basic::select('base_currency_symbol_position', 'base_currency_symbol')->first();


    return view('backend.event.booking.report', $data);
  }

  public function export()
  {
    $bookings = Session::get('booking_report');
    if (empty($bookings) || count($bookings) == 0) {
      Session::flash('warning', 'There is no bookings to export');
      return back();
    }
    return Excel::download(new BookingExport($bookings), 'bookings.csv');
  }
}
