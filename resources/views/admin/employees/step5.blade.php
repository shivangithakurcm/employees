@extends('layouts.admin')
@section('page-title', 'Add Employee - Step 5')

@section('content')
<h4 class="mb-3" style="color:#f0c040">Official Details</h4>

{{-- Step Indicator --}}
<div class="step-indicator mb-4">
    <div class="step done">1</div>
    <div class="step done">2</div>
    <div class="step done">3</div>
    <div class="step done">4</div>
    <div class="step active">5</div>
</div>

<div class="card-dark">
    <form method="POST" action="{{ route('admin.employees.saveStep5', $employee->id) }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Date of Joining (DOJ)</label>
                <input type="date" name="doj"
                       class="form-control"
                       value="{{ $employee->officialDetail->doj ?? '' }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Designation</label>
                <select name="designation" class="form-select">
                    <option value="">Select Designation</option>
                    @foreach($designations as $d)
                        <option value="{{ $d->name }}"
                            {{ old('designation', $employee->officialDetail->designation ?? '') == $d->name ? 'selected' : '' }}>
                            {{ $d->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Shift col-md-6 --}}
            <div class="col-md-6">
                <label class="form-label">Shift</label>
                <select name="shift_id" class="form-select">
                    <option value="">Select Shift</option>
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}"
                            {{ old('shift_id', $employee->officialDetail->shift_id ?? '') == $shift->id ? 'selected' : '' }}>
                            {{ $shift->shift_name }}
                            ({{ \Carbon\Carbon::parse($shift->shift_from)->format('h:i A') }} -
                             {{ \Carbon\Carbon::parse($shift->shift_to)->format('h:i A') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Salary</label>
                <input type="number" name="salary"
                       class="form-control" placeholder="Type here..."
                       value="{{ $employee->officialDetail->salary ?? '' }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Branch</label>
                <input type="text" name="branch"
                       class="form-control" placeholder="Type here..."
                       value="{{ $employee->officialDetail->branch ?? '' }}">
            </div>

            {{-- ✅ NEW: Login Role --}}
            <div class="col-md-6">
                <label class="form-label">Login Role</label>
                <select name="role" class="form-select">
                    <option value="">Select Role</option>
                    <option value="manager" {{ old('role', $employee->user?->getRoleNames()->first() ?? '') == 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="sales" {{ old('role', $employee->user?->getRoleNames()->first() ?? '') == 'sales' ? 'selected' : '' }}>Sales</option>
                </select>
            </div>

            {{-- ✅ NEW: Login Password --}}
            <div class="col-md-6">
                <label class="form-label">
                    Login Password
                    @if($employee->user_id)
                        <small style="color:#888;">(blank rakho agar change nahi karna)</small>
                    @endif
                </label>
                <input type="password" name="password"
                       class="form-control" placeholder="{{ $employee->user_id ? 'Leave blank to keep current' : 'Set login password' }}">
            </div>

        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('admin.employees.step4', $employee->id) }}"
               class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-gold">Finish ✓</button>
        </div>
    </form>
</div>
@endsection