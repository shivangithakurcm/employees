<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeOfficialDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    // Employee List
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $employees = $query->latest()->paginate(10);
        return view('admin.employees.index', compact('employees'));
    }

    // Step 1 - Basic Info
    public function create()
    {
        return view('admin.employees.create');
    }

   public function storeBasicInfo(Request $request)
{
    // 🔥 DEBUG (check if coming here)
    dd($request->all());

    // validation
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'contact' => 'required',
    ]);

    // save logic
    // Example:
    Employee::create($request->all());

    return redirect()->route('admin.employees.index')
        ->with('success', 'Employee added');
}

    // Step 2 - Educational Qualification
    public function step2($id)
    {
        $employee = Employee::with('qualifications')->findOrFail($id);
        return view('admin.employees.step2', compact('employee'));
    }

    public function saveStep2(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->qualifications()->delete();

        if ($request->has('qualifications')) {
            foreach ($request->qualifications as $qual) {
                $employee->qualifications()->create([
                    'qualification_type' => $qual['qualification_type'] ?? null,
                    'institution_name'   => $qual['institution_name'] ?? '',
                    'field_of_study'     => $qual['field_of_study'] ?? null,
                    'start_date'         => $qual['start_date'] ?? null,
                    'end_date'           => $qual['end_date'] ?? null,
                    'percentage'         => $qual['percentage'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.employees.step3', $id)
                         ->with('success', 'Qualifications saved!');
    }

    // Step 3 - Previous Employer
    public function step3($id)
    {
        $employee = Employee::with('previousEmployers')->findOrFail($id);
        return view('admin.employees.step3', compact('employee'));
    }

    public function saveStep3(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->previousEmployers()->delete();

        if ($request->has('employers')) {
            foreach ($request->employers as $employer) {
                $employee->previousEmployers()->create([
                    'company_name'   => $employer['company_name'] ?? '',
                    'hr_name'        => $employer['hr_name'] ?? null,
                    'hr_phone'       => $employer['hr_phone'] ?? null,
                    'address_line1'  => $employer['address_line1'] ?? null,
                    'state'          => $employer['state'] ?? null,
                    'city'           => $employer['city'] ?? null,
                    'pincode'        => $employer['pincode'] ?? null,
                    'monthly_salary' => $employer['monthly_salary'] ?? null,
                    'designation'    => $employer['designation'] ?? null,
                    'duration'       => $employer['duration'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.employees.step4', $id)
                         ->with('success', 'Previous employers saved!');
    }

    public function step4($id)
{
    $employee = Employee::with('bankDetails')->findOrFail($id);
    return view('admin.employees.step4', compact('employee'));
}

    // Step 4 - Bank Details
    public function saveStep4(Request $request, $id)
{
    $employee = Employee::findOrFail($id);
    $employee->bankDetails()->delete();

    if ($request->has('bank_details')) {
        foreach ($request->bank_details as $index => $detail) {
            $photoPath    = null;
            $passbookPath = null;

            if ($request->hasFile("bank_details.$index.photo")) {
                $photoPath = $request->file("bank_details.$index.photo")
                                     ->store('employees/bank', 'public');
            }
            if ($request->hasFile("bank_details.$index.passbook")) {
                $passbookPath = $request->file("bank_details.$index.passbook")
                                        ->store('employees/passbook', 'public');
            }

            $employee->bankDetails()->create([
                'holder_name'    => $detail['holder_name'] ?? '',
                'bank_name'      => $detail['bank_name'] ?? '',
                'account_number' => $detail['account_number'] ?? '',
                'ifsc_code'      => $detail['ifsc_code'] ?? '',
                'photo'          => $photoPath,
                'passbook'       => $passbookPath,
            ]);
        }
    }

    return redirect()->route('admin.employees.step5', $id)
                     ->with('success', 'Bank details saved!');

                     
}

    // Step 5 - Official Details
    public function step5($id)
    {
        $employee = Employee::with('officialDetail')->findOrFail($id);
        return view('admin.employees.step5', compact('employee'));
    }

    public function saveStep5(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        EmployeeOfficialDetail::updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'doj'         => $request->doj,
                'designation' => $request->designation,
                'salary'      => $request->salary,
                'branch'      => $request->branch,
                'permission'  => $request->permission,
                'password'    => bcrypt($request->password),
            ]
        );

        return redirect()->route('admin.employees.index')
                         ->with('success', 'Employee added successfully!');
    }

    // View Employee
    public function show($id)
    {
        $employee = Employee::with([
            'qualifications',
            'previousEmployers',
            'bankDetails',
            'officialDetail'
        ])->findOrFail($id);

        return view('admin.employees.show', compact('employee'));
    }

    // Edit Employee
    public function edit($id)
    {
        $employee = Employee::with([
            'qualifications',
            'previousEmployers',
            'bankDetails',
            'officialDetail'
        ])->findOrFail($id);

        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
{
    $employee = Employee::findOrFail($id);

    /* ---------------- BASIC INFO ---------------- */
    $employee->update($request->only([
        'name','email','contact','address',
        'state','city','pincode',
        'date_of_birth','marital_status','blood_group'
    ]));

    /* ---------------- PHOTO ---------------- */
    if ($request->hasFile('photo')) {
        $file = $request->file('photo')->store('employees', 'public');
        $employee->photo = $file;
        $employee->save();
    }

    /* ---------------- QUALIFICATIONS ---------------- */
    $employee->qualifications()->delete();

    if ($request->filled('qualifications')) {
        foreach ($request->qualifications as $q) {

            // SKIP EMPTY ROWS
            if (
                empty($q['qualification_type']) &&
                empty($q['institution_name']) &&
                empty($q['field_of_study'])
            ) {
                continue;
            }

            $employee->qualifications()->create([
                'qualification_type' => $q['qualification_type'] ?? null,
                'institution_name'   => $q['institution_name'] ?? null,
                'field_of_study'     => $q['field_of_study'] ?? null,
                'start_date'         => $q['start_date'] ?? null,
                'end_date'           => $q['end_date'] ?? null,
            ]);
        }
    }

    /* ---------------- EMPLOYERS ---------------- */
    $employee->previousEmployers()->delete();

    if ($request->filled('employers')) {
        foreach ($request->employers as $e) {

            if (empty($e['company_name'])) continue;

            // FILE HANDLING
            if (isset($e['salary_slip']) && $e['salary_slip'] instanceof \Illuminate\Http\UploadedFile) {
                $e['salary_slip'] = $e['salary_slip']->store('salary_slips', 'public');
            }

            $employee->previousEmployers()->create($e);
        }
    }

    /* ---------------- BANK DETAILS ---------------- */
    $employee->bankDetails()->delete();

    if ($request->filled('bank_details')) {
        foreach ($request->bank_details as $b) {

            if (empty($b['holder_name']) && empty($b['bank_name'])) {
                continue;
            }

            // PHOTO
            if (isset($b['photo']) && $b['photo'] instanceof \Illuminate\Http\UploadedFile) {
                $b['photo'] = $b['photo']->store('bank_photos', 'public');
            }

            // PASSBOOK
            if (isset($b['passbook']) && $b['passbook'] instanceof \Illuminate\Http\UploadedFile) {
                $b['passbook'] = $b['passbook']->store('passbooks', 'public');
            }

            $employee->bankDetails()->create($b);
        }
    }

    return redirect()
        ->route('admin.employees.show', $id)
        ->with('success', 'Employee updated successfully');
}



    // Toggle Status
    public function toggleStatus($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->status = ($employee->status === 'active') ? 'inactive' : 'active';
        $employee->save();
        return back()->with('success', 'Status updated!');
    }
}