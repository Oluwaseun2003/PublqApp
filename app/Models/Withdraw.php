<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
  use HasFactory;
  public function method()
  {
    return $this->belongsTo(WithdrawPaymentMethod::class, 'method_id', 'id');
  }
  public function organizer()
  {
    return $this->belongsTo(Organizer::class);
  }
}
