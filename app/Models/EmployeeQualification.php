<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeQualification extends Model
{
    protected $fillable = [
        'employee_id', 'qualification_type',
        'institution_name', 'field_of_study',
        'start_date', 'end_date', 'percentage'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function qualifications()
{
    return $this->hasMany(EmployeeQualification::class);
}
}