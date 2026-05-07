@extends('layouts.admin')
@section('page-title', 'LMS')
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
    $currentStatus = request('status', '');
    $currentType   = request('type', '');
    $isFollowUp         = $currentStatus === 'follow_up';
    $isCallSchedule     = $currentType === 'call_schedule';
    $isCallBackRequired = $currentType === 'call_back_required';
    $isQualified        = $currentStatus === 'qualified';
    $isProposalSent     = $currentStatus === 'proposal_sent';
    $isLost             = $currentStatus === 'lost';
    $showExtraCols      = $isCallSchedule || $isCallBackRequired;
    $showQualifiedCols  = $isQualified;
    $showProposalCols   = $isProposalSent;
    $showLostCols       = $isLost;
    $isWon = $currentStatus === 'won';
    $showWonCols = $isWon;
@endphp

{{-- Status Tabs --}}
<div class="d-flex gap-2 mb-4" style="width:100%;">
    <a href="{{ route('admin.lms.index') }}"
       class="btn tab-btn flex-fill {{ !$currentStatus ? 'btn-warning' : 'btn-outline-secondary' }}">
        All Leads <span class="badge bg-dark ms-1">{{ $counts['all'] }}</span>
    </a>
    <a href="{{ route('admin.lms.index', ['status' => 'follow_up']) }}"
       class="btn tab-btn flex-fill {{ $isFollowUp ? 'btn-warning' : 'btn-outline-secondary' }}">
        Follow Up <span class="badge bg-dark ms-1">{{ $counts['follow_up'] }}</span>
    </a>
    <a href="{{ route('admin.lms.index', ['status' => 'qualified']) }}"
       class="btn tab-btn flex-fill {{ $currentStatus == 'qualified' ? 'btn-warning' : 'btn-outline-secondary' }}">
        Qualified <span class="badge bg-dark ms-1">{{ $counts['qualified'] }}</span>
    </a>
    <a href="{{ route('admin.lms.index', ['status' => 'proposal_sent']) }}"
       class="btn tab-btn flex-fill {{ $currentStatus == 'proposal_sent' ? 'btn-warning' : 'btn-outline-secondary' }}">
        Proposal Sent <span class="badge bg-dark ms-1">{{ $counts['proposal_sent'] }}</span>
    </a>
    <a href="{{ route('admin.lms.index', ['status' => 'lost']) }}"
       class="btn tab-btn flex-fill {{ $currentStatus == 'lost' ? 'btn-warning' : 'btn-outline-secondary' }}">
        Lost <span class="badge bg-dark ms-1">{{ $counts['lost'] }}</span>
    </a>
    <a href="{{ route('admin.lms.index', ['status' => 'won']) }}"
       class="btn tab-btn flex-fill {{ $currentStatus == 'won' ? 'btn-warning' : 'btn-outline-secondary' }}">
        Won <span class="badge bg-dark ms-1">{{ $counts['won'] }}</span>
    </a>
    <a href="{{ route('admin.lms.index', ['status' => 'draft']) }}"
       class="btn tab-btn flex-fill {{ $currentStatus == 'draft' ? 'btn-warning' : 'btn-outline-secondary' }}">
        Draft <span class="badge bg-dark ms-1">{{ $counts['draft'] }}</span>
    </a>
</div>

