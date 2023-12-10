<?php

namespace App\Http\Controllers\FrontEnd\Shop\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Shop\OrderController;
use App\Models\BasicSettings\Basic;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\ShopManagement\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $allowedCurrencies = array('BIF', 'CAD', 'CDF', 'CVE', 'EUR', 'GBP', 'GHS', 'GMD', 'GNF', 'KES', 'LRD', 'MWK', 'MZN', 'NGN', 'RWF', 'SLL', 'STD', 'TZS', 'UGX', 'USD', 'XAF', 'XOF', 'ZMK', 'ZMW', 'ZWD');

        $currencyInfo = $this->getCurrencyInfo();

        // checking whether the base currency is allowed or not
        if (!in_array($currencyInfo->base_currency_text, $allowedCurrencies)) {
            return redirect()->back()->with('currency_error', 'Invalid currency for flutterwave payment.')->withInput();
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

            'method' => 'Flutterwave',
            'gateway_type' => 'online',
            'payment_status' => 'completed',
            'order_status' => 'pending',
            'tnxid' => '',
        );

        $notifyURL = route('product_order.flutterwave.notify');

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
                'amount' => $grand_total,
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
            $arrData = $request->session()->get('arrData');
            $cancel_url = route('shop.checkout');
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
                $request->session()->forget('arrData');
                $request->session()->forget('paymentId');
                return redirect($cancel_url);
            }
            if ($resp['status'] = "success") {
                $enrol = new OrderController();

                // store the course enrolment information in database
                $orderInfo = $enrol->storeData($arrData);

                // generate an invoice in pdf format
                $invoice = $enrol->generateInvoice($orderInfo);

                // then, update the invoice field info in database
                $orderInfo->update(['invoice_number' => $invoice]);

                // send a mail to the customer with the invoice
                $enrol->sendMail($orderInfo);

                // remove all session data
                $request->session()->forget('arrData');
                $request->session()->forget('paymentId');

                return redirect()->route('product_order.complete');
            }
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }
}
