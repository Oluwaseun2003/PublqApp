<?php

namespace App\Providers;

use App\Models\BasicSettings\PageHeading;
use App\Models\BasicSettings\SEO;
use App\Models\BasicSettings\SocialMedia;
use App\Models\ContactPage;
use App\Models\HomePage\Section;
use App\Models\Journal\Blog;
use App\Models\Language;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {

    if (!app()->runningInConsole()) {
      # code...
      Paginator::useBootstrap();

      $data = DB::table('basic_settings')->select('favicon', 'website_title', 'logo', 'timezone', 'preloader')->first();


      // send this information to only back-end view files
      View::composer('backend.*', function ($view) {
        if (Auth::guard('admin')->check() == true) {
          $authAdmin = Auth::guard('admin')->user();
          $role = null;

          if (!is_null($authAdmin->role_id)) {
            $role = $authAdmin->role()->first();
          }
        }

        $language = Language::where('is_default', 1)->first();

        $websiteSettings = DB::table('basic_settings')->select('admin_theme_version', 'base_currency_symbol_position', 'base_currency_symbol', 'base_currency_text')->first();

        $footerText = $language->footerContent()->first();

        if (Auth::guard('admin')->check() == true) {
          $view->with('roleInfo', $role);
        }

        $view->with('defaultLang', $language);
        $view->with('settings', $websiteSettings);
        $view->with('footerTextInfo', $footerText);
      });

      // send this information to only back-end view files
      View::composer('organizer.*', function ($view) {


        $language = Language::where('is_default', 1)->first();

        //$websiteSettings = DB::table('basic_settings')->select('admin_theme_version')->first();
        $websiteSettings = DB::table('basic_settings')->select('admin_theme_version', 'base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position', 'base_currency_rate', 'organizer_email_verification')->first();

        $footerText = $language->footerContent()->first();


        $view->with('defaultLang', $language);
        $view->with('settings', $websiteSettings);
        $view->with('footerTextInfo', $footerText);
      });


      // send this information to only front-end view files
      View::composer('frontend.*', function ($view) {
        // get basic info
        $basicData = DB::table('basic_settings')->select('theme_version', 'footer_logo', 'primary_color', 'breadcrumb_overlay_color', 'breadcrumb_overlay_opacity', 'breadcrumb', 'email_address', 'contact_number', 'address', 'latitude', 'longitude', 'base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position', 'base_currency_rate', 'is_shop_rating', 'facebook_login_status', 'google_login_status', 'google_recaptcha_status')->first();


        // get all the languages of this system
        $allLanguages = Language::all();

        // get the current locale of this website
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

        // get all the social medias
        $socialMedias = SocialMedia::orderBy('serial_number')->get();

        //seo
        $seo = SEO::where('language_id', $language->id)->first();
        //seo
        $pageHeading = PageHeading::where('language_id', $language->id)->first();

        // get the menus of this website
        $siteMenuInfo = $language->menuInfo;

        if (is_null($siteMenuInfo)) {
          $menus = json_encode([]);
        } else {
          $menus = $siteMenuInfo->menus;
        }

        // get the announcement popups
        $popups = $language->announcementPopup()->where('status', 1)->orderBy('serial_number', 'asc')->get();

        // get the cookie alert info
        $cookieAlert = $language->cookieAlertInfo()->first();

        // get footer section status (enable/disable) information
        $footerSectionStatus = Section::query()->pluck('footer_section_status')->first();

        if ($footerSectionStatus == 1) {
          // get the footer info
          $footerData = $language->footerContent()->first();

          // get the quick links of footer
          $quickLinks = $language->footerQuickLink()->orderBy('serial_number', 'asc')->get();

          // get latest blogs
          if ($basicData->theme_version != 3) {
            $blogs = Blog::join('blog_informations', 'blogs.id', '=', 'blog_informations.blog_id')
              ->where('blog_informations.language_id', '=', $language->id)
              ->select('blogs.image', 'blogs.created_at', 'blog_informations.title', 'blog_informations.slug')
              ->orderByDesc('blogs.created_at')
              ->limit(3)
              ->get();
          }

          // get newsletter title
          if ($basicData->theme_version == 2) {
            $newsletterTitle = $language->newsletterSec()->pluck('title')->first();
          }
        }

        $bex = ContactPage::where('language_id', $language->id)->first();

        $view->with('basicInfo', $basicData);
        $view->with('seo', $seo);
        $view->with('bex', $bex);
        $view->with('allLanguageInfos', $allLanguages);
        $view->with('currentLanguageInfo', $language);
        $view->with('socialMediaInfos', $socialMedias);
        $view->with('menuInfos', $menus);
        $view->with('popupInfos', $popups);
        $view->with('cookieAlertInfo', $cookieAlert);
        $view->with('footerSecStatus', $footerSectionStatus);
        $view->with('pageHeading', $pageHeading);


        if ($footerSectionStatus == 1) {
          $view->with('footerInfo', $footerData);
          $view->with('quickLinkInfos', $quickLinks);

          if ($basicData->theme_version != 3) {
            $view->with('latestBlogInfos', $blogs);
          }

          if ($basicData->theme_version == 2) {
            $view->with('newsletterTitle', $newsletterTitle);
          }
        }
      });


      // send this information to both front-end & back-end view files
      View::share(['websiteInfo' => $data]);
    }
  }
}
