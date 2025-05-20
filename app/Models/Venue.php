<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    /**
     * Get the bookings for the venue.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
} 