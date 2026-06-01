@extends('layouts.admin')
@section('page-title', 'Lead Detail')
@section('content')

<div class="d-flex justify-content-between mb-4">
    <a href="{{ route('admin.lms.index') }}"
       style="color:#f0c040; text-decoration:none; font-size:1.1rem;">← Back</a>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#historyModal">
            📋 View History
        </button>
        <a href="{{ route('admin.lms.edit', $lm->id) }}" class="btn btn-gold">✏️ Edit Lead</a>
    </div>
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
                    @elseif($lm->status == 'not_interested') bg-danger
                    @elseif($lm->status == 'not_in_scope') bg-danger
                    @elseif($lm->status == 'call_schedule') bg-primary
                    @elseif($lm->status == 'call_back_required') bg-warning text-dark
                    @elseif($lm->status == 'not_responded') bg-secondary
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
        @if($lm->status === 'won')
        <div class="col-md-4">
            <small style="color:#f0c040">Project Type</small>
            <p class="text-white mb-0">
    @if($lm->won_project_type == 'erp') ERP
    @elseif($lm->won_project_type == 'mobile_app') Mobile App
    @elseif($lm->won_project_type == 'crm') CRM
    @else {{ $lm->won_project_type ? ucwords(str_replace('_', ' ', $lm->won_project_type)) : '-' }}
    @endif
</p>
        </div>
        @endif
        @if($lm->document)
        <div class="col-md-4">
            <small style="color:#f0c040">Proposal Document</small>
            <p class="mb-0">
                <a href="{{ asset('storage/'.$lm->document) }}"
                   target="_blank" class="btn btn-sm btn-outline-warning mt-1">
                    📄 View Document
                </a>
            </p>
        </div>
        @endif
        <div class="col-md-4">
            <small style="color:#f0c040">Add Date</small>
            <p class="text-white mb-0">{{ $lm->created_at ? $lm->created_at->format('d-m-Y') : '-' }}</p>
        </div>
    </div>
</div>

