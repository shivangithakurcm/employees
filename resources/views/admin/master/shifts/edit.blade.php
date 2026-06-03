@extends('layouts.admin')
@section('page-title', 'Edit Shift')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 style="color:#f0c040">Edit Shift</h4>
    <a href="{{ route('admin.master.shift.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card-dark">
    <form method="POST" action="{{ route('admin.master.shift.update', $shift->id) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Shift Name</label>
                <input type="text" name="shift_name" class="form-control"
                       value="{{ old('shift_name', $shift->shift_name) }}" required>
                @error('shift_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Shift From</label>
                <input type="time" name="shift_from" class="form-control"
                       value="{{ old('shift_from', $shift->shift_from) }}" required>
                @error('shift_from')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Shift To</label>
                <input type="time" name="shift_to" class="form-control"
                       value="{{ old('shift_to', $shift->shift_to) }}" required>
                @error('shift_to')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-gold">Update Shift</button>
        </div>
    </form>
</div>
@endsection