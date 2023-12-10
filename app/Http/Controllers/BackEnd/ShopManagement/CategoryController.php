<?php

namespace App\Http\Controllers\BackEnd\ShopManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopManagement\CategoryRequest;
use App\Models\Language;
use App\Models\ShopManagement\ProductCategory;
use App\Models\ShopManagement\ProductContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['categories'] = ProductCategory::where('language_id', $language->id)
      ->orderByDesc('id')
      ->get();

    $information['langs'] = Language::all();


    $information['themeInfo'] = DB::table('basic_settings')->select('theme_version')->first();

    return view('backend.product.category.index', $information);
  }
  //store
  public function store(CategoryRequest $request)
  {


    $ins = $request->all();
    $ins['slug'] = createSlug($request->name);
    ProductCategory::create($ins);
    Session::flash('success', 'Added Successfully');

    return response()->json(['status' => 'success'], 200);
  }
  //update_featured
  public function update_featured(Request $request, $id)
  {
    $feature = ProductCategory::where('id', $id)->first();
    $feature->is_feature = $request->is_feature;
    $feature->save();
    if ($request->is_feature == 1) {
      Session::flash('success', 'Updated Successfully');
    } else {
      Session::flash('warning', 'Updated Successfully');
    }

    return back();
  }


  public function update(Request $request)
  {
    $ins = $request->all();
    $ins['slug'] = make_slug($request->name);

    ProductCategory::find($request->id)->update($ins);

    Session::flash('success', 'Updated Successfully');

    return response()->json(['status' => 'success'], 200);
  }
  //delete
  public function delete(Request $request, $id)
  {
    $category = ProductCategory::where('id', $id)->first();
    $product_contents = ProductContent::where('category_id', $category->id)->get();
    foreach ($product_contents as $product_content) {
      $product_content->delete();
    }
    $category->delete();
    return redirect()->back()->with('success', 'Deleted Successfully');
  }
  //bulk_delete
  public function bulk_delete(Request $request)
  {
    $ids = $request->ids;
    foreach ($ids as $id) {
      $category = ProductCategory::where('id', $id)->first();
      $product_contents = ProductContent::where('category_id', $category->id)->get();
      foreach ($product_contents as $product_content) {
        $product_content->delete();
      }
      $category->delete();
    }
    Session::flash('success', 'Deleted Successfully');
    return response()->json(['status' => 'success'], 200);
  }
}
