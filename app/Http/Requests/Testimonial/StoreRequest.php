<?php

namespace App\Http\Requests\Testimonial;

use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
      'language_id' => 'required',
      'image' => [
        'required',
        new ImageMimeTypeRule()
      ],
      'name' => 'required|max:255',
      'occupation' => 'required|max:255',
      'comment' => 'required',
      'serial_number' => 'required|numeric'
    ];
  }

  public function messages()
  {
    return [
      'language_id.required' => 'The language field is required.'
    ];
  }
}