{{-- Search + Filters --}}
<form method="GET" action="{{ route('admin.lms.index') }}">
    @if($currentStatus)
        <input type="hidden" name="status" value="{{ $currentStatus }}">
    @endif
    @if($currentType)
        <input type="hidden" name="type" value="{{ $currentType }}">
    @endif

    <div class="row g-2 mb-4 align-items-center">
        <div class="col-md-3">
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
        <div class="col-md-2">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div class="col-md-2">
            <select name="state" class="form-select">
                <option value="">— State Filter —</option>
                @foreach(['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal','Delhi','Jammu & Kashmir','Ladakh','Chandigarh','Puducherry'] as $st)
                    <option value="{{ $st }}" {{ request('state') == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" name="city" class="form-control bg-dark text-white border-secondary"
                   style="height:38px;" placeholder="City Filter" value="{{ request('city') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-gold">Filter</button>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.lms.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
        @if(!$isFollowUp)
        <div class="col-auto">
            <button type="button" class="btn btn-gold"
                data-bs-toggle="modal" data-bs-target="#addLeadModal">
                <i class="fas fa-plus me-1"></i> Add Lead
            </button>
        </div>
        @endif
    </div>
</form>

{{-- Follow Up Sub Tabs --}}
@if($isFollowUp)
<div class="d-flex gap-2 mb-3">
    <a href="{{ route('admin.lms.index', ['status'=>'follow_up','type'=>'call_back_required']) }}"
       class="btn {{ $isCallBackRequired ? 'btn-warning' : 'btn-outline-warning' }}">
        Call Back Required
        <span class="badge bg-dark ms-1">{{ $counts['call_back_required'] }}</span>
    </a>
    <a href="{{ route('admin.lms.index', ['status'=>'follow_up','type'=>'call_schedule']) }}"
       class="btn {{ $isCallSchedule ? 'btn-info' : 'btn-outline-info' }}">
        Call Schedule
        <span class="badge bg-dark ms-1">{{ $counts['call_schedule'] }}</span>
    </a>
</div>
@endif

{{-- Leads Table --}}
<div class="card-dark" style="overflow: hidden; padding-bottom: 0;">
    <div style="overflow-x: auto;">
        <table class="table table-dark table-bordered mb-0" style="white-space: nowrap;">
            <thead>
                <tr>
                    <th>Sno.</th>
                    <th>Full Name</th>
                    <th>Contact</th>
                    @if(!$showProposalCols && !$showLostCols && !$showWonCols)
                    <th>Email</th>
                    @endif
                    @if($showWonCols)
                    <th>Email</th>
                    <th>Business Name</th>
                    @endif
                    <th>City</th>
                    @if($showQualifiedCols || $showProposalCols || $showLostCols)
                        <th>State</th>
                    @endif
                    @if(!$showProposalCols && !$showLostCols && !$showWonCols)
                        <th>Requirement</th>
                    @endif
                    @if($showExtraCols)
                        <th>Date</th>
                        <th>Time</th>
                        <th>Comment</th>
                    @endif
                    @if($showQualifiedCols)
                        <th>Date</th>
                        <th>Time</th>
                    @endif
                    @if($showProposalCols)
                        <th>Proposal Amount</th>
                        <th>Timeline</th>
                        <th>Sent Date</th>
                        <th>Negotiation Amt</th>
                        <th>Proposal Doc</th>
                    @endif
                    @if($showLostCols)
                        <th>Comment</th>
                        <th>Lost Date</th>
                    @endif
                    @if($showWonCols)
                        <th>Project Cost</th>
                        <th>Timeline</th>
                        <th>Milestone</th>
                        <th>Token Received</th>
                    @endif
                    <th>Status</th>
                    @if(!$showLostCols && !$showWonCols)
                    <th>Add Date</th>
                    @endif
                    <th style="position:sticky; right:0; background:#212529; z-index:2;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr>
                    <td>{{ ($leads->currentPage() - 1) * $leads->perPage() + $loop->iteration }}</td>
                    <td>{{ $lead->first_name }} {{ $lead->last_name }}</td>
                    <td>{{ $lead->contact_number }}</td>
                    @if(!$showProposalCols && !$showLostCols && !$showWonCols)
                    <td>{{ $lead->email ?? '-' }}</td>
                    @endif
                    @if($showWonCols)
                    <td>{{ $lead->won_email ?? $lead->email ?? '-' }}</td>
                    <td>{{ $lead->won_business_name ?? '-' }}</td>
                    @endif
                    <td>{{ $lead->won_city ?? $lead->city ?? '-' }}</td>
                    @if($showQualifiedCols || $showProposalCols || $showLostCols)
                        <td>{{ $lead->state ?? '-' }}</td>
                    @endif
                    @if(!$showProposalCols && !$showLostCols && !$showWonCols)
                        <td>{{ $lead->Requirement ?? '-' }}</td>
                    @endif
                    @if($showExtraCols)
                        <td>{{ $lead->date ?? '-' }}</td>
                        <td>{{ $lead->time ?? '-' }}</td>
                        <td>{{ $lead->comment ?? '-' }}</td>
                    @endif
                    @if($showQualifiedCols)
                        <td>{{ $lead->date ?? '-' }}</td>
                        <td>{{ $lead->time ?? '-' }}</td>
                    @endif
                    @if($showProposalCols)
                        <td>{{ $lead->amount ? '₹'.number_format($lead->amount, 2) : '-' }}</td>
                        <td>{{ $lead->timeline ?? '-' }}</td>
                        <td>{{ $lead->created_at ? $lead->created_at->format('d-m-Y') : '-' }}</td>
                        <td>{{ $lead->negotiation_amount ? '₹'.number_format($lead->negotiation_amount, 2) : '-' }}</td>
                        <td>
    @if($lead->revised_proposal)
        <a href="{{ asset('storage/'.$lead->revised_proposal) }}"
           target="_blank" class="btn btn-sm"
           style="background:#fd7e14;color:#fff;font-size:11px;padding:3px 8px;">
           📋 Revised
        </a>
    @elseif($lead->proposal_document)
        <a href="{{ asset('storage/'.$lead->proposal_document) }}"
           target="_blank" class="btn btn-sm"
           style="background:#6f42c1;color:#fff;font-size:11px;padding:3px 8px;">
           📄 View
        </a>
    @else
        -
    @endif
</td>
                    @endif
                    @if($showLostCols)
                        <td>{{ $lead->comment ?? '-' }}</td>
                        <td>{{ $lead->updated_at ? $lead->updated_at->format('d-m-Y') : '-' }}</td>
                    @endif
                    @if($showWonCols)
                        <td>{{ $lead->won_final_cost ? '₹'.number_format($lead->won_final_cost, 2) : '-' }}</td>
                        <td>{{ $lead->won_timeline ?? '-' }}</td>
                        <td>{{ $lead->won_milestone ?? '-' }}</td>
                        <td>
                            @if($lead->won_token_received === 'yes')
                                <span class="badge bg-success">Yes</span>
                            @elseif($lead->won_token_received === 'no')
                                <span class="badge bg-secondary">No</span>
                            @else
                                -
                            @endif
                        </td>
                    @endif
                    <td>
                        <span class="badge
                            @if($lead->status == 'won') bg-success
                            @elseif($lead->status == 'lost') bg-danger
                            @elseif($lead->status == 'not_interested') bg-danger
                            @elseif($lead->status == 'not_in_scope') bg-danger
                            @elseif($lead->status == 'call_schedule') bg-primary
                            @elseif($lead->status == 'call_back_required') bg-warning text-dark
                            @elseif($lead->status == 'not_responded') bg-secondary
                            @elseif($lead->status == 'qualified') bg-info text-dark
                            @elseif($lead->status == 'proposal_sent') bg-purple text-white
                            @elseif($lead->status == 'on_hold') bg-warning text-dark
                            @elseif($lead->status == 'negotiation') bg-info text-dark
                            @else bg-secondary
                            @endif">
                            {{ ucwords(str_replace('_', ' ', $lead->status)) }}
                        </span>
                    </td>
                    @if(!$showLostCols && !$showWonCols)
                    <td>{{ $lead->created_at ? $lead->created_at->format('d-m-Y') : '-' }}</td>
                    @endif
                    <td style="position:sticky; right:0; background:#212529; white-space:nowrap; vertical-align:middle;">
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.lms.show', $lead->id) }}" class="btn btn-sm btn-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.lms.edit', $lead->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            @if(!$showLostCols && !$showWonCols && in_array($lead->status, ['call_schedule','call_back_required','qualified','not_responded','not_interested','not_in_scope','proposal_sent','negotiation']))
                                {{-- ✅ CHANGE 2: setLead mein lead data bhi pass kiya autofill ke liye --}}
                                <button class="btn btn-sm btn-success" title="Action"
                                    data-bs-toggle="modal" data-bs-target="#actionModal"
                                    onclick="setLead({{ $lead->id }}, '{{ $lead->status }}', {
                                        name: '{{ addslashes(trim($lead->first_name . ' ' . $lead->last_name)) }}',
                                        contact: '{{ $lead->contact_number }}',
                                        email: '{{ addslashes($lead->email ?? '') }}',
                                        city: '{{ addslashes($lead->city ?? '') }}',
                                        state: '{{ addslashes($lead->state ?? '') }}',
                                        country: '{{ addslashes($lead->country ?? '') }}'
                                    })">
                                    <i class="fas fa-bolt"></i>
                                </button>
                            @elseif(!$showLostCols && !$showWonCols)
                                <button class="btn btn-sm btn-success" disabled title="Action"
                                    style="opacity:0.4;cursor:not-allowed;">
                                    <i class="fas fa-bolt"></i>
                                </button>
                            @endif
                            <button class="btn btn-sm btn-primary" title="History"
                                data-bs-toggle="modal" data-bs-target="#historyModal"
                                onclick="loadHistory({{ $lead->id }})">
                                <i class="fas fa-history"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                onclick="confirmDelete({{ $lead->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                            {{-- ✅ CHANGE 1: Delete form mein redirect_status aur redirect_type add kiya --}}
                            <form id="deleteForm{{ $lead->id }}"
                                  action="{{ route('admin.lms.destroy', $lead->id) }}"
                                  method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="redirect_status" value="{{ $currentStatus }}">
                                <input type="hidden" name="redirect_type" value="{{ $currentType }}">
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $showLostCols ? 8 : ($showWonCols ? 10 : ($showExtraCols ? 11 : ($showQualifiedCols ? 11 : ($showProposalCols ? 12 : 9)))) }}" class="text-center text-muted">
                        No leads found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $leads->withQueryString()->links() }}
    </div>
