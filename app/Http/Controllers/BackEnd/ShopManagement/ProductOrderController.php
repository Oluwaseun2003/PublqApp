<?php

namespace App\Http\Controllers\BackEnd\ShopManagement;

use App\Exports\ProductOrderExport;
use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\ShopManagement\OrderItem;
use App\Models\ShopManagement\ProductOrder;
use Carbon\Carbon;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PHPMailer\PHPMailer\PHPMailer;

class ProductOrderController extends Controller
{
  //index
  public function index(Request $request)
  {
    $type = $status = $order_id =  null;
    if ($request->filled('type')) {
      $type = $request['type'];
    }
    if ($request->filled('status')) {
      $status = $request['status'];
    }
    if ($request->filled('order_id')) {
      $order_id = $request['order_id'];
    }

    $orders = ProductOrder::when($type, function ($query, $type) {
      return $query->where('order_status', '=', $type);
    })
      ->when($status, function ($query, $status) {
        return $query->where('payment_status', '=', $status);
      })
      ->when($order_id, function ($query, $order_id) {
        return $query->where('order_number', 'like', '%' . $order_id . '%');
      })
      ->orderByDesc('id')
      ->paginate(10);

    return view('backend.product.order.index', compact('orders'));
  }
  //delete'
  public function delete(Request $request, $id)
  {
    $order_product = ProductOrder::where('id', $id)->first();
    $items = OrderItem::where('product_order_id', $id)->get();
    foreach ($items as $item) {
      $item->delete();
    }
    @unlink(public_path('assets/admin/file/order/attachments/') . $order_product->receipt);
    @unlink(public_path('assets/admin/file/order/invoices/') . $order_product->invoice_number);
    //finally delete
    $order_product->delete();
    return redirect()->back()->with('success', 'Order deleted successfully!');
  }
  //bulk_delete
  public function bulk_delete(Request $request)
  {
    $ids = $request->ids;
    foreach ($ids as $key => $id) {
      $order_product = ProductOrder::where('id', $id)->first();
      $items = OrderItem::where('product_order_id', $id)->get();
      foreach ($items as $item) {
        $item->delete();
      }
      @unlink(public_path('assets/admin/file/order/attachments/') . $order_product->receipt);
      @unlink(public_path('assets/admin/file/order/invoices/') . $order_product->invoice_number);
      //finally delete
      $order_product->delete();
    }
    Session::flash('success', 'Orders are deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }
  //updateStatus
  public function updateStatus(Request $request, $id)
  {
    $order = ProductOrder::find($id);

    if ($request['payment_status'] == 'completed') {
      $order->update([
        'payment_status' => 'completed'
      ]);

      $invoice = $this->generateInvoice($order);

      $order->update([
        'invoice_number' => $invoice
      ]);

      $this->sendMail($order, 'Order Payment approved');
    } else if ($request['payment_status'] == 'pending') {
      $order->update([
        'payment_status' => 'pending'
      ]);
    } else {
      $order->update([
        'payment_status' => 'rejected'
      ]);

      $this->sendMail($order, 'Order Payment rejected');
    }

    return redirect()->back();
  }

  public function sendMail($orderInfo, $title)
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
      return session()->flash('warning', 'Mail could not be sent! Mailer Error: ' . $e);
    }
  }

  //updateOrderStatus
  public function updateOrderStatus(Request $request, $id)
  {
    $order = ProductOrder::find($id);

    if ($request['order_status'] == 'processing') {
      $order->update([
        'order_status' => 'processing'
      ]);

      $this->sendOrderMail($order, 'Order is Processing Now');
    } else if ($request['order_status'] == 'pending') {
      $order->update([
        'order_status' => 'pending'
      ]);
    } elseif ($request['order_status'] == 'rejected') {
      $order->update([
        'order_status' => 'rejected'
      ]);

      $this->sendOrderMail($order, 'Order was Rejected');
    } elseif ($request['order_status'] == 'completed') {
      $order->update([
        'order_status' => 'completed'
      ]);

      $this->sendOrderMail($order, 'Order is completed');
    }

    return redirect()->back();
  }

  public function sendOrderMail($orderInfo, $title)
  {
    // first get the mail template info from db
    $mailTemplate = MailTemplate::where('mail_type', 'product_shipping')->first();
    $mailSubject = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // second get the website title & mail's smtp info from db
    $info = DB::table('basic_settings')
      ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();

    $customerName = $orderInfo->billing_fname . ' ' . $orderInfo->billing_lname;
    $orderId = $orderInfo->order_number;
    $orderStatus = $orderInfo->order_status;

    $websiteTitle = $info->website_title;

    $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
    $mailBody = str_replace('{order_id}', $orderId, $mailBody);
    $mailBody = str_replace('{status}', $orderStatus, $mailBody);
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
      // Content
      $mail->isHTML(true);
      $mail->Subject = $mailSubject;
      $mail->Body    = $mailBody;

      $mail->send();

      return;
    } catch (\Exception $e) {
      return Session::flash('warning', 'Mail could not be sent! Mailer Error: ' . $e);
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


    $width = "50%";
    $float = "right";
    $mb = "35px";
    $ml = "18px";

    PDF::loadView('frontend.shop.approve_invoice', compact('orderInfo', 'width', 'float', 'mb', 'ml'))->save($fileLocated);

    return $fileName;
  }
  //details
  public function details($id)
  {
    $order = ProductOrder::with('order_items')->where('id', $id)->firstOrFail();
    return view('backend.product.order.details', compact('order'));
  }

  public function report(Request $request)
  {

    $language = $this->getLanguage();

    $fromDate = $request->from_date;
    $toDate = $request->to_date;
    $paymentStatus = $request->payment_status;
    $paymentMethod = $request->payment_method;

    if (!empty($fromDate) && !empty($toDate)) {
      $orders = ProductOrder::when($fromDate, function ($query, $fromDate) {
        return $query->whereDate('product_orders.created_at', '>=', Carbon::parse($fromDate));
      })->when($toDate, function ($query, $toDate) {
        return $query->whereDate('product_orders.created_at', '<=', Carbon::parse($toDate));
      })->when($paymentMethod, function ($query, $paymentMethod) {
        return $query->where('product_orders.method', $paymentMethod);
      })->when($paymentStatus, function ($query, $paymentStatus) {
        return $query->where('product_orders.payment_status', '=', $paymentStatus);
      })

        ->orderByDesc('id');

      Session::put('order_report', $orders->get());
      $data['orders'] = $orders->paginate(10);
    } else {
      Session::put('order_report', []);
      $data['orders'] = [];
    }


    $data['onPms'] = OnlineGateway::where('status', 1)->get();
    $data['offPms'] = OfflineGateway::where('status', 1)->get();
    $data['deLang'] = $language;
    $data['abs'] = Basic::select('base_currency_symbol_position', 'base_currency_symbol')->first();


    return view('backend.product.order.report', $data);
  }

  //export
  public function export()
  {
    $orders = Session::get('order_report');
    if (empty($orders) || count($orders) == 0) {
      Session::flash('warning', 'There is no orders to export');
      return back();
    }
    return Excel::download(new ProductOrderExport($orders), 'orders.csv');
  }
}
