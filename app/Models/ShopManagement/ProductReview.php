<?php

namespace App\Models\ShopManagement;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;
    protected $fillable = [
      'user_id',
      'product_id',
      'review',
      'comment',
    ];
}
