<?php

namespace App\Models\Event;

use App\Models\Customer;
use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
  use HasFactory;
  protected $fillable = [
    'customer_id',
    'booking_id',
    'event_id',
    'organizer_id',
    'ticket_id',
    'fname',
    'lname',
    'email',
    'phone',
    'country',
    'state',
    'city',
    'zip_code',
    'address',
    'variation',
    'price',
    'tax_percentage',
    'commission_percentage',
    'tax',
    'commission',
    'quantity',
    'discount',
    'early_bird_discount',
    'currencyText',
    'currencyTextPosition',
    'currencySymbol',
    'currencySymbolPosition',
    'paymentMethod',
    'gatewayType',
    'paymentStatus',
    'invoice',
    'attachmentFile',
    'event_date',
    'scan_status'
  ];

  public function event()
  {
    return $this->hasOne(EventContent::class, 'event_id', 'event_id');
  }
  public function evnt()
  {
    return $this->belongsTo(Event::class, 'event_id', 'id');
  }
  //userInfo
  public function customerInfo()
  {
    return $this->hasOne(Customer::class, 'id', 'customer_id');
  }
  public function organizer()
  {
    return $this->belongsTo(Organizer::class);
  }
}
