<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'contact_number',
        'email',
        'city',
        'country',
        'Requirement',
        'date',
        'time',
        'state',
        'status',
        'discussion',
        'comment',
    ];

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

   public function histories()
{
    return $this->hasMany(LeadHistory::class);
}
}