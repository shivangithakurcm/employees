@extends('layouts.admin')
@section('page-title', 'Shifts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 style="color:#f0c040">Shift Master</h4>
    <a href="{{ route('admin.master.shift.create') }}" class="btn btn-gold">+ Add Shift</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card-dark">
    <table class="table table-dark table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Shift Name</th>
                <th>From</th>
                <th>To</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shifts as $shift)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $shift->shift_name }}</td>
                <td>{{ \Carbon\Carbon::parse($shift->shift_from)->format('h:i A') }}</td>
                <td>{{ \Carbon\Carbon::parse($shift->shift_to)->format('h:i A') }}</td>
                <td>
                    <a href="{{ route('admin.master.shift.edit', $shift->id) }}" 
                       class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.master.shift.destroy', $shift->id) }}" 
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Delete this shift?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No shifts found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $shifts->links() }}
</div>
@endsection