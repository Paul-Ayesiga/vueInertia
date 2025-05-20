<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'stage_name',
        'gender',
        'nationality',
        'country',
        'region',
        'address',
        'nin',
        'bio',
        'national_id_photos',
        'profile_photo',
        'cover_photo',
        'social_media_links',
        'music_links',
    ];

    protected $casts = [
        'national_id_photos' => 'array',
        'social_media_links' => 'array',
        'music_links' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bookings for the artist.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the followers of the artist.
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'artist_follower')
            ->withTimestamps();
    }
}
