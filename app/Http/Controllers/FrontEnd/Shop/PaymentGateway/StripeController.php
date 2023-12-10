<?php

namespace App\Http\Controllers\FrontEnd\Shop\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Shop\OrderController;
use App\Models\BasicSettings\Basic;
use App\Models\ShopManagement\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Exception\UnauthorizedException;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class StripeController extends Controller
{
  public function enrolmentProcess(Request $request)
  {
    // card validation start
    $rules = [
      'stripeToken' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }
    // card validation end

    $enrol = new OrderController();


    $currencyInfo = $this->getCurrencyInfo();
    $cart_items = Session::get('cart');

    $total = 0;
    $quantity = 0;
    foreach ($cart_items as $p) {
      $total += $p['price'] * $p['qty'];
      $quantity += $p['price'] * $p['qty'];
    }
    if ($request->shipping_method) {
      $shipping_cost = ShippingCharge::where('id', $request->shipping_method)->first();
      $shipping_charge = $shipping_cost->charge;
      $shipping_method = $shipping_cost->title;
    } else {
      $shipping_charge = 0;
      $shipping_method = NULL;
    }

    $discount = Session::get('Shop_discount');
    $tax = Basic::select('shop_tax')->first();
    $tax_percentage = $tax->shop_tax;
    $total_tax_amount = ($tax_percentage / 100) * ($total - $discount);
    $grand_total = ($shipping_charge + $total + $total_tax_amount) - $discount;
    // changing the currency before redirect to Stripe
    if ($currencyInfo->base_currency_text !== 'USD') {
      $rate = floatval($currencyInfo->base_currency_rate);
      $convertedTotal = round(($grand_total / $rate), 2);
    }

    $stripeTotal = $currencyInfo->base_currency_text === 'USD' ? $grand_total : $convertedTotal;

    if (Auth::guard('customer')->user()) {
      $user_id = Auth::guard('customer')->user()->id;
    } else {
      $user_id = 0;
    }
    $arrData = array(
      'user_id' => $user_id,
      'fname' => $request->fname,
      'lname' => $request->lname,
      'email' => $request->email,
      'phone' => $request->phone,
      'country' => $request->country,
      'state' => $request->state,
      'city' => $request->city,
      'zip_code' => $request->zip_code,
      'address' => $request->address,

      's_fname' => $request->sameas_shipping == NULL ? $request->s_fname : $request->fname,
      's_lname' => $request->sameas_shipping == NULL ? $request->s_lname : $request->lname,
      's_email' => $request->sameas_shipping == NULL ? $request->s_email : $request->email,
      's_phone' => $request->sameas_shipping == NULL ? $request->s_phone : $request->phone,
      's_country' => $request->sameas_shipping == NULL ? $request->s_country : $request->country,
      's_state' => $request->sameas_shipping == NULL ? $request->s_state : $request->state,
      's_city' => $request->sameas_shipping == NULL ? $request->s_city : $request->city,
      's_zip_code' => $request->sameas_shipping == NULL ? $request->s_city : $request->city,
      's_address' => $request->sameas_shipping == NULL ? $request->s_address : $request->address,

      'cart_total' => $total,
      'discount' => $discount,
      'tax_percentage' => $tax_percentage,
      'tax' => $total_tax_amount,
      'grand_total' => $grand_total,
      'currency_code' => '',
      'shipping_charge' => $shipping_charge,
      'shipping_method' => $shipping_method,
      'order_number' => uniqid(),
      'charge_id' => $request->shipping_method,

      'method' => 'Stripe',
      'gateway_type' => 'online',
      'payment_status' => 'completed',
      'order_status' => 'pending',
      'tnxid' => '',
    );

    try {
      // initialize stripe
      $stripe = new Stripe();
      $stripe = Stripe::make(Config::get('services.stripe.secret'));

      try {
        try {
          // generate charge
          $charge = $stripe->charges()->create([
            'source' => $request->stripeToken,
            'currency' => 'USD',
            'amount'   => $stripeTotal
          ]);
        } catch (\Exception $th) {
          Session::flash('error', $th->getMessage());
          return redirect()->route('shop.checkout');
        }

        if ($charge['status'] == 'succeeded') {
          // store the course enrolment information in database
          $orderInfo = $enrol->storeData($arrData);

          //store data to oder items table
          $orderItems = $enrol->storeOders($orderInfo);

          // generate an invoice in pdf format
          $invoice = $enrol->generateInvoice($orderInfo);

          // then, update the invoice field info in database
          $orderInfo->update(['invoice_number' => $invoice]);

          // send a mail to the customer with the invoice
          $enrol->sendMail($orderInfo);

          return redirect()->route('product_order.complete');
        } else {
          Session::flash('message', 'Something went wrong');
          Session::flash('alert-type', 'error');
          return redirect()->route('shop.checkout');
        }
      } catch (CardErrorException $e) {
        Session::flash('message', $e->getMessage());
        Session::flash('alert-type', 'error');

        return redirect()->route('shop.checkout');
      }
    } catch (UnauthorizedException $e) {
      Session::flash('message', $e->getMessage());
      Session::flash('alert-type', 'error');

      return redirect()->route('shop.checkout');
    }
  }
}
