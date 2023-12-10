<?php

namespace App\Http\Controllers\BackEnd\BasicSettings;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\SEO;
use App\Models\Language;
use Illuminate\Http\Request;

class SEOController extends Controller
{
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the seo info of that language from db
    $information['data'] = SEO::where('language_id', $language->id)->first();

    // get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.basic-settings.seo', $information);
  }

  public function update(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->first();

    // then, get the seo info of that language from db
    $seoInfo = SEO::where('language_id', $language->id)->first();

    if ($seoInfo == null) {
      // if seo info of that language does not exist then create a new one
      SEO::create($request->except('language_id') + [
        'language_id' => $language->id
      ]);
    } else {
      // else update the existing seo info of that language
      $seoInfo->update($request->all());
    }

    $request->session()->flash('success', 'Updated Successfully');

    return redirect()->back();
  }
}