</div>

{{-- Add Lead Modal --}}
<div class="modal fade" id="addLeadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Add Lead</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.lms.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Contact Number *</label>
                            <input type="tel" name="contact_number" class="form-control" maxlength="10" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <select name="state" class="form-select">
                                <option value="">— Select State —</option>
                                @foreach(['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal','Delhi','Jammu & Kashmir','Ladakh','Chandigarh','Puducherry'] as $st)
                                    <option value="{{ $st }}">{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Select Type (Status) *</label>
                            <select name="status" class="form-select" id="statusSelect" required>
                                <option value="">— Select —</option>
                                <option value="call_back_required">Call Back Required</option>
                                <option value="not_responded">Not Responded</option>
                                <option value="call_schedule">Call Schedule</option>
                                <option value="not_interested">Not Interested</option>
                                <option value="not_in_scope">Not In Scope</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="dateField" style="display:none;">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control">
                        </div>
                        <div class="col-md-4" id="timeField" style="display:none;">
                            <label class="form-label">Time</label>
                            <input type="time" name="time" class="form-control">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Requirement</label>
                            <input type="text" name="requirement" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Comment</label>
                            <textarea name="comment" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="discussion" value="draft" class="btn btn-outline-warning">Draft</button>
                    <button type="submit" name="discussion" value="add" class="btn btn-gold">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Action Modal --}}
