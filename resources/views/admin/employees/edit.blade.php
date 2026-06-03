@extends('layouts.admin')
@section('page-title', 'Edit Employee')
@section('content')

<div class="d-flex justify-content-between mb-4">
    <a href="{{ route('admin.employees.show', $employee->id) }}"
       style="color:#f0c040; text-decoration:none; font-size:1.1rem;">← Back</a>
    <h4 style="color:#f0c040">Edit Employee</h4>
</div>

<form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

{{-- ══════════════ BASIC INFO ══════════════ --}}
<div class="card-dark mb-4">
    <h5 style="color:#f0c040; border-bottom:1px solid #333; padding-bottom:8px;" class="mb-3">Basic Info</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Name *</label>
            <input type="text" name="name" class="form-control" value="{{ $employee->name }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" value="{{ $employee->email }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Contact *</label>
            <input type="tel" name="contact" class="form-control" maxlength="10" value="{{ $employee->contact }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" value="{{ $employee->address }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">State</label>
            <select name="state" class="form-select">
                <option value="">— Select State —</option>
                @foreach(['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal','Delhi','Jammu & Kashmir','Ladakh','Chandigarh','Puducherry'] as $st)
                    <option value="{{ $st }}" {{ $employee->state == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" value="{{ $employee->city }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Pincode</label>
            <input type="text" name="pincode" class="form-control" value="{{ $employee->pincode }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Date of Birth</label>
            <input type="date" name="date_of_birth" class="form-control" value="{{ $employee->date_of_birth }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Marital Status</label>
            <select name="marital_status" class="form-select">
                <option value="">— Select —</option>
                @foreach(['Single','Married','Divorced','Widowed'] as $ms)
                    <option value="{{ $ms }}" {{ $employee->marital_status == $ms ? 'selected' : '' }}>{{ $ms }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Blood Group</label>
            <select name="blood_group" class="form-select">
                <option value="">— Select —</option>
                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                    <option value="{{ $bg }}" {{ $employee->blood_group == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active"   {{ $employee->status == 'active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $employee->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Upload Photo</label>
            <input type="file" name="photo" class="form-control" accept="image/*">
            @if($employee->photo)
                <img src="{{ asset('storage/'.$employee->photo) }}" height="40" class="mt-1 rounded">
            @endif
        </div>
    </div>
</div>

{{-- ══════════════ QUALIFICATIONS ══════════════ --}}
<div class="card-dark mb-4">
    <h5 style="color:#f0c040; border-bottom:1px solid #333; padding-bottom:8px;" class="mb-3">Educational Qualifications</h5>
   <div class="row g-2 mb-1" style="color:#aaa; font-size:0.85rem;">
    <div class="col-md-2">Type</div>
    <div class="col-md-3">Institution</div>
    <div class="col-md-2">Field of Study</div>
    <div class="col-md-2">Start Date</div>
    <div class="col-md-2">End Date</div>
    <div class="col-md-1"></div>
</div>
 <div id="qual-wrapper">
        @forelse($employee->qualifications as $i => $q)
        <div class="row g-2 mb-2 qual-row">
            <div class="col-md-2">
                <select name="qualifications[{{ $i }}][qualification_type]" class="form-select">
                    <option value="">Type</option>
                    @foreach(['10th','12th','Diploma','Graduate','Post Graduate','PhD'] as $qt)
                        <option value="{{ $qt }}" {{ $q->qualification_type == $qt ? 'selected' : '' }}>{{ $qt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="qualifications[{{ $i }}][institution_name]" class="form-control" placeholder="Institution" value="{{ $q->institution_name }}">
            </div>
            <div class="col-md-2">
                <input type="text" name="qualifications[{{ $i }}][field_of_study]" class="form-control" placeholder="Field of Study" value="{{ $q->field_of_study }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="qualifications[{{ $i }}][start_date]" class="form-control" value="{{ $q->start_date }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="qualifications[{{ $i }}][end_date]" class="form-control" value="{{ $q->end_date }}">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-row">✕</button>
            </div>
        </div>
        @empty
        <div class="row g-2 mb-2 qual-row">
            <div class="col-md-2">
                <select name="qualifications[0][qualification_type]" class="form-select">
                    <option value="">Type</option>
                    @foreach(['10th','12th','Diploma','Graduate','Post Graduate','PhD'] as $qt)
                        <option value="{{ $qt }}">{{ $qt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><input type="text" name="qualifications[0][institution_name]" class="form-control" placeholder="Institution"></div>
            <div class="col-md-2"><input type="text" name="qualifications[0][field_of_study]" class="form-control" placeholder="Field of Study"></div>
            <div class="col-md-2"><input type="date" name="qualifications[0][start_date]" class="form-control"></div>
            <div class="col-md-2"><input type="date" name="qualifications[0][end_date]" class="form-control"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></div>
        </div>
        @endforelse
    </div>
    <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-qual">+ Add Row</button>
</div>

{{-- ══════════════ PREVIOUS EMPLOYERS ══════════════ --}}
<div class="card-dark mb-4">
    <h5 style="color:#f0c040; border-bottom:1px solid #333; padding-bottom:8px;" class="mb-3">Previous Employers</h5>
    <div class="row g-2 mb-1" style="color:#aaa; font-size:0.85rem;">
    <div class="col-md-3">Company Name</div>
    <div class="col-md-2">Designation</div>
    <div class="col-md-2">HR Name</div>
    <div class="col-md-2">HR Phone</div>
    <div class="col-md-2">Salary</div>
    <div class="col-md-1"></div>
</div>
    
    <div id="emp-wrapper">
        @forelse($employee->previousEmployers as $i => $e)
        <div class="row g-2 mb-3 emp-row" style="border-bottom:1px solid #333; padding-bottom:10px;">
            <div class="col-md-3"><input type="text" name="employers[{{ $i }}][company_name]" class="form-control" placeholder="Company Name" value="{{ $e->company_name }}"></div>
            <div class="col-md-2"><input type="text" name="employers[{{ $i }}][designation]" class="form-control" placeholder="Designation" value="{{ $e->designation }}"></div>
            <div class="col-md-2"><input type="text" name="employers[{{ $i }}][hr_name]" class="form-control" placeholder="HR Name" value="{{ $e->hr_name }}"></div>
            <div class="col-md-2"><input type="text" name="employers[{{ $i }}][hr_phone]" class="form-control" placeholder="HR Phone" value="{{ $e->hr_phone }}"></div>
            <div class="col-md-2"><input type="number" name="employers[{{ $i }}][monthly_salary]" class="form-control" placeholder="Salary" value="{{ $e->monthly_salary }}"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></div>
            <div class="col-md-3"><input type="text" name="employers[{{ $i }}][address_line1]" class="form-control" placeholder="Address" value="{{ $e->address_line1 }}"></div>
            <div class="col-md-2"><input type="text" name="employers[{{ $i }}][state]" class="form-control" placeholder="State" value="{{ $e->state }}"></div>
            <div class="col-md-2"><input type="text" name="employers[{{ $i }}][city]" class="form-control" placeholder="City" value="{{ $e->city }}"></div>
            <div class="col-md-2"><input type="text" name="employers[{{ $i }}][pincode]" class="form-control" placeholder="Pincode" value="{{ $e->pincode }}"></div>
            <div class="col-md-2"><input type="text" name="employers[{{ $i }}][duration]" class="form-control" placeholder="Duration" value="{{ $e->duration }}"></div>
        </div>
        @empty
        <div class="row g-2 mb-2 emp-row">
            <div class="col-md-3"><input type="text" name="employers[0][company_name]" class="form-control" placeholder="Company Name"></div>
            <div class="col-md-2"><input type="text" name="employers[0][designation]" class="form-control" placeholder="Designation"></div>
            <div class="col-md-2"><input type="text" name="employers[0][hr_name]" class="form-control" placeholder="HR Name"></div>
            <div class="col-md-2"><input type="text" name="employers[0][hr_phone]" class="form-control" placeholder="HR Phone"></div>
            <div class="col-md-2"><input type="number" name="employers[0][monthly_salary]" class="form-control" placeholder="Salary"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></div>
        </div>
        @endforelse
    </div>
    <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-emp">+ Add Row</button>
</div>

{{-- ══════════════ BANK DETAILS ══════════════ --}}
<div class="card-dark mb-4">
    <h5 style="color:#f0c040; border-bottom:1px solid #333; padding-bottom:8px;" class="mb-3">Bank Details</h5>
<div class="row g-2 mb-1" style="color:#aaa; font-size:0.85rem;">
    <div class="col-md-3">Account Holder</div>
    <div class="col-md-3">Bank Name</div>
    <div class="col-md-2">Account No.</div>
    <div class="col-md-2">IFSC Code</div>
    <div class="col-md-1"></div>
</div>
    <div id="bank-wrapper">
        @forelse($employee->bankDetails as $i => $b)
        <div class="row g-2 mb-2 bank-row">
            <div class="col-md-3"><input type="text" name="bank_details[{{ $i }}][holder_name]" class="form-control" placeholder="Account Holder" value="{{ $b->holder_name }}"></div>
            <div class="col-md-3"><input type="text" name="bank_details[{{ $i }}][bank_name]" class="form-control" placeholder="Bank Name" value="{{ $b->bank_name }}"></div>
            <div class="col-md-2"><input type="text" name="bank_details[{{ $i }}][account_number]" class="form-control" placeholder="Account No." value="{{ $b->account_number }}"></div>
            <div class="col-md-2"><input type="text" name="bank_details[{{ $i }}][ifsc_code]" class="form-control" placeholder="IFSC Code" value="{{ $b->ifsc_code }}"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></div>
        </div>
        @empty
        <div class="row g-2 mb-2 bank-row">
            <div class="col-md-3"><input type="text" name="bank_details[0][holder_name]" class="form-control" placeholder="Account Holder"></div>
            <div class="col-md-3"><input type="text" name="bank_details[0][bank_name]" class="form-control" placeholder="Bank Name"></div>
            <div class="col-md-2"><input type="text" name="bank_details[0][account_number]" class="form-control" placeholder="Account No."></div>
            <div class="col-md-2"><input type="text" name="bank_details[0][ifsc_code]" class="form-control" placeholder="IFSC Code"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></div>
        </div>
        @endforelse
    </div>
    <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-bank">+ Add Row</button>
</div>

{{-- ══════════════ OFFICIAL DETAILS ══════════════ --}}
<div class="card-dark mb-4">
    <h5 style="color:#f0c040; border-bottom:1px solid #333; padding-bottom:8px;" class="mb-3">Official Details</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Date of Joining</label>
            <input type="date" name="doj" class="form-control"
                   value="{{ $employee->officialDetail->doj ?? '' }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Designation</label>
            <select name="designation" class="form-select">
                <option value="">Select Designation</option>
                @foreach($designations as $d)
                    <option value="{{ $d->name }}"
                        {{ ($employee->officialDetail->designation ?? $employee->designation) == $d->name ? 'selected' : '' }}>
                        {{ $d->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Shift</label>
            <select name="shift_id" class="form-select">
                <option value="">Select Shift</option>
                @foreach($shifts as $shift)
                    <option value="{{ $shift->id }}"
                        {{ ($employee->officialDetail->shift_id ?? '') == $shift->id ? 'selected' : '' }}>
                        {{ $shift->shift_name }}
                        ({{ \Carbon\Carbon::parse($shift->shift_from)->format('h:i A') }} -
                         {{ \Carbon\Carbon::parse($shift->shift_to)->format('h:i A') }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Salary</label>
            <input type="number" name="salary" class="form-control" placeholder="Type here..."
                   value="{{ $employee->officialDetail->salary ?? '' }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Branch</label>
            <input type="text" name="branch" class="form-control" placeholder="Type here..."
                   value="{{ $employee->officialDetail->branch ?? '' }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Permission</label>
            <input type="text" name="permission" class="form-control" placeholder="Type here..."
                   value="{{ $employee->officialDetail->permission ?? '' }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Password <small class="text-muted">(blank = no change)</small></label>
            <input type="password" name="password" class="form-control" placeholder="New password...">
        </div>
    </div>
</div>

{{-- ══════════════ SUBMIT ══════════════ --}}
<div class="mt-3 text-end mb-4">
    <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-secondary me-2">Cancel</a>
    <button type="button" id="updateBtn" class="btn btn-gold">Update Employee</button>
    <button type="submit" id="submitBtn" hidden></button>
</div>

</form>

{{-- Confirm Modal --}}
<div id="confirmModal"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.7); justify-content:center; align-items:center; z-index:999999;">
    <div style="background:#1e1e2e; padding:25px; border-radius:10px;
                width:350px; text-align:center; border:1px solid #f0c040;">
        <h5 style="color:#f0c040; margin-bottom:15px;">Confirm Update</h5>
        <p style="color:#fff;">Do you want to save these changes?</p>
        <div class="mt-3 d-flex justify-content-center gap-2">
            <button id="cancelBtn" class="btn btn-secondary btn-sm">Cancel</button>
            <button id="confirmBtn" class="btn btn-gold btn-sm">Yes, Save</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Confirm modal
    document.getElementById('updateBtn').addEventListener('click', () => {
        document.getElementById('confirmModal').style.display = 'flex';
    });
    document.getElementById('cancelBtn').addEventListener('click', () => {
        document.getElementById('confirmModal').style.display = 'none';
    });
    document.getElementById('confirmBtn').addEventListener('click', () => {
        document.getElementById('confirmModal').style.display = 'none';
        document.getElementById('submitBtn').click();
    });

    // Remove row
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('.qual-row, .emp-row, .bank-row')?.remove();
        }
    });

    // Add Qualification row
    document.getElementById('add-qual').addEventListener('click', function () {
        const wrapper = document.getElementById('qual-wrapper');
        const idx = wrapper.querySelectorAll('.qual-row').length;
        wrapper.insertAdjacentHTML('beforeend', `
        <div class="row g-2 mb-2 qual-row">
            <div class="col-md-2">
                <select name="qualifications[${idx}][qualification_type]" class="form-select">
                    <option value="">Type</option>
                    ${['10th','12th','Diploma','Graduate','Post Graduate','PhD'].map(t => `<option value="${t}">${t}</option>`).join('')}
                </select>
            </div>
            <div class="col-md-3"><input type="text" name="qualifications[${idx}][institution_name]" class="form-control" placeholder="Institution"></div>
            <div class="col-md-2"><input type="text" name="qualifications[${idx}][field_of_study]" class="form-control" placeholder="Field of Study"></div>
            <div class="col-md-2"><input type="date" name="qualifications[${idx}][start_date]" class="form-control"></div>
            <div class="col-md-2"><input type="date" name="qualifications[${idx}][end_date]" class="form-control"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></div>
        </div>`);
    });

    // Add Employer row
    document.getElementById('add-emp').addEventListener('click', function () {
        const wrapper = document.getElementById('emp-wrapper');
        const idx = wrapper.querySelectorAll('.emp-row').length;
        wrapper.insertAdjacentHTML('beforeend', `
        <div class="row g-2 mb-2 emp-row">
            <div class="col-md-3"><input type="text" name="employers[${idx}][company_name]" class="form-control" placeholder="Company Name"></div>
            <div class="col-md-2"><input type="text" name="employers[${idx}][designation]" class="form-control" placeholder="Designation"></div>
            <div class="col-md-2"><input type="text" name="employers[${idx}][hr_name]" class="form-control" placeholder="HR Name"></div>
            <div class="col-md-2"><input type="text" name="employers[${idx}][hr_phone]" class="form-control" placeholder="HR Phone"></div>
            <div class="col-md-2"><input type="number" name="employers[${idx}][monthly_salary]" class="form-control" placeholder="Salary"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></div>
        </div>`);
    });

    // Add Bank row
    document.getElementById('add-bank').addEventListener('click', function () {
        const wrapper = document.getElementById('bank-wrapper');
        const idx = wrapper.querySelectorAll('.bank-row').length;
        wrapper.insertAdjacentHTML('beforeend', `
        <div class="row g-2 mb-2 bank-row">
            <div class="col-md-3"><input type="text" name="bank_details[${idx}][holder_name]" class="form-control" placeholder="Account Holder"></div>
            <div class="col-md-3"><input type="text" name="bank_details[${idx}][bank_name]" class="form-control" placeholder="Bank Name"></div>
            <div class="col-md-2"><input type="text" name="bank_details[${idx}][account_number]" class="form-control" placeholder="Account No."></div>
            <div class="col-md-2"><input type="text" name="bank_details[${idx}][ifsc_code]" class="form-control" placeholder="IFSC Code"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></div>
        </div>`);
    });

});
</script>
@endpush