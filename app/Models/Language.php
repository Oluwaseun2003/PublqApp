<?php

namespace App\Models;

use App\Models\BasicSettings\CookieAlert;
use App\Models\BasicSettings\PageHeading;
use App\Models\BasicSettings\SEO;
use App\Models\Curriculum\CourseCategory;
use App\Models\Curriculum\CourseFaq;
use App\Models\Curriculum\CourseInformation;
use App\Models\CustomPage\PageContent;
use App\Models\Event\EventCategory;
use App\Models\Event\EventContent;
use App\Models\FAQ;
use App\Models\Footer\FooterContent;
use App\Models\Footer\QuickLink;
use App\Models\HomePage\AboutUsSection;
use App\Models\HomePage\ActionSection;
use App\Models\HomePage\Fact\CountInformation;
use App\Models\HomePage\Fact\FunFactSection;
use App\Models\HomePage\Feature;
use App\Models\HomePage\HeroSection;
use App\Models\HomePage\NewsletterSection;
use App\Models\HomePage\SectionTitle;
use App\Models\HomePage\Testimonial;
use App\Models\HomePage\VideoSection;
use App\Models\Journal\BlogCategory;
use App\Models\Journal\BlogInformation;
use App\Models\MenuBuilder;
use App\Models\Popup;
use App\Models\Teacher\Instructor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HomePage\EventFeature;
use App\Models\HomePage\HowWork;
use App\Models\HomePage\HowWorkItem;
use App\Models\ShopManagement\ProductCategory;
use App\Models\ShopManagement\ProductContent;

class Language extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'code', 'direction', 'is_default'];

  public function event_category()
  {
    return $this->hasMany(EventCategory::class, 'language_id', 'id');
  }
  public function event_content()
  {
    return $this->hasMany(EventContent::class, 'language_id', 'id');
  }
  public function event_features()
  {
    return $this->hasMany(EventFeature::class, 'language_id', 'id');
  }
  public function event_feature_section()
  {
    return $this->hasMany(EventFeature::class, 'language_id', 'id');
  }
  public function contact_pages()
  {
    return $this->hasMany(ContactPage::class, 'language_id', 'id');
  }
  //product_categories
  public function product_categories()
  {
    return $this->hasMany(ProductCategory::class, 'language_id', 'id');
  }

  // how_works
  public function how_works()
  {
    return $this->hasMany(HowWork::class);
  }



  public function pageName()
  {
    return $this->hasOne(PageHeading::class);
  }

  public function seoInfo()
  {
    return $this->hasOne(SEO::class);
  }

  public function cookieAlertInfo()
  {
    return $this->hasOne(CookieAlert::class);
  }

  public function faq()
  {
    return $this->hasMany(FAQ::class);
  }

  public function customPageInfo()
  {
    return $this->hasMany(PageContent::class);
  }

  public function footerContent()
  {
    return $this->hasOne(FooterContent::class);
  }

  public function footerQuickLink()
  {
    return $this->hasMany(QuickLink::class);
  }

  public function announcementPopup()
  {
    return $this->hasMany(Popup::class);
  }


  public function heroSec()
  {
    return $this->hasOne(HeroSection::class, 'language_id', 'id');
  }

  public function sectionTitle()
  {
    return $this->hasOne(SectionTitle::class, 'language_id', 'id');
  }


  public function feature()
  {
    return $this->hasMany(Feature::class, 'language_id', 'id');
  }


  public function testimonial()
  {
    return $this->hasMany(Testimonial::class, 'language_id', 'id');
  }


  public function blogCategory()
  {
    return $this->hasMany(BlogCategory::class);
  }

  public function blogInformation()
  {
    return $this->hasMany(BlogInformation::class);
  }

  public function menuInfo()
  {
    return $this->hasOne(MenuBuilder::class, 'language_id', 'id');
  }

  public function aboutUsSec()
  {
    return $this->hasOne(AboutUsSection::class, 'language_id', 'id');
  }

  public function event_feature()
  {
    return $this->hasMany(EventFeature::class);
  }
  public function how_work_items()
  {
    return $this->hasMany(HowWorkItem::class);
  }

  //product_contents
  public function product_contents()
  {
    return $this->hasMany(ProductContent::class);
  }
}
