<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\HomePage\HeroSection;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class HeroController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['data'] = $language->heroSec()->first();

    $information['langs'] = Language::all();

    $information['themeInfo'] = DB::table('basic_settings')->select('theme_version')->first();

    return view('backend.home-page.hero-section', $information);
  }

  public function update(Request $request)
  {
    $language = Language::where('code', $request->language)->first();

    $heroInfo = $language->heroSec()->first();

    $themeInfo = DB::table('basic_settings')->select('theme_version')->first();

    $rules = [];

    if (empty($heroInfo)) {
      $rules['background_image'] = 'required';
    }
    if ($request->hasFile('background_image')) {
      $rules['background_image'] = new ImageMimeTypeRule();
    }

    if ($themeInfo->theme_version == 3 && $request->hasFile('image')) {
      $rules['image'] = new ImageMimeTypeRule();
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    // format video link
    $link = NULL;

    if ($request->filled('video_url')) {
      $link = $request->video_url;

      if (strpos($link, '&') != 0) {
        $link = substr($link, 0, strpos($link, '&'));
      }
    }

    // insert data into db
    if (empty($heroInfo)) {
      $backgroundImageName = UploadFile::store(public_path('assets/admin/img/hero-section/'), $request->file('background_image'));

      $imageName = NULL;

      if ($themeInfo->theme_version == 3 && $request->hasFile('image')) {
        $imageName = UploadFile::store(public_path('assets/admin/img/hero-section/'), $request->file('image'));
      }

      HeroSection::create($request->except('language_id', 'background_image', 'image', 'video_url') + [
        'language_id' => $language->id,
        'background_image' => $backgroundImageName,
        'image' => $imageName,
        'video_url' => $link
      ]);

      Session::flash('success', 'Added Successfully');

      return redirect()->back();
    } else {
      if ($request->hasFile('background_image')) {
        $backgroundImageName = UploadFile::update(public_path('assets/admin/img/hero-section/'), $request->file('background_image'), $heroInfo->background_image);
      }

      if ($themeInfo->theme_version == 3 && $request->hasFile('image')) {
        $imageName = UploadFile::update(public_path('assets/admin/img/hero-section/'), $request->file('image'), $heroInfo->image);
      }

      $heroInfo->update($request->except('background_image', 'image', 'video_url') + [
        'background_image' => $request->hasFile('background_image') ? $backgroundImageName : $heroInfo->background_image,
        'image' => $request->hasFile('image') ? $imageName : $heroInfo->image,
        'video_url' => $link
      ]);

      Session::flash('success', 'Updated Successfully');

      return redirect()->back();
    }
  }
}
