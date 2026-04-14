@extends('layouts.admin')
@section('page-title', 'Employees')

@section('content')

<style>
/* Hide arrows */
svg.w-5.h-5 {
    display: none;
}

/* Pagination */
.pagination {
    justify-content: center;
}
.pagination .page-link {
    background: #f0c040 !important;
    color: #000 !important;
    border: 1px solid #000 !important;
    margin: 2px;
}
.pagination .page-link:hover {
    background: #000 !important;
    color: #f0c040 !important;
}
.pagination .active .page-link {
    background: #000 !important;
    color: #f0c040 !important;
}
.pagination .disabled .page-link {
    background: #ddd !important;
    color: #666 !important;
}

/* Table Fix */
th:last-child, td:last-child {
    white-space: nowrap;
}
.btn-sm {
    padding: 3px 6px;
    font-size: 12px;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 style="color:#f0c040">Employee</h4>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-gold">+ Add Employee</a>
</div>

{{-- Search/Filter --}}
<form method="GET" action="{{ route('admin.employees.index') }}" class="row g-2 mb-4">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control"
               placeholder="Search..." value="{{ request('search') }}">
    </div>
    <div class="col-md-3">
        <input type="date" name="date" class="form-control"
               value="{{ request('date') }}">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">-- Status --</option>
            <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-gold w-100">Search</button>
    </div>
</form>

{{-- Table --}}
<div class="card-dark">
    <table class="table table-dark table-hover">
        <thead>
            <tr>
                <th>Sno.</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Address</th>
                <th>Status</th>
                <th>Created At</th>
                <th class="text-center" style="width:90px;">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($employees as $i => $emp)
            <tr>
                <td>{{ $employees->firstItem() + $i }}</td>
                <td>{{ $emp->name }}</td>
                <td>{{ $emp->contact }}</td>
                <td>{{ $emp->email }}</td>
                <td>{{ $emp->address }}</td>

                <td>
                    <span class="badge {{ $emp->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ ucfirst($emp->status) }}
                    </span>
                </td>

                <td>{{ $emp->created_at->format('d/m/Y') }}</td>

                {{-- ✅ FIXED ACTION COLUMN --}}
                <td class="text-center p-1">
                    <div class="d-flex justify-content-center align-items-center gap-1">

                        <!-- Edit -->
                        <a href="{{ route('admin.employees.edit', $emp->id) }}"
                           class="btn btn-sm btn-warning p-1" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>

                        <!-- View -->
                        <a href="{{ route('admin.employees.show', $emp->id) }}"
                           class="btn btn-sm btn-info p-1" title="View">
                            <i class="fas fa-eye"></i>
                        </a>

                        <!-- Toggle -->
                        <form action="{{ route('admin.employees.toggle', $emp->id) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            <button type="submit"
                                class="btn btn-sm {{ $emp->status === 'active' ? 'btn-danger' : 'btn-success' }} p-1"
                                title="Toggle Status">
                                <i class="fas {{ $emp->status === 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                            </button>
                        </form>

                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted">No employees found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $employees->withQueryString()->links() }}
</div>

@endsection