<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class BusinessClient extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'job_title',
        'phone',
        'business_type',
        'specific_business_type',
        'terms_accepted',
        'organization',
        'email_verified_at'
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'approval_status' => 'string',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
