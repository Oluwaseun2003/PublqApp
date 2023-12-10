<?php

namespace App\Http\Controllers\BackEnd\ShopManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopManagement\ProductStoreRequest;
use App\Http\Requests\ShopManagement\ProductUpdateRequest;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\ShopManagement\Product;
use App\Models\ShopManagement\ProductContent;
use App\Models\ShopManagement\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class ProductController extends Controller
{
  public function index()
  {
    $digital_product = Product::where('type', 'digital')->get()->count();
    $physical_product = Product::where('type', 'physical')->get()->count();

    $information['digital_product'] = $digital_product;
    $information['physical_product'] = $physical_product;
    return view('backend.product.index', $information);
  }
  public function settings()
  {
    $data['abex'] = Basic::first();
    return view('backend.product.settings', $data);
  }
  public function setting_update(Request $request)
  {
    $in = $request->all();
    $bex = Basic::where('uniqid', 12345)->first();
    $bex->update($in);

    Session::flash('success', 'Updated Successfully');
    return response()->json(['status' => 'success'], 200);
  }
  //create
  public function create(Request $request)
  {
    $languages = Language::get();
    $information['languages'] = $languages;
    return view('backend.product.create', $information);
  }
  //imgstore
  public function imgstore(Request $request)
  {
    $img = $request->file('file');
    $allowedExts = array('jpg', 'png', 'jpeg');
    $rules = [
      'file' => [
        function ($attribute, $value, $fail) use ($img, $allowedExts) {
          $ext = $img->getClientOriginalExtension();
          if (!in_array($ext, $allowedExts)) {
            return $fail("Only png, jpg, jpeg images are allowed");
          }
        },
      ]
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      $validator->getMessageBag()->add('error', 'true');
      return response()->json($validator->errors());
    }
    $filename = uniqid() . '.jpg';
    @mkdir(public_path('assets/admin/img/product/gallery/'), 0775, true);
    $img->move(public_path('assets/admin/img/product/gallery/'), $filename);
    $pi = new ProductImage();
    if (!empty($request->product_id)) {
      $pi->product_id = $request->product_id;
    }
    $pi->image = $filename;
    $pi->save();
    return response()->json(['status' => 'success', 'file_id' => $pi->id]);
  }
  //imgrmv
  public function imgrmv(Request $request)
  {
    $pi = ProductImage::where('id', $request->fileid)->first();
    @unlink(public_path('assets/admin/img/product/gallery/') . $pi->image);
    $pi->delete();
    return $pi->id;
  }
  //store
  public function store(ProductStoreRequest $request)
  {
    $img = $request->file('feature_image');
    $in = $request->all();
    if ($request->hasFile('feature_image')) {
      $filename = time() . '.' . $img->getClientOriginalExtension();
      @mkdir(public_path('assets/admin/img/product/feature_image/'), 0775, true);
      $request->file('feature_image')->move(public_path('assets/admin/img/product/feature_image/'), $filename);
      $in['feature_image'] = $filename;
    }

    $zip_file = $request->file('download_file');
    if ($request->hasFile('download_file')) {
      $filename = time() . '.' . $zip_file->getClientOriginalExtension();
      @mkdir(public_path('assets/admin/img/product/download_file/'), 0775, true);
      $request->file('download_file')->move(public_path('assets/admin/img/product/download_file/'), $filename);
      $in['download_file'] = $filename;
    }

    $product = Product::create($in);
    $in['product_id'] = $product->id;
    $product_id = $product->id;
    $languages = Language::all();

    foreach ($languages as $language) {
      $product_content = new ProductContent();
      $product_content->language_id = $language->id;
      $product_content->category_id = $request[$language->code . '_category_id'];
      $product_content->product_id = $product_id;
      $product_content->title = $request[$language->code . '_title'];
      $product_content->slug = createSlug($request[$language->code . '_title']);
      $product_content->summary = $request[$language->code . '_summary'];
      $product_content->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
      $product_content->meta_description = $request[$language->code . '_meta_description'];
      $product_content->tags = $request[$language->code . '_tags'];
      $product_content->meta_keywords = $request[$language->code . '_meta_keywords'];
      $product_content->save();
    }

    $slders = $request->slider_images;
    foreach ($slders as $key => $pi) {
      $pis = ProductImage::where('id', $pi)->first();
      if ($pis) {
        $pis->product_id = $product_id;
        $pis->save();
      }
    }

    Session::flash('success', 'Added Successfully');
    return response()->json(['status' => 'success'], 200);
  }
  //show
  public function show(Request $request)
  {
    $information['langs'] = Language::all();

    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    $products = Product::join('product_contents', 'product_contents.product_id', '=', 'products.id')
      ->join('product_categories', 'product_categories.id', '=', 'product_contents.category_id')
      ->where('product_contents.language_id', '=', $language->id)
      ->select('products.*', 'product_contents.id as productInfoId', 'product_contents.title', 'product_categories.name as category')
      ->orderByDesc('products.id')
      ->paginate(10);

    $information['products'] = $products;
    return view('backend.product.show', $information);
  }
  //status_update
  public function status_update(Request $request)
  {
    $product = Product::find($request->id);

    $product->update([
      'status' => $request['status']
    ]);
    Session::flash('success', 'Updated Successfully');

    return redirect()->back();
  }
  //feature_update
  public function feature_update(Request $request)
  {
    $product = product::find($request->id);

    if ($request['is_feature'] == 'yes') {
      $product->is_feature = 'yes';
      $product->save();

      Session::flash('success', 'Updated Successfully');
    } else {
      $product->is_feature = 'no';
      $product->save();

      Session::flash('success', 'Updated Successfully');
    }

    return redirect()->back();
  }
  public function edit(Request $request)
  {
    $id = $request->id;
    $product = Product::findOrFail($id);
    $information['product'] = $product;

    $information['languages'] = Language::all();

    return view('backend.product.edit', $information);
  }
  //load_images
  public function load_images($id)
  {
    $images = ProductImage::where('product_id', $id)->get();
    return $images;
  }
  //imgdbrmv
  public function imagedbrmv(Request $request)
  {
    $pi = ProductImage::where('id', $request->fileid)->first();
    @unlink(public_path('assets/admin/img/product/gallery/') . $pi->image);
    $pi->delete();
    return $pi->id;
  }
  //update
  public function update(ProductUpdateRequest $request)
  {
    $product = Product::where('id', $request->product_id)->first();
    $img = $request->file('feature_image');
    $in = $request->all();
    if ($request->hasFile('feature_image')) {
      $filename = time() . '.' . $img->getClientOriginalExtension();
      @mkdir(public_path('assets/admin/img/product/feature_image/'), 0775, true);
      $request->file('feature_image')->move(public_path('assets/admin/img/product/feature_image/'), $filename);
      $in['feature_image'] = $filename;
      @unlink(public_path('assets/admin/img/product/feature_image/') . $product->feature_image);
    }

    $zip_file = $request->file('download_file');
    if ($request->hasFile('download_file')) {
      $filename = time() . '.' . $zip_file->getClientOriginalExtension();
      @mkdir(public_path('assets/admin/img/product/download_file/'), 0775, true);
      $request->file('download_file')->move(public_path('assets/admin/img/product/download_file/'), $filename);
      $in['download_file'] = $filename;
    }

    $in['product_id'] = $product->id;



    $languages = Language::all();

    foreach ($languages as $language) {
      $product_content =  ProductContent::where('product_id', $product->id)->where('language_id', $language->id)->first();
      if (empty($product_content)) {
        $product_content = new ProductContent();
      }
      $product_content->language_id = $language->id;
      $product_content->category_id = $request[$language->code . '_category_id'];
      $product_content->product_id = $product->id;
      $product_content->title = $request[$language->code . '_title'];
      $product_content->slug = createSlug($request[$language->code . '_title']);
      $product_content->summary = $request[$language->code . '_summary'];
      $product_content->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
      $product_content->meta_description = $request[$language->code . '_meta_description'];
      $product_content->tags = $request[$language->code . '_tags'];
      $product_content->meta_keywords = $request[$language->code . '_meta_keywords'];
      $product_content->save();
    }

    $slders = $request->slider_images;
    if ($slders) {
      foreach ($slders as $key => $pi) {
        $pis = ProductImage::where('id', $pi)->first();
        if ($pis) {
          $pis->product_id = $product->id;
          $pis->save();
        }
      }
    }

    $product = $product->update($in);
    Session::flash('success', 'Updated Successfully');
    return response()->json(['status' => 'success'], 200);
  }
  //destroy
  public function destroy(Request $request)
  {
    $id = $request->id;
    $product = Product::find($id);

    @unlink(public_path('assets/admin/img/product/feature_image/') . $product->feature_image);
    @unlink(public_path('assets/admin/img/product/download_file/') . $product->download_file);

    $product_contents = ProductContent::where('product_id', $product->id)->get();
    foreach ($product_contents as $product_content) {
      $product_content->delete();
    }
    $product_images = ProductImage::where('product_id', $product->id)->get();
    foreach ($product_images as $product_image) {
      @unlink(public_path('assets/admin/img/product/gallery/') . $product_image->image);
      $product_image->delete();
    }
    //order_items
    $order_items = $product->order_items()->get();
    foreach ($order_items as $order_item) {
      $order_item->delete();
    }
    //product_reviews
    $product_reviews = $product->product_reviews()->get();
    foreach ($product_reviews as $product_review) {
      $product_review->delete();
    }

    // finally delete the course
    $product->delete();

    return redirect()->back()->with('success', 'Deleted Successfully');
  }
  //bulk_destroy
  public function bulk_destroy(Request $request)
  {
    $ids = $request->ids;
    foreach ($ids as $id) {
      $product = Product::find($id);

      @unlink(public_path('assets/admin/img/product/feature_image/') . $product->feature_image);
      @unlink(public_path('assets/admin/img/product/download_file/') . $product->download_file);

      $product_contents = ProductContent::where('product_id', $product->id)->get();
      foreach ($product_contents as $product_content) {
        $product_content->delete();
      }
      $product_images = ProductImage::where('product_id', $product->id)->get();
      foreach ($product_images as $product_image) {
        @unlink(public_path('assets/admin/img/product/gallery/') . $product_image->image);
        $product_image->delete();
      }
      //order_items
      $order_items = $product->order_items()->get();
      foreach ($order_items as $order_item) {
        $order_item->delete();
      }
      //product_reviews
      $product_reviews = $product->product_reviews()->get();
      foreach ($product_reviews as $product_review) {
        $product_review->delete();
      }

      // finally delete the course
      $product->delete();
    }
    Session::flash('success', 'Deleted Successfully');
    return response()->json(['status' => 'success'], 200);
  }
}
