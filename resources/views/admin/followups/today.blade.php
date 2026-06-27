@extends('layouts.admin')
@section('page-title', 'Follow-ups')

@section('content')

<h4 class="mb-4" style="color:#f0c040;">
    <i class="fas fa-bell me-2"></i>Follow-ups
</h4>

{{-- Summary Strip --}}
<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="stat-card" style="border-left:3px solid #EF9F27;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                <div style="width:38px;height:38px;background:#2a1f0a;border-radius:8px;
                            display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-calendar-check" style="color:#EF9F27;font-size:16px;"></i>
                </div>
                <span style="font-size:11px;padding:2px 8px;border-radius:99px;
                             background:#1a3322;color:#4ade80;font-weight:600;">Today</span>
            </div>
            <h3 style="color:#f0c040;font-size:2rem;margin:0;font-weight:bold;">
                {{ $todayList->count() }}
            </h3>
            <p style="color:#aaa;margin:5px 0 0;font-size:13px;">Today's Follow-ups</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card" style="border-left:3px solid #E24B4A;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                <div style="width:38px;height:38px;background:#2d1515;border-radius:8px;
                            display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-exclamation-circle" style="color:#E24B4A;font-size:16px;"></i>
                </div>
                <span style="font-size:11px;padding:2px 8px;border-radius:99px;
                             background:#2d1515;color:#f87171;font-weight:600;">Overdue</span>
            </div>
            <h3 style="color:#f0c040;font-size:2rem;margin:0;font-weight:bold;">
                {{ $overdueList->count() }}
            </h3>
            <p style="color:#aaa;margin:5px 0 0;font-size:13px;">Overdue Follow-ups</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card" style="border-left:3px solid #1D9E75;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                <div style="width:38px;height:38px;background:#0f2a1a;border-radius:8px;
                            display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-check-circle" style="color:#1D9E75;font-size:16px;"></i>
                </div>
                <span style="font-size:11px;padding:2px 8px;border-radius:99px;
                             background:#1a3322;color:#4ade80;font-weight:600;">Done</span>
            </div>
            <h3 style="color:#f0c040;font-size:2rem;margin:0;font-weight:bold;">
                {{ $doneToday }}
            </h3>
            <p style="color:#aaa;margin:5px 0 0;font-size:13px;">Completed Today</p>
        </div>
    </div>

</div>

{{-- Employee Stats Collapse --}}
@if(auth()->user()->hasRole('admin') && $employees->count())
<div class="mb-4">
    <button onclick="toggleEmpStats()" id="empStatsBtn"
        style="background:#1a1a1a; border:1px solid #f0c040; color:#f0c040;
               border-radius:8px; padding:7px 18px; font-size:13px; cursor:pointer;
               display:flex; align-items:center; gap:8px;">
        <i class="fas fa-users"></i>
        <span>Show Employee Status</span>
        <i class="fas fa-chevron-down" id="empStatsChevron"></i>
    </button>

    <div id="empStatsBox" style="display:none; margin-top:12px;">
        <div class="card-dark" style="padding:0; overflow:hidden;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid #2a2a2a; background:#111;">
                        <th style="color:#888; font-size:12px; padding:10px 14px; text-align:left;">Employee</th>
                        <th style="color:#888; font-size:12px; padding:10px 14px; text-align:center;">Total</th>
                        <th style="color:#888; font-size:12px; padding:10px 14px; text-align:center;">Follow Up</th>
                        <th style="color:#888; font-size:12px; padding:10px 14px; text-align:center;">Won</th>
                        <th style="color:#888; font-size:12px; padding:10px 14px; text-align:center;">Lost</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $emp)
                    <tr style="border-bottom:1px solid #1a1a1a; transition:background 0.2s;"
                        onmouseover="this.style.background='#1a1a1a'"
                        onmouseout="this.style.background='transparent'">
                        <td style="padding:10px 14px;">
                            <span style="color:#f0c040; font-size:13px; font-weight:600;">
                                {{ $emp->name }}
                            </span>
                        </td>
                        <td style="padding:10px 14px; text-align:center;">
                            <span style="color:#fff; font-size:14px; font-weight:700;">
                                {{ $emp->total_leads }}
                            </span>
                        </td>
                        <td style="padding:10px 14px; text-align:center;">
                            <span style="background:#0d1b3e; color:#60a5fa; font-size:12px;
                                         padding:2px 12px; border-radius:99px; font-weight:600;">
                                {{ $emp->followup_leads }}
                            </span>
                        </td>
                        <td style="padding:10px 14px; text-align:center;">
                            <span style="background:#0f2a1a; color:#4ade80; font-size:12px;
                                         padding:2px 12px; border-radius:99px; font-weight:600;">
                                {{ $emp->won_leads }}
                            </span>
                        </td>
                        <td style="padding:10px 14px; text-align:center;">
                            <span style="background:#2d1515; color:#f87171; font-size:12px;
                                         padding:2px 12px; border-radius:99px; font-weight:600;">
                                {{ $emp->lost_leads }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- Overdue Table --}}
