@extends('layouts.admin')
@section('page-title', 'Edit Employee')

@section('content')

<div class="d-flex justify-content-between mb-4">
    <a href="{{ route('admin.employees.show', $employee->id) }}"
       style="color:#f0c040; text-decoration:none; font-size:1.1rem;">← Back</a>
    <h4 style="color:#f0c040">Edit Employee</h4>
</div>

<form method="POST"
      action="{{ route('admin.employees.update', $employee->id) }}"
      enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- BASIC INFO --}}
    <div class="card-dark mb-4">
        <h5 style="color:#f0c040" class="mb-3">Basic Info</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control"
                       value="{{ $employee->name }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ $employee->email }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Contact</label>
                <input type="text" name="contact" class="form-control"
                       value="{{ $employee->contact }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control"
                       value="{{ $employee->address }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">State</label>
                <select name="state" class="form-select">
                    <option value="">Select State</option>
                    @foreach(['Chhattisgarh','Maharashtra','Delhi','Karnataka','Uttar Pradesh'] as $s)
                        <option value="{{ $s }}" {{ $employee->state == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">City</label>
                <select name="city" class="form-select">
                    <option value="">Select City</option>
                    @foreach(['Raipur','Mumbai','Delhi','Bangalore','Lucknow'] as $c)
                        <option value="{{ $c }}" {{ $employee->city == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Pincode</label>
                <input type="text" name="pincode" class="form-control"
                       value="{{ $employee->pincode }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control"
                       value="{{ $employee->date_of_birth }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Marital Status</label>
                <select name="marital_status" class="form-select">
                    <option value="">Select</option>
                    <option value="Single"  {{ $employee->marital_status == 'Single'  ? 'selected' : '' }}>Single</option>
                    <option value="Married" {{ $employee->marital_status == 'Married' ? 'selected' : '' }}>Married</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Blood Group</label>
                <select name="blood_group" class="form-select">
                    <option value="">Select</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}" {{ $employee->blood_group == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Upload Photo</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
                @if($employee->photo)
                    <img src="{{ asset('storage/'.$employee->photo) }}"
                         class="mt-2 rounded" height="60">
                @endif
            </div>
        </div>
    </div>

    {{-- EDUCATIONAL QUALIFICATION --}}
    <div class="card-dark mb-4">
        <h5 style="color:#f0c040" class="mb-3">Educational Qualification</h5>
        <div id="qual-items">
            @forelse($employee->qualifications as $index => $qual)
            <div class="qual-item border border-secondary rounded p-3 mb-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Qualification Type</label>
                        <select name="qualifications[{{ $index }}][qualification_type]" class="form-select">
                            <option value="">Select</option>
                            @foreach(['10th','12th','Diploma',"Bachelor's","Master's",'PhD'] as $q)
                                <option value="{{ $q }}" {{ $qual->qualification_type == $q ? 'selected' : '' }}>{{ $q }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Institution Name</label>
                        <input type="text" name="qualifications[{{ $index }}][institution_name]"
                               class="form-control" value="{{ $qual->institution_name }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Field of Study</label>
                        <input type="text" name="qualifications[{{ $index }}][field_of_study]"
                               class="form-control" value="{{ $qual->field_of_study }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="qualifications[{{ $index }}][start_date]"
                               class="form-control" value="{{ $qual->start_date }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" name="qualifications[{{ $index }}][end_date]"
                               class="form-control" value="{{ $qual->end_date }}">
                    </div>
                    
                </div>
            </div>
            @empty
            <div class="qual-item border border-secondary rounded p-3 mb-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Qualification Type</label>
                        <select name="qualifications[0][qualification_type]" class="form-select">
                            <option value="">Select</option>
                            <option>10th</option><option>12th</option>
                            <option>Diploma</option><option>Bachelor's</option>
                            <option>Master's</option><option>PhD</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Institution Name</label>
                        <input type="text" name="qualifications[0][institution_name]"
                               class="form-control" placeholder="Type here...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Field of Study</label>
                        <input type="text" name="qualifications[0][field_of_study]"
                               class="form-control" placeholder="e.g. Computer Science">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="qualifications[0][start_date]" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" name="qualifications[0][end_date]" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Percentage</label>
                        <input type="text" name="qualifications[0][percentage]"
                               class="form-control" placeholder="e.g. 85%">
                    </div>
                </div>
            </div>
            @endforelse
        </div>
        <button type="button" id="add-qual" class="btn btn-outline-warning btn-sm">+ Add More</button>
    </div>

    {{-- PREVIOUS EMPLOYER --}}
<div class="card-dark mb-4">
    <h5 style="color:#f0c040" class="mb-3">Previous Employer</h5>
    <div id="employer-items">
        @forelse($employee->previousEmployers as $index => $emp)
        <div class="employer-item border border-secondary rounded p-3 mb-3">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="employers[{{ $index }}][company_name]"
                           class="form-control" value="{{ $emp->company_name }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">HR Name</label>
                    <input type="text" name="employers[{{ $index }}][hr_name]"
                           class="form-control" value="{{ $emp->hr_name }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">HR Phone</label>
                    <input type="text" name="employers[{{ $index }}][hr_phone]"
                           class="form-control" value="{{ $emp->hr_phone }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Address</label>
                    <input type="text" name="employers[{{ $index }}][address_line1]"
                           class="form-control" value="{{ $emp->address_line1 }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Monthly Salary</label>
                    <input type="number" name="employers[{{ $index }}][monthly_salary]"
                           class="form-control" value="{{ $emp->monthly_salary }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Designation</label>
                    <input type="text" name="employers[{{ $index }}][designation]"
                           class="form-control" value="{{ $emp->designation }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Duration</label>
                    <input type="text" name="employers[{{ $index }}][duration]"
                           class="form-control" value="{{ $emp->duration }}">
                </div>

                {{-- Salary Slip --}}
                <div class="col-md-4">
                    <label class="form-label">Upload Salary Slip</label>
                    <input type="file" name="employers[{{ $index }}][salary_slip]"
                           class="form-control">
                    @if($emp->salary_slip)
                        <div class="mt-2">
                            @php
                                $ext = pathinfo($emp->salary_slip, PATHINFO_EXTENSION);
                                $imgExts = ['jpg','jpeg','png','gif','webp'];
                            @endphp
                            @if(in_array(strtolower($ext), $imgExts))
                                <a href="{{ asset('storage/'.$emp->salary_slip) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$emp->salary_slip) }}"
                                         style="width:70px;height:70px;object-fit:cover;
                                                border:2px solid #f0c040;border-radius:6px;">
                                </a>
                            @else
                                <a href="{{ asset('storage/'.$emp->salary_slip) }}"
                                   target="_blank" style="color:#f0c040">📄 View File</a>
                            @endif
                        </div>
                    @endif
                </div>

            </div>
        </div>
        @empty
        <div class="employer-item border border-secondary rounded p-3 mb-3">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="employers[0][company_name]"
                           class="form-control" placeholder="Type here...">
                </div>
                <div class="col-md-4">
                    <label class="form-label">HR Name</label>
                    <input type="text" name="employers[0][hr_name]"
                           class="form-control" placeholder="Type here...">
                </div>
                <div class="col-md-4">
                    <label class="form-label">HR Phone</label>
                    <input type="text" name="employers[0][hr_phone]"
                           class="form-control" placeholder="Type here...">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Monthly Salary</label>
                    <input type="number" name="employers[0][monthly_salary]"
                           class="form-control" placeholder="Type here...">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Designation</label>
                    <input type="text" name="employers[0][designation]"
                           class="form-control" placeholder="Type here...">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Duration</label>
                    <input type="text" name="employers[0][duration]"
                           class="form-control" placeholder="e.g. 2 Years">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Upload Salary Slip</label>
                    <input type="file" name="employers[0][salary_slip]"
                           class="form-control">
                </div>
            </div>
        </div>
        @endforelse
    </div>
    <button type="button" id="add-employer"
            class="btn btn-outline-warning btn-sm mt-2">+ Add More</button>
</div>

    {{-- BANK DETAILS --}}
<div class="card-dark mb-4">
    <h5 style="color:#f0c040" class="mb-3">Bank Details</h5>
    <div id="bank-items">
        @forelse($employee->bankDetails as $index => $bank)
        <div class="bank-item border border-secondary rounded p-3 mb-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Holder Name</label>
                    <input type="text" name="bank_details[{{ $index }}][holder_name]"
                           class="form-control" value="{{ $bank->holder_name }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bank Name</label>
                    <input type="text" name="bank_details[{{ $index }}][bank_name]"
                           class="form-control" value="{{ $bank->bank_name }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Account Number</label>
                    <input type="text" name="bank_details[{{ $index }}][account_number]"
                           class="form-control" value="{{ $bank->account_number }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">IFSC Code</label>
                    <input type="text" name="bank_details[{{ $index }}][ifsc_code]"
                           class="form-control" value="{{ $bank->ifsc_code }}">
                </div>

                {{-- Photo --}}
                <div class="col-md-6">
                    <label class="form-label">Upload Photo</label>
                    <input type="file" name="bank_details[{{ $index }}][photo]"
                           class="form-control" accept="image/*">
                    @if($bank->photo)
                        <img src="{{ asset('storage/'.$bank->photo) }}"
                             class="mt-2 rounded"
                             style="width:70px;height:70px;object-fit:cover;
                                    border:2px solid #f0c040;">
                    @endif
                </div>

                {{-- Passbook --}}
                <div class="col-md-6">
                    <label class="form-label">Upload Passbook</label>
                    <input type="file" name="bank_details[{{ $index }}][passbook]"
                           class="form-control">
                    @if($bank->passbook)
                        <div class="mt-2">
                            @php
                                $ext = pathinfo($bank->passbook, PATHINFO_EXTENSION);
                                $imgExts = ['jpg','jpeg','png','gif','webp'];
                            @endphp
                            @if(in_array(strtolower($ext), $imgExts))
                                <a href="{{ asset('storage/'.$bank->passbook) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$bank->passbook) }}"
                                         style="width:70px;height:70px;object-fit:cover;
                                                border:2px solid #f0c040;border-radius:6px;">
                                </a>
                            @else
                                <a href="{{ asset('storage/'.$bank->passbook) }}"
                                   target="_blank" style="color:#f0c040">📄 View Passbook</a>
                            @endif
                        </div>
                    @endif
                </div>

            </div>
        </div>
        @empty
        <div class="bank-item border border-secondary rounded p-3 mb-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Holder Name</label>
                    <input type="text" name="bank_details[0][holder_name]"
                           class="form-control" placeholder="Type here...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bank Name</label>
                    <input type="text" name="bank_details[0][bank_name]"
                           class="form-control" placeholder="Type here...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Account Number</label>
                    <input type="text" name="bank_details[0][account_number]"
                           class="form-control" placeholder="Type here...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">IFSC Code</label>
                    <input type="text" name="bank_details[0][ifsc_code]"
                           class="form-control" placeholder="Type here...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Upload Photo</label>
                    <input type="file" name="bank_details[0][photo]"
                           class="form-control" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Upload Passbook</label>
                    <input type="file" name="bank_details[0][passbook]"
                           class="form-control">
                </div>
            </div>
        </div>
        @endforelse
    </div>
    <button type="button" id="add-bank"
            class="btn btn-outline-warning btn-sm mt-2">+ Add More</button>
</div>

<div class="text-end mb-4">
    <button type="submit" class="btn btn-warning px-4">
        Update Employee
    </button>
</div>
</form>
@endsection

@push('scripts')
<script>
let qCount  = {{ $employee->qualifications->count()      ?: 1 }};
let empCount = {{ $employee->previousEmployers->count()  ?: 1 }};
let bankCount = {{ $employee->bankDetails->count()       ?: 1 }};

document.getElementById('add-qual').addEventListener('click', function() {
    const c = document.getElementById('qual-items');
    const d = document.createElement('div');
    d.className = 'qual-item border border-secondary rounded p-3 mb-3';
    d.innerHTML = `<div class="row g-3">
        <div class="col-md-4"><label class="form-label">Qualification Type</label>
        <select name="qualifications[${qCount}][qualification_type]" class="form-select">
            <option value="">Select</option><option>10th</option><option>12th</option>
            <option>Diploma</option><option>Bachelor's</option><option>Master's</option><option>PhD</option>
        </select></div>
        <div class="col-md-4"><label class="form-label">Institution Name</label>
        <input type="text" name="qualifications[${qCount}][institution_name]" class="form-control" placeholder="Type here..."></div>
        <div class="col-md-4"><label class="form-label">Field of Study</label>
        <input type="text" name="qualifications[${qCount}][field_of_study]" class="form-control" placeholder="e.g. Computer Science"></div>
        <div class="col-md-4"><label class="form-label">Start Date</label>
        <input type="date" name="qualifications[${qCount}][start_date]" class="form-control"></div>
        <div class="col-md-4"><label class="form-label">End Date</label>
        <input type="date" name="qualifications[${qCount}][end_date]" class="form-control"></div>
        <div class="col-md-4"><label class="form-label">Percentage</label>
        <input type="text" name="qualifications[${qCount}][percentage]" class="form-control" placeholder="e.g. 85%"></div>
    </div>`;
    c.appendChild(d); qCount++;
});

document.getElementById('add-employer').addEventListener('click', function() {
    const c = document.getElementById('employer-items');
    const d = document.createElement('div');
    d.className = 'employer-item border border-secondary rounded p-3 mb-3';
    d.innerHTML = `<div class="row g-3">
        <div class="col-md-4"><label class="form-label">Company Name</label>
        <input type="text" name="employers[${empCount}][company_name]" class="form-control" placeholder="Type here..."></div>
        <div class="col-md-4"><label class="form-label">HR Name</label>
        <input type="text" name="employers[${empCount}][hr_name]" class="form-control" placeholder="Type here..."></div>
        <div class="col-md-4"><label class="form-label">HR Phone</label>
        <input type="text" name="employers[${empCount}][hr_phone]" class="form-control" placeholder="Type here..."></div>
        <div class="col-md-4"><label class="form-label">Monthly Salary</label>
        <input type="number" name="employers[${empCount}][monthly_salary]" class="form-control" placeholder="Type here..."></div>
        <div class="col-md-4"><label class="form-label">Designation</label>
        <input type="text" name="employers[${empCount}][designation]" class="form-control" placeholder="Type here..."></div>
        <div class="col-md-4"><label class="form-label">Duration</label>
        <input type="text" name="employers[${empCount}][duration]" class="form-control" placeholder="e.g. 2 Years"></div>
    </div>`;
    c.appendChild(d); empCount++;
});

document.getElementById('add-bank').addEventListener('click', function() {
    const c = document.getElementById('bank-items');
    const d = document.createElement('div');
    d.className = 'bank-item border border-secondary rounded p-3 mb-3';
    d.innerHTML = `<div class="row g-3">
        <div class="col-md-6"><label class="form-label">Holder Name</label>
        <input type="text" name="bank_details[${bankCount}][holder_name]" class="form-control" placeholder="Type here..."></div>
        <div class="col-md-6"><label class="form-label">Bank Name</label>
        <input type="text" name="bank_details[${bankCount}][bank_name]" class="form-control" placeholder="Type here..."></div>
        <div class="col-md-6"><label class="form-label">Account Number</label>
        <input type="text" name="bank_details[${bankCount}][account_number]" class="form-control" placeholder="Type here..."></div>
        <div class="col-md-6"><label class="form-label">IFSC Code</label>
        <input type="text" name="bank_details[${bankCount}][ifsc_code]" class="form-control" placeholder="Type here..."></div>
    </div>`;
    c.appendChild(d); bankCount++;
});
</script>
@endpush