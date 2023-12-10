<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\FeatureRequest;
use App\Models\HomePage\Feature;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class FeatureController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['data'] = DB::table('basic_settings')->select('features_section_image')->firstOrFail();

    $information['features'] = $language->feature()->orderByDesc('id')->get();

    $information['langs'] = Language::all();

    $information['themeInfo'] = DB::table('basic_settings')->select('theme_version')->first();

    return view('backend.home-page.feature-section.index', $information);
  }

  public function updateImage(Request $request)
  {
    $data = DB::table('basic_settings')->select('features_section_image')->first();

    $rules = [];

    if (!$request->filled('features_section_image') && empty($data->features_section_image)) {
      $rules['features_section_image'] = 'required';
    }
    if ($request->hasFile('features_section_image')) {
      $rules['features_section_image'] = new ImageMimeTypeRule();
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('features_section_image')) {
      $imgName = UploadFile::update(public_path('assets/admin/img/'), $request->file('features_section_image'), $data->features_section_image);

      // finally, store the image into db
      DB::table('basic_settings')->updateOrInsert(
        ['uniqid' => 12345],
        ['features_section_image' => $imgName]
      );

      Session::flash('success', 'Updated Successfully');
    }

    return redirect()->back();
  }


  public function store(FeatureRequest $request)
  {
    Feature::create($request->all());

    Session::flash('success', 'Added Successfully');

    return response()->json(['status' => 'success'], 200);
  }

  public function update(FeatureRequest $request)
  {
    Feature::find($request->id)->update($request->all());

    Session::flash('success', 'Updated Successfully');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    Feature::find($id)->delete();

    return redirect()->back()->with('success', 'Deleted Successfully');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      Feature::find($id)->delete();
    }

    Session::flash('success', 'Deleted Successfully');

    return response()->json(['status' => 'success'], 200);
  }
}
