<?php

namespace App\Models\HomePage;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsSection extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['language_id', 'image', 'title', 'subtitle', 'text'];

  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }
}
