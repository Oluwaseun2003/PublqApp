<?php

namespace App\Http\Controllers\FrontEnd\Shop;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\FlutterwaveController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\InstamojoController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\MercadoPagoController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\MollieController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\OfflineController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\PaypalController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\PaystackController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\PaytmController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\RazorpayController;
use App\Http\Controllers\FrontEnd\Shop\PaymentGateway\StripeController;
use App\Models\ShopManagement\ProductContent;
use App\Models\ShopManagement\ProductOrder;
use App\Models\BasicSettings\MailTemplate;
use App\Models\ShopManagement\OrderItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Earning;
use PDF;

class OrderController extends Controller
{
  public function enrol(Request $request)
  {
    $rules = [
      'fname' => 'required',
      'lname' => 'required',
      'email' => 'required',
      'phone' => 'required',
      'city' => 'required',
      'country' => 'required',
      'zip_code' => 'required',
      'gateway' => 'required',
      'address' => 'required',
    ];

    if ($request->sameas_shipping == null) {
      $rules['s_fname'] = 'required';
      $rules['s_lname'] = 'required';
      $rules['s_email'] = 'required';
      $rules['s_phone'] = 'required';
      $rules['s_country'] = 'required';
      $rules['s_city'] = 'required';
      $rules['s_zip_code'] = 'required';
      $rules['s_address'] = 'required';
    }

    $message = [];

    $message['fname.required'] = 'The first name feild is required';
    $message['lname.required'] = 'The last name feild is required';
    $message['gateway.required'] = 'The payment gateway feild is required';

    if ($request->sameas_shipping == null) {
      $message['s_fname.required'] = 'The first name feild is required';
      $message['s_lname.required'] = 'The last name feild is required';
      $message['s_email.required'] = 'The email feild is required';
      $message['s_phone.required'] = 'The phone no feild is required';
      $message['s_country.required'] = 'The country feild is required';
      $message['s_city.required'] = 'The city feild is required';
      $message['s_zip_code.required'] = 'The zipcode feild is required';
      $message['s_address.required'] = 'The address feild is required';
    }
    $request->validate($rules, $message);

    // check whether user is logged in or not
    if (!request()->input('type') && request()->input('type') != 'guest') {
      if (Auth::guard('customer')->check() == false) {
        return redirect()->route('customer.login', ['redirectPath' => 'checkout']);
      }
    }

    // event bookings
    if (!$request->exists('gateway')) {
      Session::flash('error', 'Please select a payment method.');

      return redirect()->back();
    } else if ($request['gateway'] == 'paypal') {
      $paypal = new PaypalController();

      return $paypal->enrolmentProcess($request);
    } else if ($request['gateway'] == 'razorpay') {
      $razorpay = new RazorpayController();

      return $razorpay->enrolmentProcess($request);
    } else if ($request['gateway'] == 'instamojo') {
      $instamojo = new InstamojoController();

      return $instamojo->enrolmentProcess($request);
    } else if ($request['gateway'] == 'paystack') {
      $paystack = new PaystackController();

      return $paystack->enrolmentProcess($request);
    } else if ($request['gateway'] == 'flutterwave') {
      $flutterwave = new FlutterwaveController();

      return $flutterwave->enrolmentProcess($request);
    } else if ($request['gateway'] == 'mercadopago') {
      $mercadopago = new MercadoPagoController();

      return $mercadopago->enrolmentProcess($request);
    } else if ($request['gateway'] == 'mollie') {
      $mollie = new MollieController();

      return $mollie->enrolmentProcess($request);
    } else if ($request['gateway'] == 'stripe') {
      $stripe = new StripeController();

      return $stripe->enrolmentProcess($request);
    } else if ($request['gateway'] == 'paytm') {
      $paytm = new PaytmController();

      return $paytm->enrolmentProcess($request);
    } else {
      $offline = new OfflineController();

      return $offline->enrolmentProcess($request);
    }
  }

