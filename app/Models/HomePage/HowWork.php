<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HowWork extends Model
{
    use HasFactory;
    protected $fillable = [
      'language_id',
      'title',
      'text',
    ];
}
