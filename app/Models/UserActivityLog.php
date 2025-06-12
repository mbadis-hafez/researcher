<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'browser',
        'platform',
        'device',
        'country',
        'city',
        'action',
        'details'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
