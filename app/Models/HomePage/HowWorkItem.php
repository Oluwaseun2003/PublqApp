<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HowWorkItem extends Model
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
