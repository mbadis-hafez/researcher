<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $fillable = [
        'artist_id',
        'name',
        'description',
        'date',
    ];

     public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    protected $casts = [
        'date' => 'date',
    ];
}
