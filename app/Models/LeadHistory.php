<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadHistory extends Model
{
    protected $fillable = [
        'lead_id',
        'event_type',    // created | status_changed | edited
        'from_status',
        'to_status',
        'date',
        'time',
        'comment',
        'document',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}