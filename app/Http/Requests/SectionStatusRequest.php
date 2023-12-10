<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionStatusRequest extends FormRequest
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
      'categories_section_status' => 'numeric',
      'about_section_status' => 'numeric',
      'featured_section_status' => 'numeric',
      'features_section_status' => 'numeric',
      'how_work_section_status' => 'numeric',
      'testimonials_section_status' => 'numeric',
      'about_us_section_status' => 'numeric',
      'partner_section_status' => 'numeric',
      'footer_section_status' => 'numeric'
    ];
  }
}
