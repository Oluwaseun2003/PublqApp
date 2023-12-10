<?php

namespace App\Http\Controllers\FrontEnd\Shop\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Shop\OrderController;
use App\Http\Helpers\Instamojo;
use App\Models\BasicSettings\Basic;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\ShopManagement\ShippingCharge;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class InstamojoController extends Controller
{
  private $api;

  public function __construct()
  {
    $data = OnlineGateway::whereKeyword('instamojo')->first();
    $instamojoData = json_decode($data->information, true);

    if ($instamojoData['sandbox_status'] == 1) {
      $this->api = new Instamojo($instamojoData['key'], $instamojoData['token'], 'https://test.instamojo.com/api/1.1/');
    } else {
      $this->api = new Instamojo($instamojoData['key'], $instamojoData['token']);
    }
  }

  public function enrolmentProcess(Request $request)
  {

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

    // checking whether the currency is set to 'INR' or not
    if ($currencyInfo->base_currency_text !== 'INR') {
      return redirect()->back()->with('error', 'Invalid currency for instamojo payment.')->withInput();
    }

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

      'method' => 'PayPal',
      'gateway_type' => 'online',
      'payment_status' => 'completed',
      'order_status' => 'pending',
      'tnxid' => '',
    );


    $title = 'Product Order';
    $notifyURL = route('product_order.instamojo.notify');

    try {
      $response = $this->api->paymentRequestCreate(array(
        'purpose' => $title,
        'amount' => $grand_total,
        'buyer_name' => $request->fname . ' ' . $request->lname,
        'email' => $request->email,
        'send_email' => false,
        'phone' => $request->phone,
        'send_sms' => false,
        'redirect_url' => $notifyURL
      ));


      // put some data in session before redirect to instamojo url
      $request->session()->put('arrData', $arrData);
      $request->session()->put('paymentId', $response['id']);

      return redirect($response['longurl']);
    } catch (Exception $e) {
      return redirect()->back()->with('error', 'Sorry, transaction failed!')->withInput();
    }
  }

  public function notify(Request $request)
  {
    // get the information from session
    $arrData = $request->session()->get('arrData');
    $paymentId = $request->session()->get('paymentId');

    $urlInfo = $request->all();

    if ($urlInfo['payment_request_id'] == $paymentId) {
      $enrol = new OrderController();

      // store the course enrolment information in database
      $enrolmentInfo = $enrol->storeData($arrData);

      // generate an invoice in pdf format
      $invoice = $enrol->generateInvoice($enrolmentInfo);

      // then, update the invoice field info in database
      $enrolmentInfo->update(['invoice_number' => $invoice]);

      // send a mail to the customer with the invoice
      $enrol->sendMail($enrolmentInfo);

      // remove all session data
      $request->session()->forget('arrData');
      $request->session()->forget('paymentId');

      return redirect()->route('product_order.complete');
    } else {
      // remove all session data
      $request->session()->forget('arrData');
      $request->session()->forget('paymentId');

      return redirect()->route('shop.checkout');
    }
  }
}
