<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePreviousEmployer extends Model
{
    protected $fillable = [
        'employee_id', 'company_name', 'hr_name',
        'hr_phone', 'address_line1', 'state', 'city',
        'pincode', 'monthly_salary', 'designation',
        'duration', 'salary_slip'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function previousEmployers()
{
    return $this->hasMany(EmployeePreviousEmployer::class);
}
}