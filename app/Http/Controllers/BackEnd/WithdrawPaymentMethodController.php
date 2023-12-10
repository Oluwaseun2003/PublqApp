<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawPaymentMethodRequest;
use App\Models\WithdrawPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WithdrawPaymentMethodController extends Controller
{
  public function index(Request $request)
  {
    $information = [];
    $collection =  WithdrawPaymentMethod::get();
    $information['collection'] = $collection;
    return view('backend.withdraw.index', $information);
  }
  //store
  public function store(WithdrawPaymentMethodRequest $request)
  {
    WithdrawPaymentMethod::create($request->all());
    Session::flash('success', 'Added Successfully');

    return response()->json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $request->validate([
      'min_limit' => 'required',
      'max_limit' => 'required',
      'name' => 'required',
      'status' => 'required'
    ]);
    WithdrawPaymentMethod::where('id', $request->id)->first()->update($request->all());
    Session::flash('success', 'Updated Successfully');

    return response()->json(['status' => 'success'], 200);
  }
  public function destroy($id)
  {
    $method = WithdrawPaymentMethod::where('id', $id)->first();

    //withdraws
    $withdraws = $method->withdraws()->get();
    foreach ($withdraws as $withdraw) {
      $withdraw->delete();
    }
    //inputs
    $inputs = $method->inputs()->get();
    foreach ($inputs as $input) {
      $options = $input->options()->get();
      foreach ($options as $option) {
        $option->delete();
      }
      $input->delete();
    }

    $method->delete();
    //finally delete

    return redirect()->back()->with('success', 'Deleted Successfully');
  }
}
