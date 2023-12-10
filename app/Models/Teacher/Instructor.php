<?php

namespace App\Models\Teacher;

use App\Models\Curriculum\CourseInformation;
use App\Models\Language;
use App\Models\Teacher\SocialLink;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'image',
    'name',
    'occupation',
    'description',
    'is_featured'
  ];

  public function instructorLang()
  {
    return $this->belongsTo(Language::class);
  }

  public function socialPlatform()
  {
    return $this->hasMany(SocialLink::class);
  }

  public function courseList()
  {
    return $this->hasMany(CourseInformation::class);
  }

  public function socials()
  {
    return $this->hasMany(SocialLink::class);
  }
}