<div class="modal fade" id="actionModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white">
            <form id="actionForm" action="{{ route('admin.lms.action') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="lead_id" id="leadId">
                <input type="hidden" name="lead_status" id="leadStatus">
                <div class="modal-header">
                    <h5>Take Action</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height:75vh; overflow-y:auto;">

                    {{-- Action Type --}}
                    <div class="mb-3">
                        <label>Select Action</label>
                        <select name="action_type" id="actionType" class="form-select" required>
                            <option value="">Select</option>
                        </select>
                    </div>

                    {{-- Date --}}
                    <div class="mb-3" id="actionDate" style="display:none;">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control">
                    </div>

                    {{-- Time --}}
                    <div class="mb-3" id="actionTime" style="display:none;">
                        <label>Time</label>
                        <input type="time" name="time" class="form-control">
                    </div>

                    {{-- Proposal Upload --}}
                    <div class="mb-3" id="actionProposal" style="display:none;">
                        <label>Upload Proposal <span class="text-danger">*</span></label>
                        <input type="file" name="proposal" id="proposalInput" class="form-control" accept=".pdf,.doc,.docx">
                        <small class="text-muted">PDF, DOC, DOCX allowed</small>
                    </div>

                    {{-- Amount --}}
                    <div class="mb-3" id="actionAmount" style="display:none;">
                        <label>Amount <span class="text-danger">*</span></label>
                        <input type="number" name="amount" id="amountInput" class="form-control" placeholder="Enter amount">
                    </div>

                    {{-- Timeline --}}
                    <div class="mb-3" id="actionTimeline" style="display:none;">
                        <label>Timeline <span class="text-danger">*</span></label>
                        <input type="text" name="timeline" id="timelineInput" class="form-control" placeholder="e.g. 2 weeks, 1 month">
                    </div>

                    {{-- Negotiation Amount --}}
                    <div class="mb-3" id="actionNegotiationAmt" style="display:none;">
                        <label>Negotiation Amount <span class="text-danger">*</span></label>
                        <input type="number" name="negotiation_amount" id="negotiationAmtInput" class="form-control" placeholder="Enter negotiation amount">
                    </div>

                    {{-- Revised Proposal --}}
                    <div class="mb-3" id="actionRevisedDoc" style="display:none;">
                        <label>Revised Proposal <span class="text-danger">*</span></label>
                        <input type="file" name="revised_proposal" id="revisedDocInput" class="form-control" accept=".pdf,.doc,.docx">
                        <small class="text-muted">PDF, DOC, DOCX allowed</small>
                    </div>

                    {{-- WON FORM --}}
                    <div id="actionWonForm" style="display:none;">
                        <hr style="border-color:#333;">
                        <p style="color:#f0c040; font-weight:700; font-size:14px; margin-bottom:14px;">
                            👤 Client Details
                        </p>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="won_name" id="wonName" class="form-control" placeholder="Full Name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact No <span class="text-danger">*</span></label>
                                <input type="text" name="won_contact" id="wonContact" class="form-control" placeholder="Contact Number">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="won_email" id="wonEmail" class="form-control" placeholder="Email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Designation</label>
                                <input type="text" name="won_designation" class="form-control" placeholder="Designation">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Business Name <span class="text-danger">*</span></label>
                                <input type="text" name="won_business_name" class="form-control" placeholder="Business Name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">GST No</label>
                                <input type="text" name="won_gst_no" class="form-control" placeholder="GST Number">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <input type="text" name="won_location" id="wonLocation" class="form-control" placeholder="Location / Address">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <input type="text" name="won_country" id="wonCountry" class="form-control" placeholder="Country">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">State</label>
                                <select name="won_state" id="wonState" class="form-select">
                                    <option value="">— Select State —</option>
                                    @foreach(['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal','Delhi','Jammu & Kashmir','Ladakh','Chandigarh','Puducherry'] as $st)
                                        <option value="{{ $st }}">{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <input type="text" name="won_city" id="wonCity" class="form-control" placeholder="City">
                            </div>
                        </div>

                        <hr style="border-color:#333; margin-top:16px;">
                        <p style="color:#f0c040; font-weight:700; font-size:14px; margin-bottom:14px;">
                            🏗️ Project Details
                        </p>
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label">Project Detail <span class="text-danger">*</span></label>
                                <textarea name="won_project_detail" class="form-control" rows="3" placeholder="Describe project details..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Final Project Cost <span class="text-danger">*</span></label>
                                <input type="number" name="won_final_cost" class="form-control" placeholder="Enter amount (₹)">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Milestone</label>
                                <input type="text" name="won_milestone" class="form-control" placeholder="e.g. Phase 1, Phase 2">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Timeline <span class="text-danger">*</span></label>
                                <input type="text" name="won_timeline" class="form-control" placeholder="e.g. 2 months">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Token Received?</label>
                                <div class="d-flex gap-4 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="won_token_received"
                                               id="tokenYes" value="yes" onclick="toggleTokenFields(true)">
                                        <label class="form-check-label text-white" for="tokenYes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="won_token_received"
                                               id="tokenNo" value="no" onclick="toggleTokenFields(false)" checked>
                                        <label class="form-check-label text-white" for="tokenNo">No</label>
                                    </div>
                                </div>
                            </div>
                            <div id="tokenFields" class="col-12" style="display:none;">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Token Amount <span class="text-danger">*</span></label>
                                        <input type="number" name="won_token_amount" class="form-control" placeholder="Enter token amount (₹)">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">GST Type</label>
                                        <select name="won_amount_type" class="form-select">
                                            <option value="">— Select —</option>
                                            <option value="with_gst">With GST</option>
                                            <option value="without_gst">Without GST</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Received Date <span class="text-danger">*</span></label>
                                        <input type="date" name="won_received_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End Won Form --}}

                    {{-- Comment (always visible) --}}
                    <div class="mb-3 mt-3">
                        <label>Comment</label>
                        <textarea name="comment" class="form-control" required></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning" id="actionSubmitBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Action Confirm Popup --}}
