<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactPageRequest extends FormRequest
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
          'contact_form_title' => 'required|max:255',
          'contact_form_subtitle' => 'required|max:255',
          'contact_addresses' => 'required',
          'contact_numbers' => 'required',
          'contact_mails' => 'required',
          'latitude' => 'nullable|max:255',
          'longitude' => 'nullable|max:255',
          'map_zoom' => 'nullable|max:255',
        ];
    }
}