  public function storeData($info)
  {
    $currencyInfo = $this->getCurrencyInfo();
    $order = ProductOrder::create([
      'user_id' => $info['user_id'],
      'billing_fname' => $info['fname'],
      'billing_lname' => $info['lname'],
      'billing_email' => $info['email'],
      'billing_phone' => $info['phone'],
      'billing_country' => $info['country'],
      'billing_state' => $info['state'],
      'billing_city' => $info['city'],
      'billing_zip_code' => $info['zip_code'],
      'billing_address' => $info['address'],

      'shipping_fname' => $info['s_fname'],
      'shipping_lname' => $info['s_lname'],
      'shipping_email' => $info['s_email'],
      'shipping_phone' => $info['s_phone'],
      'shipping_country' => $info['s_country'],
      'shipping_state' => $info['s_state'],
      'shipping_city' => $info['s_city'],
      'shipping_zip_code' => $info['s_zip_code'],
      'shipping_address' => $info['s_address'],

      'cart_total' => $info['cart_total'],
      'discount' => $info['discount'],
      'tax' => $info['tax'],
      'tax_percentage' => $info['tax_percentage'],
      'total' => $info['grand_total'],

      'currency_text' => $currencyInfo->base_currency_text,
      'currency_text_position' => $currencyInfo->base_currency_text_position,
      'currency_symbol' => $currencyInfo->base_currency_symbol,
      'currency_symbol_position' => $currencyInfo->base_currency_symbol_position,

      'order_number' => uniqid(),
      'shipping_method' => $info['shipping_method'],
      'shipping_charge' => $info['shipping_charge'],

      'method' => $info['method'],
      'gateway_type' => $info['gateway_type'],
      'payment_status' => $info['payment_status'],
      'order_status' => $info['order_status'],
      'tnxid' => $info['tnxid'],
      'charge_id' => $info['charge_id'],
      'invoice' => array_key_exists('attachmentFile', $info) ? $info['attachmentFile'] : null,
      'receipt' => array_key_exists('attachmentFile', $info) ? $info['attachmentFile'] : null
    ]);

    //add blance to admin revinue
    $earning = Earning::first();
    $earning->total_revenue = $earning->total_revenue + $order->total;
    $earning->total_earning = $earning->total_earning + $order->total;
    $earning->save();

    //store data to transcation table 
    $transcation = Transaction::create([
      'transcation_id' => time(),
      'booking_id' => $order->id,
      'transcation_type' => 2,
      'customer_id' => Auth::guard('customer')->check() == true ? Auth::guard('customer')->user()->id : null,
      'organizer_id' => null,
      'payment_status' => 1,
      'payment_method' => $order->method,
      'grand_total' => $order->total,
      'commission' => $order->total,
      'gateway_type' => $order->gateway_type,
      'currency_symbol' => $order->currency_symbol,
      'currency_symbol_position' => $order->currency_symbol_position,
    ]);
    return $order;
  }
  //storeOders
  public function storeOders($info)
  {
    $language = $this->getLanguage();
    $cart = Session::get('cart');
    foreach ($cart as $key => $c) {
      $product = ProductContent::join('products', 'products.id', 'product_contents.product_id')
        ->where('product_contents.language_id', $language->id)
        ->where('products.id', $key)
        ->select('products.*', 'product_contents.summary', 'product_contents.description')
        ->first();

      $order_item = OrderItem::create([
        'product_order_id' => $info->id,
        'product_id' => $key,
        'user_id' => $info->user_id,
        'title' => $c['name'],
        'sku' => $product->sku,
        'qty' => $c['qty'],
        'category' => '',
        'image' => $c['photo'],
        'summery' => $product->summary,
        'description' => $product->description,
        'price' => $c['price'],
        'previous_price' => $product->previous_price,
      ]);
    }
  }


  public function complete(Request $request, $via = null)
  {
    Session::put('cart', NULL);
    $language = $this->getLanguage();
    $queryResult['paidVia'] = $via;
    return view('frontend.payment.order_success');
  }

  public function cancel()
  {
    return redirect()->route('shopping.cart');
  }

  public function sendMail($orderInfo)
  {
    // first get the mail template info from db
    $mailTemplate = MailTemplate::where('mail_type', 'product_order')->first();
    $mailSubject = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // second get the website title & mail's smtp info from db
    $info = DB::table('basic_settings')
      ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();

    $customerName = $orderInfo->billing_fname . ' ' . $orderInfo->billing_lname;
    $orderId = $orderInfo->order_number;

    $websiteTitle = $info->website_title;

    $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
    $mailBody = str_replace('{order_id}', $orderId, $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);

    // initialize a new mail
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // if smtp status == 1, then set some value for PHPMailer
    if ($info->smtp_status == 1) {
      $mail->isSMTP();
      $mail->Host       = $info->smtp_host;
      $mail->SMTPAuth   = true;
      $mail->Username   = $info->smtp_username;
      $mail->Password   = $info->smtp_password;

      if ($info->encryption == 'TLS') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      }

      $mail->Port       = $info->smtp_port;
    }

    // finally add other informations and send the mail
    try {
      // Recipients
      $mail->setFrom($info->from_mail, $info->from_name);
      $mail->addAddress($orderInfo->billing_email);

      // Attachments (Invoice)
      $mail->addAttachment(public_path('assets/admin/file/order/invoices/') . $orderInfo->invoice_number);

      // Content
      $mail->isHTML(true);
      $mail->Subject = $mailSubject;
      $mail->Body    = $mailBody;

      $mail->send();

      return;
    } catch (\Exception $e) {
      return session()->flash('error', 'Mail could not be sent! Mailer Error: ' . $e);
    }
  }
  public function generateInvoice($orderInfo)
  {
    $fileName = $orderInfo->order_number . '.pdf';
    $directory = public_path('assets/admin/file/order/invoices/');

    @mkdir($directory, 0775, true);

    $fileLocated = $directory . $fileName;

    // get course title
    $language = $this->getLanguage();


    $width = "40%";
    $float = "right";
    $mb = "35px";
    $ml = "18px";

    PDF::loadView('frontend.shop.invoice', compact('orderInfo', 'width', 'float', 'mb', 'ml'))->save($fileLocated);

    return $fileName;
  }
}
