<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtWorkView extends Model
{
    protected $fillable = ['art_work_id', 'user_id', 'ip_address', 'user_agent', 'viewed_at'];
    public $timestamps = false;

    public function artwork(): BelongsTo
    {
        return $this->belongsTo(ArtWork::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
