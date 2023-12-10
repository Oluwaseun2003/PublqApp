<?php

namespace App\Http\Controllers\BackEnd\BasicSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageHeadingRequest;
use App\Models\BasicSettings\PageHeading;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PageHeadingController extends Controller
{
  public function pageHeadings(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the page headings info of that language from db
    $information['data'] = PageHeading::where('language_id', $language->id)->first();

    // get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.basic-settings.page-headings', $information);
  }

  public function updatePageHeadings(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->first();

    // then, get the page heading info of that language from db
    $heading = $language->pageName()->first();

    if ($heading == null) {
      // if page heading of that language does not exist then create a new one
      PageHeading::create($request->except('language_id') + [
        'language_id' => $language->id
      ]);
    } else {
      // else update the existing page heading info of that language
      $heading->update($request->all());
    }

    Session::flash('success', 'Updated Successfully');

    return redirect()->back();
  }
}
