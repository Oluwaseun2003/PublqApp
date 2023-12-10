<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HomePage\SectionTitle;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SectionTitleController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['data'] = $language->sectionTitle()->first();

    $information['langs'] = Language::all();

    $information['themeInfo'] = DB::table('basic_settings')->select('theme_version')->first();

    return view('backend.home-page.section-titles', $information);
  }

  public function update(Request $request)
  {
    $language = Language::where('code', $request->language)->first();

    $titleInfo = $language->sectionTitle()->first();

    if (empty($titleInfo)) {
      SectionTitle::create($request->except('language_id') + [
        'language_id' => $language->id
      ]);

      Session::flash('success', 'Added Successfully');

      return redirect()->back();
    } else {
      $titleInfo->update($request->all());

      Session::flash('success', 'Updated Successfully');

      return redirect()->back();
    }
  }
}
