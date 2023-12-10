<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event\TicketVariation;

class Ticket extends Model
{
  use HasFactory;

  protected $fillable = [
    'event_id',
    'event_type',
    'title',
    'ticket_available_type',
    'ticket_available',
    'max_ticket_buy_type',
    'max_buy_ticket',
    'description',
    'pricing_type',
    'price',
    'f_price',
    'early_bird_discount_type',
    'early_bird_discount',
    'early_bird_discount_amount',

    'early_bird_discount_date',
    'early_bird_discount_time',
    'variations',
    'trans_vars'
  ];
  //ticket_variations
  public function ticket_variations()
  {
    return $this->hasMany(TicketVariation::class);
  }
}
