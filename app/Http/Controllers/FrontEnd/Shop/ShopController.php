<?php

namespace App\Http\Controllers\FrontEnd\Shop;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\ShopManagement\Product;
use App\Models\ShopManagement\ProductCategory;
use App\Models\ShopManagement\ProductImage;
use App\Models\ShopManagement\ProductReview;
use App\Models\ShopManagement\ShippingCharge;
use App\Models\ShopManagement\ShopCoupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\Null_;

class ShopController extends Controller
{
  public function index(Request $request)
  {
    $basic = Basic::select('shop_status')->first();
    if ($basic->shop_status == 0) {
      return redirect()->route('index');
    }
    $language = $this->getLanguage();
    $information  = [];
    $product_categories = ProductCategory::where('status', 1)->where('language_id', $language->id)->get();

    $category = $search = $min = $max = $product_short = null;
    if ($request->filled('category')) {
      $category = $request['category'];
      $category = ProductCategory::where('slug', $category)->first();
      $category = $category->id;
    }
    if ($request->filled('search')) {
      $search = $request['search'];
    }
    if ($request->filled('min') && $request->filled('max')) {
      $min = $request['min'];
      $max = $request['max'];
    }
    if ($request->filled('product_short')) {
      if ($request['product_short'] == 'new') {
        $order_by_column = 'products.id';
        $order = 'desc';
      } elseif ($request['product_short'] == 'default') {
        $order_by_column = 'products.id';
        $order = 'desc';
      } elseif ($request['product_short'] == 'old') {
        $order_by_column = 'products.id';
        $order = 'asc';
      } elseif ($request['product_short'] == 'hight-to-low') {
        $order_by_column = 'products.current_price';
        $order = 'desc';
      } elseif ($request['product_short'] == 'low-to-high') {
        $order_by_column = 'products.current_price';
        $order = 'asc';
      }
    } else {
      $order_by_column = 'products.id';
      $order = 'desc';
    }

    $products = Product::join('product_contents', 'product_contents.product_id', '=', 'products.id')
      ->join('product_categories', 'product_categories.id', '=', 'product_contents.category_id')
      ->where('product_contents.language_id', '=', $language->id)
      ->when($category, function ($query, $category) {
        return $query->where('product_contents.category_id', '=', $category);
      })
      ->when($search, function ($query, $keyword) {
        return $query->where('product_contents.title', 'like', '%' . $keyword . '%');
      })
      ->when(($min && $max), function ($query) use ($min, $max) {
        return $query->where('products.current_price', '>=', $min)->where('products.current_price', '<=', $max);
      })
      ->where('products.status', 1)
      ->select('products.*', 'product_contents.id as productInfoId', 'product_contents.title', 'product_contents.slug', 'product_categories.name as category')
      ->orderBy($order_by_column, $order)
      ->paginate(9);
    $information['products'] = $products;
    $information['product_categories'] = $product_categories;

    $max = Product::max('current_price');
    $min = Product::min('current_price');
    $information['max'] = $max;
    $information['min'] = $min;


    return view('frontend.shop.index', $information);
  }
  //details
  public function details($slug, $id)
  {
    $basic = Basic::select('shop_status')->first();
    if ($basic->shop_status == 0) {
      return redirect()->route('index');
    }
    $language = $this->getLanguage();
    $information = [];
    $product = Product::join('product_contents', 'product_contents.product_id', '=', 'products.id')
      ->join('product_categories', 'product_categories.id', '=', 'product_contents.category_id')
      ->where('product_contents.language_id', '=', $language->id)
      ->select('products.*', 'product_contents.id as productInfoId', 'product_contents.title', 'product_contents.description', 'product_contents.summary', 'product_contents.meta_keywords', 'product_contents.meta_description', 'product_contents.slug',  'product_categories.name as category', 'product_categories.slug as slug')
      ->where('products.id', $id)
      ->firstOrFail();
    $information['product'] = $product;
    $product_gallery = ProductImage::where('product_id', $id)->get();
    $information['galleries'] = $product_gallery;
    return view('frontend.shop.details', $information);
  }
  //addToCart
  public function addToCart($id)
  {
    $language = $this->getLanguage();
    $cart = Session::get('cart');


    if (strpos($id, ',,,') == true) {
      $data = explode(',,,', $id);
      $id = $data[0];
      $qty = $data[1];

      $product = Product::join('product_contents', 'product_contents.product_id', 'products.id')
        ->where('products.id', $id)
        ->where('product_contents.language_id', $language->id)
        ->select('products.*', 'product_contents.title')
        ->first();

      if ($product->type != 'digital') {

        if (!empty($cart) && array_key_exists($id, $cart)) {
          if ($product->stock < $cart[$id]['qty'] + $qty) {
            return response()->json(['error' => 'Out of Stock']);
          }
        } else {
          if ($product->stock < $qty) {
            return response()->json(['error' => 'Out of Stock']);
          }
        }
      }

      if (!$product) {
        abort(404);
      }
      $cart = Session::get('cart');
      // if cart is empty then this the first product
      if (!$cart) {

        $cart = [
          $id => [
            "name" => $product->title,
            "qty" => $qty,
            "price" => $product->current_price,
            "photo" => $product->feature_image,
            "type" => $product->type
          ]
        ];

        Session::put('cart', $cart);
        return response()->json(['message' => 'Product added to cart successfully!']);
      }


      // if cart not empty then check if this product exist then increment quantity
      if (isset($cart[$id])) {
        $cart[$id]['qty'] +=  $qty;
        Session::put('cart', $cart);
        return response()->json(['message' => 'Product added to cart successfully!']);
      }

      // if item not exist in cart then add to cart with quantity = 1
      $cart[$id] = [
        "name" => $product->title,
        "qty" => $qty,
        "price" => $product->current_price,
        "photo" => $product->feature_image,
        "type" => $product->type
      ];
    } else {

      $id = $id;
      $product = Product::join('product_contents', 'product_contents.product_id', 'products.id')
        ->where('products.id', $id)
        ->where('product_contents.language_id', $language->id)
        ->select('products.*', 'product_contents.title')
        ->first();
      if (!$product) {
        abort(404);
      }


      if ($product->type != 'digital') {
        if (!empty($cart) && array_key_exists($id, $cart)) {
          if ($product->stock < $cart[$id]['qty'] + 1) {
            return response()->json(['error' => 'Out of Stock']);
          }
        } else {
          if ($product->stock < 1) {
            return response()->json(['error' => 'Out of Stock']);
          }
        }
      }


      $cart = Session::get('cart');
      // if cart is empty then this the first product
      if (!$cart) {

        $cart = [
          $id => [
            "name" => $product->title,
            "qty" => 1,
            "price" => $product->current_price,
            "photo" => $product->feature_image,
            "type" => $product->type
          ]
        ];

        Session::put('cart', $cart);
        return response()->json(['message' => 'Product added to cart successfully!']);
      }

      // if cart not empty then check if this product exist then increment quantity
      if (isset($cart[$id])) {
        $cart[$id]['qty']++;
        Session::put('cart', $cart);
        return response()->json(['message' => 'Product added to cart successfully!']);
      }

      // if item not exist in cart then add to cart with quantity = 1
      $cart[$id] = [
        "name" => $product->title,
        "qty" => 1,
        "price" => $product->current_price,
        "photo" => $product->feature_image,
        "type" => $product->type
      ];
    }

    Session::put('cart', $cart);
    return response()->json(['message' => 'Product added to cart successfully!']);
  }

