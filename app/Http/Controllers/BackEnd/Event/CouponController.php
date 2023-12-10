<?php

namespace App\Http\Controllers\BackEnd\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Event;
use App\Models\Event\Coupon;
use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
  public function index()
  {
    // get the coupons from db
    $information['coupons'] = Coupon::orderByDesc('id')->get();
    $information['events'] = Event::get();
    $information['deLang'] = Language::where('is_default', 1)->firstOrFail();

    // also, get the currency information from db
    $information['currencyInfo'] = $this->getCurrencyInfo();

    return view('backend.event.coupon.index', $information);
  }

  public function store(CouponRequest $request)
  {
    $startDate = Carbon::parse($request->start_date);
    $endDate = Carbon::parse($request->end_date);

    Coupon::create($request->except('start_date', 'end_date', 'events') + [
      'events' => json_encode($request->events),
      'start_date' => date_format($startDate, 'Y-m-d'),
      'end_date' => date_format($endDate, 'Y-m-d')
    ]);

    Session::flash('success', 'Added Successfully');

    return response()->json(['status' => 'success'], 200);
  }

  public function update(CouponRequest $request)
  {
    $startDate = Carbon::parse($request->start_date);
    $endDate = Carbon::parse($request->end_date);
    $events = !empty($request->events) ? json_encode($request->events) : NULL;

    Coupon::where('id', $request->id)->first()->update(
      $request->except('start_date', 'end_date', 'events') + [
        'events' => $events,
        'start_date' => date_format($startDate, 'Y-m-d'),
        'end_date' => date_format($endDate, 'Y-m-d')
      ]
    );

    Session::flash('success', 'Updated Successfully');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    Coupon::where('id', $id)->first()->delete();

    return redirect()->back()->with('success', 'Deleted Successfully');
  }
}
