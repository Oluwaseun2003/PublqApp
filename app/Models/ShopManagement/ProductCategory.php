<?php

namespace App\Models\ShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
  use HasFactory;
  protected $fillable = [
    'name',
    'slug',
    'language_id',
    'image',
    'status',
    'is_feature',
  ];
}
