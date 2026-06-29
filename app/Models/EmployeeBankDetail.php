<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBankDetail extends Model
{
    protected $fillable = [
        'employee_id', 'holder_name',
        'bank_name', 'account_number',
        'ifsc_code', 'photo','passbook'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}