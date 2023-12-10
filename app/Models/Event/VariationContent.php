<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_id',
        'ticket_id',
        'name',
        'key',
    ];
}
