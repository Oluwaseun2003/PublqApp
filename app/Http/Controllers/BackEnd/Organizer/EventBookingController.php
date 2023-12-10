<?php

namespace App\Http\Controllers\BackEnd\Organizer;

use App\Exports\BookingExport;
use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Event;
use App\Models\Event\Booking;
use App\Models\Event\EventContent;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PHPMailer\PHPMailer\PHPMailer;

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

    $organizer_id = Auth::guard('organizer')->user()->id;

    $bookings = Booking::join('events', 'events.id', 'bookings.event_id')
      ->when($bookingId, function ($query, $bookingId) {
        return $query->where('bookings.booking_id', 'like', '%' . $bookingId . '%');
      })
      ->when($eventIds, function ($query) use ($eventIds) {
        return $query->whereIn('event_id', $eventIds);
      })
      ->when($paymentStatus, function ($query, $paymentStatus) {
        return $query->where('bookings.paymentStatus', '=', $paymentStatus);
      })
      ->select('bookings.*')
      ->where('events.organizer_id', $organizer_id)
      ->orderByDesc('id')
      ->paginate(10);
    return view('organizer.event.booking.index', compact('bookings'));
  }
  //updatePaymentStatus
  public function updatePaymentStatus(Request $request, $id)
  {
    $booking = Booking::find($id);
    if (Auth::guard('organizer')->user()->id != $booking->organizer_id) {
      return back();
    }

    if ($request['payment_status'] == 'completed') {
      $booking->update([
        'paymentStatus' => 'completed'
      ]);

      $invoice = $this->generateInvoice($booking);

      $booking->update([
        'invoice' => $invoice
      ]);

      $this->sendMail($request, $booking, 'Booking approved');
    } else if ($request['payment_status'] == 'pending') {
      $booking->update([
        'paymentStatus' => 'pending'
      ]);
    } else {
      $booking->update([
        'paymentStatus' => 'rejected'
      ]);

      $this->sendMail($request, $booking, 'Booking rejected');
    }

    return redirect()->back();
  }

  public function generateInvoice($bookingInfo)
  {
    $fileName = $bookingInfo->booking_id . '.pdf';
    $directory = public_path('assets/admin/file/invoices/');

    @mkdir($directory, 0775, true);

    $fileLocated = $directory . $fileName;

    // get course title
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

      Session::flash('success', 'Payment status updated & mail has been sent successfully!');
    } catch (Exception $e) {
      Session::flash('warning', 'Mail could not be sent. Mailer Error: ' . $mail->ErrorInfo);
    }
    return;
  }
  //show
  public function show($id)
  {
    $booking = Booking::where('id', $id)->firstOrFail();

    if (!($booking) || $booking->organizer_id != Auth::guard('organizer')->user()->id) {
      return redirect()->route('organizer.dashboard');
    }

    // get course title
    $language = $this->getLanguage();

    $eventContent = EventContent::where('event_id', $booking->event_id)->where('language_id', $language->id)->first();
    if (empty($eventContent)) {
      $eventContent = EventContent::where('event_id', $booking->event_id)->first();
    }

    return view('organizer.event.booking.details', compact('booking'));
  }

  public function destroy($id)
  {
    $Booking = Booking::where('id', $id)->first();

    if (Auth::guard('organizer')->user()->id != $Booking->organizer_id) {
      return back();
    }

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
      $booking = Booking::where('id', $id)->first();

      // first, delete the attachment
      @unlink(public_path('assets/admin/file/attachments/') . $booking->attachment);

      // second, delete the invoice
      @unlink(public_path('assets/admin/file/invoices/') . $booking->invoice);

      $booking->delete();
    }

    Session::flash('success', 'Booking deleted successfully!');

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
        ->join('events', 'events.id', 'bookings.event_id')
        ->where('event_contents.language_id', $language->id)
        ->where('events.organizer_id', Auth::guard('organizer')->user()->id)
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


    return view('organizer.event.booking.report', $data);
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
