<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'artist_id', 'name', 'description', 
        'start_date', 'end_date', 'location', 'image'
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $appends = ['status_text','status_color'];

    public function getStatusTextAttribute():string
    {
        $now = Carbon::now();
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        $statusText = '';
        if ($now->lt($start)) {
            $statusText = 'Upcoming';
        } elseif ($now->between($start, $end)) {
            $statusText = 'Ongoing';
        } else {
            $statusText = 'Ended';
        }
        return $statusText;
    }

    public function getStatusColorAttribute():string
    {
        $now = Carbon::now();
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        $statusColor = '';
        if ($now->lt($start)) {
            $statusColor = 'warning'; // green - upcoming
        } elseif ($now->between($start, $end)) {
            $statusColor = 'success'; // green - ongoing
        } else {
            $statusColor = 'danger'; // red - ended
        }
        return $statusColor;
    }
}