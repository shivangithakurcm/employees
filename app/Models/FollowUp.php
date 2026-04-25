<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $fillable = [
        'lead_id',
        'comment',
        'status',
        'date',
        'time',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}