  //addToCart
  public function addToCart2($id)
  {
    $quantity = $_GET['qty'];
    $language = $this->getLanguage();
    $cart = Session::get('cart');


    if (strpos($id, ',,,') == true) {
      $data = explode(',,,', $id);
      $id = $data[0];
      $qty = $data[1];

      $product = Product::join('product_contents', 'product_contents.product_id', 'products.id')
        ->where('products.id', $id)
        ->where('product_contents.language_id', $language->id)
        ->select('products.*', 'product_contents.title')
        ->first();

      if ($product->type != 'digital') {

        if (!empty($cart) && array_key_exists($id, $cart)) {
          if ($product->stock < $cart[$id]['qty'] + $quantity) {
            return response()->json(['error' => 'Out of Stock']);
          }
        } else {
          if ($product->stock < $quantity) {
            return response()->json(['error' => 'Out of Stock']);
          }
        }
      }

      if (!$product) {
        abort(404);
      }
      $cart = Session::get('cart');
      // if cart is empty then this the first product
      if (!$cart) {

        $cart = [
          $id => [
            "name" => $product->title,
            "qty" => $quantity,
            "price" => $product->current_price,
            "photo" => $product->feature_image,
            "type" => $product->type
          ]
        ];

        Session::put('cart', $cart);
        return response()->json(['message' => 'Product added to cart successfully!']);
      }


      // if cart not empty then check if this product exist then increment quantity
      if (isset($cart[$id])) {
        $cart[$id]['qty'] +=  $quantity;
        Session::put('cart', $cart);
        return response()->json(['message' => 'Product added to cart successfully!']);
      }

      // if item not exist in cart then add to cart with quantity = 1
      $cart[$id] = [
        "name" => $product->title,
        "qty" => $quantity,
        "price" => $product->current_price,
        "photo" => $product->feature_image,
        "type" => $product->type
      ];
    } else {

      $id = $id;
      $product = Product::join('product_contents', 'product_contents.product_id', 'products.id')
        ->where('products.id', $id)
        ->where('product_contents.language_id', $language->id)
        ->select('products.*', 'product_contents.title')
        ->first();
      if (!$product) {
        abort(404);
      }


      if ($product->type != 'digital') {
        if (!empty($cart) && array_key_exists($id, $cart)) {
          if ($product->stock < $cart[$id]['qty'] + $quantity) {
            return response()->json(['error' => 'Out of Stock']);
          }
        } else {
          if ($product->stock < 1) {
            return response()->json(['error' => 'Out of Stock']);
          }
        }
      }


      $cart = Session::get('cart');
      // if cart is empty then this the first product
      if (!$cart) {

        $cart = [
          $id => [
            "name" => $product->title,
            "qty" => $quantity,
            "price" => $product->current_price,
            "photo" => $product->feature_image,
            "type" => $product->type
          ]
        ];

        Session::put('cart', $cart);
        return response()->json(['message' => 'Product added to cart successfully!']);
      }
      // if cart not empty then check if this product exist then increment quantity
      if (isset($cart[$id])) {
        $quantity + $cart[$id]['qty']++;
        Session::put('cart', $cart);
        return response()->json(['message' => 'Product added to cart successfully!']);
      }

      // if item not exist in cart then add to cart with quantity = 1
      $cart[$id] = [
        "name" => $product->title,
        "qty" => $quantity,
        "price" => $product->current_price,
        "photo" => $product->feature_image,
        "type" => $product->type
      ];
    }

    Session::put('cart', $cart);
    return response()->json(['message' => 'Product added to cart successfully!']);
  }

