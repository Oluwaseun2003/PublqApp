<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EventFeatureRequest;
use App\Models\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\HomePage\EventFeatureSection;
use App\Models\HomePage\EventFeature;
use Illuminate\Support\Facades\Session;

class EventFeatureController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['data'] = DB::table('event_feature_sections')->where('language_id', $language->id)->first();

    $information['features'] = $language->event_feature()->orderByDesc('id')->get();

    $information['langs'] = Language::all();

    $information['themeInfo'] = DB::table('basic_settings')->select('theme_version')->first();

    return view('backend.home-page.event-feature.index', $information);
  }
  public function update(Request $request)
  {
    $datas = [];
    $rules = [];

    $rules['title'] = 'required';

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $language = Language::where('code', $request->language_code)->first();
    $data = EventFeatureSection::where('language_id', $language->id)->first();

    $datas['language_id'] = $language->id;
    $datas['title'] = $request->title;
    $datas['text'] = $request->text;
    if(empty($data)){
      EventFeatureSection::create($datas);
    }else{
      $data->update($datas);
    }
    Session::flash('success', 'Updated Successfully');

    return redirect()->back();
  }
  public function store(EventFeatureRequest $request){
    EventFeature::create($request->all());

    Session::flash('success', 'Added Successfully');

    return response()->json(['status' => 'success'], 200);
  }
  public function update_feature(EventFeatureRequest $request)
  {
    EventFeature::find($request->id)->update($request->all());

    Session::flash('success', 'Updated Successfully');

    return response()->json(['status' => 'success'], 200);
  }
  //delete
  public function delete($id){
    EventFeature::find($id)->delete();
    return redirect()->back()->with('success', 'Deleted Successfully');
  }
  public function bulk_delete(Request $request){
    $ids = $request->ids;

    foreach ($ids as $id) {
      EventFeature::find($id)->delete();
    }

    Session::flash('success', 'Deleted Successfully');

    return response()->json(['status' => 'success'], 200);
  }
}
