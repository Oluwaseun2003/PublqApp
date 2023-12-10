<?php

namespace App\Models;

use App\Models\Event\Booking;
use App\Models\ShopManagement\ProductOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transcation_id',
        'booking_id',
        'transcation_type',
        'customer_id',
        'organizer_id',
        'payment_status',
        'payment_method',
        'grand_total',
        'pre_balance',
        'after_balance',
        'commission',
        'tax',
        'gateway_type',
        'currency_symbol',
        'currency_symbol_position'
    ];
    //method
    public function method()
    {
        return $this->belongsTo(WithdrawPaymentMethod::class, 'payment_method', 'id');
    }

    //room_booking 
    public function product_order()
    {
        return $this->belongsTo(ProductOrder::class, 'booking_id', 'id');
    }
    //event_booking 
    public function event_booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }
}