  //cart
  public function cart()
  {
    $basic = Basic::select('shop_status')->first();
    if ($basic->shop_status == 0) {
      return redirect()->route('index');
    }
    Session::put('Shop_discount', NULL);
    Session::put('shipping_cost', null);
    $cart_items = Session::get('cart');
    return view('frontend.shop.cart', compact('cart_items'));
  }
  public function cartitemremove($id)
  {
    if ($id) {
      $cart = Session::get('cart');
      if (isset($cart[$id])) {
        unset($cart[$id]);
        Session::put('cart', $cart);
      }

      $total = 0;
      $count = 0;
      foreach ($cart as $i) {
        $total += $i['price'] * $i['qty'];
        $count += $i['qty'];
      }
      $total = round($total, 2);

      return response()->json(['message' => 'Product removed successfully', 'count' => $count, 'total' => $total]);
    }
  }
  public function updatecart(Request $request)
  {
    $language = $this->getLanguage();
    if (Session::has('cart')) {
      $cart = Session::get('cart');
      foreach ($request->product_id as $key => $id) {
        $product = Product::join('product_contents', 'product_contents.product_id', 'products.id')
          ->where('products.id', $id)
          ->where('product_contents.language_id', $language->id)
          ->select('products.*', 'product_contents.title')
          ->first();
        if ($product->type != 'digital') {
          if ($product->stock < $request->qty[$key]) {
            return response()->json(['error' => $product->title . ' stock not available']);
          }
        }
        if (isset($cart[$id])) {
          $cart[$id]['qty'] =  $request->qty[$key];
          Session::put('cart', $cart);
        }
      }
    }
    $total = 0;
    $count = 0;
    foreach ($cart as $i) {
      $total += $i['price'] * $i['qty'];
      $count += $i['qty'];
    }

    $total = round($total, 2);

    return response()->json(['message' => 'Cart Update Successfully.', 'total' => $total, 'count' => $count]);
  }
  //orderNow
  public function orderNow(Request $request)
  {
    $id = $request->product_id;
    $language = $this->getLanguage();
    $cart = Session::get('cart');


    if (strpos($id, ',,,') == true) {
      $data = explode(',,,', $id);
      $id = $data[0];
      $qty = $data[1];

      $product = Product::join('product_contents', 'product_contents.product_id', 'products.id')
        ->where('products.id', $id)
        ->where('product_contents.language_id', $language->id)
        ->select('products.*', 'product_contents.title')
        ->first();

      if ($product->type != 'digital') {

        if (!empty($cart) && array_key_exists($id, $cart)) {
          if ($product->stock < $cart[$id]['qty'] + $request->quantity) {
            $notification = array('message' => 'Out of Stock..!', 'alert-type' => 'error');
            return back()->with($notification);
          }
        } else {
          if ($product->stock < $qty) {
            $notification = array('message' => 'Out of Stock..!', 'alert-type' => 'error');
            return back()->with($notification);
          }
        }
      }

      if (!$product) {
        abort(404);
      }
      $cart = Session::get('cart');
      // if cart is empty then this the first product
      if (!$cart) {

        $cart = [
          $id => [
            "name" => $product->title,
            "qty" => $request->quantity,
            "price" => $product->current_price,
            "photo" => $product->feature_image,
            "type" => $product->type
          ]
        ];

        Session::put('cart', $cart);
        $notification = array('message' => 'Product added to cart successfully!', 'alert-type' => 'success');
        return redirect()->route('shopping.cart');
      }


      // if cart not empty then check if this product exist then increment quantity
      if (isset($cart[$id])) {
        $cart[$id]['qty'] +=  $request->quantity;
        Session::put('cart', $cart);
        $notification = array('message' => 'Product added to cart successfully!', 'alert-type' => 'success');
        return redirect()->route('shopping.cart');
      }

      // if item not exist in cart then add to cart with quantity = 1
      $cart[$id] = [
        "name" => $product->title,
        "qty" => $request->quantity,
        "price" => $product->current_price,
        "photo" => $product->feature_image,
        "type" => $product->type
      ];
    } else {

      $id = $id;
      $product = Product::join('product_contents', 'product_contents.product_id', 'products.id')
        ->where('products.id', $id)
        ->where('product_contents.language_id', $language->id)
        ->select('products.*', 'product_contents.title')
        ->first();
      if (!$product) {
        abort(404);
      }


      if ($product->type != 'digital') {
        if (!empty($cart) && array_key_exists($id, $cart)) {
          if ($product->stock < $cart[$id]['qty'] + $request->quantity) {
            $notification = array('message' => 'Out of Stock!', 'alert-type' => 'error');
            return back()->with($notification);
          }
        } else {
          if ($product->stock < 1) {
            $notification = array('message' => 'Out of Stock!', 'alert-type' => 'error');
            return back()->with($notification);
          }
        }
      }


      $cart = Session::get('cart');
      // if cart is empty then this the first product
      if (!$cart) {

        $cart = [
          $id => [
            "name" => $product->title,
            "qty" => $request->quantity,
            "price" => $product->current_price,
            "photo" => $product->feature_image,
            "type" => $product->type
          ]
        ];

        Session::put('cart', $cart);
        $notification = array('message' => 'Product added to cart successfully!', 'alert-type' => 'success');
        return redirect()->route('shopping.cart');
      }
      // if cart not empty then check if this product exist then increment quantity
      if (isset($cart[$id])) {
        $cart[$id]['qty']++;
        Session::put('cart', $cart);
        $notification = array('message' => 'Product added to cart successfully!', 'alert-type' => 'success');
        return redirect()->route('shopping.cart');
      }

      // if item not exist in cart then add to cart with quantity = 1
      $cart[$id] = [
        "name" => $product->title,
        "qty" => $request->quantity,
        "price" => $product->current_price,
        "photo" => $product->feature_image,
        "type" => $product->type
      ];
    }

    Session::put('cart', $cart);
    return redirect()->route('shopping.cart');
  }
  //checkout
  public function checkout()
  {
    $basic = Basic::select('shop_status', 'shop_guest_checkout', 'shop_tax')->first();
    if ($basic->shop_status == 0) {
      return redirect()->route('index');
    }
    $type = request()->input('type');
    if ($type == 'guest' || $basic->shop_guest_checkout == 1) {
      $cart = Session::get('cart');

      if ($cart == NULL) {
        $notification = array('message' => 'Your Cart is Empty!', 'alert-type' => 'error');
        return back()->with($notification);
      } else {
        return view('frontend.shop.checkout', ['type' => 'guest']);
      }
    } else {
      if (Auth::guard('customer')->user()) {
        return view('frontend.shop.checkout');
      } else {
        return redirect()->route("customer.login", ['redirectPath' => 'checkout']);
      }
    }
  }
  public function applyCoupon(Request $request)
  {
    $coupon = ShopCoupon::where('code', $request->coupon_code)->first();


    if (!$coupon) {
      return response()->json(['status' => 'error', 'message' => "Coupon is not valid"]);
    } else {

      $start = Carbon::parse($coupon->start_date);
      $end = Carbon::parse($coupon->end_date);
      $today = Carbon::now();
      Session::put('Shop_discount', NULL);
      if ($today->greaterThanOrEqualTo($start) && $today->lessThan($end)) {
        $value = $coupon->value;
        $type = $coupon->type;

        $cartTotal = 0;
        $countitem = 0;
        $cart_items = Session::get('cart');
        if ($cart_items) {
          foreach ($cart_items as $p) {
            $cartTotal += $p['price'] * $p['qty'];
            $countitem += $p['qty'];
          }
          if ($type == 'fixed') {
            $couponAmount = $value;
          } else {
            $couponAmount = ($cartTotal * $value) / 100;
          }
          $cartTotal - $couponAmount;
          Session::put('Shop_discount', $couponAmount);
          Session::put('shipping_cost', $request->shipping_cost);
          return response()->json(['status' => 'success', 'message' => "Coupon applied successfully"]);
        } else {
          return response()->json(['status' => 'error', 'message' => "Coupon is not valid"]);
        }
      } else {
        return response()->json(['status' => 'error', 'message' => "Coupon is not valid"]);
      }
    }
  }
  //buy
  public function buy(Request $request)
  {
  }
  //review
  public function review(Request $request)
  {
    if ($request->review || $request->comment) {
      if (ProductReview::where('user_id', Auth::guard('customer')->user()->id)->where('product_id', $request->product_id)->exists()) {
        $exists =    ProductReview::where('user_id', Auth::guard('customer')->user()->id)->where('product_id', $request->product_id)->first();
        if ($request->review) {
          $exists->update([
            'review' => $request->review,
          ]);
          $avgreview = ProductReview::where('product_id', $request->product_id)->avg('review');
        }
        if ($request->comment) {
          $exists->update([
            'comment' => $request->comment,
          ]);
        }
        Session::flash('success', 'Review update successfully');
        return back();
      } else {
        $input = $request->all();
        $input['user_id'] = Auth::guard('customer')->user()->id;
        $data = new ProductReview;
        $data->create($input);
        $avgreview = ProductReview::where('product_id', $request->product_id)->avg('review');
        Session::flash('success', 'Review submit successfully');
        return back();
      }
    } else {
      Session::flash('error', 'Review submit not succesfull');
      return back();
    }
  }
}
