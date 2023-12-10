<?php

namespace App\Models\HomePage;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'background_image',
    'first_title',
    'second_title',
    'first_button',
    'first_button_url',
    'second_button',
    'second_button_url',
    'video_url',
    'image'
  ];

  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }
}
