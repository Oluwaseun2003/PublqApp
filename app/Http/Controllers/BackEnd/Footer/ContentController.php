<?php

namespace App\Http\Controllers\BackEnd\Footer;

use App\Http\Controllers\Controller;
use App\Models\Footer\FooterContent;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class ContentController extends Controller
{
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the footer content info of that language from db
    $information['data'] = FooterContent::where('language_id', $language->id)->first();

    // also, get all the languages from db
    $information['langs'] = Language::all();

    $information['themeInfo'] = DB::table('basic_settings')->select('theme_version')->first();

    return view('backend.footer.content', $information);
  }

  public function update(Request $request)
  {
    $rules = [
      'footer_background_color' => 'required',
      'about_company' => function ($attribute, $value, $fail) {
        if ($value === null) {
          $fail('The ' . str_replace('_', ' ', $attribute) . ' field is required.');
        }
      },
      'copyright_text' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    // first, get the language info from db
    $language = Language::where('code', $request->language)->first();

    // then, get the footer content info of that language from db
    $data = FooterContent::where('language_id', $language->id)->first();

    if ($request->hasFile('footer_logo')) {
      $filename = time() . '.' . $request->file('footer_logo')->getClientOriginalExtension();
      $request->file('footer_logo')->move(public_path('assets/admin/img/footer_logo/'), $filename);
      $logo = $filename;
    } else {
      $logo = $data->footer_logo;
    }

    if ($data == null) {
      // if footer content of that language does not exist then create a new one
      FooterContent::create($request->except('language_id', 'copyright_text', 'footer_logo', 'about_company') + [
        'language_id' => $language->id,
        'footer_logo' => $logo,
        'about_company' => Purifier::clean($request->about_company),
        'copyright_text' => Purifier::clean($request->copyright_text)
      ]);
    } else {
      // else update the existing footer content info of that language
      $data->update($request->except('copyright_text', 'footer_logo', 'about_company') + [
        'copyright_text' => Purifier::clean($request->copyright_text),
        'about_company' => Purifier::clean($request->about_company),
        'footer_logo' => $logo,
      ]);
    }

    Session::flash('success', 'Updated Successfully');

    return Response::json(['status' => 'success'], 200);
  }
}
