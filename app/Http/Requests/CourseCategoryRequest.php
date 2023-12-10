<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseCategoryRequest extends FormRequest
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
      'icon' => request()->isMethod('POST') ? 'required' : '',
      'color' => 'required',
      'name' => [
        'required',
        request()->isMethod('POST') ? 'unique:course_categories' : Rule::unique('course_categories')->ignore($this->id)
      ],
      'status' => 'required|numeric',
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
