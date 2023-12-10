<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDates extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'duration',
        'start_date_time',
        'end_date_time'
    ];
}
