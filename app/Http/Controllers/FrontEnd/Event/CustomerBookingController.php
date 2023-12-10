<?php

namespace App\Http\Controllers\FrontEnd\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerBookingController extends Controller
{
  public function my_booking()
  {
    $bookings = Booking::where('customer_id', Auth::guard('customer')->user()->id)->orderBy('created_at', 'desc')->paginate(20);
    return view('frontend.customer.dashboard.booking.my_booking', compact('bookings'));
  }
  //details
  public function details($id)
  {
    $booking = Booking::where('id', $id)->firstOrFail();
    if (Auth::guard('customer')->user()->id != $booking->customer_id) {
      return back();
    }
    return view('frontend.customer.dashboard.booking.details', compact('booking'));
  }
}
