<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'categories_section_status',
    'about_section_status',
    'featured_section_status',
    'features_section_status',
    'how_work_section_status',
    'testimonials_section_status',
    'testimonials_section_status',
    'newsletter_section_status',
    'partner_section_status',
    'footer_section_status'
  ];
}
