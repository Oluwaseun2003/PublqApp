<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventCategoryRequest extends FormRequest
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
   * @return array<string, mixed>
   */
  public function rules()
  {
    return [
      'language_id' => 'required',
      'image' => 'required',
      'name' => 'required',
      'status' => 'required',
      'serial_number' => 'required',
      'is_featured' => 'required',
    ];
  }

  public function messages()
  {
    return [
      'language_id.required' => 'The language field is required.'
    ];
  }
}
