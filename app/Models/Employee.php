<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    // ==============================
    // ✅ MASS ASSIGNABLE FIELDS
    // ==============================
    protected $fillable = [
        'name',
        'email',
        'contact',
        'address',
        'designation',
        'salary',
        'permission',
        'photo',
        'date_of_birth',
        'blood_group',
        'marital_status',
        'state',
        'city',
        'pincode',
        'status',
        'password',
        'user_id',
    ];

    // ==============================
    // ✅ HIDE SENSITIVE DATA
    // ==============================
    protected $hidden = [
        'password'
    ];

    
    // 🔹 Qualifications
    public function qualifications()
    {
        return $this->hasMany(EmployeeQualification::class);
    }

    // 🔹 Previous Employers
    public function previousEmployers()
    {
        return $this->hasMany(EmployeePreviousEmployer::class);
    }

    // 🔹 Bank Details
    public function bankDetails()
    {
        return $this->hasMany(EmployeeBankDetail::class);
    }

    // 🔹 Official Detail (Single)
    public function officialDetail()
    {
        return $this->hasOne(EmployeeOfficialDetail::class);
    }

    public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}

    
}