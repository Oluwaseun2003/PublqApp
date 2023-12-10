<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\CustomPage\Page;
use App\Models\CustomPage\PageContent;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class CustomPageController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the custom pages of that language from db
    $information['pages'] = DB::table('pages')
      ->join('page_contents', 'pages.id', '=', 'page_contents.page_id')
      ->where('page_contents.language_id', '=', $language->id)
      ->orderByDesc('pages.id')
      ->get();

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.custom-page.index', $information);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    // get all the languages from db
    $information['languages'] = Language::all();

    return view('backend.custom-page.create', $information);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $rules = ['status' => 'required'];

    $languages = Language::all();

    $messages = [];

    foreach ($languages as $language) {
      $slug = createSlug($request[$language->code . '_title']);
      $rules[$language->code . '_title'] = [
        'required',
        'max:255',
        function ($attribute, $value, $fail) use ($slug, $language) {
          $pcs = PageContent::where('language_id', $language->id)->get();
          foreach ($pcs as $key => $pc) {
            if (strtolower($slug) == strtolower($pc->slug)) {
              $fail('The title field must be unique for ' . $language->name . ' language.');
            }
          }
        }
      ];
      $rules[$language->code . '_content'] = 'min:15';

      $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language.';

      $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language.';

      $messages[$language->code . '_title.unique'] = 'The title field must be unique for ' . $language->name . ' language.';

      $messages[$language->code . '_content.min'] = 'The content field atleast have 15 characters for ' . $language->name . ' language.';
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $page = new Page();

    $page->status = $request->status;
    $page->save();

    foreach ($languages as $language) {
      $pageContent = new PageContent();
      $pageContent->language_id = $language->id;
      $pageContent->page_id = $page->id;
      $pageContent->title = $request[$language->code . '_title'];
      $pageContent->slug = createSlug($request[$language->code . '_title']);
      $pageContent->content = Purifier::clean($request[$language->code . '_content'], 'youtube');
      $pageContent->meta_keywords = $request[$language->code . '_meta_keywords'];
      $pageContent->meta_description = $request[$language->code . '_meta_description'];
      $pageContent->save();
    }

    Session::flash('success', 'Added Successfully');

    return Response::json(['status' => 'success'], 200);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $information['page'] = Page::where('id', $id)->firstOrFail();

    // get all the languages from db
    $information['languages'] = Language::all();

    return view('backend.custom-page.edit', $information);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $rules = ['status' => 'required'];

    $languages = Language::all();

    $messages = [];

    foreach ($languages as $language) {
      $slug = createSlug($request[$language->code . '_title']);
      $rules[$language->code . '_title'] = [
        'required',
        'max:255',
        function ($attribute, $value, $fail) use ($slug, $id, $language) {
          $pcs = PageContent::where('page_id', '<>', $id)->where('language_id', $language->id)->get();
          foreach ($pcs as $key => $pc) {
            if (strtolower($slug) == strtolower($pc->slug)) {
              $fail('The title field must be unique for ' . $language->name . ' language.');
            }
          }
        }
      ];

      $rules[$language->code . '_content'] = 'min:15';

      $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language.';

      $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language.';

      $messages[$language->code . '_title.unique'] = 'The title field must be unique for ' . $language->name . ' language.';

      $messages[$language->code . '_content.min'] = 'The content field atleast have 15 characters for ' . $language->name . ' language.';
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $page = Page::where('id', $id)->first();

    $page->update(['status' => $request->status]);

    foreach ($languages as $language) {
      $pageContent = PageContent::where('page_id', $id)
        ->where('language_id', $language->id)
        ->first();
      if (empty($pageContent)) {
        $pageContent = new PageContent();
      }
      $pageContent->language_id = $language->id;
      $pageContent->page_id = $id;
      $pageContent->title = $request[$language->code . '_title'];
      $pageContent->slug = createSlug($request[$language->code . '_title']);
      $pageContent->content = Purifier::clean($request[$language->code . '_content'], 'youtube');
      $pageContent->meta_keywords = $request[$language->code . '_meta_keywords'];
      $pageContent->meta_description = $request[$language->code . '_meta_description'];
      $pageContent->save();
    }

    Session::flash('success', 'Updated Successfully');
    return Response::json(['status' => 'success'], 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    Page::where('id', $id)->first()->delete();

    return redirect()->back()->with('success', 'Deleted Successfully');
  }

  /**
   * Remove the selected or all resources from storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      Page::where('id', $id)->first()->delete();
    }

    Session::flash('success', 'Deleted Successfully');

    return Response::json(['status' => 'success'], 200);
  }
}
