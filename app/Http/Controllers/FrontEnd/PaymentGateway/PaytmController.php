<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Event\BookingController;
use App\Models\BasicSettings\Basic;
use App\Models\Earning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaytmController extends Controller
{
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

    new BookingController();


    $currencyInfo = $this->getCurrencyInfo();
    $total = Session::get('grand_total');
    $quantity = Session::get('quantity');
    $discount = Session::get('discount');
    $total_early_bird_dicount = Session::get('total_early_bird_dicount');

    //tax and commission end
    $basicSetting = Basic::select('commission')->first();

    $tax_amount = Session::get('tax');
    $commission_amount = ($total * $basicSetting->commission) / 100;

    // checking whether the currency is set to 'INR' or not
    if ($currencyInfo->base_currency_text !== 'INR') {
      return redirect()->back()->with('error', 'Invalid currency for paytm payment.')->withInput();
    }

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
      'paymentMethod' => 'Paytm',
      'gatewayType' => 'online',
      'paymentStatus' => 'completed',
    );

    $notifyURL = route('event_booking.paytm.notify');

    $payment = PaytmWallet::with('receive');

    try {
      $payment->prepare([
        'order' => time(),
        'user' => uniqid(),
        'mobile_number' => $request->phone,
        'email' => $request->email,
        'amount' => $total + $tax_amount,
        'callback_url' => $notifyURL
      ]);
    } catch (\Exception $th) {
      Session::flash('error', $th);
      return redirect()->route('check-out');
    }

    // put some data in session before redirect to paytm url
    $request->session()->put('eventId', $eventId);
    $request->session()->put('arrData', $arrData);


    return $payment->receive();
  }

  public function notify(Request $request)
  {

    // get the information from session
    $eventId = $request->session()->get('eventId');
    $arrData = $request->session()->get('arrData');

    $transaction = PaytmWallet::with('receive');

    // this response is needed to check the transaction status
    $response = $transaction->response();

    if ($transaction->isSuccessful()) {
      $enrol = new BookingController();

      // store the course enrolment information in database
      $bookingInfo = $enrol->storeData($arrData);

      // generate an invoice in pdf format
      $invoice = $enrol->generateInvoice($bookingInfo, $eventId);

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

      //unlink qr code
      @unlink(public_path('assets/admin/qrcodes/') . $bookingInfo->booking_id . '.svg');
      //end unlink qr code
      // send a mail to the customer with the invoice
      $enrol->sendMail($bookingInfo);

      // remove all session data
      $request->session()->forget('eventId');
      $request->session()->forget('arrData');
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
