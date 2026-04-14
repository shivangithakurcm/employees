<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class EmployeeApiController extends Controller
{
    public function store(Request $request)
    {
        
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:employees,email'
        ]);

        
        $employee = Employee::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'contact'         => $request->contact,
            'address'         => $request->address,
            'designation'     => $request->designation,
            'salary'          => $request->salary,
            'permission'      => $request->permission,
            'state'           => $request->state,
            'city'            => $request->city,
            'pincode'         => $request->pincode,
            'date_of_birth'   => $request->date_of_birth,
            'marital_status'  => $request->marital_status,
            'blood_group'     => $request->blood_group,
            'status'          => 1,
            'password'        => Hash::make($request->password ?? '123456')
        ]);

        
        if (!empty($request->qualifications)) {

            foreach ($request->qualifications as $qual) {

                // Skip empty rows
                if (
                    empty($qual['qualification_type']) &&
                    empty($qual['institution_name'])
                ) {
                    continue;
                }

                $employee->qualifications()->create([
                    'qualification_type' => $qual['qualification_type'] ?? null,
                    'institution_name'  => $qual['institution_name'] ?? null,
                    'field_of_study'    => $qual['field_of_study'] ?? null,
                    'start_date'        => $qual['start_date'] ?? null,
                    'end_date'          => $qual['end_date'] ?? null,
                    'percentage'        => $qual['percentage'] ?? null,
                ]);
            }
        }

        //final response
        return response()->json([
            'status'  => true,
            'message' => 'Employee + qualifications saved successfully',
            'data'    => $employee->load('qualifications')
        ], 201);
    }

    public function index()
{
    $employees = Employee::with([
        'qualifications',
        'previousEmployers',
        'bankDetails'
    ])->get();

    return response()->json([
        'status' => true,
        'data' => $employees
    ]);
}


}