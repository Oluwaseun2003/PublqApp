<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Event\BookingController;
use App\Models\BasicSettings\Basic;
use App\Models\Earning;
use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Exception\UnauthorizedException;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class StripeController extends Controller
{

  public function bookingProcess(Request $request, $eventId)
  {
    $eventId = $eventId;
    // card validation start
    $rules = [
      'fname' => 'required',
      'lname' => 'required',
      'email' => 'required',
      'phone' => 'required',
      'country' => 'required',
      'address' => 'required',
      'gateway' => 'required',

      'stripeToken' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }
    // card validation end

    $enrol = new BookingController();


    $currencyInfo = $this->getCurrencyInfo();
    $total = Session::get('grand_total');
    $quantity = Session::get('quantity');
    $discount = Session::get('discount');
    $total_early_bird_dicount = Session::get('total_early_bird_dicount');
    //tax and commission end
    $basicSetting = Basic::select('commission')->first();

    $tax_amount = Session::get('tax');
    $commission_amount = ($total * $basicSetting->commission) / 100;

    // changing the currency before redirect to Stripe
    if ($currencyInfo->base_currency_text !== 'USD') {
      $rate = floatval($currencyInfo->base_currency_rate);
      $convertedTotal = round(((Session::get('grand_total') + $tax_amount) / $rate), 2);
    }

    $stripeTotal = $currencyInfo->base_currency_text === 'USD' ? ($total + $tax_amount) : $convertedTotal;

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
      'paymentMethod' => 'Stripe',
      'gatewayType' => 'online',
      'paymentStatus' => 'completed',
    );

    try {
      // initialize stripe
      $stripe = new Stripe();
      $stripe = Stripe::make(Config::get('services.stripe.secret'));

      try {
        // generate token
        try {
          // generate charge
          $charge = $stripe->charges()->create([
            // 'source' => $token['id'],
            'source' => $request->stripeToken,
            'currency' => 'USD',
            'amount'   => $stripeTotal
          ]);
        } catch (\Exception $th) {
          Session::flash('error', $th->getMessage());
          return redirect()->route('check-out');
        }

        if ($charge['status'] == 'succeeded') {

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
          return redirect()->route('event_booking.complete', [
            'id' => $eventId, 'booking_id' => $bookingInfo->id
          ]);
        } else {
          return redirect()->route('event_booking.cancel', ['id' => $eventId]);
        }
      } catch (CardErrorException $e) {
        Session::flash('error', $e->getMessage());

        return redirect()->route('event_booking.cancel', ['id' => $eventId]);
      }
    } catch (UnauthorizedException $e) {
      Session::flash('error', $e->getMessage());

      return redirect()->route('event_booking.cancel', ['id' => $eventId]);
    }
  }
}
