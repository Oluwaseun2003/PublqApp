<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
  use HasFactory;
  protected $fillable = [
    'user_id',
    'user_type',
    'email',
    'subject',
    'description',
    'attachment',
    'status',
    'last_message',
  ];
  public function customer()
  {
    return $this->belongsTo(Customer::class, 'user_id', 'id');
  }
  public function organizer()
  {
    return $this->belongsTo(Organizer::class, 'user_id', 'id');
  }
  public function messages()
  {
    return $this->hasMany(Conversation::class);
  }
}
