<?php

namespace App\Http\Requests;

use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
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
      'image' => $this->hasFile('image') ? new ImageMimeTypeRule() : '',
      'first_name' => 'required',
      'last_name' => 'required',
      'contact_number' => 'required',
      'address' => 'required',
      'city' => 'required',
      'country' => 'required'
    ];
  }
}
