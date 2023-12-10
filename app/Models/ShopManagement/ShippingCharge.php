<?php

namespace App\Models\ShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
  use HasFactory;

  protected $fillable = [
    'title',
    'language_id',
    'text',
    'days',
    'charge',
  ];
}
