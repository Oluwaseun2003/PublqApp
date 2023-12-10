<?php

namespace App\Http\Requests\ShopManagement;

use App\Models\Language;
use App\Models\ShopManagement\Product;
use App\Models\ShopManagement\ProductContent;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
    $ruleArray = [
      'feature_image' => $this->hasFile('feature_image') ? new ImageMimeTypeRule() : '',
      'sku' => 'required',
      'current_price' => 'required',
      'is_feature' => 'required',
      'status' => 'required',
    ];

    if ($this->type == 'physical') {

      $ruleArray['stock'] = 'required';
    }



    if ($this->type == 'digital') {
      if ($this->file_type == 'upload') {
        $Product = Product::where('id', $this->product_id)->first();
        if ($Product->download_link == null) {
          $ruleArray['download_file'] = 'required';
        }
      } else {
        $ruleArray['download_link'] = 'required';
      }
    }

    $languages = Language::all();
    $id = $this->route('id');


    foreach ($languages as $language) {
      $slug = createSlug($this[$language->code . '_title']);
      $ruleArray[$language->code . '_title'] = [
        'required',
        'max:255',
        function ($attribute, $value, $fail) use ($slug, $id, $language) {
          $cis = ProductContent::where('product_id', '<>', $id)->get();
          foreach ($cis as $key => $ci) {
            if (strtolower($slug) == strtolower($ci->slug)) {
              $fail('The title field must be unique for ' . $language->name . ' language.');
            }
          }
        }
      ];
      $ruleArray[$language->code . '_title'] = 'required';
      $ruleArray[$language->code . '_category_id'] = 'required';
      $ruleArray[$language->code . '_summary'] = 'required';
      $ruleArray[$language->code . '_description'] = 'min:30';
    }

    return $ruleArray;
  }

  public function messages()
  {
    $messageArray = [];

    $languages = Language::all();

    foreach ($languages as $language) {
      $messageArray[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language.';

      $messageArray[$language->code . '_category_id.required'] = 'The category field is required for ' . $language->name . ' language.';
      $messageArray[$language->code . '_summary.required'] = 'The Summery field is required for ' . $language->name . ' language.';

      $messageArray[$language->code . '_description.min'] = 'The description must be at least 30 characters for ' . $language->name . ' language.';
    }

    return $messageArray;
  }
}
