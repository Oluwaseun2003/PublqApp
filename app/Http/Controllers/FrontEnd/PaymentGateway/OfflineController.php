<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Event\BookingController;
use App\Models\BasicSettings\Basic;
use App\Models\PaymentGateway\OfflineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class OfflineController extends Controller
{
  public function bookingProcess(Request $request, $eventId)
  {
    $request->validate([
      'fname' => 'required',
      'lname' => 'required',
      'email' => 'required',
      'phone' => 'required',
      'country' => 'required',
      'address' => 'required',
      'gateway' => 'required',
    ]);
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

    $booking = new BookingController();

    $currencyInfo = $this->getCurrencyInfo();
    $total = Session::get('grand_total');
    $quantity = Session::get('quantity');
    $discount = Session::get('discount');
    $total_early_bird_dicount = Session::get('total_early_bird_dicount');

    //tax and commission end


    $basicSetting = Basic::select('tax', 'commission')->first();

    $tax_amount = Session::get('tax');
    $commission_amount = ($total * $basicSetting->commission) / 100;

    $arrData = array(
      'event_id' => $eventId,
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
      'zip_code' => $request->city,
      'address' => $request->address,
      'paymentMethod' => $offlineGateway->name,
      'gatewayType' => 'offline',
      'paymentStatus' => 'pending',
    );

    if ($request->hasFile('attachment')) {
      $filename = time() . '.' . $request->file('attachment')->getClientOriginalExtension();
      @mkdir(public_path('assets/admin/file/attachments/'), 0775, true);
      $request->file('attachment')->move(public_path('assets/admin/file/attachments/'), $filename);
      $arrData['attachmentFile'] = $filename;
    }
    // store the course enrolment information in database
    $bookingInfo = $booking->storeData($arrData);

    $request->session()->forget('event_id');
    $request->session()->forget('selTickets');
    $request->session()->forget('arrData');
    $request->session()->forget('discount');

    return redirect()->route('event_booking.complete', ['id' => $eventId, 'via' => 'offline', 'booking_id' => $bookingInfo->id]);
  }
}
