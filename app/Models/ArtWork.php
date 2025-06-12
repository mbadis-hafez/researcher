<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtWork extends Model
{
    protected $table = "artworks";

    protected $fillable = [
        "title",
        "description",
        "stock_number",
        "inventory_number",
        "year",
        "medium",
        "dimensions",
        "condition_report",
        "provenance",
        "comment",
        "source",
        "status",
        "price",
        "contract_type",
        "contract_duration",
        "contract_start_date",
        "contract_end_date",
        "contract_validity",
        "gallery_commission",
        "artist_share",
        "edition",
        "signed_dated",
        "importance",
        "artwork_type",
        "artist_id",
        "nationality",
        "current_location",
        "image_path",
        "signature",
        "is_active",
        "category_id",
        "is_featured",
        "additional_info",
        "condition_report",
        "images",
        'exhibition',
        'status',
        'author_id',
        'researcher_id',
        'researcher_name'
    ];


    protected $casts = [
        'images' => 'array',
        'additional_info' => 'array',
        'price' => 'float',
        'year_created' => 'integer',
        'status' => 'integer',
    ];

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'artwork_user', 'artwork_id', 'user_id');
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artist_id');
    }

    public function researcher()
    {
        return $this->belongsTo(User::class, 'researcher_id');
    }


     public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getDisplayImageAttribute()
{
    // If image_path contains JSON array
    if (!empty($this->image_path)) {
        $images = json_decode($this->image_path, true);
        
        if (is_array($images) && !empty($images)) {
            return asset('storage/' . $images[0]);
        }
        
        // If it's a plain string path (not JSON)
        if (is_string($this->image_path)) {
            return asset('storage/' . $this->image_path);
        }
    }

    // Fallback to the images attribute if needed (for legacy data)
    if (!empty($this->images)) {
        $images = is_array($this->images) ? $this->images : json_decode($this->images, true);
        if (!empty($images)) {
            return asset('storage/' . $images[0]);
        }
    }

    return asset('assets/img/logo.png');
}

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'artwork_collection', 'artwork_id', 'collection_id');
    }
}
