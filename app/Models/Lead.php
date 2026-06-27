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
    'assigned_to',      
    'amount',
    'timeline',
    'proposal_document',
    'negotiation_amount',
    'revised_proposal',
    'won_name',
    'won_contact',
    'won_email',
    'won_designation',
    'won_business_name',
    'won_gst_no',
    'won_location',
    'won_country',
    'won_state',
    'won_city',
    'won_project_type',
    'won_project_detail',
    'won_final_cost',
    'won_milestone',
    'won_timeline',
    'won_token_received',
    'won_token_amount',
    'won_amount_type',
    'won_received_date',
];

protected $casts = [
    'assigned_to' => 'integer',
];

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

   public function histories()
{
    return $this->hasMany(LeadHistory::class);
}

public function wonProjectType()
{
    return $this->belongsTo(\App\Models\Master\ProjectType::class, 'won_project_type');
}
// App/Models/Lead.php
public function assignedTo()
{
    return $this->belongsTo(User::class, 'assigned_to');
}
// User model mein add karo
public function assignedLeads()
{
    return $this->hasMany(\App\Models\Lead::class, 'assigned_to');
}
// App/Models/Lead.php
public function getNameAttribute()
{
    return trim($this->first_name . ' ' . $this->last_name);
}
protected static function booted()
{
    static::addGlobalScope('assigned', function ($query) {
        if (auth()->check() && auth()->user()->role === 'employee') {
            $query->where('assigned_to', auth()->id());
        }
    });
}
}