<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'artist_id',
        'venue_id',
        'organiser_id',
        'event_name',
        'event_date',
        'start_time',
        'end_time',
        'venue_name',
        'venue_location',
        'description',
        'status',
        'price',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'price' => 'decimal:2',
    ];

    /**
     * Get the artist that owns the booking.
     */
    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    /**
     * Get the venue that owns the booking.
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Get the organiser that owns the booking.
     */
    public function organiser(): BelongsTo
    {
        return $this->belongsTo(Organiser::class);
    }
} 