<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FollowUp extends Model
{
    protected $fillable = [
        'lead_id',
        'comment',
        'status',
        'date',
        'time',
    ];

    // Date casting
    protected $casts = [
        'date' => 'date',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    // ✅ Ye new additions hain
    
    // Overdue hai ya nahi
    public function getIsOverdueAttribute()
    {
        return $this->status === 'pending' 
            && Carbon::parse($this->date)->isPast();
    }

    // Readable date
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->format('d M Y');
    }
    

    // Readable time
    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->time)->format('h:i A');
    }
}