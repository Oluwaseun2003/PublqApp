<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageHeadingRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {

    return [
      'blog_page_title'=>'required',
      'blog_details_page_title'=> 'required',
      'contact_page_title'=> 'required',
      'event_page_title'=> 'required',
      'shop_page_title'=> 'required',
      'cart_page_title'=> 'required',
      'event_details_page_title'=> 'required',
      'faq_page_title'=> 'required',
      'customer_forget_password_page_title'=> 'required',
      'organizer_forget_password_page_title'=> 'required',
      'organizer_page_title'=> 'required',
      'customer_login_page_title'=> 'required',
      'customer_signup_page_title'=> 'required',
      'organizer_login_page_title'=> 'required',
      'organizer_signup_page_title'=> 'required',
    ];
  }
}
