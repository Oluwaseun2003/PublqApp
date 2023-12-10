<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
      'name',
      'code',
      'type',
      'value',
      'events',
      'start_date',
      'end_date'
    ];
}
