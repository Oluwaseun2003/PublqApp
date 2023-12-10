<?php

namespace App\Http\Requests\ShopManagement;

use Illuminate\Foundation\Http\FormRequest;

class ShippingChargeRequest extends FormRequest
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
        'title' => 'required',
        'text' => 'required',
        'charge' => 'required',
      ];
    }
}
