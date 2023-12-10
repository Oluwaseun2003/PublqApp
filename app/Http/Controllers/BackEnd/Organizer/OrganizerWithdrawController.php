<?php

namespace App\Http\Controllers\BackEnd\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawRequest;
use App\Models\Language;
use App\Models\Organizer;
use App\Models\Transaction;
use App\Models\Withdraw;
use App\Models\WithdrawMethodInput;
use App\Models\WithdrawPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OrganizerWithdrawController extends Controller
{
  public function index()
  {
    $currencyInfo = $this->getCurrencyInfo();
    $collection = Withdraw::with('method')->where('organizer_id', Auth::guard('organizer')->user()->id)->orderby('id', 'desc')->get();
    return view('organizer.withdraw.index', compact('collection', 'currencyInfo'));
  }
  //create
  public function create()
  {
    $information = [];
    $language = Language::where('code', request()->input('language'))->firstOrFail();
    $lang_id = $language->id;
    $lang = Language::where('id', $lang_id)->firstOrFail();
    $methods = WithdrawPaymentMethod::where('status', '=', 1)->get();
    $information['lang'] = $lang;
    $information['methods'] = $methods;
    return view('organizer.withdraw.create', $information);
  }
  //get_inputs
  public function get_inputs($id)
  {
    $data = WithdrawMethodInput::with('options')->where('withdraw_payment_method_id', $id)->orderBy('order_number', 'asc')->get();

    return $data;
  }
  //balance_calculation
  public function balance_calculation($method, $amount)
  {
    if (Auth::guard('organizer')->user()->amount < $amount) {
      return 'error';
    }
    $method = WithdrawPaymentMethod::find($method);
    $fixed_charge = $method->fixed_charge;
    $percentage = $method->percentage_charge;

    $percentage_balance = (($amount - $fixed_charge) * $percentage) / 100;

    $total_charge = $percentage_balance + $fixed_charge;
    $receive_balance = $amount - $total_charge;
    $user_balance = Auth::guard('organizer')->user()->amount - $amount;


    return ['total_charge' => round($total_charge, 2), 'receive_balance' => round($receive_balance, 2), 'user_balance' => round($user_balance, 2)];
  }
  //send_request
  public function send_request(WithdrawRequest $request)
  {
    $method = WithdrawPaymentMethod::where('id', $request->withdraw_method)->first();

    $organizer = Organizer::where('id', Auth::guard('organizer')->user()->id)->first();


    if (intval($request->withdraw_amount) < $method->min_limit) {
      return Response::json(
        [
          'errors' => [
            'withdraw_amount' => [
              'Minimum withdraw limit is ' . $method->min_limit
            ]
          ]
        ],
        400
      );
    } elseif (intval($request->withdraw_amount) > $method->max_limit) {
      return Response::json(
        [
          'errors' => [
            'withdraw_amount' => [
              'Maximum withdraw limit is ' . $method->max_limit
            ]
          ]
        ],
        400
      );
    } elseif ($organizer->amount < $request->withdraw_amount) {
      return Response::json(
        [
          'errors' => [
            'withdraw_amount' => [
              'You do not have enough balance to Withdraw.'
            ]
          ]
        ],
        400
      );
    }

    $rules = [
      'withdraw_method' => 'required',
      'withdraw_amount' => 'required'
    ];

    $inputs = WithdrawMethodInput::where('withdraw_payment_method_id', $request->withdraw_method)->orderBy('order_number', 'asc')->get();

    foreach ($inputs as $input) {
      if ($input->required == 1) {
        $rules["$input->name"] = 'required';
      }

      $fields = [];
      foreach ($inputs as $key => $input) {
        $in_name = $input->name;
        if ($request["$in_name"]) {
          $fields["$in_name"] = $request["$in_name"];
        }
      }
      $jsonfields = json_encode($fields);
      $jsonfields = str_replace("\/", "/", $jsonfields);;
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    //calculation

    $fixed_charge = $method->fixed_charge;
    $percentage = $method->percentage_charge;

    $percentage_balance = (($request->withdraw_amount - $fixed_charge) * $percentage) / 100;
    $total_charge = $percentage_balance + $fixed_charge;
    $receive_balance = $request->withdraw_amount - $total_charge;
    //calculation end


    $save = new Withdraw;
    $save->withdraw_id = uniqid();
    $save->organizer_id = Auth::guard('organizer')->user()->id;
    $save->method_id = $request->withdraw_method;


    $organizer = Organizer::where('id', Auth::guard('organizer')->user()->id)->first();
    $pre_balance = $organizer->amount;
    $organizer->amount = ($organizer->amount - ($request->withdraw_amount));
    $organizer->save();
    $after_balance = $organizer->amount;

    $save->amount = $request->withdraw_amount;
    $save->payable_amount = $receive_balance;
    $save->total_charge = $total_charge;
    $save->additional_reference = $request->additional_reference;
    $save->feilds = json_encode($fields);
    $save->save();

    //store data to transcation table 
    $currencyInfo = $this->getCurrencyInfo();
    $transcation = Transaction::create([
      'transcation_id' => time(),
      'booking_id' => $save->id,
      'transcation_type' => 3,
      'user_id' => null,
      'organizer_id' => Auth::guard('organizer')->user()->id,
      'payment_status' => 0,
      'payment_method' => $save->method_id,
      'grand_total' => $save->amount,
      'pre_balance' => $pre_balance,
      'after_balance' => $after_balance,
      'gateway_type' => null,
      'currency_symbol' => $currencyInfo->base_currency_symbol,
      'currency_symbol_position' => $currencyInfo->base_currency_text_position,
    ]);

    Session::flash('success', 'Withdraw Request Send Successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  //Delete
  public function Delete(Request $request)
  {
    $delete = Withdraw::where([['organizer_id', Auth::guard('organizer')->user()->id], ['id', $request->id]])->first();
    $delete->delete();
    return redirect()->back()->with('success', 'Withdraw Request Deleted Successfully!');
  }

  //bulkDelete
  public function bulkDelete(Request $request)
  {
    $ids = $request->ids;
    foreach ($ids as $id) {
      $withdraw = Withdraw::where([['organizer_id', Auth::guard('organizer')->user()->id], ['id', $id]])->first();
      $withdraw->delete();
    }
    Session::flash('success', 'Deleted Successfully');

    return Response::json(['status' => 'success'], 200);
  }
}
