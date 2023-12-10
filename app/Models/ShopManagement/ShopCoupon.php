<?php

namespace App\Models\ShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCoupon extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'code',
    'type',
    'value',
    'start_date',
    'end_date',
    'minimum_spend'
  ];
}
