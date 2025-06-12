<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artist extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'email',
        'password',
        'description',
        'short_biography',
        'full_biography',
        'website',
        'notes',
        'country',
        'image',
        'artist_date'
    ];

    protected $appends = ['image_path'];

    public function images(): HasMany
    {
        return $this->hasMany(ArtistImage::class, 'artist_id');
    }

    public function getImagePathAttribute(): string
    {
        return asset('storage/artist/' . $this->image);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exhibitions()
    {
        return $this->hasMany(Exhibition::class);
    }

    public function artworks()
    {
        return $this->hasMany(ArtWork::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function awards()
    {
        return $this->hasMany(Award::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
}
