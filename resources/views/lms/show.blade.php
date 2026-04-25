@extends('layouts.admin')
@section('page-title', 'Lead Detail')
@section('content')

<div class="d-flex justify-content-between mb-4">
    {{-- ✅ Fix 1: admin.lmsindex → admin.lms.index --}}
    <a href="{{ route('admin.lms.index') }}"
       style="color:#f0c040; text-decoration:none; font-size:1.1rem;">← Back</a>
    {{-- ✅ Fix 2: lms.edit → admin.lms.edit --}}
    <a href="{{ route('admin.lms.edit', $lm->id) }}" class="btn btn-gold">✏️ Edit Lead</a>
</div>

<div class="card-dark">
    <h5 style="color:#f0c040" class="mb-4">Lead Detail</h5>
    <div class="row g-4">
        <div class="col-md-4">
            <small style="color:#f0c040">First Name</small>
            <p class="text-white mb-0">{{ $lm->first_name ?? '-' }}</p>
        </div>
        <div class="col-md-4">
            <small style="color:#f0c040">Middle Name</small>
            <p class="text-white mb-0">{{ $lm->middle_name ?? '-' }}</p>
        </div>
        <div class="col-md-4">
            <small style="color:#f0c040">Last Name</small>
            <p class="text-white mb-0">{{ $lm->last_name ?? '-' }}</p>
        </div>
        <div class="col-md-4">
            <small style="color:#f0c040">Contact Number</small>
            <p class="text-white mb-0">{{ $lm->contact_number ?? '-' }}</p>
        </div>
        <div class="col-md-4">
            <small style="color:#f0c040">Email</small>
            <p class="text-white mb-0">{{ $lm->email ?? '-' }}</p>
        </div>
        <div class="col-md-4">
            <small style="color:#f0c040">State</small>
            <p class="text-white mb-0">{{ $lm->state ?? '-' }}</p>
        </div>
        <div class="col-md-4">
            <small style="color:#f0c040">City</small>
            <p class="text-white mb-0">{{ $lm->city ?? '-' }}</p>
        </div>
        <div class="col-md-4">
            <small style="color:#f0c040">Country</small>
            <p class="text-white mb-0">{{ $lm->country ?? '-' }}</p>
        </div>
        <div class="col-md-4">
            <small style="color:#f0c040">Requirement</small>
            <p class="text-white mb-0">{{ $lm->Requirement ?? '-' }}</p>
        </div>
        <div class="col-md-4">
            <small style="color:#f0c040">Date</small>
            <p class="text-white mb-0">{{ $lm->date ?? '-' }}</p>
        </div>
        <div class="col-md-4">
            <small style="color:#f0c040">Time</small>
            <p class="text-white mb-0">{{ $lm->time ?? '-' }}</p>
        </div>
        <div class="col-md-4">
            <small style="color:#f0c040">Status</small>
            <p class="mb-0">
                <span class="badge
                    @if($lm->status == 'won') bg-success
                    @elseif($lm->status == 'lost') bg-danger
                    @elseif($lm->status == 'call_schedule') bg-primary
                    @elseif($lm->status == 'call_back_required') bg-warning text-dark
                    @elseif($lm->status == 'qualified') bg-info text-dark
                    @else bg-secondary
                    @endif">
                    {{ ucwords(str_replace('_', ' ', $lm->status)) }}
                </span>
            </p>
        </div>
        <div class="col-md-4">
            <small style="color:#f0c040">Comment</small>
            <p class="text-white mb-0">{{ $lm->comment ?? '-' }}</p>
        </div>
        <div class="col-md-4">
            <small style="color:#f0c040">Add Date</small>
            <p class="text-white mb-0">{{ $lm->created_at ? $lm->created_at->format('d-m-Y') : '-' }}</p>
        </div>
    </div>
</div>

@endsection