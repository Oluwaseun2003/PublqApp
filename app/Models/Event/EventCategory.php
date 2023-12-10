<?php

namespace App\Models\Event;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event\EventContent;

class EventCategory extends Model
{
  use HasFactory;
  protected $fillable = ['name', 'language_id', 'image', 'slug', 'status', 'serial_number', 'is_featured'];

  public function events_contens()
  {
    return $this->hasMany(EventContent::class);
  }
}
