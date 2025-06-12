<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'image', 'is_active'];

    public function artworks(): HasMany
    {
        return $this->hasMany(ArtWork::class);
    }
}
