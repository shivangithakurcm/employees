@extends('layouts.admin')
@section('page-title', 'Add Employee')

@section('content')
<h4 class="mb-3" style="color:#f0c040">Basic Info</h4>

{{-- Step Indicator --}}
<div class="step-indicator mb-4">
    <div class="step active">1</div>
    <div class="step">2</div>
    <div class="step">3</div>
    <div class="step">4</div>
    <div class="step">5</div>
</div>

<div class="card-dark">
    <form method="POST" action="{{ route('admin.employees.storeBasicInfo') }}"
          enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control"
                       placeholder="Type here..." value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control"
                       placeholder="admin@gmail.com" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact" class="form-control"
                       placeholder="Type here..." value="{{ old('contact') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Address Line 1</label>
                <input type="text" name="address" class="form-control"
                       placeholder="Type here..." value="{{ old('address') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">State</label>
                <select name="state" class="form-select">
                    <option value="">Select State</option>
                    <option value="Chhattisgarh">Chhattisgarh</option>
                    <option value="Maharashtra">Maharashtra</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Karnataka">Karnataka</option>
                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">City</label>
                <select name="city" class="form-select">
                    <option value="">Select City</option>
                    <option value="Raipur">Raipur</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Bangalore">Bangalore</option>
                    <option value="Lucknow">Lucknow</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Pincode</label>
                <input type="text" name="pincode" class="form-control"
                       placeholder="Type here..." value="{{ old('pincode') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Date Of Birth</label>
                <input type="date" name="date_of_birth" class="form-control"
                       value="{{ old('date_of_birth') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Marital Status</label>
                <select name="marital_status" class="form-select">
                    <option value="">Select Marital Status</option>
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Blood Group</label>
                <select name="blood_group" class="form-select">
                    <option value="">Select Blood Group</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}">{{ $bg }}</option>
                    @endforeach
                </select>
            </div>

        
            <div class="col-md-4">
                <label class="form-label">Upload Photo</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
                <small class="text-muted">Max size: 2MB</small>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-gold">Next →</button>
        </div>
    </form>
</div>
@endsection
