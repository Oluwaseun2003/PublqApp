<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventContent extends Model
{
  use HasFactory;

  protected $fillable = [
    'event_id',
    'event_category_id',
    'title',
    'address',
    'country',
    'state',
    'city',
    'zip_code',
    'description',
    'meta_keywords',
    'meta_description',
    'google_calendar_id',
    'refund_policy',
  ];

  public function tickets()
  {
    return $this->hasMany(Ticket::class, 'event_id', 'event_id');
  }
}
