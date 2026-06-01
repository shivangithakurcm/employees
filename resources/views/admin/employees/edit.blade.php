@extends('layouts.admin')
@section('page-title', 'Edit Employee')
@section('content')

<div class="d-flex justify-content-between mb-4">
    <a href="{{ route('admin.employees.show', $employee->id) }}"
       style="color:#f0c040; text-decoration:none; font-size:1.1rem;">← Back</a>
    <h4 style="color:#f0c040">Edit Employee</h4>
</div>

<div class="card-dark">
    <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Name *</label>
                <input type="text" name="name" class="form-control"
                       value="{{ $employee->name }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control"
                       value="{{ $employee->email }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Contact *</label>
                <input type="tel" name="contact" class="form-control"
                       maxlength="10" value="{{ $employee->contact }}" required>
            </div>
            <div class="col-md-8">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control"
                       value="{{ $employee->address }}">
            </div>
            <div class="col-md-4">
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
                <input type="text" name="city" class="form-control"
                       value="{{ $employee->city }}">
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
                    <option value="">— Select —</option>
                    <option value="single"   {{ $employee->marital_status == 'single'   ? 'selected' : '' }}>Single</option>
                    <option value="married"  {{ $employee->marital_status == 'married'  ? 'selected' : '' }}>Married</option>
                    <option value="divorced" {{ $employee->marital_status == 'divorced' ? 'selected' : '' }}>Divorced</option>
                    <option value="widowed"  {{ $employee->marital_status == 'widowed'  ? 'selected' : '' }}>Widowed</option>
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
    <label class="form-label">Designation</label>
    <input type="text" name="designation" class="form-control"
       value="{{ $employee->officialDetail->designation ?? $employee->designation ?? '' }}"
       placeholder="Type designation...">
</div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active"   {{ $employee->status == 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $employee->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="mt-4 text-end">
            <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-secondary me-2">Cancel</a>
            <button type="button" id="updateBtn" class="btn btn-gold">Update Employee</button>
            <button type="submit" id="submitBtn" hidden></button>
        </div>
    </form>
</div>

{{-- Confirm Popup --}}
<div id="confirmModal"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.7); justify-content:center; align-items:center; z-index:999999;">
    <div style="background:#1e1e1e; padding:25px; border-radius:10px;
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
    const confirmModal = document.getElementById('confirmModal');

    document.getElementById('updateBtn').addEventListener('click', function () {
        confirmModal.style.display = 'flex';
    });

    document.getElementById('cancelBtn').addEventListener('click', function () {
        confirmModal.style.display = 'none';
    });

    document.getElementById('confirmBtn').addEventListener('click', function () {
        confirmModal.style.display = 'none';
        document.getElementById('submitBtn').click();
    });
});
</script>
@endpush