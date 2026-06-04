@extends('layouts.admin')
@section('page-title', 'Designation Master')
@section('content')

<style>
.pagination { justify-content: center; }
.pagination .page-link { background: #f0c040 !important; color: #000 !important; border: 1px solid #000 !important; margin: 2px; }
.pagination .page-link:hover { background: #000 !important; color: #f0b207 !important; }
.pagination .active .page-link { background: #000 !important; color: #f0c040 !important; }

.pt-tag {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #2a2a2a;
    border: 1px solid #444;
    border-radius: 8px;
    padding: 10px 18px;
    font-size: 14px;
    color: #fff;
    transition: border-color 0.2s;
}
.pt-tag:hover { border-color: #f0c040; }
.pt-tag .tag-edit {
    cursor: pointer; color: #f0c040;
    font-size: 12px; opacity: 0.7; transition: opacity 0.2s;
}
.pt-tag .tag-edit:hover { opacity: 1; }
.pt-tag .tag-delete {
    cursor: pointer; color: #dc3545;
    font-size: 12px; opacity: 0.7; transition: opacity 0.2s;
}
.pt-tag .tag-delete:hover { opacity: 1; }

/* Modal */
.pt-modal-overlay {
    display: none; position: fixed; top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.75);
    justify-content: center; align-items: center;
    z-index: 999999;
}
.pt-modal-overlay.show { display: flex; }
.pt-modal-box {
    background: #1e1e1e;
    border: 1px solid #f0c040;
    border-radius: 12px;
    padding: 28px;
    width: 380px;
    position: relative;
}
.pt-modal-box h5 { color: #f0c040; margin-bottom: 20px; font-size: 16px; }
.pt-modal-close {
    position: absolute; top:14px; right:16px;
    color: #aaa; cursor: pointer; font-size: 20px; line-height:1;
}
.pt-modal-close:hover { color: #fff; }
</style>

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="text-warning mb-0 fw-bold">
            <i class="fas fa-id-badge me-2"></i>Designation Master
        </h4>
        <small class="text-muted">Manage designation options</small>
    </div>
    <button class="btn btn-gold" onclick="openAddModal()">
        <i class="fas fa-plus me-1"></i>Add Designation
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Tags Area --}}
<div class="card-dark">
   
    <div class="d-flex flex-wrap gap-2 align-items-center">
        @forelse($items as $item)
        <div class="pt-tag">
            <span>{{ $item->name }}</span>
            <i class="fas fa-pen tag-edit" title="Edit"
               onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->name) }}')"></i>
            <i class="fas fa-times tag-delete" title="Delete"
               onclick="confirmDelete({{ $item->id }})"></i>
        </div>
        @empty
        <p class="text-muted mb-0">
            <i class="fas fa-inbox me-2 opacity-50"></i>
            No designations added yet. Click "Add Designation" to start.
        </p>
        @endforelse
    </div>

    @if($items->hasPages())
    <div class="mt-4">{{ $items->withQueryString()->links() }}</div>
    @endif
</div>


{{-- ADD MODAL --}}
<div class="pt-modal-overlay" id="addModal">
    <div class="pt-modal-box">
        <span class="pt-modal-close" onclick="closeAddModal()">&#x2715;</span>
        <h5><i class="fas fa-plus-circle me-2"></i>Add Designation</h5>
        <form method="POST" action="{{ route('admin.master.designation.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label text-muted" style="font-size:13px;">Designation Name</label>
                <input type="text" name="name"
                       class="form-control bg-dark text-white border-secondary"
                       placeholder="e.g. Manager, Developer, Designer..."
                       value="{{ old('name') }}" required autofocus>
                @error('name')
                    <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeAddModal()">Cancel</button>
                <button type="submit" class="btn btn-gold btn-sm">
                    <i class="fas fa-save me-1"></i>Save
                </button>
            </div>
        </form>
    </div>
</div>


{{-- EDIT MODAL --}}
<div class="pt-modal-overlay" id="editModal">
    <div class="pt-modal-box">
        <span class="pt-modal-close" onclick="closeEditModal()">&#x2715;</span>
        <h5><i class="fas fa-pen me-2"></i>Edit Designation</h5>
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label text-muted" style="font-size:13px;">Designation Name</label>
                <input type="text" name="name" id="editNameInput"
                       class="form-control bg-dark text-white border-secondary"
                       required>
            </div>
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-gold btn-sm">
                    <i class="fas fa-save me-1"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>


{{-- DELETE CONFIRM MODAL --}}
<div class="pt-modal-overlay" id="deleteModal">
    <div class="pt-modal-box" style="width:320px; text-align:center;">
        <span class="pt-modal-close" onclick="closeDeleteModal()">&#x2715;</span>
        <h5><i class="fas fa-trash me-2"></i>Confirm Delete</h5>
        <p class="text-secondary mb-0" style="font-size:14px;">
            Are you sure you want to delete this designation?
        </p>
        <div class="d-flex gap-2 justify-content-center mt-4">
            <button class="btn btn-secondary btn-sm" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn btn-danger btn-sm" id="deleteConfirmBtn">Yes, Delete</button>
        </div>
        <form id="deleteForm" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Add Modal
function openAddModal()  { document.getElementById('addModal').classList.add('show'); }
function closeAddModal() { document.getElementById('addModal').classList.remove('show'); }

// Edit Modal
function openEditModal(id, name) {
    document.getElementById('editNameInput').value = name;
    document.getElementById('editForm').action = '/admin/master/designation/' + id;
    document.getElementById('editModal').classList.add('show');
}
function closeEditModal() { document.getElementById('editModal').classList.remove('show'); }

// Delete Modal
function confirmDelete(id) {
    document.getElementById('deleteForm').action = '/admin/master/designation/' + id;
    document.getElementById('deleteModal').classList.add('show');
}
function closeDeleteModal() { document.getElementById('deleteModal').classList.remove('show'); }
document.getElementById('deleteConfirmBtn').addEventListener('click', function () {
    document.getElementById('deleteForm').submit();
});

// Close on overlay click
['addModal','editModal','deleteModal'].forEach(function(id) {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('show');
    });
});

// Auto open add modal on validation error
@if($errors->any())
    openAddModal();
@endif
</script>
@endpush