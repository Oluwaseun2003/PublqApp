<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Event\BookingController;
use App\Models\BasicSettings\Basic;
use App\Models\Earning;
use App\Models\PaymentGateway\OnlineGateway;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;


class RazorpayController extends Controller
{
  private $key, $secret, $api;

  public function __construct()
  {
    $data = OnlineGateway::whereKeyword('razorpay')->first();
    $razorpayData = json_decode($data->information, true);

    $this->key = $razorpayData['key'];
    $this->secret = $razorpayData['secret'];

    $this->api = new Api($this->key, $this->secret);
  }

  public function bookingProcess(Request $request, $event_id)
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

    new BookingController();


    $currencyInfo = $this->getCurrencyInfo();
    $total = Session::get('grand_total');
    $quantity = Session::get('quantity');
    $discount = Session::get('discount');
    //tax and commission end
    $basicSetting = Basic::select('commission')->first();

    $tax_amount = Session::get('tax');
    $commission_amount = ($total * $basicSetting->commission) / 100;

    $total_early_bird_dicount = Session::get('total_early_bird_dicount');
    // checking whether the currency is set to 'INR' or not
    if ($currencyInfo->base_currency_text !== 'INR') {
      return redirect()->back()->with('currency_error', 'Invalid currency for razorpay payment.')->withInput();
    }

    $arrData = array(
      'event_id' => $event_id,
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
      'paymentMethod' => 'Razorpay',
      'gatewayType' => 'online',
      'paymentStatus' => 'completed',
    );

    $notifyURL = route('event_booking.razorpay.notify');

    // create order data
    $orderData = [
      'receipt'         => 'Course Enrolment',
      'amount'          => (($total + $tax_amount) * 100),
      'currency'        => 'INR',
      'payment_capture' => 1 // auto capture
    ];

    try {
      $razorpayOrder = $this->api->order->create($orderData);
    } catch (Exception $e) {
      return redirect()->back()->with('error', 'Something went wrong or invalid api key.!')->withInput();
    }

    $webInfo = DB::table('basic_settings')->select('website_title')->first();
    $buyerName = $request->fname . ' ' . $request->lname;
    $buyerEmail = $request->email;
    $buyerContact = $request->phone;

    // create checkout data
    $checkoutData = [
      'key'               => $this->key,
      'amount'            => $orderData['amount'],
      'name'              => $webInfo->website_title,
      'description'       => 'Event Booking Via Razorpay',
      'prefill'           => [
        'name'              => $buyerName,
        'email'             => $buyerEmail,
        'contact'           => $buyerContact
      ],
      'order_id'          => $razorpayOrder->id
    ];

    $jsonData = json_encode($checkoutData);

    // put some data in session before redirect to razorpay url
    $request->session()->put('event_id', $event_id);
    $request->session()->put('arrData', $arrData);
    $request->session()->put('razorpayOrderId', $razorpayOrder->id);

    return view('frontend.payment.razorpay', compact('jsonData', 'notifyURL'));
  }

  public function notify(Request $request)
  {
    // get the information from session
    $eventId = $request->session()->get('event_id');
    $arrData = $request->session()->get('arrData');
    $razorpayOrderId = $request->session()->get('razorpayOrderId');

    $urlInfo = $request->all();

    // assume that the transaction was successful
    $success = true;

    /**
     * either razorpay_order_id or razorpay_subscription_id must be present.
     * the keys of $attributes array must be follow razorpay convention.
     */
    try {
      $attributes = [
        'razorpay_order_id' => $razorpayOrderId,
        'razorpay_payment_id' => $urlInfo['razorpayPaymentId'],
        'razorpay_signature' => $urlInfo['razorpaySignature']
      ];

      $this->api->utility->verifyPaymentSignature($attributes);
    } catch (SignatureVerificationError $e) {
      $success = false;
    }

    if ($success === true) {
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
      $request->session()->forget('razorpayOrderId');
      return redirect()->route('event_booking.complete', ['id' => $eventId, 'booking_id' => $bookingInfo->id]);
    } else {
      // remove all session data
      $request->session()->forget('event_id');
      $request->session()->forget('arrData');
      $request->session()->forget('razorpayOrderId');
      $request->session()->forget('discount');

      return redirect()->route('event_booking.cancel', ['id' => $eventId]);
    }
  }
}
