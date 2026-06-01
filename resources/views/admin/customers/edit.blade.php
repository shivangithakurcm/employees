@extends('layouts.admin')
@section('page-title', 'Edit Customer')
@section('content')

<style>
.section-card {
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-left: 4px solid #f0c040;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}
.section-title {
    color: #f0c040;
    font-weight: 700;
    font-size: 14px;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 1px solid #2a2a2a;
}
</style>

<div class="d-flex justify-content-between mb-4">
    <a href="{{ route('admin.customers.show', $customer->id) }}"
       style="color:#f0c040; text-decoration:none; font-size:1.1rem;">← Back</a>
    <h4 style="color:#f0c040">Edit Customer</h4>
</div>

<form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Client Details --}}
    <div class="section-card">
        <div class="section-title">👤 Client Details</div>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Name</label>
                <input type="text" name="won_name" class="form-control" value="{{ $customer->won_name }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Contact No</label>
                <input type="text" name="won_contact" class="form-control" value="{{ $customer->won_contact }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" name="won_email" class="form-control" value="{{ $customer->won_email }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Designation</label>
                <input type="text" name="won_designation" class="form-control" value="{{ $customer->won_designation }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Business Name</label>
                <input type="text" name="won_business_name" class="form-control" value="{{ $customer->won_business_name }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">GST No</label>
                <input type="text" name="won_gst_no" class="form-control" value="{{ $customer->won_gst_no }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Location</label>
                <input type="text" name="won_location" class="form-control" value="{{ $customer->won_location }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">City</label>
                <input type="text" name="won_city" class="form-control" value="{{ $customer->won_city }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">State</label>
                <select name="won_state" class="form-select">
                    <option value="">— Select State —</option>
                    @foreach(['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal','Delhi','Jammu & Kashmir','Ladakh','Chandigarh','Puducherry'] as $st)
                        <option value="{{ $st }}" {{ $customer->won_state == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Country</label>
                <input type="text" name="won_country" class="form-control" value="{{ $customer->won_country }}">
            </div>
        </div>
    </div>

    {{-- Project Details --}}
    <div class="section-card">
        <div class="section-title">🏗️ Project Details</div>
        <div class="row g-3">
           <div class="col-md-6">
                <label class="form-label">Project Type</label>
               {{-- temporary debug --}}
<select name="won_project_type" class="form-select">
    <option value="">— Select Type —</option>
    @foreach($projectTypes as $pt)
        <option value="{{ $pt->id }}" {{ $customer->won_project_type == $pt->id ? 'selected' : '' }}>{{ $pt->name }}</option>
    @endforeach
</select>
            </div>
            <div class="col-12">
                <label class="form-label">Project Detail</label>
                <textarea name="won_project_detail" class="form-control" rows="3">{{ $customer->won_project_detail }}</textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Final Project Cost</label>
                <input type="number" name="won_final_cost" class="form-control" value="{{ $customer->won_final_cost }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Timeline</label>
                <input type="text" name="won_timeline" class="form-control" value="{{ $customer->won_timeline }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Milestone</label>
                <input type="text" name="won_milestone" class="form-control" value="{{ $customer->won_milestone }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Project Status</label>
                <select name="project_status" class="form-select">
                    <option value="">— Select —</option>
                    <option value="Active"     {{ $customer->project_status == 'Active'     ? 'selected' : '' }}>Active</option>
                    <option value="Completed"  {{ $customer->project_status == 'Completed'  ? 'selected' : '' }}>Completed</option>
                    <option value="On Hold"    {{ $customer->project_status == 'On Hold'    ? 'selected' : '' }}>On Hold</option>
                    <option value="Cancelled"  {{ $customer->project_status == 'Cancelled'  ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
        </div>
    </div>

    <div class="text-end mt-3">
        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-secondary me-2">Cancel</a>
        <button type="button" id="updateBtn" class="btn btn-gold">Update Customer</button>
        <button type="submit" id="submitBtn" hidden></button>
    </div>
</form>

{{-- Confirm Modal --}}
<div id="confirmModal"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.7); justify-content:center; align-items:center; z-index:9999;">
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
    const modal = document.getElementById('confirmModal');
    document.getElementById('updateBtn').addEventListener('click', function () {
        modal.style.display = 'flex';
    });
    document.getElementById('cancelBtn').addEventListener('click', function () {
        modal.style.display = 'none';
    });
    document.getElementById('confirmBtn').addEventListener('click', function () {
        modal.style.display = 'none';
        document.getElementById('submitBtn').click();
    });
});
</script>
@endpush