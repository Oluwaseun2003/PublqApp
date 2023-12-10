<?php

namespace App\Models;

use App\Models\Event\Booking;
use App\Models\Event\EventContent;
use App\Models\Event\EventDates;
use App\Models\Event\EventImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event\Ticket;
use App\Models\Event\Wishlist;

class Event extends Model
{
  use HasFactory;
  protected $fillable = [
    'organizer_id',
    'thumbnail',
    'status',
    'countdown_status',
    'date_type',
    'start_date',
    'start_time',
    'duration',
    'end_date',
    'end_time',
    'end_date_time',
    'is_featured',
    'event_type',
    'latitude',
    'longitude'
  ];
  public function ticket()
  {
    return $this->hasOne(Ticket::class);
  }
  public function tickets()
  {
    return $this->hasMany(Ticket::class);
  }
  //information
  public function information()
  {
    return $this->hasOne(EventContent::class);
  }
  //bookings
  public function booking()
  {
    return $this->hasMany(Booking::class);
  }

  //wishtlist
  public function wishlists()
  {
    return $this->hasMany(Wishlist::class, 'event_id', 'id');
  }

  public function organizer()
  {
    return $this->belongsTo(Organizer::class);
  }

  public function galleries()
  {
    return $this->hasMany(EventImage::class);
  }

  public function dates()
  {
    return $this->hasMany(EventDates::class);
  }
}
