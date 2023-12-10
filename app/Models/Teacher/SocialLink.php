<?php

namespace App\Models\Teacher;

use App\Models\Teacher\Instructor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
  use HasFactory;

  protected $table = 'social_links';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['instructor_id', 'icon', 'url', 'serial_number'];

  public function instructor()
  {
    return $this->belongsTo(Instructor::class);
  }
}
