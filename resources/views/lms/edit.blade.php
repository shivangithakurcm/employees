@extends('layouts.admin')
@section('page-title', 'Edit Lead')
@section('content')

<div class="d-flex justify-content-between mb-4">
    <a href="{{ route('admin.lms.show', $lm->id) }}"
       style="color:#f0c040; text-decoration:none; font-size:1.1rem;">← Back</a>
    <h4 style="color:#f0c040">Edit Lead</h4>
</div>

<div class="card-dark">
    <form action="{{ route('admin.lms.update', $lm->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="discussion" id="discussionField" value="update">

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">First Name *</label>
                <input type="text" name="first_name" class="form-control"
                       value="{{ $lm->first_name }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Middle Name</label>
                <input type="text" name="middle_name" class="form-control"
                       value="{{ $lm->middle_name }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Last Name *</label>
                <input type="text" name="last_name" class="form-control"
                       value="{{ $lm->last_name }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Contact Number *</label>
                <input type="tel" name="contact_number" class="form-control"
                       maxlength="10" value="{{ $lm->contact_number }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ $lm->email }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">State</label>
                <select name="state" class="form-select">
                    <option value="">— Select State —</option>
                    @foreach(['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal','Delhi','Jammu & Kashmir','Ladakh','Chandigarh','Puducherry'] as $st)
                        <option value="{{ $st }}" {{ $lm->state == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" value="{{ $lm->city }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Country</label>
                <input type="text" name="country" class="form-control" value="{{ $lm->country }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status *</label>
                <select name="status" class="form-select" id="editStatus" required>
                    <option value="">— Select —</option>
                    @foreach([
                        'call_back_required' => 'Call Back Required',
                        'call_schedule'      => 'Call Schedule',
                        'not_interested'     => 'Not Interested',
                        'not_responded'      => 'Not Responded',
                        'not_in_scope'       => 'Not In Scope',
                        'qualified'          => 'Qualified',
                        'proposal_sent'      => 'Proposal Sent',
                        'lost'               => 'Lost',
                        'won'                => 'Won',
                    ] as $val => $label)
                        <option value="{{ $val }}" {{ $lm->status == $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4" id="editDateField"
                 style="{{ in_array($lm->status, ['call_back_required','call_schedule']) ? '' : 'display:none;' }}">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ $lm->date }}">
            </div>
            <div class="col-md-4" id="editTimeField"
                 style="{{ in_array($lm->status, ['call_back_required','call_schedule']) ? '' : 'display:none;' }}">
                <label class="form-label">Time</label>
                <input type="time" name="time" class="form-control" value="{{ $lm->time }}">
            </div>

            <div class="col-md-8">
                <label class="form-label">Requirement</label>
                <input type="text" name="requirement" class="form-control" value="{{ $lm->Requirement }}">
            </div>
            <div class="col-12">
                <label class="form-label">Comment</label>
                <textarea name="comment" class="form-control" rows="3">{{ $lm->comment }}</textarea>
            </div>
        </div>

        <div class="mt-4 text-end">
            <a href="{{ route('admin.lms.index') }}" class="btn btn-secondary me-2">Cancel</a>
            <button type="button" id="draftBtn" class="btn btn-outline-warning me-2">Save as Draft</button>
            <button type="button" id="updateBtn" class="btn btn-gold">Update Lead</button>
            <button type="submit" id="submitBtn" hidden></button>
        </div>
    </form>
</div>

{{-- Confirm Modal --}}
<div id="confirmModal"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.7); justify-content:center; align-items:center; z-index:9999;">
    <div style="background:#1e1e1e; padding:25px; border-radius:10px;
                width:350px; text-align:center; border:1px solid #f0c040;">
        <h5 style="color:#f0c040; margin-bottom:15px;" id="confirmModalTitle">Confirm Update</h5>
        <p style="color:#fff;" id="confirmModalText">Do you want to save these changes?</p>
        <div class="mt-3 d-flex justify-content-center gap-2">
            <button id="cancelBtn" class="btn btn-secondary btn-sm">Cancel</button>
            <button id="confirmBtn" class="btn btn-gold btn-sm">Yes, Save</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('editStatus').addEventListener('change', function() {
    var show = ['call_back_required', 'call_schedule'].includes(this.value);
    document.getElementById('editDateField').style.display = show ? 'block' : 'none';
    document.getElementById('editTimeField').style.display = show ? 'block' : 'none';
});

const modal             = document.getElementById('confirmModal');
const updateBtn         = document.getElementById('updateBtn');
const draftBtn          = document.getElementById('draftBtn');
const cancelBtn         = document.getElementById('cancelBtn');
const confirmBtn        = document.getElementById('confirmBtn');
const submitBtn         = document.getElementById('submitBtn');
const discussionField   = document.getElementById('discussionField');
const confirmModalTitle = document.getElementById('confirmModalTitle');
const confirmModalText  = document.getElementById('confirmModalText');

updateBtn.addEventListener('click', function() {
    discussionField.value = 'update';
    confirmModalTitle.textContent = 'Confirm Update';
    confirmModalText.textContent  = 'Do you want to save these changes?';
    modal.style.display = 'flex';
});

draftBtn.addEventListener('click', function() {
    discussionField.value = 'draft';
    confirmModalTitle.textContent = 'Save as Draft';
    confirmModalText.textContent  = 'Do you want to save this lead as Draft?';
    modal.style.display = 'flex';
});

cancelBtn.addEventListener('click', function() { modal.style.display = 'none'; });
confirmBtn.addEventListener('click', function() {
    modal.style.display = 'none';
    submitBtn.click();
});
</script>
@endpush