<div id="actionConfirmModal"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.7); justify-content:center; align-items:center; z-index:999999;">
    <div style="background:#1e1e1e; padding:25px; border-radius:10px;
                width:350px; text-align:center; border:1px solid #f0c040;">
        <h5 style="color:#f0c040; margin-bottom:15px;">Confirm Action</h5>
        <p style="color:#fff;">Do you want to save this action?</p>
        <div class="mt-3 d-flex justify-content-center gap-2">
            <button id="actionCancelBtn" class="btn btn-secondary btn-sm">Cancel</button>
            <button id="actionConfirmBtn" class="btn btn-gold btn-sm">Yes, Submit</button>
        </div>
    </div>
</div>

{{-- Delete Confirm Popup --}}
<div id="deleteConfirmModal"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.7); justify-content:center; align-items:center; z-index:999999;">
    <div style="background:#1e1e1e; padding:25px; border-radius:10px;
                width:350px; text-align:center; border:1px solid #f0c040;">
        <h5 style="color:#f0c040; margin-bottom:15px;">Confirm Delete</h5>
        <p style="color:#fff;">Are you sure you want to delete this lead?</p>
        <div class="mt-3 d-flex justify-content-center gap-2">
            <button id="deleteCancelBtn" class="btn btn-secondary btn-sm">Cancel</button>
            <button id="deleteConfirmBtn" class="btn btn-danger btn-sm">Yes, Delete</button>
        </div>
    </div>
</div>

