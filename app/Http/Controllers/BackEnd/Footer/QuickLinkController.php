<?php

namespace App\Http\Controllers\BackEnd\Footer;

use App\Http\Controllers\Controller;
use App\Models\Footer\QuickLink;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class QuickLinkController extends Controller
{
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the quick-links of that language from db
    $information['quickLinks'] = QuickLink::where('language_id', $language->id)
      ->orderBy('id', 'desc')
      ->get();

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.footer.quick-link.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'title' => 'required',
      'url' => 'required',
      'serial_number' => 'required|numeric'
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

    QuickLink::create($request->all());

    Session::flash('success', 'Added Successfully');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rules = [
      'title' => 'required',
      'url' => 'required',
      'serial_number' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    QuickLink::where('id', $request->id)->first()->update($request->all());

    Session::flash('success', 'Updated Successfully');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    QuickLink::where('id', $id)->first()->delete();

    return redirect()->back()->with('success', 'Deleted Successfully');
  }
}
