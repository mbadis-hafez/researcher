<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{

    protected $fillable = ["name", "artist_id"];


    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function artworks()
    {
        return $this->belongsToMany(Artwork::class, 'artwork_collection', 'collection_id', 'artwork_id');
    }
}