@if($overdueList->count() > 0)
<div class="card-dark mb-4">
    <p style="color:#E24B4A;font-size:13px;font-weight:600;margin:0 0 16px;">
        <i class="fas fa-exclamation-circle me-2"></i>Overdue Follow-ups
    </p>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:1px solid #2a2a2a;">
                    <th style="color:#888;font-size:12px;font-weight:500;padding:8px 12px;text-align:left;">SNO.</th>
                    <th style="color:#888;font-size:12px;font-weight:500;padding:8px 12px;text-align:left;">Lead</th>
                    <th style="color:#888;font-size:12px;font-weight:500;padding:8px 12px;text-align:left;">Due Date</th>
                    <th style="color:#888;font-size:12px;font-weight:500;padding:8px 12px;text-align:left;">Time</th>
                    <th style="color:#888;font-size:12px;font-weight:500;padding:8px 12px;text-align:left;">Comment</th>
                    <th style="color:#888;font-size:12px;font-weight:500;padding:8px 12px;text-align:left;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($overdueList as $i => $fu)
                <tr style="border-bottom:1px solid #1a1a1a;transition:background 0.2s;"
                    onmouseover="this.style.background='#1a1a1a'"
                    onmouseout="this.style.background='transparent'">
                    <td style="color:#555;font-size:12px;padding:10px 12px;">{{ $i+1 }}</td>
                    <td style="padding:10px 12px;">
                       <p style="color:#fff;font-size:13px;margin:0;font-weight:500;">
    {{ trim(($fu->lead->first_name ?? '') . ' ' . ($fu->lead->last_name ?? '')) ?: '—' }}
</p>
<p style="color:#888;font-size:11px;margin:0;">
    {{ $fu->lead->email ?? $fu->lead->contact_number ?? '' }}
</p>
                    </td>
                    <td style="padding:10px 12px;">
                        <span style="color:#E24B4A;font-size:12px;">
                            <i class="fas fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($fu->date)->format('d M Y') }}
                        </span>
                    </td>
                    <td style="color:#aaa;font-size:12px;padding:10px 12px;">
                        {{ \Carbon\Carbon::parse($fu->time)->format('h:i A') }}
                    </td>
                    <td style="color:#aaa;font-size:12px;padding:10px 12px;max-width:180px;">
                        {{ $fu->comment ?? '—' }}
                    </td>
                    <td style="padding:10px 12px;">
                        <form action="{{ route('admin.followups.done', $fu->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    style="background:#1a3322;border:1px solid #1D9E75;color:#4ade80;
                                           border-radius:6px;padding:4px 12px;font-size:11px;cursor:pointer;">
                                <i class="fas fa-check me-1"></i>Done
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Today's Table --}}
<div class="card-dark">
    <p style="color:#f0c040;font-size:13px;font-weight:600;margin:0 0 16px;">
        <i class="fas fa-calendar-check me-2"></i>Today's Follow-ups
        <span style="color:#888;font-weight:400;font-size:12px;margin-left:8px;">
            {{ \Carbon\Carbon::today()->format('d M Y') }}
        </span>
    </p>

    @if($todayList->count() === 0)
        <div style="text-align:center;padding:40px 0;">
            <i class="fas fa-check-circle" style="color:#1D9E75;font-size:2.5rem;"></i>
            <p style="color:#aaa;margin:12px 0 0;font-size:14px;">No follow-ups for today!</p>
        </div>
    @else
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:1px solid #2a2a2a;">
                    <th style="color:#888;font-size:12px;font-weight:500;padding:8px 12px;text-align:left;">SNO.</th>
                    <th style="color:#888;font-size:12px;font-weight:500;padding:8px 12px;text-align:left;">Lead</th>
                    <th style="color:#888;font-size:12px;font-weight:500;padding:8px 12px;text-align:left;">Time</th>
                    <th style="color:#888;font-size:12px;font-weight:500;padding:8px 12px;text-align:left;">Comment</th>
                    <th style="color:#888;font-size:12px;font-weight:500;padding:8px 12px;text-align:left;">Status</th>
                    <th style="color:#888;font-size:12px;font-weight:500;padding:8px 12px;text-align:left;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($todayList as $i => $fu)
                <tr style="border-bottom:1px solid #1a1a1a;transition:background 0.2s;"
                    onmouseover="this.style.background='#1a1a1a'"
                    onmouseout="this.style.background='transparent'">
                    <td style="color:#555;font-size:12px;padding:10px 12px;">{{ $i+1 }}</td>
                    <td style="padding:10px 12px;">
                        <p style="color:#fff;font-size:13px;margin:0;font-weight:500;">
                            {{ $fu->lead->name ?? '—' }}
                        </p>
                        <p style="color:#888;font-size:11px;margin:0;">
                            {{ $fu->lead->email ?? '' }}
                        </p>
                    </td>
                    <td style="padding:10px 12px;">
                        <span style="color:#EF9F27;font-size:12px;">
                            <i class="fas fa-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($fu->time)->format('h:i A') }}
                        </span>
                    </td>
                    <td style="color:#aaa;font-size:12px;padding:10px 12px;max-width:180px;">
                        {{ $fu->comment ?? '—' }}
                    </td>
                    <td style="padding:10px 12px;">
                        <span style="font-size:11px;padding:2px 10px;border-radius:99px;font-weight:600;
                            background:#2a1f0a;color:#EF9F27;">
                            Pending
                        </span>
                    </td>
                    <td style="padding:10px 12px;">
                        <form action="{{ route('admin.followups.done', $fu->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    style="background:#1a3322;border:1px solid #1D9E75;color:#4ade80;
                                           border-radius:6px;padding:4px 12px;font-size:11px;cursor:pointer;">
                                <i class="fas fa-check me-1"></i>Done
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function toggleEmpStats() {
    var box      = document.getElementById('empStatsBox');
    var chevron  = document.getElementById('empStatsChevron');
    var btnSpan  = document.querySelector('#empStatsBtn span');
    var isHidden = box.style.display === 'none';

    box.style.display     = isHidden ? 'block' : 'none';
    btnSpan.textContent   = isHidden ? 'Hide Employee Stats' : 'Show Employee Stats';
    chevron.className     = isHidden ? 'fas fa-chevron-up' : 'fas fa-chevron-down';
}
</script>
@endpush