<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\ContactPage;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ContactController extends Controller
{
  public function index(Request $request)
  {
    $lang = Language::where('code', $request->language)->firstOrFail();
    $data['langs'] = Language::get();
    $data['lang_id'] = $lang->id;
    $data['abs'] = ContactPage::where('language_id', $lang->id)->first();
    return view('backend.contact', $data);
  }

  public function update(Request $request, $langid)
  {
    $lang = Language::where('code', $langid)->first();
    $request->validate([
      'contact_form_title' => 'required|max:255',
      'contact_form_subtitle' => 'required|max:255',
      'contact_addresses' => 'required',
      'contact_numbers' => 'required',
      'contact_mails' => 'required',
      'latitude' => 'nullable|max:255',
      'longitude' => 'nullable|max:255',
      'map_zoom' => 'nullable|max:255',
    ]);

    $bs = ContactPage::firstOrNew(array('language_id' => $lang->id));
    $bs->contact_form_title = $request->contact_form_title;
    $bs->contact_form_subtitle = $request->contact_form_subtitle;
    $bs->contact_addresses = $request->contact_addresses;
    $bs->contact_numbers = $request->contact_numbers;
    $bs->contact_mails = $request->contact_mails;
    $bs->latitude = $request->latitude;
    $bs->longitude = $request->longitude;
    $bs->map_zoom = $request->map_zoom;
    $bs->language_id = $lang->id;
    $bs->save();

    Session::flash('success', 'Updated Successfully');
    return response()->json(['status' => 'success'], 200);
  }
}
