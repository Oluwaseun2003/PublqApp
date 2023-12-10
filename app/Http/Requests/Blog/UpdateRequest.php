<?php

namespace App\Http\Requests\Blog;

use App\Models\Journal\BlogInformation;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
    $ruleArray = [
      'image' => $this->hasFile('image') ? new ImageMimeTypeRule() : '',
      'serial_number' => 'required|numeric'
    ];

    $languages = Language::all();
    $id = $this->route('id');
    $request = $this->request->all();

    foreach ($languages as $language) {
      $slug = createSlug($request[$language->code . '_title']);
      $ruleArray[$language->code . '_title'] = [
        'required',
        'max:255',
        function ($attribute, $value, $fail) use ($slug, $id, $language) {
            $bis = BlogInformation::where('blog_id', '<>', $id)->where('language_id', $language->id)->get();
            foreach ($bis as $key => $bi) {
                if (strtolower($slug) == strtolower($bi->slug)) {
                    $fail('The title field must be unique for ' . $language->name . ' language.');
                }
            }
        }
      ];
      $ruleArray[$language->code . '_category_id'] = 'required';
      $ruleArray[$language->code . '_author'] = 'required';
      $ruleArray[$language->code . '_content'] = 'min:30';
    }

    return $ruleArray;
  }

  public function messages()
  {
    $messageArray = [];

    $languages = Language::all();

    foreach ($languages as $language) {
      $messageArray[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language.';

      $messageArray[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language.';

      $messageArray[$language->code . '_title.unique'] = 'The title field must be unique for ' . $language->name . ' language.';

      $messageArray[$language->code . '_category_id.required'] = 'The category field is required for ' . $language->name . ' language.';

      $messageArray[$language->code . '_author.required'] = 'The author field is required for ' . $language->name . ' language.';

      $messageArray[$language->code . '_content.min'] = 'The content must be at least 30 characters for ' . $language->name . ' language.';
    }

    return $messageArray;
  }
}
