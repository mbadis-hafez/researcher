<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtistImage extends Model
{
    protected $fillable = ['name','path','artist_id'];

    public function artist():BelongsTo
    {
        return $this->belongsTo(Artist::class,'artist_id');
    }
}