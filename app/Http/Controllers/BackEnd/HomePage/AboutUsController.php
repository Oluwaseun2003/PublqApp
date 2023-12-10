<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\HomePage\AboutUsSection;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AboutUsController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['data'] = $language->aboutUsSec()->first();

    $information['langs'] = Language::all();

    return view('backend.home-page.about-us-section', $information);
  }

  public function update(Request $request)
  {
    $language = Language::where('code', $request->language)->first();

    $aboutUsInfo = $language->aboutUsSec()->first();

    $rules = [];

    if (empty($aboutUsInfo->image)) {
      $rules['image'] = 'required';
    }
    if ($request->hasFile('image')) {
      $rules['image'] = new ImageMimeTypeRule();
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    // store data in db start
    if (empty($aboutUsInfo)) {
      $imageName = UploadFile::store(public_path('assets/admin/img/about-us-section/'), $request->file('image'));

      AboutUsSection::create($request->except('language_id', 'image') + [
        'language_id' => $language->id,
        'image' => $imageName
      ]);

      Session::flash('success', 'Added Successfully');

      return redirect()->back();
    } else {
      if ($request->hasFile('image')) {
        $imageName = UploadFile::update(public_path('assets/admin/img/about-us-section/'), $request->file('image'), $aboutUsInfo->image);
      }

      $aboutUsInfo->update($request->except('image') + [
        'image' => isset($imageName) ? $imageName : $aboutUsInfo->image
      ]);

      Session::flash('success', 'Updated Successfully');

      return redirect()->back();
    }
  }
}
