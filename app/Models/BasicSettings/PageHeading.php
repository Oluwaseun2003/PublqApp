<?php

namespace App\Models\BasicSettings;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageHeading extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'blog_page_title',
    'blog_details_page_title',
    'contact_page_title',
    'about_page_title',
    'event_page_title',
    'shop_page_title',
    'cart_page_title',
    'event_details_page_title',
    'faq_page_title',
    'customer_forget_password_page_title',
    'organizer_forget_password_page_title',
    'organizer_page_title',
    'customer_login_page_title',
    'customer_signup_page_title',
    'organizer_login_page_title',
    'organizer_signup_page_title',

    'customer_dashboard_page_title',
    'customer_booking_page_title',
    'customer_booking_details_page_title',
    'customer_order_page_title',
    'customer_order_details_page_title',
    'customer_wishlist_page_title',
    'customer_support_ticket_page_title',
    'support_ticket_create_page_title',
    'support_ticket_details_page_title',
    'customer_edit_profile_page_title',
    'customer_change_password_page_title',
  ];

  public function headingLang()
  {
    return $this->belongsTo(Language::class);
  }
}
