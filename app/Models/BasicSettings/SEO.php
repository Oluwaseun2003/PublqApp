<?php

namespace App\Models\BasicSettings;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEO extends Model
{
  use HasFactory;

  protected $table = 'seos';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'meta_keyword_home',
    'meta_description_home',
    'meta_keyword_event',
    'meta_description_event',
    'meta_keyword_organizer',
    'meta_description_organizer',
    'meta_keyword_shop',
    'meta_description_shop',
    'meta_keyword_blog',
    'meta_description_blog',
    'meta_keyword_faq',
    'meta_description_faq',
    'meta_keyword_contact',
    'meta_description_contact',
    'meta_keyword_about',
    'meta_description_about',

    'meta_keyword_customer_login',
    'meta_description_customer_login',
    'meta_keyword_customer_signup',
    'meta_description_customer_signup',

    'meta_keyword_organizer_login',
    'meta_description_organizer_login',
    'meta_keyword_organizer_signup',
    'meta_description_organizer_signup',
    'meta_keyword_customer_forget_password',
    'meta_description_customer_forget_password',
    'meta_keyword_organizer_forget_password',
    'meta_description_organizer_forget_password',

    'meta_keyword_forget_password',
    'meta_description_forget_password',
  ];

  public function seoLang()
  {
    return $this->belongsTo(Language::class);
  }
}
