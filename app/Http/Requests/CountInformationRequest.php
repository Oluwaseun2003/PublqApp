<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountInformationRequest extends FormRequest
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
      'language_id' => request()->isMethod('POST') ? 'required' : '',
      'icon' => 'required_if:theme_version,3',
      'color' => 'required_if:theme_version,3',
      'title' => 'required',
      'amount' => 'required|numeric',
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
