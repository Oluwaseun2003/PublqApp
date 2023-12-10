<?php

namespace App\Models\HomePage;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
  use HasFactory;

  protected $table = 'testimonials';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['language_id', 'image', 'name', 'occupation', 'comment', 'serial_number', 'rating'];

  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }
}
