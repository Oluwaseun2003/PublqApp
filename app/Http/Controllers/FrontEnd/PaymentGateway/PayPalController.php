<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Event\BookingController;
use App\Models\BasicSettings\Basic;
use App\Models\Earning;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PayPalController extends Controller
{
  private $api_context;

  public function __construct()
  {
    $data = OnlineGateway::whereKeyword('paypal')->first();
    $paypalData = json_decode($data->information, true);


    $paypal_conf = Config::get('paypal');
    $paypal_conf['client_id'] = $paypalData['client_id'];
    $paypal_conf['secret'] = $paypalData['client_secret'];
    $paypal_conf['settings']['mode'] = $paypalData['sandbox_status'] == 1 ? 'sandbox' : 'live';

    $this->api_context = new ApiContext(
      new OAuthTokenCredential(
        $paypal_conf['client_id'],
        $paypal_conf['secret']
      )
    );

    $this->api_context->setConfig($paypal_conf['settings']);
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

    $booking = new BookingController();

    $currencyInfo = $this->getCurrencyInfo();

    $total = Session::get('grand_total');
    $quantity = Session::get('quantity');
    $discount = Session::get('discount');

    //tax and commission end
    $basicSetting = Basic::select('commission')->first();

    $tax_amount = Session::get('tax');
    $commission_amount = ($total * $basicSetting->commission) / 100;

    $total_early_bird_dicount = Session::get('total_early_bird_dicount');
    // changing the currency before redirect to PayPal
    if ($currencyInfo->base_currency_text !== 'USD') {
      $rate = floatval($currencyInfo->base_currency_rate);
      $convertedTotal = round((($total + $tax_amount) / $rate), 2);
    }

    $paypalTotal = $currencyInfo->base_currency_text === 'USD' ? $total + $tax_amount : $convertedTotal;

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
      'zip_code' => $request->zip_code,
      'address' => $request->address,
      'paymentMethod' => 'PayPal',
      'gatewayType' => 'online',
      'paymentStatus' => 'completed',
    );

    $title = 'Event Booking';
    $notifyURL = route('event_booking.paypal.notify');
    $cancelURL = route('event_booking.cancel', ['id' => $event_id]);
    $payer = new Payer();
    $payer->setPaymentMethod('paypal');
    $item_1 = new Item();
    $item_1->setName($title)
      /** item name **/
      ->setCurrency('USD')
      ->setQuantity(1)
      ->setPrice($paypalTotal);
    /** unit price **/
    $item_list = new ItemList();
    $item_list->setItems(array($item_1));
    $amount = new Amount();
    $amount->setCurrency('USD')
      ->setTotal($paypalTotal);
    $transaction = new Transaction();
    $transaction->setAmount($amount)
      ->setItemList($item_list)
      ->setDescription($title . ' via PayPal');
    $redirect_urls = new RedirectUrls();
    $redirect_urls->setReturnUrl($notifyURL)
      /** Specify return URL **/
      ->setCancelUrl($cancelURL);
    $payment = new Payment();
    $payment->setIntent('Sale')
      ->setPayer($payer)
      ->setRedirectUrls($redirect_urls)
      ->setTransactions(array($transaction));
    try {
      $payment->create($this->api_context);
    } catch (\Exception $ex) {
      Session::put('paypal_error', $ex->getMessage());
      return redirect($cancelURL)->with('error', $ex->getMessage());
    }

    foreach ($payment->getLinks() as $link) {
      if ($link->getRel() == 'approval_url') {
        $redirectURL = $link->getHref();
        break;
      }
    }

    // put some data in session before redirect to paypal url
    $request->session()->put('event_id', $event_id);
    $request->session()->put('arrData', $arrData);
    $request->session()->put('paymentId', $payment->getId());

    if (isset($redirectURL)) {
      /** redirect to paypal **/
      return Redirect::away($redirectURL);
    }
  }

  public function notify(Request $request)
  {
    try {
      // get the information from session
      $event_id = $request->session()->get('event_id');
      $arrData = $request->session()->get('arrData');
      $paymentId = $request->session()->get('paymentId');

      $urlInfo = $request->all();

      if (empty($urlInfo['token']) || empty($urlInfo['PayerID'])) {
        return redirect()->route('event_booking.cancel', ['id' => $event_id]);
      }

      /** Execute The Payment **/
      $payment = Payment::get($paymentId, $this->api_context);
      $execution = new PaymentExecution();
      $execution->setPayerId($urlInfo['PayerID']);
      $result = $payment->execute($execution, $this->api_context);



      if ($result->getState() == 'approved') {
        $enrol = new BookingController();

        $bookingInfo['transcation_type'] = 1;

        // store the course enrolment information in database
        $bookingInfo = $enrol->storeData($arrData);
        // generate an invoice in pdf format
        $invoice = $enrol->generateInvoice($bookingInfo, $event_id);
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
        return redirect()->route('event_booking.complete', ['id' => $event_id, 'booking_id' => $bookingInfo->id]);
      } else {
        // remove all session data
        $request->session()->forget('event_id');
        $request->session()->forget('selTickets');
        $request->session()->forget('arrData');
        $request->session()->forget('paymentId');
        $request->session()->forget('discount');
        return redirect()->route('event_booking.cancel', ['id' => $event_id]);
      }
    } catch (\Exception $th) {
    }
  }
}
