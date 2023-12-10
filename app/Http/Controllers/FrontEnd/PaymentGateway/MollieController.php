<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Event\BookingController;
use App\Models\BasicSettings\Basic;
use App\Models\Earning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Mollie\Laravel\Facades\Mollie;

class MollieController extends Controller
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

    $booking = new BookingController();
    $allowedCurrencies = array('AED', 'AUD', 'BGN', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HRK', 'HUF', 'ILS', 'ISK', 'JPY', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'THB', 'TWD', 'USD', 'ZAR');
    $total = Session::get('grand_total');
    $quantity = Session::get('quantity');
    $discount = Session::get('discount');
    $total_early_bird_dicount = Session::get('total_early_bird_dicount');
    //tax and commission end
    $basicSetting = Basic::select('commission')->first();

    $tax_amount = Session::get('tax');
    $commission_amount = ($total * $basicSetting->commission) / 100;

    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the base currency is allowed or not
    if (!in_array($currencyInfo->base_currency_text, $allowedCurrencies)) {
      return redirect()->back()->with('currency_error', 'Invalid currency for mollie payment.')->withInput();
    }

    $arrData = array(
      'event_id' => $eventId,
      'price' => $total,
      'tax' => $tax_amount,
      'commission' => $commission_amount,
      'quantity' => $quantity,
      'discount' => $discount,
      'total_early_bird_dicount' => $total_early_bird_dicount,
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
      'paymentMethod' => 'Mollie',
      'gatewayType' => 'online',
      'paymentStatus' => 'completed',
    );

    $notifyURL = route('event_booking.mollie.notify');

    /**
     * we must send the correct number of decimals.
     * thus, we have used sprintf() function for format.
     */
    try {
      $payment = Mollie::api()->payments->create([
        'amount' => [
          'currency' => $currencyInfo->base_currency_text,
          'value' => sprintf('%0.2f', ($total + $tax_amount))
        ],
        'description' => 'Event Booking Via Mollie',
        'redirectUrl' => $notifyURL
      ]);
    } catch (\Exception $th) {
      return redirect()->back()->with('error', 'Something went wrong or invalid api key')->withInput();
    }

    // put some data in session before redirect to mollie url
    $request->session()->put('eventId', $eventId);
    $request->session()->put('arrData', $arrData);
    $request->session()->put('paymentId', $payment->id);

    return redirect($payment->getCheckoutUrl(), 303);
  }

  public function notify(Request $request)
  {
    // get the information from session
    $eventId = $request->session()->get('eventId');
    $arrData = $request->session()->get('arrData');
    $paymentId = $request->session()->get('paymentId');

    $paymentInfo = Mollie::api()->payments->get($paymentId);

    if ($paymentInfo->isPaid() == true) {
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
      $request->session()->forget('selTickets');
      $request->session()->forget('arrData');
      $request->session()->forget('paymentId');
      $request->session()->forget('discount');

      return redirect()->route('event_booking.cancel', ['id' => $eventId]);
    }
  }
}
