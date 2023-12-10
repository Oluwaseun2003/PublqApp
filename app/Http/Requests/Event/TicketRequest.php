<?php

namespace App\Http\Requests\Event;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ImageMimeTypeRule;

class TicketRequest extends FormRequest
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
      'ticket_available_type' => 'required',
      'max_ticket_buy_type' => 'required',
      'early_bird_discount_type' => 'required',
      'pricing_type_2' => 'required',
    ];

    $ruleArray['ticket_available'] = 'required_if:ticket_available_type,limited';
    $ruleArray['max_buy_ticket'] = 'required_if:max_ticket_buy_type,limited';
    $ruleArray['discount_type'] = 'required_if:early_bird_discount_type,enable';
    $ruleArray['early_bird_discount_amount'] = 'required_if:early_bird_discount_type,enable';
    $ruleArray['early_bird_discount_date'] = 'required_if:early_bird_discount_type,enable';
    $ruleArray['early_bird_discount_time'] = 'required_if:early_bird_discount_type,enable';

    if ($this->pricing_type_2 == 'normal') {
      $ruleArray['price'] = 'required|numeric|min:0';

      if ($request['early_bird_discount_type'] == 'enable' && $request['discount_type'] == 'percentage') {
        $ruleArray['early_bird_discount_amount'] = 'numeric|between:1,99';
      } elseif ($request['early_bird_discount_type'] == 'enable' && $request['discount_type'] == 'fixed') {
        $price = $request['price'] - 1;
        $ruleArray['early_bird_discount_amount'] = "numeric|between:1, $price";
      }
    }
    if ($this->pricing_type_2 == 'variation') {
      $ruleArray['variation_name.**'] = 'required';
      $ruleArray['variation_price.**'] = 'required|numeric|min:1';

      if ($request['early_bird_discount_type'] == 'enable' && $request['discount_type'] == 'percentage') {
        $ruleArray['early_bird_discount_amount'] = 'numeric|between:1,99';
      } elseif ($request['early_bird_discount_type'] == 'enable' && $request['discount_type'] == 'fixed') {
        $price = min($this->variation_price) - 1;
        $ruleArray['early_bird_discount_amount'] = "numeric|between:1, $price";
      }
    }

    $languages = Language::all();


    foreach ($languages as $language) {
      $ruleArray[$language->code . '_title'] = 'required';
    }

    return $ruleArray;
  }

  public function messages()
  {
    $messageArray = [];
    $languages = Language::all();

    $messageArray['variation_name.**.required'] = 'The variation name field is required.';
    $messageArray['variation_price.**.required'] = 'The variation price field is required.';
    $messageArray['v_ticket_available.**.required'] = 'The ticket available field is required.';
    $messageArray['v_ticket_available'] = 'The ticket available field is required.';
    $messageArray['v_ticket_available.**.min'] = 'The ticket available field must be at least 1.';

    foreach ($languages as $language) {
      $messageArray[$language->code . '_title.required'] = 'The Ticket name field is required for ' . $language->name;
    }
    return $messageArray;
  }
}
