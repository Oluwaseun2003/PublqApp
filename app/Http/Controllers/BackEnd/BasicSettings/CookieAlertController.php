<?php

namespace App\Http\Controllers\BackEnd\BasicSettings;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\CookieAlert;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class CookieAlertController extends Controller
{
  public function cookieAlert(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the cookie alert info of that language from db
    $information['data'] = CookieAlert::where('language_id', $language->id)->first();

    // get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.basic-settings.cookie-alert', $information);
  }

  public function updateCookieAlert(Request $request)
  {
    $rules = [
      'cookie_alert_status' => 'required',
      'cookie_alert_btn_text' => 'required',
      'cookie_alert_text' => 'required'
    ];

    $message = [
      'cookie_alert_btn_text.required' => 'The cookie alert button text field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    // first, get the language info from db
    $lang = Language::where('code', $request->language)->first();

    // then, get the cookie alert info of that language from db
    $data = CookieAlert::where('language_id', $lang->id)->first();

    if ($data == null) {
      // if cookie alert of that language does not exist then create a new one
      CookieAlert::create($request->except('language_id', 'cookie_alert_text') + [
        'language_id' => $lang->id,
        'cookie_alert_text' => Purifier::clean($request->cookie_alert_text, 'youtube')
      ]);
    } else {
      // else update the existing cookie alert info of that language
      $data->update($request->all());
    }

    $request->session()->flash('success', 'Updated Successfully');

    return response()->json(['status' => 'success'], 200);
  }
}
