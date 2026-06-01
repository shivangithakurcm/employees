@extends('layouts.admin')
@section('page-title', 'Customers')
@section('content')

<style>
.tab-btn { border-radius: 6px !important; font-size: 15px !important; padding: 8px 18px !important; }
svg.w-5.h-5 { display: none; }
.pagination { justify-content: center; }
.pagination .page-link { background: #f0c040 !important; color: #000 !important; border: 1px solid #000 !important; margin: 2px; }
.pagination .page-link:hover { background: #000 !important; color: #f0b207 !important; }
.pagination .active .page-link { background: #000 !important; color: #f0c040 !important; }
.form-control::placeholder { color: #bbb !important; opacity: 1 !important; }
.form-control { color: #fff !important; }
</style>

@php
    $currentProjectType  = request('project_type', '');
    $currentStatus       = request('status', '');
    $currentMaster       = request('master', '');
    $currentDesignation  = request('designation', '');
    $currentShift        = request('shift', '');
@endphp

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-warning mb-0 fw-bold"><i class="fas fa-users me-2"></i>Customer List</h4>
        <small class="text-muted">Won leads converted to customers</small>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.customers.index') }}">
    <div class="row g-2 mb-4 align-items-center">

        {{-- Search (chhota: col-md-2) --}}
        <div class="col-md-2">
            <div class="input-group">
                <span class="input-group-text bg-dark text-warning border-secondary border-end-0">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search"
                       class="form-control bg-dark text-white border-secondary border-start-0"
                       placeholder="Search..."
                       value="{{ request('search') }}">
            </div>
        </div>

        {{-- Project Type --}}
        <div class="col-md-2">
            <select name="project_type" class="form-select bg-dark text-white border-secondary">
                <option value="">— Project Type —</option>
                @foreach($projectTypes as $pt)
    <option value="{{ $pt->id }}" {{ request('project_type') == $pt->id ? 'selected' : '' }}>{{ $pt->name }}</option>
@endforeach
            </select>
        </div>

        {{-- City --}}
        <div class="col-md-2">
            <input type="text" name="city"
                   class="form-control bg-dark text-white border-secondary"
                   placeholder="City" value="{{ request('city') }}">
        </div>

        {{-- Date Range --}}
        <div class="col-md-2">
            <input type="date" name="date_from" class="form-control bg-dark text-white border-secondary"
                   value="{{ request('date_from') }}" placeholder="From Date">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" class="form-control bg-dark text-white border-secondary"
                   value="{{ request('date_to') }}" placeholder="To Date">
        </div>

        {{-- Buttons (filter icon removed) --}}
        <div class="col-auto">
            <button type="submit" class="btn btn-gold">Filter</button>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </div>
</form>

{{-- Customer Table --}}
<div class="card-dark" style="overflow: hidden; padding-bottom: 0;">
    <div style="overflow-x: auto;">
        <table class="table table-dark table-bordered mb-0" style="white-space: nowrap;">
            <thead>
                <tr>
                    <th>Sno.</th>
                    <th>Customer Name</th>
                    <th>City</th>
                    <th>Project Type</th>
                    <th>Onboarded Date</th>
                    <th>Project Status</th>
                    <th style="position:sticky; right:0; background:#212529; z-index:2;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers ?? [] as $customer)
                <tr>
                    <td>{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</td>
                    <td>
                        {{-- Circle avatar hata diya, sirf naam + contact --}}
                        <div class="fw-semibold">{{ $customer->won_name ?? ($customer->first_name . ' ' . $customer->last_name) }}</div>
                        <div class="text-muted" style="font-size:11px;">{{ $customer->won_contact ?? $customer->contact_number ?? '-' }}</div>
                    </td>
                    <td>{{ $customer->won_city ?? $customer->city ?? '-' }}</td>
                    <td>
                       <span class="badge bg-info text-dark">
    {{ $customer->wonProjectType->name ?? '-' }}
</span>
                    </td>
                    <td>{{ $customer->updated_at ? $customer->updated_at->format('d-m-Y') : '-' }}</td>
                    <td>
                        <span class="badge bg-success">Won</span>
                    </td>
                    <td style="position:sticky; right:0; background:#212529; white-space:nowrap; vertical-align:middle;">
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.customers.show', $customer->id) }}"
                               class="btn btn-sm btn-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.customers.edit', $customer->id) }}"
                               class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                onclick="confirmDeleteCustomer({{ $customer->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="deleteCustomerForm{{ $customer->id }}"
                                  action="{{ route('admin.customers.destroy', $customer->id) }}"
                                  method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-users fa-2x mb-2 d-block opacity-25"></i>
                        No customers found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $customers->withQueryString()->links() }}
    </div>
</div>

{{-- Delete Confirm Popup --}}
<div id="deleteCustomerConfirmModal"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.7); justify-content:center; align-items:center; z-index:999999;">
    <div style="background:#1e1e1e; padding:25px; border-radius:10px;
                width:350px; text-align:center; border:1px solid #f0c040;">
        <h5 style="color:#f0c040; margin-bottom:15px;">Confirm Delete</h5>
        <p style="color:#fff;">Are you sure you want to delete this customer?</p>
        <div class="mt-3 d-flex justify-content-center gap-2">
            <button id="deleteCustomerCancelBtn" class="btn btn-secondary btn-sm">Cancel</button>
            <button id="deleteCustomerConfirmBtn" class="btn btn-danger btn-sm">Yes, Delete</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let deleteCustomerId = null;
const deleteCustomerConfirmModal = document.getElementById('deleteCustomerConfirmModal');

window.confirmDeleteCustomer = function(id) {
    deleteCustomerId = id;
    deleteCustomerConfirmModal.style.display = 'flex';
};

document.getElementById('deleteCustomerCancelBtn').addEventListener('click', function() {
    deleteCustomerId = null;
    deleteCustomerConfirmModal.style.display = 'none';
});

document.getElementById('deleteCustomerConfirmBtn').addEventListener('click', function() {
    if (deleteCustomerId) document.getElementById('deleteCustomerForm' + deleteCustomerId).submit();
});
</script>
@endpush