<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Event\BookingController;
use App\Models\BasicSettings\Basic;
use App\Models\Earning;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FlutterwaveController extends Controller
{
  private $public_key, $secret_key;

  public function __construct()
  {
    $data = OnlineGateway::whereKeyword('flutterwave')->first();
    $flutterwaveData = json_decode($data->information, true);

    $this->public_key = $flutterwaveData['public_key'];
    $this->secret_key = $flutterwaveData['secret_key'];
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

    $total = Session::get('grand_total');

    //tax and commission end
    $basicSetting = Basic::select('commission')->first();

    $tax_amount = Session::get('tax');
    $commission_amount = ($total * $basicSetting->commission) / 100;

    $allowedCurrencies = array('BIF', 'CAD', 'CDF', 'CVE', 'EUR', 'GBP', 'GHS', 'GMD', 'GNF', 'KES', 'LRD', 'MWK', 'MZN', 'NGN', 'RWF', 'SLL', 'STD', 'TZS', 'UGX', 'USD', 'XAF', 'XOF', 'ZMK', 'ZMW', 'ZWD');

    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the base currency is allowed or not
    if (!in_array($currencyInfo->base_currency_text, $allowedCurrencies)) {
      return redirect()->back()->with('currency_error', 'Invalid currency for flutterwave payment.')->withInput();
    }

    $arrData = array(
      'event_id' => $eventId,
      'price' => $total,
      'tax' => $tax_amount,
      'commission' => $commission_amount,
      'quantity' => Session::get('quantity'),
      'discount' => Session::get('discount'),
      'total_early_bird_dicount' => Session::get('total_early_bird_dicount'),
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
      'paymentMethod' => 'Flutterwave',
      'gatewayType' => 'online',
      'paymentStatus' => 'completed',
    );

    $notifyURL = route('event_booking.flutterwave.notify');

    // generate a payment reference
    // send payment to flutterwave for processing
    $curl = curl_init();

    $payment_plan = ""; // this is only required for recurring payments.
    $txref = uniqid();
    Session::put('txref', $txref);


    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode([
        'amount' => $total + $tax_amount,
        'customer_email' => $request->email,
        'currency' => $currencyInfo->base_currency_text,
        'txref' => $txref,
        'PBFPubKey' => $this->public_key,
        'redirect_url' => $notifyURL,
        'payment_plan' => $payment_plan
      ]),
      CURLOPT_HTTPHEADER => [
        "content-type: application/json",
        "cache-control: no-cache"
      ],
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $responseData = json_decode($response, true);

    //curl end

    // put some data in session before redirect to flutterwave url
    $request->session()->put('eventId', $eventId);
    $request->session()->put('arrData', $arrData);

    if ($responseData['status'] === 'success') {
      return redirect($responseData['data']['link']);
    } else {
      return redirect()->back()->with('error', 'Error: ' . $responseData['message'])->withInput();
    }
  }

  public function notify(Request $request)
  {
    try {
      // get the information from session
      $eventId = $request->session()->get('eventId');
      $arrData = $request->session()->get('arrData');
      if (isset($request['txref'])) {
        $ref = Session::get('txref');
        $query = array(
          "SECKEY" => $this->secret_key,
          "txref" => $ref
        );
      }
      $data_string = json_encode($query);

      $ch = curl_init('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify');
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      $response = curl_exec($ch);
      curl_close($ch);
      $resp = json_decode($response, true);
      if ($resp['status'] == 'error') {
        // remove all session data
        $request->session()->forget('eventId');
        $request->session()->forget('arrData');
        $request->session()->forget('discount');

        return redirect()->route('event_booking.cancel', ['id' => $eventId]);
      }

      if ($resp['status'] = "success") {
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
      }
    } catch (\Exception $e) {
      return view('errors.404');
    }
  }
}
