<?php

namespace App\Http\Controllers\FrontEnd\Shop\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Shop\OrderController;
use App\Models\BasicSettings\Basic;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\ShopManagement\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OfflineController extends Controller
{
  public function enrolmentProcess(Request $request)
  {
    $offlineGateway = OfflineGateway::find($request->gateway);

    // check whether attachment is required or not
    if ($offlineGateway->has_attachment == 1) {
      $rules = [
        'attachment' => [
          'required',
          'mimes:jpg,jpeg,png'
        ]
      ];

      $validator = Validator::make($request->all(), $rules);

      Session::flash('gatewayId', $offlineGateway->id);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator->errors())->withInput();
      }
    }

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

      'method' => $offlineGateway->name,
      'gateway_type' => 'offline',
      'payment_status' => 'pending',
      'order_status' => 'pending',
      'tnxid' => '',
    );

    if ($request->hasFile('attachment')) {

      $filename = time() . '.' . $request->file('attachment')->getClientOriginalExtension();
      @mkdir(public_path('assets/admin/file/order/attachments/'), 0775, true);
      $request->file('attachment')->move(public_path('assets/admin/file/order/attachments/'), $filename);
      $arrData['attachmentFile'] = $filename;
    }
    // store the course enrolment information in database
    $orderInfo = $enrol->storeData($arrData);

    //store data to oder items table
    $orderItems = $enrol->storeOders($orderInfo);

    return redirect()->route('product_order.complete');
  }
}
