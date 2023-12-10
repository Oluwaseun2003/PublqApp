<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPage extends Model
{
    use HasFactory;
    protected $fillable = [
      'contact_form_title',
      'contact_form_subtitle',
      'contact_addresses',
      'contact_numbers',
      'contact_mails',
      'latitude',
      'longitude',
      'map_zoom',
      'language_id'
    ];
}
