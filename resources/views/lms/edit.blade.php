@extends('layouts.admin')
@section('page-title', 'Edit Lead')
@section('content')

<div class="d-flex justify-content-between mb-4">
    <a href="javascript:history.back()"
   style="color:#f0c040; text-decoration:none; font-size:1.1rem;">← Back</a>
    <h4 style="color:#f0c040">Edit Lead</h4>
</div>

<div class="card-dark">
    <form action="{{ route('admin.lms.update', $lm->id) }}" method="POST" enctype="multipart/form-data">
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

            {{-- Saare Status Options --}}
            <div class="col-md-4">
                <label class="form-label">Status *</label>
                <select name="status" class="form-select" id="editStatus" required>
                    <option value="">— Select —</option>
                   @foreach([
    'call_back_required' => 'Call Back Required',
    'not_responded'      => 'Not Responded',
    'call_schedule'      => 'Call Schedule',
    'not_interested'     => 'Not Interested',
    'not_in_scope'       => 'Not In Scope',
] as $val => $label)
                        <option value="{{ $val }}" {{ $lm->status == $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Date/Time --}}
            <div class="col-md-4" id="editDateField"
                 style="{{ in_array($lm->status, ['call_back_required','call_schedule','not_responded','qualified']) ? '' : 'display:none;' }}">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ $lm->date }}">
            </div>
            <div class="col-md-4" id="editTimeField"
                 style="{{ in_array($lm->status, ['call_back_required','call_schedule','not_responded','qualified']) ? '' : 'display:none;' }}">
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

        {{-- ══════════════════════════════════════════════════════ --}}
        {{-- Proposal Sent Fields                                  --}}
        {{-- ══════════════════════════════════════════════════════ --}}
        <div id="proposalFields" style="{{ $lm->status == 'proposal_sent' ? '' : 'display:none;' }}">
            <hr style="border-color:#333; margin-top:24px;">
            <p style="color:#f0c040; font-weight:700; font-size:14px; margin-bottom:14px;">📄 Proposal Details</p>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Proposal Amount</label>
                    <input type="number" name="amount" class="form-control" value="{{ $lm->amount }}" placeholder="Enter amount">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Timeline</label>
                    <input type="text" name="timeline" class="form-control" value="{{ $lm->timeline }}" placeholder="e.g. 2 weeks">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Negotiation Amount</label>
                    <input type="number" name="negotiation_amount" class="form-control" value="{{ $lm->negotiation_amount }}" placeholder="Enter negotiation amount">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Upload Proposal</label>
                    <input type="file" name="proposal_document" class="form-control" accept=".pdf,.doc,.docx">
                    @if($lm->proposal_document)
                        <a href="{{ asset('storage/'.$lm->proposal_document) }}" target="_blank"
                           class="text-warning" style="font-size:12px; margin-top:4px; display:inline-block;">
                           📄 View Current Proposal
                        </a>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label">Revised Proposal</label>
                    <input type="file" name="revised_proposal" class="form-control" accept=".pdf,.doc,.docx">
                    @if($lm->revised_proposal)
                        <a href="{{ asset('storage/'.$lm->revised_proposal) }}" target="_blank"
                           class="text-warning" style="font-size:12px; margin-top:4px; display:inline-block;">
                           📋 View Current Revised
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════ --}}
        {{-- Won Fields                                            --}}
        {{-- ══════════════════════════════════════════════════════ --}}
        <div id="wonFields" style="{{ $lm->status == 'won' ? '' : 'display:none;' }}">
            <hr style="border-color:#333; margin-top:24px;">
            <p style="color:#f0c040; font-weight:700; font-size:14px; margin-bottom:14px;">👤 Client Details</p>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input type="text" name="won_name" class="form-control" value="{{ $lm->won_name }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contact No</label>
                    <input type="text" name="won_contact" class="form-control" value="{{ $lm->won_contact }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="won_email" class="form-control" value="{{ $lm->won_email }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Designation</label>
                    <input type="text" name="won_designation" class="form-control" value="{{ $lm->won_designation }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Business Name</label>
                    <input type="text" name="won_business_name" class="form-control" value="{{ $lm->won_business_name }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">GST No</label>
                    <input type="text" name="won_gst_no" class="form-control" value="{{ $lm->won_gst_no }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Location</label>
                    <input type="text" name="won_location" class="form-control" value="{{ $lm->won_location }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country</label>
                    <input type="text" name="won_country" class="form-control" value="{{ $lm->won_country }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <select name="won_state" class="form-select">
                        <option value="">— Select State —</option>
                        @foreach(['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal','Delhi','Jammu & Kashmir','Ladakh','Chandigarh','Puducherry'] as $st)
                            <option value="{{ $st }}" {{ $lm->won_state == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">City</label>
                    <input type="text" name="won_city" class="form-control" value="{{ $lm->won_city }}">
                </div>
            </div>

            <hr style="border-color:#333; margin-top:16px;">
            <p style="color:#f0c040; font-weight:700; font-size:14px; margin-bottom:14px;">🏗️ Project Details</p>
            <div class="row g-3">
               <div class="col-md-6">
                <label class="form-label">Project Type</label>
                <select name="won_project_type" class="form-select">
                  <option value="">— Select Type —</option>
@foreach($projectTypes as $pt)
    <option value="{{ $pt->id }}" {{ $lm->won_project_type == $pt->id ? 'selected' : '' }}>{{ $pt->name }}</option>
@endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Project Detail</label>
                <textarea name="won_project_detail" class="form-control" rows="3">{{ $lm->won_project_detail }}</textarea>
            </div>
                <div class="col-md-4">
                    <label class="form-label">Final Project Cost</label>
                    <input type="number" name="won_final_cost" class="form-control" value="{{ $lm->won_final_cost }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Milestone</label>
                    <input type="text" name="won_milestone" class="form-control" value="{{ $lm->won_milestone }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Timeline</label>
                    <input type="text" name="won_timeline" class="form-control" value="{{ $lm->won_timeline }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Token Received?</label>
                    <div class="d-flex gap-4 mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="won_token_received"
                                   id="tokenYes" value="yes"
                                   {{ $lm->won_token_received === 'yes' ? 'checked' : '' }}
                                   onclick="toggleTokenFields(true)">
                            <label class="form-check-label text-white" for="tokenYes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="won_token_received"
                                   id="tokenNo" value="no"
                                   {{ $lm->won_token_received !== 'yes' ? 'checked' : '' }}
                                   onclick="toggleTokenFields(false)">
                            <label class="form-check-label text-white" for="tokenNo">No</label>
                        </div>
                    </div>
                </div>
                <div id="tokenFields" class="col-12" style="{{ $lm->won_token_received === 'yes' ? '' : 'display:none;' }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Token Amount</label>
                            <input type="number" name="won_token_amount" class="form-control" value="{{ $lm->won_token_amount }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Amount Type</label>
                            <select name="won_amount_type" class="form-select">
                                <option value="">— Select —</option>
                                <option value="with_gst" {{ $lm->won_amount_type == 'with_gst' ? 'selected' : '' }}>With GST</option>
                                <option value="without_gst" {{ $lm->won_amount_type == 'without_gst' ? 'selected' : '' }}>Without GST</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Received Date</label>
                            <input type="date" name="won_received_date" class="form-control" value="{{ $lm->won_received_date }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">GST Type</label>
                            <select name="won_gst_type" class="form-select">
                                <option value="">— Select —</option>
                                <option value="IGST" {{ $lm->won_gst_type == 'IGST' ? 'selected' : '' }}>IGST</option>
                                <option value="CGST_SGST" {{ $lm->won_gst_type == 'CGST_SGST' ? 'selected' : '' }}>CGST + SGST</option>
                                <option value="UTGST" {{ $lm->won_gst_type == 'UTGST' ? 'selected' : '' }}>UTGST</option>
                                <option value="exempt" {{ $lm->won_gst_type == 'exempt' ? 'selected' : '' }}>Exempt</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-end">
            <a href="{{ route('admin.lms.show', $lm->id) }}" class="btn btn-secondary me-2">Cancel</a>
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
    var val  = this.value;
    var showDateTime = ['call_back_required', 'call_schedule', 'not_responded', 'qualified'].includes(val);

    document.getElementById('editDateField').style.display  = showDateTime ? 'block' : 'none';
    document.getElementById('editTimeField').style.display  = showDateTime ? 'block' : 'none';
    document.getElementById('proposalFields').style.display = val === 'proposal_sent' ? 'block' : 'none';
    document.getElementById('wonFields').style.display      = val === 'won' ? 'block' : 'none';
});

window.toggleTokenFields = function(show) {
    document.getElementById('tokenFields').style.display = show ? 'block' : 'none';
};

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