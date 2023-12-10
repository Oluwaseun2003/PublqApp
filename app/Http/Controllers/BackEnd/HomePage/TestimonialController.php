<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Testimonial\StoreRequest;
use App\Http\Requests\Testimonial\UpdateRequest;
use App\Models\HomePage\Testimonial;
use App\Models\HomePage\TestimonialSection;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['data'] = TestimonialSection::where('language_id', $language->id)->first();

    $information['testimonials'] = $language->testimonial()->orderByDesc('id')->get();

    $information['langs'] = Language::all();
    $information['themeInfo'] = DB::table('basic_settings')->select('theme_version')->first();
    return view('backend.home-page.testimonial-section.index', $information);
  }

  public function updateImage(Request $request)
  {
    $language = Language::where('code', $request->language_code)->first();
    $data = TestimonialSection::where('language_id', $language->id);



    $rules = [];

    if ($request->hasFile('image')) {
      $rules['image'] = new ImageMimeTypeRule();
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('image')) {
      if ($data->count() == 0) {
        $imgName = UploadFile::store(public_path('assets/admin/img/testimonial'), $request->file('image'));
      } else {
        $imgName = UploadFile::update(public_path('assets/admin/img/testimonial'), $request->file('image'), $data->image);
      }

      $datas['language_id'] = $language->id;
      $datas['title'] = $request->title;
      $datas['text'] = $request->text;
      $datas['review_text'] = $request->review_text;
      $datas['image'] = $imgName;
      if ($data->count() == 0) {
        TestimonialSection::create($datas);
      } else {
        $data->update($datas);
      }
      Session::flash('success', 'Updated Successfully');
    } else {
      $datas['language_id'] = $language->id;
      $datas['title'] = $request->title;
      $datas['text'] = $request->text;
      $datas['review_text'] = $request->review_text;
      if ($data->count() == 0) {
        TestimonialSection::create($datas);
      } else {
        $data->update($datas);
      }
      Session::flash('success', 'Updated Successfully');
    }

    return redirect()->back();
  }


  public function store(StoreRequest $request)
  {
    $imageName = UploadFile::store(public_path('assets/admin/img/clients/'), $request->file('image'));

    Testimonial::create($request->except('image') + [
      'image' => $imageName
    ]);

    Session::flash('success', 'Added Successfully');

    return response()->json(['status' => 'success'], 200);
  }

  public function update(UpdateRequest $request)
  {
    $testimonial = Testimonial::find($request->id);

    if ($request->hasFile('image')) {
      $imageName = UploadFile::update(public_path('assets/admin/img/clients/'), $request->file('image'), $testimonial->image);
    }

    $testimonial->update($request->except('image') + [
      'image' => $request->hasFile('image') ? $imageName : $testimonial->image
    ]);

    Session::flash('success', 'Updated Successfully');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $testimonial = Testimonial::find($id);

    // delete client picture
    @unlink(public_path('assets/admin/img/clients/') . $testimonial->image);

    $testimonial->delete();

    return redirect()->back()->with('success', 'Deleted Successfully');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $testimonial = Testimonial::find($id);

      // delete client picture
      @unlink(public_path('assets/admin/img/clients/') . $testimonial->image);

      $testimonial->delete();
    }

    Session::flash('success', 'Deleted Successfully');

    return response()->json(['status' => 'success'], 200);
  }
}
