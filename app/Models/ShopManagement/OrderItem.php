<?php

namespace App\Models\ShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
      'product_order_id',
        'product_id',
        'user_id',
        'title',
        'sku',
        'qty',
        'category',
        'image',
        'summery',
        'description',
        'price',
        'previous_price',
    ];
}