{{-- History Modal --}}
<div class="modal fade" id="historyModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-warning"><i class="fas fa-history me-2"></i>View History</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                <div id="historyContent">
                    <p class="text-muted">Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ─── Action Modal ────────────────────────────────────────────────
    const actionConfirmModal = document.getElementById('actionConfirmModal');
    const actionSubmitBtn    = document.getElementById('actionSubmitBtn');
    const actionCancelBtn    = document.getElementById('actionCancelBtn');
    const actionConfirmBtn   = document.getElementById('actionConfirmBtn');
    const actionForm         = document.getElementById('actionForm');

    actionSubmitBtn.addEventListener('click', function() {
        var bsModal = bootstrap.Modal.getInstance(document.getElementById('actionModal'));
        if (bsModal) bsModal.hide();
        setTimeout(function() { actionConfirmModal.style.display = 'flex'; }, 300);
    });

    actionCancelBtn.addEventListener('click', function() {
        actionConfirmModal.style.display = 'none';
    });

    actionConfirmBtn.addEventListener('click', function() {
        actionConfirmModal.style.display = 'none';
        actionForm.submit();
    });

    // ─── Delete Modal ────────────────────────────────────────────────
    let deleteLeadId = null;
    const deleteConfirmModal = document.getElementById('deleteConfirmModal');
    const deleteCancelBtn    = document.getElementById('deleteCancelBtn');
    const deleteConfirmBtn   = document.getElementById('deleteConfirmBtn');

    window.confirmDelete = function(id) {
        deleteLeadId = id;
        deleteConfirmModal.style.display = 'flex';
    };

    deleteCancelBtn.addEventListener('click', function() {
        deleteLeadId = null;
        deleteConfirmModal.style.display = 'none';
    });

    deleteConfirmBtn.addEventListener('click', function() {
        if (deleteLeadId) document.getElementById('deleteForm' + deleteLeadId).submit();
    });

    // ─── setLead ─────────────────────────────────────────────────────
    // ✅ CHANGE 2: leadData parameter add kiya - won form autofill ke liye
    window.setLead = function(id, status, leadData) {
        leadData = leadData || {};

        document.getElementById('leadId').value     = id;
        document.getElementById('leadStatus').value = status;

        document.getElementById('actionDate').style.display           = 'none';
        document.getElementById('actionTime').style.display           = 'none';
        document.getElementById('actionProposal').style.display       = 'none';
        document.getElementById('actionAmount').style.display         = 'none';
        document.getElementById('actionTimeline').style.display       = 'none';
        document.getElementById('actionNegotiationAmt').style.display = 'none';
        document.getElementById('actionRevisedDoc').style.display     = 'none';
        document.getElementById('actionWonForm').style.display        = 'none';
        document.getElementById('tokenFields').style.display          = 'none';
        document.getElementById('actionType').value                   = '';
        document.getElementById('proposalInput').value                = '';
        document.getElementById('amountInput').value                  = '';
        document.getElementById('timelineInput').value                = '';
        document.getElementById('negotiationAmtInput').value          = '';
        document.getElementById('revisedDocInput').value              = '';
        if (document.getElementById('tokenNo')) document.getElementById('tokenNo').checked = true;

        // ✅ Won form fields autofill karo lead data se
        document.getElementById('wonName').value    = leadData.name    || '';
        document.getElementById('wonContact').value = leadData.contact || '';
        document.getElementById('wonEmail').value   = leadData.email   || '';
        document.getElementById('wonCity').value    = leadData.city    || '';
        document.getElementById('wonCountry').value = leadData.country || '';
        document.getElementById('wonLocation').value = leadData.city   || '';

        // State select autofill
        var wonStateEl = document.getElementById('wonState');
        wonStateEl.value = '';
        if (leadData.state) {
            for (var i = 0; i < wonStateEl.options.length; i++) {
                if (wonStateEl.options[i].value === leadData.state) {
                    wonStateEl.selectedIndex = i;
                    break;
                }
            }
        }

        let actionType = document.getElementById('actionType');
        actionType.innerHTML = '<option value="">Select</option>';

        if (status === 'call_schedule') {
            actionType.innerHTML += '<option value="lost">Lost</option>';
            actionType.innerHTML += '<option value="qualified">Qualified</option>';
            actionType.innerHTML += '<option value="reschedule">Reschedule</option>';
        } else if (status === 'call_back_required') {
            actionType.innerHTML += '<option value="lost">Lost</option>';
            actionType.innerHTML += '<option value="call_schedule">Call Schedule</option>';
            actionType.innerHTML += '<option value="call_back_required">Call Back Required</option>';
        } else if (status === 'qualified') {
            actionType.innerHTML += '<option value="lost">Lost</option>';
            actionType.innerHTML += '<option value="proposal_sent">Proposal Sent</option>';
        } else if (status === 'proposal_sent') {
            actionType.innerHTML += '<option value="won">Won</option>';
            actionType.innerHTML += '<option value="negotiation">Negotiation</option>';
            actionType.innerHTML += '<option value="lost">Lost</option>';
            actionType.innerHTML += '<option value="on_hold">On Hold</option>';
        } else {
            actionType.innerHTML += '<option value="lost">Lost</option>';
            actionType.innerHTML += '<option value="qualified">Qualified</option>';
            actionType.innerHTML += '<option value="reschedule">Reschedule</option>';
        }
    };

    // ─── actionType change ───────────────────────────────────────────
    document.getElementById('actionType').addEventListener('change', function() {
        let val    = this.value;
        let status = document.getElementById('leadStatus').value;

        document.getElementById('actionDate').style.display           = 'none';
        document.getElementById('actionTime').style.display           = 'none';
        document.getElementById('actionProposal').style.display       = 'none';
        document.getElementById('actionAmount').style.display         = 'none';
        document.getElementById('actionTimeline').style.display       = 'none';
        document.getElementById('actionNegotiationAmt').style.display = 'none';
        document.getElementById('actionRevisedDoc').style.display     = 'none';
        document.getElementById('actionWonForm').style.display        = 'none';
        document.getElementById('tokenFields').style.display          = 'none';
        if (document.getElementById('tokenNo')) document.getElementById('tokenNo').checked = true;

        if (status === 'call_schedule' || status === 'call_back_required') {
            if (val !== 'lost' && val !== '' && val !== 'qualified') {
                document.getElementById('actionDate').style.display = 'block';
                document.getElementById('actionTime').style.display = 'block';
            }
        } else if (status === 'qualified') {
            if (val === 'proposal_sent') {
                document.getElementById('actionProposal').style.display = 'block';
                document.getElementById('actionAmount').style.display   = 'block';
                document.getElementById('actionTimeline').style.display = 'block';
            } else if (val === 'lost') {
                document.getElementById('actionDate').style.display = 'block';
                document.getElementById('actionTime').style.display = 'block';
            }
        } else if (status === 'proposal_sent') {
            if (val === 'won') {
                document.getElementById('actionWonForm').style.display = 'block';
            } else if (val === 'negotiation') {
                document.getElementById('actionNegotiationAmt').style.display = 'block';
                document.getElementById('actionRevisedDoc').style.display     = 'block';
            }
        } else {
            if (val === 'reschedule') {
                document.getElementById('actionDate').style.display = 'block';
                document.getElementById('actionTime').style.display = 'block';
            }
        }
    });

    // ─── Token Fields Toggle ─────────────────────────────────────────
    window.toggleTokenFields = function(show) {
        document.getElementById('tokenFields').style.display = show ? 'block' : 'none';
    };

    // ─── Add Lead Modal reset ────────────────────────────────────────
    document.getElementById('addLeadModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('dateField').style.display = 'none';
        document.getElementById('timeField').style.display = 'none';
        document.getElementById('statusSelect').value      = '';
    });

    document.getElementById('statusSelect').addEventListener('change', function() {
        var show = ['call_back_required', 'call_schedule'].includes(this.value);
        document.getElementById('dateField').style.display = show ? 'block' : 'none';
        document.getElementById('timeField').style.display = show ? 'block' : 'none';
    });

    // ─── History Modal ───────────────────────────────────────────────
    window.loadHistory = function(leadId) {
        document.getElementById('historyContent').innerHTML =
            '<p class="text-muted text-center mt-3">Loading...</p>';

        fetch(`/admin/lms/history/${leadId}`)
            .then(res => res.json())
            .then(data => {
                const history = data.history || [];

                if (!history.length) {
                    document.getElementById('historyContent').innerHTML =
                        '<p class="text-muted text-center mt-3">No history found</p>';
                    return;
                }

                const statusLabel = {
                    'new_lead':           'New Lead',
                    'call_back_required': 'Call Back Required',
                    'call_schedule':      'Call Schedule',
                    'not_responded':      'Not Responded',
                    'not_interested':     'Not Interested',
                    'not_in_scope':       'Not In Scope',
                    'qualified':          'Qualified',
                    'proposal_sent':      'Proposal Sent',
                    'won':                'Won',
                    'lost':               'Lost',
                    'draft':              'Draft',
                    'reschedule':         'Reschedule',
                    'negotiation':        'Negotiation',
                    'on_hold':            'On Hold',
                };

                const statusColor = {
                    'call_back_required': '#ffc107',
                    'call_schedule':      '#0d6efd',
                    'not_responded':      '#6c757d',
                    'not_interested':     '#dc3545',
                    'not_in_scope':       '#dc3545',
                    'qualified':          '#0dcaf0',
                    'proposal_sent':      '#6f42c1',
                    'won':                '#198754',
                    'lost':               '#dc3545',
                    'draft':              '#aaa',
                    'reschedule':         '#17a2b8',
                    'negotiation':        '#fd7e14',
                    'on_hold':            '#ffc107',
                };

                const sectionInfo = {
                    'created':        { icon: '🟢', color: '#17a2b8' },
                    'status_changed': { icon: '🔄', color: '#ffc107' },
                    'edited':         { icon: '✏️', color: '#6f42c1' },
                };

                let html = `
                <div style="position:relative; padding-left:38px; padding-top:6px; padding-bottom:8px;">
                    <div style="position:absolute; left:15px; top:0; bottom:0; width:2px; background:#2a2a2a;"></div>`;

                history.forEach((item, index) => {

                    // ─── edited entries skip karo ───
                    if (item.event_type === 'edited') return;

                    const isLast  = index === history.length - 1;
                    const dotClr  = statusColor[item.to_status] || '#aaa';
                    const sec     = sectionInfo[item.event_type] || { icon: '📌', color: '#aaa' };
                    const toLabel = statusLabel[item.to_status] || item.to_status || '';
                    const toClr   = statusColor[item.to_status] || '#aaa';

                    let sectionName = '';
                    if (item.event_type === 'created') {
                        sectionName = 'New Lead';
                    } else {
                        if (item.to_status === 'qualified')                                         sectionName = 'Qualified';
                        else if (item.to_status === 'proposal_sent')                                sectionName = 'Proposal Sent';
                        else if (item.to_status === 'won')                                          sectionName = 'Won';
                        else if (item.to_status === 'negotiation')                                  sectionName = 'Negotiation';
                        else if (item.to_status === 'on_hold')                                      sectionName = 'On Hold';
                        else if (['lost','not_interested','not_in_scope'].includes(item.to_status)) sectionName = 'Lost';
                        else                                                                         sectionName = 'Follow Up';
                    }

                    let heading = `<span style="color:${sec.color}; font-weight:700; font-size:14px;">${sec.icon} ${sectionName}</span>`;

                    if (item.event_type === 'created') {
                        heading += `<span style="background:${toClr}22; color:${toClr}; font-size:11px;
                            padding:2px 10px; border-radius:20px; margin-left:8px; font-weight:600;">
                            ${toLabel}</span>`;
                    } else {
                        const fromLabel = statusLabel[item.from_status] || item.from_status || '?';
                        heading += `<span style="font-size:11px; color:#777; margin-left:8px;">
                            ${fromLabel}
                            <span style="margin:0 5px; color:#444;">→</span>
                            <span style="color:${toClr}; font-weight:700;">${toLabel}</span>
                        </span>`;
                    }

                    // ─── Fields ───────────────────────────────────────
                    let fields = '';

                    if (item.date) {
                        fields += `
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                            <span style="font-size:16px; width:20px; text-align:center;">📅</span>
                            <span style="color:#f0c040; font-size:12px; font-weight:600; min-width:70px;">Date :</span>
                            <strong style="color:#fff; font-size:13px;">${item.date}</strong>
                        </div>`;
                    }

                    if (item.time) {
                        fields += `
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                            <span style="font-size:16px; width:20px; text-align:center;">🕐</span>
                            <span style="color:#f0c040; font-size:12px; font-weight:600; min-width:70px;">Time :</span>
                            <strong style="color:#fff; font-size:13px;">${item.time}</strong>
                        </div>`;
                    }

                    if (item.negotiation_amount) {
                        fields += `
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                            <span style="font-size:16px; width:20px; text-align:center;">💰</span>
                            <span style="color:#f0c040; font-size:12px; font-weight:600; min-width:70px;">Nego Amt :</span>
                            <strong style="color:#fff; font-size:13px;">₹${item.negotiation_amount}</strong>
                        </div>`;
                    }

                    if (item.comment) {
                        fields += `
                        <div style="display:flex; align-items:flex-start; gap:10px; margin-bottom:8px;">
                            <span style="font-size:16px; width:20px; text-align:center;">💬</span>
                            <span style="color:#f0c040; font-size:12px; font-weight:600; min-width:70px;">Comment :</span>
                            <span style="color:#ddd; font-size:13px; line-height:1.5;">${item.comment}</span>
                        </div>`;
                    }

                    if (item.document) {
                        fields += `
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                            <span style="font-size:16px; width:20px; text-align:center;">📄</span>
                            <span style="color:#f0c040; font-size:12px; font-weight:600; min-width:70px;">Proposal :</span>
                            <a href="${item.document}" target="_blank"
                               style="color:#6f42c1; font-size:13px; text-decoration:none; font-weight:600;">
                               View Document ↗</a>
                        </div>`;
                    }

                    if (item.revised_document) {
                        fields += `
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                            <span style="font-size:16px; width:20px; text-align:center;">📋</span>
                            <span style="color:#f0c040; font-size:12px; font-weight:600; min-width:70px;">Revised Doc :</span>
                            <a href="${item.revised_document}" target="_blank"
                               style="color:#fd7e14; font-size:13px; text-decoration:none; font-weight:600;">
                               View Document ↗</a>
                        </div>`;
                    }

                    // ─── created_at HAR ENTRY MEIN - SABSE NEECHE ────
                    fields += `
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:4px; margin-top:4px;">
                        <span style="font-size:14px; width:20px; text-align:center;">🕒</span>
                        <span style="color:#666; font-size:11px; font-weight:600; min-width:70px;">On :</span>
                        <strong style="color:#888; font-size:11px;">${item.created_at}</strong>
                    </div>`;

                    html += `
                    <div style="position:relative; margin-bottom:${isLast ? '4px' : '22px'};">
                        <div style="position:absolute; left:-30px; top:13px;
                            width:14px; height:14px; border-radius:50%;
                            background:${dotClr}; border:2px solid #0d0d0d;
                            box-shadow:0 0 0 3px ${dotClr}33; z-index:1;"></div>
                        <div style="background:#1a1a1a; border:1px solid #2a2a2a;
                            border-left:4px solid ${sec.color}; border-radius:8px; padding:13px 16px;">
                            <div style="display:flex; align-items:center; flex-wrap:wrap;
                                gap:4px; margin-bottom:12px;">
                                ${heading}
                            </div>
                            <div style="border-top:1px solid #2a2a2a; padding-top:12px;">${fields}</div>
                        </div>
                    </div>`;
                });

                html += `</div>`;
                document.getElementById('historyContent').innerHTML = html;
            })
            .catch(err => {
                console.error(err);
                document.getElementById('historyContent').innerHTML =
                    '<p class="text-danger text-center mt-3">Error loading history. Please try again.</p>';
            });
    };

}); // closes DOMContentLoaded
</script>
@endpush