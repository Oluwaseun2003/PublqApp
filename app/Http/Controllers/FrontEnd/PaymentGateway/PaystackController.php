<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Curriculum\EnrolmentController;
use App\Http\Controllers\FrontEnd\Event\BookingController;
use App\Models\BasicSettings\Basic;
use App\Models\Earning;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PaystackController extends Controller
{
  private $api_key;

  public function __construct()
  {
    $data = OnlineGateway::whereKeyword('paystack')->first();
    $paystackData = json_decode($data->information, true);

    $this->api_key = $paystackData['key'];
  }

  public function bookingProcess(Request $request, $eventId)
  {
    $rules = [
      'fname' => 'required',
      'lname' => 'required',
      'email' => 'required',
      'phone' => 'required',
      'country' => 'required',
      'address' => 'required',
      'gateway' => 'required',
    ];

    $message = [];

    $message['fname.required'] = 'The first name feild is required';
    $message['lname.required'] = 'The last name feild is required';
    $message['gateway.required'] = 'The payment gateway feild is required';
    $request->validate($rules, $message);

    $booking = new BookingController();

    $currencyInfo = $this->getCurrencyInfo();
    $total = Session::get('grand_total');
    $quantity = Session::get('quantity');
    $discount = Session::get('discount');

    //tax and commission end
    $basicSetting = Basic::select('commission')->first();

    $tax_amount = Session::get('tax');
    $commission_amount = ($total * $basicSetting->commission) / 100;


    $total_early_bird_dicount = Session::get('total_early_bird_dicount');
    // checking whether the currency is set to 'NGN' or not
    if ($currencyInfo->base_currency_text !== 'NGN') {
      return redirect()->back()->with('currency_error', 'Invalid currency for paystack payment.')->withInput();
    }

    try {
      $arrData = array(
        'event_id' => $eventId,
        'price' => $total,
        'tax' => $tax_amount,
        'commission' => $commission_amount,
        'quantity' => $quantity,
        'discount' => $discount,
        'total_early_bird_dicount' => $total_early_bird_dicount,
        'currencyText' => $currencyInfo->base_currency_text,
        'currencyTextPosition' => $currencyInfo->base_currency_text_position,
        'currencySymbol' => $currencyInfo->base_currency_symbol,
        'currencySymbolPosition' => $currencyInfo->base_currency_symbol_position,
        'fname' => $request->fname,
        'lname' => $request->lname,
        'email' => $request->email,
        'phone' => $request->phone,
        'country' => $request->country,
        'state' => $request->state,
        'city' => $request->city,
        'zip_code' => $request->city,
        'address' => $request->address,
        'paymentMethod' => 'Paystack',
        'gatewayType' => 'online',
        'paymentStatus' => 'completed',
      );

      $notifyURL = route('event_booking.paystack.notify');

      $curl = curl_init();

      $payableAmount = intval($total + $tax_amount);

      curl_setopt_array($curl, array(
        CURLOPT_URL            => 'https://api.paystack.co/transaction/initialize',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_POSTFIELDS     => json_encode([
          'amount'       => intval($payableAmount * 100),
          'email'        => $request->email,
          'callback_url' => $notifyURL
        ]),
        CURLOPT_HTTPHEADER     => [
          'authorization: Bearer ' . $this->api_key,
          'content-type: application/json',
          'cache-control: no-cache'
        ]
      ));

      $response = curl_exec($curl);

      curl_close($curl);

      $transaction = json_decode($response, true);

      // put some data in session before redirect to paystack url
      $request->session()->put('eventId', $eventId);
      $request->session()->put('arrData', $arrData);

      if ($transaction['status'] == true) {
        return redirect($transaction['data']['authorization_url']);
      } else {
        return redirect()->back()->with('error', 'Error: ' . $transaction['message'])->withInput();
      }
    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'Something went wrong');
    }
  }

  public function notify(Request $request)
  {
    // get the information from session
    $eventId = $request->session()->get('eventId');
    $arrData = $request->session()->get('arrData');

    $urlInfo = $request->all();

    if ($urlInfo['trxref'] === $urlInfo['reference']) {
      $enrol = new BookingController();

      $bookingInfo['transcation_type'] = 1;

      // store the course enrolment information in database
      $bookingInfo = $enrol->storeData($arrData);
      // generate an invoice in pdf format
      $invoice = $enrol->generateInvoice($bookingInfo, $eventId);
      //unlink qr code
      @unlink(public_path('assets/admin/qrcodes/') . $bookingInfo->booking_id . '.svg');
      //end unlink qr code

      // then, update the invoice field info in database
      $bookingInfo->update(['invoice' => $invoice]);

      //add blance to admin revinue
      $earning = Earning::first();
      $earning->total_revenue = $earning->total_revenue + $arrData['price'] + $bookingInfo->tax;
      if ($bookingInfo['organizer_id'] != null) {
        $earning->total_earning = $earning->total_earning + ($bookingInfo->tax + $bookingInfo->commission);
      } else {
        $earning->total_earning = $earning->total_earning + $arrData['price'] + $bookingInfo->tax;
      }
      $earning->save();

      //storeTransaction
      $bookingInfo['paymentStatus'] = 1;
      $bookingInfo['transcation_type'] = 1;

      storeTranscation($bookingInfo);

      //store amount to organizer
      $organizerData['organizer_id'] = $bookingInfo['organizer_id'];
      $organizerData['price'] = $arrData['price'];
      $organizerData['tax'] = $bookingInfo->tax;
      $organizerData['commission'] = $bookingInfo->commission;
      storeOrganizer($organizerData);

      // send a mail to the customer with the invoice
      $enrol->sendMail($bookingInfo);

      // remove all session data
      $request->session()->forget('event_id');
      $request->session()->forget('selTickets');
      $request->session()->forget('arrData');
      $request->session()->forget('paymentId');
      $request->session()->forget('discount');
      return redirect()->route('event_booking.complete', ['id' => $eventId, 'booking_id' => $bookingInfo->id]);
    } else {
      // remove all session data
      $request->session()->forget('eventId');
      $request->session()->forget('arrData');
      $request->session()->forget('discount');

      return redirect()->route('event_booking.cancel', ['id' => $eventId]);
    }
  }
}
