<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawPaymentMethod extends Model
{
  use HasFactory;
  protected $fillable = [
    'language_id',
    'fixed_charge',
    'percentage_charge',
    'max_limit',
    'min_limit',
    'name',
    'status'
  ];

  public function withdraws()
  {
    return $this->hasMany(Withdraw::class, 'method_id', 'id');
  }
  public function inputs()
  {
    return $this->hasMany(WithdrawMethodInput::class, 'withdraw_payment_method_id', 'id');
  }
}