{{-- History Modal --}}
<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" style="color:#f0c040">📋 Lead History</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                @php
                    $statusLabel = [
                        'new_lead'           => 'New Lead',
                        'call_back_required' => 'Call Back Required',
                        'call_schedule'      => 'Call Schedule',
                        'not_responded'      => 'Not Responded',
                        'not_interested'     => 'Not Interested',
                        'not_in_scope'       => 'Not In Scope',
                        'qualified'          => 'Qualified',
                        'proposal_sent'      => 'Proposal Sent',
                        'won'                => 'Won',
                        'lost'               => 'Lost',
                        'draft'              => 'Draft',
                        'reschedule'         => 'Reschedule',
                    ];
                    $statusColor = [
                        'call_back_required' => '#ffc107',
                        'call_schedule'      => '#0d6efd',
                        'not_responded'      => '#6c757d',
                        'not_interested'     => '#dc3545',
                        'not_in_scope'       => '#dc3545',
                        'qualified'          => '#0dcaf0',
                        'proposal_sent'      => '#6f42c1',
                        'won'                => '#198754',
                        'lost'               => '#dc3545',
                        'draft'              => '#aaa',
                        'reschedule'         => '#17a2b8',
                    ];
                    $histories = $lm->histories()->orderBy('created_at','asc')->get();
                @endphp

                @forelse($histories as $h)
                @php
                    $dotClr = $statusColor[$h->to_status] ?? '#aaa';

                    if ($h->event_type === 'created') {
                        $icon     = '🟢';
                        $secLabel = 'New Lead';
                        $secColor = '#17a2b8';
                    } elseif ($h->event_type === 'edited') {
                        $icon     = '✏️';
                        $secLabel = 'Lead Edited';
                        $secColor = '#6f42c1';
                    } else {
                        $icon     = '🔄';
                        $secColor = '#ffc107';
                        if ($h->to_status === 'qualified')                                                $secLabel = 'Qualified';
                        elseif ($h->to_status === 'proposal_sent')                                        $secLabel = 'Proposal Sent';
                        elseif ($h->to_status === 'won')                                                  $secLabel = 'Won';
                        elseif (in_array($h->to_status, ['lost','not_interested','not_in_scope']))        $secLabel = 'Lost';
                        else                                                                               $secLabel = 'Follow Up';
                    }
                @endphp

                <div style="position:relative; padding-left:36px; margin-bottom:20px;">
                    {{-- vertical line --}}
                    <div style="position:absolute; left:14px; top:0; bottom:-20px; width:2px; background:#2a2a2a;"></div>
                    {{-- dot --}}
                    <div style="position:absolute; left:7px; top:12px;
                        width:14px; height:14px; border-radius:50%;
                        background:{{ $dotClr }}; border:2px solid #121212;
                        box-shadow:0 0 0 3px {{ $dotClr }}44; z-index:1;"></div>

                    <div style="background:#1a1a1a; border:1px solid #2a2a2a;
                        border-left:4px solid {{ $secColor }}; border-radius:8px; padding:12px 16px;">

                        {{-- Heading --}}
                        <div style="display:flex; align-items:center; flex-wrap:wrap; gap:6px; margin-bottom:{{ ($h->date || $h->time || $h->comment || $h->document) ? '12px' : '0' }};">
                            <span style="color:{{ $secColor }}; font-weight:700; font-size:14px;">
                                {{ $icon }} {{ $secLabel }}
                            </span>
                            @if($h->event_type === 'created')
                                <span style="background:{{ $dotClr }}22; color:{{ $dotClr }};
                                    font-size:11px; padding:2px 10px; border-radius:20px; font-weight:600;">
                                    {{ $statusLabel[$h->to_status] ?? $h->to_status }}
                                </span>
                            @else
                                <span style="font-size:11px; color:#888;">
                                    {{ $statusLabel[$h->from_status] ?? $h->from_status }}
                                    <span style="margin:0 4px; color:#555;">→</span>
                                    <span style="color:{{ $statusColor[$h->to_status] ?? '#aaa' }}; font-weight:700;">
                                        {{ $statusLabel[$h->to_status] ?? $h->to_status }}
                                    </span>
                                </span>
                            @endif
                        </div>

                        {{-- Fields --}}
                        @if($h->date || $h->time || $h->comment || $h->document)
                        <div style="border-top:1px solid #2a2a2a; padding-top:10px;">
                            @if($h->date)
                            <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                                <span style="font-size:15px; width:20px; text-align:center;">📅</span>
                                <span style="color:#f0c040; font-size:12px; font-weight:600; min-width:70px;">Date :</span>
                                <strong style="color:#fff; font-size:13px;">
                                    {{ \Carbon\Carbon::parse($h->date)->format('d-m-Y') }}
                                </strong>
                            </div>
                            @endif
                            @if($h->time)
                            <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                                <span style="font-size:15px; width:20px; text-align:center;">🕐</span>
                                <span style="color:#f0c040; font-size:12px; font-weight:600; min-width:70px;">Time :</span>
                                <strong style="color:#fff; font-size:13px;">
                                    {{ \Carbon\Carbon::parse($h->time)->format('h:i A') }}
                                </strong>
                            </div>
                            @endif
                            @if($h->comment)
                            <div style="display:flex; align-items:flex-start; gap:10px; margin-bottom:8px;">
                                <span style="font-size:15px; width:20px; text-align:center;">💬</span>
                                <span style="color:#f0c040; font-size:12px; font-weight:600; min-width:70px;">Comment :</span>
                                <span style="color:#ddd; font-size:13px; line-height:1.5;">{{ $h->comment }}</span>
                            </div>
                            @endif
                            @if($h->document)
                            <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                                <span style="font-size:15px; width:20px; text-align:center;">📄</span>
                                <span style="color:#f0c040; font-size:12px; font-weight:600; min-width:70px;">Proposal :</span>
                                <a href="{{ asset('storage/'.$h->document) }}" target="_blank"
                                   style="color:#6f42c1; font-size:13px; text-decoration:none; font-weight:600;">
                                   View Document ↗</a>
                            </div>
                            @endif
                        </div>
                        @endif

                    </div>
                </div>
                @empty
                    <p class="text-muted text-center mt-3">No history found.</p>
                @endforelse
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection