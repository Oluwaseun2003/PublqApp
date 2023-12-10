<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\Event;
use App\Models\Event\Booking;
use App\Models\Event\EventCategory;
use App\Models\Event\EventContent;
use App\Models\Footer\FooterContent;
use App\Models\Footer\QuickLink;
use App\Models\HomePage\AboutUsSection;
use App\Models\HomePage\EventFeature;
use App\Models\HomePage\EventFeatureSection;
use App\Models\HomePage\HeroSection;
use App\Models\HomePage\HowWork;
use App\Models\HomePage\HowWorkItem;
use App\Models\HomePage\Partner;
use App\Models\HomePage\PartnerSection;
use App\Models\HomePage\Section;
use App\Models\HomePage\Testimonial;
use App\Models\HomePage\TestimonialSection;
use Carbon\Carbon;

class HomeController extends Controller
{
  private $now_date_time;
  public function __construct()
  {
    $this->now_date_time = Carbon::now();
  }
  public function index()
  {
    $language = $this->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_home', 'meta_description_home')->first();

    // get the sections of selected home version
    $sectionInfo = Section::first();
    $queryResult['secInfo'] = $sectionInfo;

    $queryResult['heroInfo'] = $language->heroSec()->first();

    $queryResult['secTitleInfo'] = $language->sectionTitle()->first();

    $categories = $language->event_category()->where('status', 1)->where('is_featured', '=', 'yes')->orderBy('serial_number', 'asc')
      ->get();


    $queryResult['categories'] = $categories;

    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    if ($sectionInfo->features_section_status == 1) {
      $queryResult['featureData'] = Basic::select('features_section_image')->first();

      $queryResult['features'] = $language->feature()->orderBy('serial_number', 'asc')->get();
    }


    if ($sectionInfo->about_us_section_status == 1) {
      $queryResult['aboutUsInfo'] = $language->aboutUsSec()->first();
    }
    $queryResult['heroSection'] = HeroSection::where('language_id', $language->id)->first();
    $queryResult['eventCategories'] = EventCategory::where([['language_id', $language->id], ['status', 1], ['is_featured', 'yes']])->orderBy('serial_number', 'asc')->get();

    $queryResult['aboutUsSection'] = AboutUsSection::where('language_id', $language->id)->first();

    $queryResult['featureEventSection'] = EventFeatureSection::where('language_id', $language->id)->first();
    $queryResult['featureEventItems'] = EventFeature::where('language_id', $language->id)->orderBy('serial_number', 'asc')->get();

    $queryResult['howWork'] = HowWork::where('language_id', $language->id)->first();
    $queryResult['howWorkItems'] = HowWorkItem::where('language_id', $language->id)->orderBy('serial_number', 'asc')->get();

    if ($sectionInfo->testimonials_section_status == 1) {
      $queryResult['testimonialData'] = TestimonialSection::where('language_id', $language->id)->first();

      $queryResult['testimonials'] = Testimonial::where('language_id', $language->id)->orderBy('serial_number', 'asc')->get();
    }

    $queryResult['partnerInfo'] = PartnerSection::where('language_id', $language->id)->first();
    $queryResult['partners'] = Partner::orderBy('serial_number', 'asc')->get();
    $queryResult['footerInfo'] = FooterContent::where('language_id', $language->id)->first();
    $queryResult['quickLinkInfos'] = QuickLink::orderBy('serial_number', 'asc')->get();

    return view('frontend.home.index-v1', $queryResult);
  }
  //offline
  public function offline()
  {
    return view('frontend.offline');
  }

  public function about()
  {
    try {
      $language = $this->getLanguage();

      $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_home', 'meta_description_home')->first();

      // get the sections of selected home version
      $sectionInfo = Section::first();
      $queryResult['secInfo'] = $sectionInfo;

      $queryResult['secTitleInfo'] = $language->sectionTitle()->first();

      $queryResult['currencyInfo'] = $this->getCurrencyInfo();


      if ($sectionInfo->about_us_section_status == 1) {
        $queryResult['aboutUsInfo'] = $language->aboutUsSec()->first();
      }
      $queryResult['heroSection'] = HeroSection::where('language_id', $language->id)->first();

      $queryResult['aboutUsSection'] = AboutUsSection::where('language_id', $language->id)->first();

      if ($sectionInfo->testimonials_section_status == 1) {
        $queryResult['testimonialData'] = TestimonialSection::where('language_id', $language->id)->first();

        $queryResult['testimonials'] = Testimonial::where('language_id', $language->id)->orderBy('serial_number', 'asc')->get();
      }

      $queryResult['featureEventSection'] = EventFeatureSection::where('language_id', $language->id)->first();
      $queryResult['featureEventItems'] = EventFeature::where('language_id', $language->id)->orderBy('serial_number', 'asc')->get();

      $queryResult['partnerInfo'] = PartnerSection::where('language_id', $language->id)->first();
      $queryResult['partners'] = Partner::orderBy('serial_number', 'asc')->get();
      $queryResult['footerInfo'] = FooterContent::where('language_id', $language->id)->first();
      $queryResult['quickLinkInfos'] = QuickLink::orderBy('serial_number', 'asc')->get();
      return view('frontend.about', $queryResult); //code...
    } catch (\Exception $th) {
      dd($th);
    }
  }
}
