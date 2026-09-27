<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'start_time',
        'end_time',
        'capacity',
        'booked_count',
    ];

    public $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }


    public function remainingCapacity(): int
    {
        return $this->capacity - $this->booked_count;
    }
}
