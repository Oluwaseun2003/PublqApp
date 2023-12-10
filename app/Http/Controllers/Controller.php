<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\PageHeading;
use App\Models\Language;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function getCurrencyInfo()
  {
    $baseCurrencyInfo = Basic::select('base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position', 'base_currency_rate')
      ->first();

    return $baseCurrencyInfo;
  }


  public function getLanguage()
  {
    // get the current locale of this system
    if (Session::has('lang')) {
      $locale = Session::get('lang');
    }
    if (empty($locale)) {
      $language = Language::where('is_default', 1)->first();
    } else {
      $language = Language::where('code', $locale)->first();
      if (empty($language)) {
        $language = Language::where('is_default', 1)->first();
      }
    }

    return $language;
  }


  public function getPageHeading($language)
  {
    if (URL::current() == Route::is('courses')) {
      $pageHeading = PageHeading::where('language_id', $language->id)->select('courses_page_title')->first();
    } else if (URL::current() == Route::is('course_details')) {
      $pageHeading = PageHeading::where('language_id', $language->id)->select('course_details_page_title')->first();
    } else if (URL::current() == Route::is('instructors')) {
      $pageHeading = PageHeading::where('language_id', $language->id)->select('instructors_page_title')->first();
    } else if (URL::current() == Route::is('blogs')) {
      $pageHeading = PageHeading::where('language_id', $language->id)->select('blog_page_title')->first();
    } else if (URL::current() == Route::is('blog_details')) {
      $pageHeading = PageHeading::where('language_id', $language->id)->select('blog_details_page_title')->first();
    } else if (URL::current() == Route::is('faqs')) {
      $pageHeading = PageHeading::where('language_id', $language->id)->select('faq_page_title')->first();
    } else if (URL::current() == Route::is('contact')) {
      $pageHeading = PageHeading::where('language_id', $language->id)->select('contact_page_title')->first();
    } else if (URL::current() == Route::is('user.login')) {
      $pageHeading = PageHeading::where('language_id', $language->id)->select('login_page_title')->first();
    } else if (URL::current() == Route::is('user.forget_password')) {
      $pageHeading = PageHeading::where('language_id', $language->id)->select('forget_password_page_title')->first();
    } else if (URL::current() == Route::is('user.signup')) {
      $pageHeading = PageHeading::where('language_id', $language->id)->select('signup_page_title')->first();
    }

    return $pageHeading;
  }


  public static function getBreadcrumb()
  {
    $breadcrumb = Basic::select('breadcrumb')->first();

    return $breadcrumb;
  }


  public function changeLanguage(Request $request)
  {
    // put the selected language in session
    $langCode = $request['lang_code'];

    $request->session()->put('lang', $langCode);

    return redirect()->back();
  }


  public function serviceUnavailable()
  {
    $info = DB::table('basic_settings')->select('maintenance_img', 'maintenance_msg')->first();

    return view('errors.503', compact('info'));
  }


  public function countAdView($id)
  {
    try {
      $ad = Advertisement::where('id', $id)->first();

      $ad->update([
        'views' => $ad->views + 1
      ]);

      return response()->json(['success' => 'Advertisement view counted successfully.']);
    } catch (ModelNotFoundException $e) {
      return response()->json(['error' => 'Sorry, something went wrong!']);
    }
  }


  public function storeSubscriber(Request $request)
  {
    $rules = [
      'email_id' => 'required|email:rfc,dns|unique:subscribers'
    ];

    $messages = [
      'email_id.required' => 'Please enter your email address.',
      'email_id.unique' => 'This email address is already exist!'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'error' => $validator->getMessageBag()
      ], 400);
    }

    Subscriber::create($request->all());

    return Response::json([
      'success' => 'You have successfully subscribed to our newsletter.'
    ], 200);
  }
}
