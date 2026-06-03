<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeOfficialDetail;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    // ─── Employee List ────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name',    'like', '%' . $request->search . '%')
                  ->orWhere('email',   'like', '%' . $request->search . '%')
                  ->orWhere('contact', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $employees = $query->latest()->paginate(5);

        return view('admin.employees.index', compact('employees'));
    }

    // ─── Step 1 – Basic Info ──────────────────────────────────────────────────
    public function create()
    {
        $designations = \App\Models\Designation::orderBy('name')->get();
        return view('admin.employees.create', compact('designations'));
    }

    public function storeBasicInfo(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|unique:employees,email',
            'contact' => 'required|numeric|digits:10',
        ], [
            'name.required'    => 'Name is required.',
            'name.max'         => 'Name cannot exceed 100 characters.',
            'email.required'   => 'Email is required.',
            'email.email'      => 'Please enter a valid email.',
            'email.unique'     => 'This email is already registered.',
            'contact.required' => 'Contact number is required.',
            'contact.numeric'  => 'Contact must contain numbers only.',
            'contact.digits'   => 'Contact must be exactly 10 digits.',
        ]);

        $employee = new Employee();
        $employee->name           = $request->name;
        $employee->email          = $request->email;
        $employee->contact        = $request->contact;
        $employee->address        = $request->address;
        $employee->state          = $request->state;
        $employee->city           = $request->city;
        $employee->pincode        = $request->pincode;
        $employee->date_of_birth  = $request->date_of_birth;
        $employee->marital_status = $request->marital_status;
        $employee->blood_group    = $request->blood_group;
        $employee->designation    = $request->designation;
        $employee->password       = '';
        $employee->save();

        if ($request->hasFile('photo')) {
            $employee->photo = $request->file('photo')->store('employees', 'public');
            $employee->save();
        }

        return redirect()->route('admin.employees.step2', $employee->id)
                         ->with('success', 'Basic info saved!');
    }

    // ─── Step 2 – Educational Qualifications ─────────────────────────────────
    public function step2($id)
    {
        $employee = Employee::with('qualifications')->findOrFail($id);
        return view('admin.employees.step2', compact('employee'));
    }

    public function saveStep2(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        // Check karo koi bhi qualification filled hai ya nahi
        $hasData = false;
        if ($request->has('qualifications')) {
            foreach ($request->qualifications as $qual) {
                if (!empty($qual['qualification_type']) || !empty($qual['institution_name'])) {
                    $hasData = true;
                    break;
                }
            }
        }

        // Data hai tabhi save karo, warna skip karke aage
        if ($hasData) {
            $employee->qualifications()->delete();
            foreach ($request->qualifications as $qual) {
                if (empty($qual['qualification_type']) && empty($qual['institution_name'])) continue;
                $employee->qualifications()->create([
                    'qualification_type' => $qual['qualification_type'] ?? null,
                    'institution_name'   => $qual['institution_name']   ?? '',
                    'field_of_study'     => $qual['field_of_study']     ?? null,
                    'start_date'         => $qual['start_date']         ?? null,
                    'end_date'           => $qual['end_date']           ?? null,
                    'percentage'         => $qual['percentage']         ?? null,
                ]);
            }
            return redirect()->route('admin.employees.step3', $id)
                             ->with('success', 'Qualifications saved!');
        }

        // Skip — bina message ke aage
        return redirect()->route('admin.employees.step3', $id);
    }

    // ─── Step 3 – Previous Employers ─────────────────────────────────────────
    public function step3($id)
    {
        $employee = Employee::with('previousEmployers')->findOrFail($id);
        return view('admin.employees.step3', compact('employee'));
    }

    public function saveStep3(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        // Check karo koi bhi employer filled hai ya nahi
        $hasData = false;
        if ($request->has('employers')) {
            foreach ($request->employers as $employer) {
                if (!empty($employer['company_name'])) {
                    $hasData = true;
                    break;
                }
            }
        }

        // Data hai tabhi save karo, warna skip karke aage
        if ($hasData) {
            $employee->previousEmployers()->delete();
            foreach ($request->employers as $employer) {
                if (empty($employer['company_name'])) continue;
                $employee->previousEmployers()->create([
                    'company_name'   => $employer['company_name']   ?? '',
                    'hr_name'        => $employer['hr_name']        ?? null,
                    'hr_phone'       => $employer['hr_phone']       ?? null,
                    'address_line1'  => $employer['address_line1']  ?? null,
                    'state'          => $employer['state']          ?? null,
                    'city'           => $employer['city']           ?? null,
                    'pincode'        => $employer['pincode']        ?? null,
                    'monthly_salary' => $employer['monthly_salary'] ?? null,
                    'designation'    => $employer['designation']    ?? null,
                    'duration'       => $employer['duration']       ?? null,
                ]);
            }
            return redirect()->route('admin.employees.step4', $id)
                             ->with('success', 'Previous employers saved!');
        }

        // Skip — bina message ke aage
        return redirect()->route('admin.employees.step4', $id);
    }

    // ─── Step 4 – Bank Details ────────────────────────────────────────────────
    public function step4($id)
    {
        $employee = Employee::with('bankDetails')->findOrFail($id);
        return view('admin.employees.step4', compact('employee'));
    }

    public function saveStep4(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        // Check karo koi bhi bank detail filled hai ya nahi
        $hasData = false;
        if ($request->has('bank_details')) {
            foreach ($request->bank_details as $detail) {
                if (!empty($detail['holder_name']) || !empty($detail['bank_name'])) {
                    $hasData = true;
                    break;
                }
            }
        }

        // Data hai tabhi save karo, warna skip karke aage
        if ($hasData) {
            $employee->bankDetails()->delete();
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
                    'holder_name'    => $detail['holder_name']    ?? '',
                    'bank_name'      => $detail['bank_name']      ?? '',
                    'account_number' => $detail['account_number'] ?? '',
                    'ifsc_code'      => $detail['ifsc_code']      ?? '',
                    'photo'          => $photoPath,
                    'passbook'       => $passbookPath,
                ]);
            }
            return redirect()->route('admin.employees.step5', $id)
                             ->with('success', 'Bank details saved!');
        }

        // Skip — bina message ke aage
        return redirect()->route('admin.employees.step5', $id);
    }

    // ─── Step 5 – Official Details ────────────────────────────────────────────
    public function step5($id)
    {
        $employee     = Employee::findOrFail($id);
        $shifts       = Shift::all();
        $designations = \App\Models\Designation::orderBy('name')->get();
        return view('admin.employees.step5', compact('employee', 'shifts', 'designations'));
    }

    public function saveStep5(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $officialData = [
            'doj'         => $request->doj,
            'designation' => $request->designation,
            'shift_id'    => $request->shift_id,
            'salary'      => $request->salary,
            'branch'      => $request->branch,
            'permission'  => $request->permission,
        ];

        if ($request->filled('password')) {
            $officialData['password'] = bcrypt($request->password);
        } else {
            $existing = $employee->officialDetail;
            $officialData['password'] = $existing ? $existing->password : null;
        }

        $employee->officialDetail()->updateOrCreate(
            ['employee_id' => $employee->id],
            $officialData
        );

        $employee->designation = $request->designation;
        $employee->save();

        return redirect()->route('admin.employees.index')
                         ->with('success', 'Employee added successfully.');
    }

    // ─── Show Employee ────────────────────────────────────────────────────────
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

    // ─── Edit Employee ────────────────────────────────────────────────────────
    public function edit($id)
    {
        $employee = Employee::with([
            'qualifications',
            'previousEmployers',
            'bankDetails',
            'officialDetail'
        ])->findOrFail($id);

        $designations = \App\Models\Designation::orderBy('name')->get();
        $shifts       = Shift::all();

        return view('admin.employees.edit', compact('employee', 'designations', 'shifts'));
    }

    // ─── Update Employee ──────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|unique:employees,email,' . $id,
            'contact' => 'required|numeric|digits:10',
        ], [
            'name.required'    => 'Name zaroori hai.',
            'name.max'         => 'Name 100 characters se zyada nahi hona chahiye.',
            'email.required'   => 'Email zaroori hai.',
            'email.email'      => 'Valid email enter karo.',
            'email.unique'     => 'Yeh email already registered hai.',
            'contact.required' => 'Contact number zaroori hai.',
            'contact.numeric'  => 'Contact sirf numbers mein hona chahiye.',
            'contact.digits'   => 'Contact exactly 10 digits ka hona chahiye.',
        ]);

        /* ── BASIC INFO ── */
        $employee->update($request->only([
            'name', 'email', 'contact', 'address',
            'state', 'city', 'pincode',
            'date_of_birth', 'marital_status', 'blood_group', 'status',
        ]));

        /* ── PHOTO ── */
        if ($request->hasFile('photo')) {
            $employee->photo = $request->file('photo')->store('employees', 'public');
            $employee->save();
        }

        /* ── QUALIFICATIONS ── */
        $employee->qualifications()->delete();
        if ($request->filled('qualifications')) {
            foreach ($request->qualifications as $q) {
                if (empty($q['qualification_type']) && empty($q['institution_name'])) continue;
                $employee->qualifications()->create([
                    'qualification_type' => $q['qualification_type'] ?? null,
                    'institution_name'   => $q['institution_name']   ?? null,
                    'field_of_study'     => $q['field_of_study']     ?? null,
                    'start_date'         => $q['start_date']         ?? null,
                    'end_date'           => $q['end_date']           ?? null,
                    'percentage'         => $q['percentage']         ?? null,
                ]);
            }
        }

        /* ── PREVIOUS EMPLOYERS ── */
        $employee->previousEmployers()->delete();
        if ($request->filled('employers')) {
            foreach ($request->employers as $e) {
                if (empty($e['company_name'])) continue;
                $employee->previousEmployers()->create([
                    'company_name'   => $e['company_name']   ?? '',
                    'hr_name'        => $e['hr_name']        ?? null,
                    'hr_phone'       => $e['hr_phone']       ?? null,
                    'address_line1'  => $e['address_line1']  ?? null,
                    'state'          => $e['state']          ?? null,
                    'city'           => $e['city']           ?? null,
                    'pincode'        => $e['pincode']        ?? null,
                    'monthly_salary' => $e['monthly_salary'] ?? null,
                    'designation'    => $e['designation']    ?? null,
                    'duration'       => $e['duration']       ?? null,
                ]);
            }
        }

        /* ── BANK DETAILS ── */
        $employee->bankDetails()->delete();
        if ($request->filled('bank_details')) {
            foreach ($request->bank_details as $index => $b) {
                if (empty($b['holder_name']) && empty($b['bank_name'])) continue;
                $photoPath = $passbookPath = null;
                if ($request->hasFile("bank_details.$index.photo")) {
                    $photoPath = $request->file("bank_details.$index.photo")
                                         ->store('bank_photos', 'public');
                }
                if ($request->hasFile("bank_details.$index.passbook")) {
                    $passbookPath = $request->file("bank_details.$index.passbook")
                                            ->store('passbooks', 'public');
                }
                $employee->bankDetails()->create([
                    'holder_name'    => $b['holder_name']    ?? '',
                    'bank_name'      => $b['bank_name']      ?? '',
                    'account_number' => $b['account_number'] ?? '',
                    'ifsc_code'      => $b['ifsc_code']      ?? '',
                    'photo'          => $photoPath,
                    'passbook'       => $passbookPath,
                ]);
            }
        }

        /* ── OFFICIAL DETAILS ── */
        $officialData = [
            'designation' => $request->designation,
            'doj'         => $request->doj,
            'shift_id'    => $request->shift_id,
            'salary'      => $request->salary,
            'branch'      => $request->branch,
            'permission'  => $request->permission,
        ];
        if ($request->filled('password')) {
            $officialData['password'] = bcrypt($request->password);
        }
        $employee->officialDetail()->updateOrCreate(
            ['employee_id' => $employee->id],
            $officialData
        );

        $employee->designation = $request->designation;
        $employee->save();

        return redirect()->route('admin.employees.show', $id)
                         ->with('success', 'Employee updated successfully');
    }

    // ─── Toggle Status ────────────────────────────────────────────────────────
    public function toggleStatus($id)
    {
        $employee         = Employee::findOrFail($id);
        $employee->status = ($employee->status === 'active') ? 'inactive' : 'active';
        $employee->save();
        return back()->with('success', 'Status updated!');
    }
}