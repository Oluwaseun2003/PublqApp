<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the faqs of that language from db
    $information['faqs'] = FAQ::where('language_id', $language->id)
      ->orderBy('id', 'desc')
      ->get();

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.faq.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'question' => 'required',
      'answer' => 'required',
      'serial_number' => 'required'
    ];

    $message = [
      'language_id.required' => 'The language field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    FAQ::create($request->all());

    Session::flash('success', 'Added Successfully');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rules = [
      'question' => 'required',
      'answer' => 'required',
      'serial_number' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    FAQ::find($request->id)->update($request->all());

    Session::flash('success', 'Updated Successfully');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    FAQ::find($id)->delete();

    return redirect()->back()->with('success', 'Deleted Successfully');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      FAQ::find($id)->delete();
    }

    Session::flash('success', 'Deleted Successfully');

    return Response::json(['status' => 'success'], 200);
  }
}
