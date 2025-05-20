<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organiser extends Model
{
    /**
     * Get the bookings for the organiser.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
} 