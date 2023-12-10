<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventFeature extends Model
{
    use HasFactory;
    protected $fillable = [
      'language_id',
      'icon',
      'title',
      'text',
      'serial_number',
    ];
}
