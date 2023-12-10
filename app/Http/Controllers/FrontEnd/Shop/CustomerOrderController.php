<?php

namespace App\Http\Controllers\FrontEnd\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ShopManagement\ProductOrder;

class CustomerOrderController extends Controller
{
  public function index()
  {
    $orders = ProductOrder::where('user_id', Auth::guard('customer')->user()->id)->orderBy('id', 'desc')->get();
    return view('frontend.customer.dashboard.order.my_order', compact('orders'));
  }
  //details
  public function details($id)
  {
    $order = ProductOrder::where('id', $id)->firstOrFail();
    if (Auth::guard('customer')->user()->id != $order->user_id) {
      return back();
    }
    return view('frontend.customer.dashboard.order.details', compact('order'));
  }
}
