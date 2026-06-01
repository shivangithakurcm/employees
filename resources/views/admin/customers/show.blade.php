@extends('layouts.admin')
@section('page-title', 'Customer Detail')
@section('content')

<style>
.detail-label { color: #f0c040; font-size: 12px; font-weight: 600; margin-bottom: 2px; }
.detail-value { color: #fff; font-size: 14px; }
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
.customer-photo {
    width: 100px; height: 100px; border-radius: 50%;
    background: linear-gradient(135deg, #f0c040, #e0a800);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 36px; color: #000;
    border: 3px solid #f0c040;
}
.status-timeline {
    position: relative; padding-left: 30px;
}
.status-timeline::before {
    content: ''; position: absolute; left: 10px; top: 0; bottom: 0;
    width: 2px; background: #2a2a2a;
}
</style>

{{-- Back Button --}}
<div class="mb-4">
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-warning btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back to Customer List
    </a>
</div>

<div class="row g-4">

    {{-- LEFT: Customer Detail --}}
    <div class="col-md-4">

        {{-- Photo + Name Card --}}
        <div class="section-card text-center" style="border-left-color: #f0c040;">
            {{-- Photo --}}
            <div class="d-flex justify-content-center mb-3">
                @if($customer->photo ?? false)
                    <img src="{{ asset('storage/' . $customer->photo) }}"
                         alt="Customer Photo"
                         style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:3px solid #f0c040;">
                @else
                    <div class="customer-photo">
                        {{ strtoupper(substr($customer->won_name ?? $customer->first_name ?? 'C', 0, 1)) }}
                    </div>
                @endif
            </div>

            <h5 class="text-warning fw-bold mb-1">
                {{ $customer->won_name ?? ($customer->first_name . ' ' . $customer->last_name) }}
            </h5>
            <div class="text-muted small mb-2">{{ $customer->won_designation ?? 'Client' }}</div>
            <span class="badge bg-success">Won Customer</span>
        </div>

        {{-- Contact Info --}}
        <div class="section-card">
            <div class="section-title">📞 Contact Details</div>
            <div class="row g-3">
                <div class="col-12">
                    <div class="detail-label">Name</div>
                    <div class="detail-value">{{ $customer->won_name ?? ($customer->first_name . ' ' . $customer->last_name) }}</div>
                </div>
                <div class="col-12">
                    <div class="detail-label">Contact Number</div>
                    <div class="detail-value">
                        <a href="tel:{{ $customer->won_contact ?? $customer->contact_number }}"
                           class="text-warning text-decoration-none">
                            <i class="fas fa-phone me-1"></i>
                            {{ $customer->won_contact ?? $customer->contact_number ?? '-' }}
                        </a>
                    </div>
                </div>
                <div class="col-12">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">
                        <a href="mailto:{{ $customer->won_email ?? $customer->email }}"
                           class="text-warning text-decoration-none">
                            <i class="fas fa-envelope me-1"></i>
                            {{ $customer->won_email ?? $customer->email ?? '-' }}
                        </a>
                    </div>
                </div>
                <div class="col-12">
                    <div class="detail-label">Address</div>
                    <div class="detail-value">{{ $customer->won_location ?? '-' }}</div>
                </div>
                <div class="col-6">
                    <div class="detail-label">City</div>
                    <div class="detail-value">{{ $customer->won_city ?? $customer->city ?? '-' }}</div>
                </div>
                <div class="col-6">
                    <div class="detail-label">State</div>
                    <div class="detail-value">{{ $customer->won_state ?? $customer->state ?? '-' }}</div>
                </div>
                <div class="col-12">
                    <div class="detail-label">Country</div>
                    <div class="detail-value">{{ $customer->won_country ?? $customer->country ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Other Details --}}
        <div class="section-card">
            <div class="section-title">🏢 Other Details</div>
            <div class="row g-3">
                <div class="col-12">
                    <div class="detail-label">Business Name</div>
                    <div class="detail-value">{{ $customer->won_business_name ?? '-' }}</div>
                </div>
                <div class="col-12">
                    <div class="detail-label">GST Number</div>
                    <div class="detail-value">{{ $customer->won_gst_no ?? '-' }}</div>
                </div>
                <div class="col-12">
                    <div class="detail-label">Designation</div>
                    <div class="detail-value">{{ $customer->won_designation ?? '-' }}</div>
                </div>
                <div class="col-12">
                    <div class="detail-label">Onboarded Date</div>
                    <div class="detail-value">
                        {{ $customer->updated_at ? $customer->updated_at->format('d M, Y') : '-' }}
                    </div>
                </div>
                <div class="col-12">
                    <div class="detail-label">Onboarded By</div>
                    <div class="detail-value">{{ $customer->onboarded_by ?? auth()->user()->name ?? '-' }}</div>
                </div>
                @if($customer->project_status ?? false)
                <div class="col-12">
                    <div class="detail-label">Project Status</div>
                    <div class="detail-value">
                        <span class="badge
                            @if($customer->project_status == 'Active') bg-success
                            @elseif($customer->project_status == 'Completed') bg-info text-dark
                            @elseif($customer->project_status == 'On Hold') bg-warning text-dark
                            @else bg-danger
                            @endif">
                            {{ $customer->project_status }}
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- RIGHT: Project Details --}}
    <div class="col-md-8">

        {{-- Project Overview --}}
        <div class="section-card">
            <div class="section-title">🏗️ Project Details</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="detail-label">Project Detail</div>
                    <div class="detail-value" style="white-space: pre-wrap; line-height: 1.6;">
                        {{ $customer->won_project_detail ?? '-' }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-label">Project Type / Requirement</div>
                    <div class="detail-value">
                        <span class="badge bg-info text-dark fs-6">
    {{ $customer->wonProjectType->name ?? '-' }}
</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="detail-label">Final Project Cost</div>
                    <div class="detail-value text-warning fw-bold fs-5">
                        {{ $customer->won_final_cost ? '₹' . number_format($customer->won_final_cost, 2) : '-' }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="detail-label">Timeline</div>
                    <div class="detail-value">{{ $customer->won_timeline ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="detail-label">Milestone</div>
                    <div class="detail-value">{{ $customer->won_milestone ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Token Payment --}}
        <div class="section-card" style="border-left-color: #198754;">
            <div class="section-title">💰 Token / Payment</div>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="detail-label">Token Received?</div>
                    <div class="detail-value">
                        @if($customer->won_token_received === 'yes')
                            <span class="badge bg-success fs-6">✔ Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </div>
                </div>
                @if($customer->won_token_received === 'yes')
                <div class="col-md-4">
                    <div class="detail-label">Token Amount</div>
                    <div class="detail-value text-success fw-bold">
                        {{ $customer->won_token_amount ? '₹' . number_format($customer->won_token_amount, 2) : '-' }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="detail-label">GST Type</div>
                    <div class="detail-value">
                        {{ $customer->won_amount_type ? ucwords(str_replace('_', ' ', $customer->won_amount_type)) : '-' }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="detail-label">Received Date</div>
                    <div class="detail-value">
                        {{ $customer->won_received_date ? \Carbon\Carbon::parse($customer->won_received_date)->format('d M, Y') : '-' }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Lead Journey / History --}}
        <div class="section-card" style="border-left-color: #17a2b8;">
            <div class="section-title">📋 Lead Journey</div>
            <div class="status-timeline" id="customerHistory">
                <p class="text-muted">
                    <a href="javascript:void(0)" onclick="loadCustomerHistory({{ $customer->id }})"
                       class="text-warning">
                        <i class="fas fa-history me-1"></i>Click to load history
                    </a>
                </p>
            </div>
        </div>

    </div>
</div>

{{-- Action Buttons --}}
<div class="d-flex gap-2 mt-2">
    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-warning">
        <i class="fas fa-pen me-1"></i> Edit Customer
    </a>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-list me-1"></i> All Customers
    </a>
</div>

@endsection

@push('scripts')
<script>
function loadCustomerHistory(leadId) {
    document.getElementById('customerHistory').innerHTML =
        '<p class="text-muted text-center">Loading...</p>';

    fetch(`/admin/lms/history/${leadId}`)
        .then(res => res.json())
        .then(data => {
            const history = data.history || [];
            if (!history.length) {
                document.getElementById('customerHistory').innerHTML =
                    '<p class="text-muted">No history found</p>';
                return;
            }

            const statusColor = {
                'call_back_required': '#ffc107', 'call_schedule': '#0d6efd',
                'not_responded': '#6c757d', 'qualified': '#0dcaf0',
                'proposal_sent': '#6f42c1', 'won': '#198754',
                'lost': '#dc3545', 'negotiation': '#fd7e14',
            };
            const statusLabel = {
                'new_lead': 'New Lead', 'call_back_required': 'Call Back Required',
                'call_schedule': 'Call Schedule', 'not_responded': 'Not Responded',
                'qualified': 'Qualified', 'proposal_sent': 'Proposal Sent',
                'won': 'Won', 'lost': 'Lost', 'negotiation': 'Negotiation',
                'on_hold': 'On Hold', 'reschedule': 'Reschedule',
            };

            let html = '';
            history.forEach(item => {
                if (item.event_type === 'edited') return;
                const clr = statusColor[item.to_status] || '#aaa';
                const lbl = statusLabel[item.to_status] || item.to_status || '';

                html += `
                <div style="position:relative; margin-bottom:18px;">
                    <div style="position:absolute; left:-22px; top:8px;
                        width:12px; height:12px; border-radius:50%;
                        background:${clr}; border:2px solid #0d0d0d; z-index:1;"></div>
                    <div style="background:#111; border:1px solid #2a2a2a; border-radius:6px; padding:10px 14px;">
                        <div style="color:${clr}; font-weight:700; font-size:13px;">${lbl}</div>
                        ${item.comment ? `<div style="color:#ccc; font-size:12px; margin-top:4px;">💬 ${item.comment}</div>` : ''}
                        <div style="color:#666; font-size:11px; margin-top:4px;">🕒 ${item.created_at}</div>
                    </div>
                </div>`;
            });

            document.getElementById('customerHistory').innerHTML = html;
        })
        .catch(() => {
            document.getElementById('customerHistory').innerHTML =
                '<p class="text-danger">Error loading history.</p>';
        });
}
</script>
@endpush