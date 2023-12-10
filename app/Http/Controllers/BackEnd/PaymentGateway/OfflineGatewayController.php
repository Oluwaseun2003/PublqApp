<?php

namespace App\Http\Controllers\BackEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway\OfflineGateway;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class OfflineGatewayController extends Controller
{
  public function index()
  {
    $offlineGateways = OfflineGateway::orderBy('id', 'desc')->get();

    return view('backend.payment-gateways.offline-gateways.index', compact('offlineGateways'));
  }

  public function store(Request $request)
  {
    $rules = [
      'name' => 'required',
      'has_attachment' => 'required',
      'serial_number' => 'required|numeric'
    ];

    $message = [
      'has_attachment.required' => 'The attachment field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    OfflineGateway::create($request->except('instructions') + [
      'instructions' => Purifier::clean($request->instructions, 'youtube')
    ]);

    Session::flash('success', 'Added Successfully');

    return Response::json(['status' => 'success'], 200);
  }

  public function updateStatus(Request $request, $id)
  {
    try {
      $offlineGateway = OfflineGateway::where('id', $id)->first();

      if ($request->status == 1) {
        $offlineGateway->update(['status' => 1]);
      } else {
        $offlineGateway->update(['status' => 0]);
      }

      Session::flash('success', 'Updated Successfully');
    } catch (ModelNotFoundException $e) {
      Session::flash('warning', 'No record found in database!');
    }

    return redirect()->back();
  }

  public function update(Request $request)
  {
    $rules = [
      'name' => 'required',
      'has_attachment' => 'required',
      'serial_number' => 'required|numeric'
    ];

    $message = [
      'has_attachment.required' => 'The attachment field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    try {
      $gateway = OfflineGateway::where('id', $request->id)->first();

      $gateway->update($request->except('instructions') + [
        'instructions' => Purifier::clean($request->instructions, 'youtube')
      ]);

      Session::flash('success', 'Updated Successfully');
    } catch (ModelNotFoundException $e) {
      Session::flash('warning', 'No record found in database!');
    }

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    try {
      OfflineGateway::where('id', $id)->first()->delete();

      return redirect()->back()->with('success', 'Deleted Successfully');
    } catch (ModelNotFoundException $e) {
      return redirect()->back()->with('warning', 'No record found in database!');
    }
  }
}
