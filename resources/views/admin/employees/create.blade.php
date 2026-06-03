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
                <input type="text" name="name"
                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       placeholder="Type here..." value="{{ old('name') }}" required>
                @error('name')
                    <div style="color:#e07070; font-size:12px; margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Email Address</label>
                <input type="email" name="email"
                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       placeholder="admin@gmail.com" value="{{ old('email') }}" required>
                @error('email')
                    <div style="color:#e07070; font-size:12px; margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Contact Number</label>
                <input type="tel" name="contact"
                       class="form-control {{ $errors->has('contact') ? 'is-invalid' : '' }}"
                       placeholder="Type here..." value="{{ old('contact') }}"
                       maxlength="10"
                       oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)"
                       required>
                @error('contact')
                    <div style="color:#e07070; font-size:12px; margin-top:4px;">{{ $message }}</div>
                @enderror
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
                    <option value="Chhattisgarh" {{ old('state') == 'Chhattisgarh' ? 'selected' : '' }}>Chhattisgarh</option>
                    <option value="Maharashtra"  {{ old('state') == 'Maharashtra'  ? 'selected' : '' }}>Maharashtra</option>
                    <option value="Delhi"        {{ old('state') == 'Delhi'        ? 'selected' : '' }}>Delhi</option>
                    <option value="Karnataka"    {{ old('state') == 'Karnataka'    ? 'selected' : '' }}>Karnataka</option>
                    <option value="Uttar Pradesh"{{ old('state') == 'Uttar Pradesh'? 'selected' : '' }}>Uttar Pradesh</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">City</label>
                <select name="city" class="form-select">
                    <option value="">Select City</option>
                    <option value="Raipur"     {{ old('city') == 'Raipur'     ? 'selected' : '' }}>Raipur</option>
                    <option value="Mumbai"     {{ old('city') == 'Mumbai'     ? 'selected' : '' }}>Mumbai</option>
                    <option value="Delhi"      {{ old('city') == 'Delhi'      ? 'selected' : '' }}>Delhi</option>
                    <option value="Bangalore"  {{ old('city') == 'Bangalore'  ? 'selected' : '' }}>Bangalore</option>
                    <option value="Lucknow"    {{ old('city') == 'Lucknow'    ? 'selected' : '' }}>Lucknow</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Pincode</label>
                <input type="tel" name="pincode" class="form-control"
                       placeholder="Type here..." value="{{ old('pincode') }}"
                       maxlength="6"
                       oninput="this.value=this.value.replace(/\D/g,'').slice(0,6)">
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
                    <option value="Single"  {{ old('marital_status') == 'Single'  ? 'selected' : '' }}>Single</option>
                    <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Blood Group</label>
                <select name="blood_group" class="form-select">
                    <option value="">Select Blood Group</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>
                            {{ $bg }}
                        </option>
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