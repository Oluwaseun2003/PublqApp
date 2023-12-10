<?php

namespace App\Models\ShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductContent extends Model
{
  use HasFactory;
  protected $fillable = [
    'title',
    'slug',
    'language_id',
    'tags',
    'summary',
    'description',
    'meta_keywords',
    'meta_description',
  ];
}
