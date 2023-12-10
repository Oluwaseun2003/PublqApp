<?php

namespace App\Models\Footer;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterContent extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['language_id', 'footer_background_color', 'about_company', 'copyright_text', 'footer_logo'];

  public function contentLang()
  {
    return $this->belongsTo(Language::class);
  }
}
