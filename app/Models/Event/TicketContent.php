<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketContent extends Model
{
    use HasFactory;
    protected $fillable = [
        'language_id',
        'ticket_id',
        'title',
        'description'
    ];
}
