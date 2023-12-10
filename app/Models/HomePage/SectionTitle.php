<?php

namespace App\Models\HomePage;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionTitle extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'event_section_title',
    'category_section_title',
    'featured_instructors_section_title',
    'testimonials_section_title',
    'features_section_title',
    'blog_section_title'
  ];

  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }
}
