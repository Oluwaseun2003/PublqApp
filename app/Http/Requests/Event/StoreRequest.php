<?php

namespace App\Http\Requests\Event;

use App\Models\Event\EventContent;
use App\Models\Language;
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
   * @return array<string, mixed>
   */
  public function rules()
  {
    $request = $this->request->all();
    $ruleArray = [
      'slider_images' => 'required',
      'thumbnail' => [
        'required',
        'dimensions:width=320,height=230',
        new ImageMimeTypeRule()
      ],
      'status' => 'required',
      'is_featured' => 'required'
    ];

    if ($this->date_type == 'single') {
      $ruleArray['start_date'] = 'required';
      $ruleArray['start_time'] = 'required';
      $ruleArray['end_date'] = 'required';
      $ruleArray['end_time'] = 'required';
    }

    if ($this->date_type == 'multiple') {
      $ruleArray['m_start_date.**'] = 'required';
      $ruleArray['m_start_time.**'] = 'required';
      $ruleArray['m_end_date.**'] = 'required';
      $ruleArray['m_end_time.**'] = 'required';
    }


    if ($this->event_type == 'online') {
      $ruleArray['early_bird_discount_type'] = 'required';
      $ruleArray['discount_type'] = 'required_if:early_bird_discount_type,enable';
      $ruleArray['early_bird_discount_amount'] = 'required_if:early_bird_discount_type,enable';
      $ruleArray['early_bird_discount_date'] = 'required_if:early_bird_discount_type,enable';
      $ruleArray['early_bird_discount_time'] = 'required_if:early_bird_discount_type,enable';
      $ruleArray['ticket_available_type'] = 'required';
      if ($this->filled('ticket_available_type') && $this->ticket_available_type == 'limited') {
        $ruleArray['ticket_available'] = 'required';
      }
      $ruleArray['max_ticket_buy_type'] = 'required';
      if ($this->filled('max_ticket_buy_type') && $this->max_ticket_buy_type == 'limited') {
        $ruleArray['max_buy_ticket'] = 'required';
      }

      if (!$this->filled('pricing_type')) {
        $ruleArray['price'] = 'required';
      }

      if ($request['early_bird_discount_type'] == 'enable' && $request['discount_type'] == 'percentage') {
        $ruleArray['early_bird_discount_amount'] = 'numeric|between:1,99';
      } elseif ($request['early_bird_discount_type'] == 'enable' && $request['discount_type'] == 'fixed') {
        $price = $request['price'] - 1;
        $ruleArray['early_bird_discount_amount'] = "numeric|between:1, $price";
      }
    }






    if ($this->event_type == 'venue') {
      $ruleArray['latitude'] = 'required_if:event_type,venue';
      $ruleArray['longitude'] = 'required_if:event_type,venue';
    }

    $languages = Language::all();


    foreach ($languages as $language) {
      $slug = createSlug($this[$language->code . '_title']);
      $ruleArray[$language->code . '_title'] = [
        'required',
        'max:255',
        function ($attribute, $value, $fail) use ($slug, $language) {
          $cis = EventContent::where('language_id', $language->id)->get();
          foreach ($cis as $key => $ci) {
            if (strtolower($slug) == strtolower($ci->slug)) {
              $fail('The title field must be unique for ' . $language->name . ' language.');
            }
          }
        }
      ];
      $ruleArray[$language->code . '_title'] = 'required';
      $ruleArray[$language->code . '_category_id'] = 'required';
      $ruleArray[$language->code . '_description'] = 'min:30';
      $ruleArray[$language->code . '_address'] = 'required_if:event_type,venue';
      $ruleArray[$language->code . '_country'] = 'required_if:event_type,venue';
      $ruleArray[$language->code . '_city'] = 'required_if:event_type,venue';
    }
    return $ruleArray;
  }

  public function messages()
  {
    $messageArray = [];

    $languages = Language::all();

    foreach ($languages as $language) {
      $messageArray[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language.';

      $messageArray[$language->code . '_address.required'] = 'The address field is required for ' . $language->name . ' language.';
      $messageArray[$language->code . '_country.required'] = 'The Country field is required for ' . $language->name . ' language.';
      $messageArray[$language->code . '_city.required'] = 'The City field is required for ' . $language->name . ' language.';

      $messageArray[$language->code . '_address.required_if'] = 'The Address field is required for ' . $language->name . ' language.';
      $messageArray[$language->code . '_country.required_if'] = 'The Country field is required for ' . $language->name . ' language.';
      $messageArray[$language->code . '_city.required_if'] = 'The City field is required for ' . $language->name . ' language.';

      $messageArray[$language->code . '_category_id.required'] = 'The category field is required for ' . $language->name . ' language.';

      $messageArray[$language->code . '_description.min'] = 'The description must be at least 30 characters for ' . $language->name . ' language.';
    }


    $messageArray['m_start_date.required'] = 'The start date feild is required.!';
    $messageArray['m_start_time.required'] = 'The start time feild is required.!';
    $messageArray['m_end_date.required'] = 'The end date feild is required.!';
    $messageArray['m_end_time.required'] = 'The end time feild is required.!';

    return $messageArray;
  }
}
