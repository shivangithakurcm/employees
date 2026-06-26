@extends('layouts.admin')
@section('page-title', 'Dashboard')

@section('content')

<h4 class="mb-4" style="color:#f5a623; font-family:'Sora',sans-serif; font-weight:700;">
    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
</h4>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">

    @php
        $cards = [
            ['label'=>'Total Employees', 'key'=>'total_employees', 'icon'=>'fas fa-users',       'bg'=>'#15203a','ic'=>'#5b9bf5', 'badge'=>'+4%',  'up'=>true],
            ['label'=>'Total Leads',     'key'=>'total_leads',     'icon'=>'fas fa-crosshairs',  'bg'=>'#241a3a','ic'=>'#a78bfa', 'badge'=>'+12%', 'up'=>true],
            ['label'=>'Total Qualified', 'key'=>'total_qualified', 'icon'=>'fas fa-check-circle','bg'=>'#0f2a24','ic'=>'#2dd4bf', 'badge'=>'+8%',  'up'=>true],
            ['label'=>'Total Prospect',  'key'=>'total_prospect',  'icon'=>'fas fa-eye',         'bg'=>'#2a200a','ic'=>'#f5a623', 'badge'=>'+5%',  'up'=>true],
            ['label'=>'Total Lost',      'key'=>'total_lost',      'icon'=>'fas fa-times-circle','bg'=>'#2d1515','ic'=>'#e24b4a', 'badge'=>'-3%',  'up'=>false],
            ['label'=>'Total Won',       'key'=>'total_won',       'icon'=>'fas fa-trophy',      'bg'=>'#0a2a1a','ic'=>'#1d9e75', 'badge'=>'+18%', 'up'=>true],
        ];
    @endphp

    @foreach($cards as $c)
    <div class="col-md-4 col-6">
        <div class="stat-card" style="border-left: 3px solid {{ $c['ic'] }}; text-align:left;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                <div style="width:38px;height:38px;background:{{ $c['bg'] }};border-radius:8px;
                            display:flex;align-items:center;justify-content:center;">
                    <i class="{{ $c['icon'] }}" style="color:{{ $c['ic'] }};font-size:16px;"></i>
                </div>
                <span style="font-size:11px;padding:2px 8px;border-radius:99px;font-weight:600;
                    font-family:'JetBrains Mono',monospace;
                    background:{{ $c['up'] ? 'rgba(45,212,191,0.12)' : 'rgba(226,75,74,0.12)' }};
                    color:{{ $c['up'] ? '#2dd4bf' : '#f87171' }};">
                    <i class="fas fa-arrow-{{ $c['up'] ? 'up' : 'down' }}" style="font-size:9px;"></i>
                    {{ $c['badge'] }}
                </span>
            </div>
            <h3>{{ $stats[$c['key']] }}</h3>
            <p>{{ $c['label'] }}</p>
        </div>
    </div>
    @endforeach

</div>

{{-- Bottom Row: Pipeline + Donut + Followups --}}
<div class="row g-3">

    {{-- Pipeline Bars --}}
    <div class="col-md-4">
        <div class="card-dark">
            <p style="color:#f5a623;font-family:'Sora',sans-serif;font-size:13px;font-weight:600;margin:0 0 16px;">
                <i class="fas fa-chart-bar me-2"></i>Lead Pipeline
            </p>
            @php
                $mx = max($stats['total_leads'], 1);
                $bars = [
                    ['label'=>'Leads',     'val'=>$stats['total_leads'],     'color'=>'#a78bfa'],
                    ['label'=>'Qualified', 'val'=>$stats['total_qualified'], 'color'=>'#2dd4bf'],
                    ['label'=>'Prospect',  'val'=>$stats['total_prospect'],  'color'=>'#f5a623'],
                    ['label'=>'Won',       'val'=>$stats['total_won'],       'color'=>'#1d9e75'],
                    ['label'=>'Lost',      'val'=>$stats['total_lost'],      'color'=>'#e24b4a'],
                ];
            @endphp
            @foreach($bars as $b)
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <span style="color:#8d93b0;font-size:12px;width:68px;flex-shrink:0;font-family:'JetBrains Mono',monospace;">{{ $b['label'] }}</span>
                <div style="flex:1;height:7px;background:#1d2340;border-radius:99px;overflow:hidden;">
                    <div style="width:{{ round($b['val']/$mx*100) }}%;height:100%;
                                background:{{ $b['color'] }};border-radius:99px;"></div>
                </div>
                <span style="color:#ccc;font-size:12px;font-weight:600;width:24px;text-align:right;">
                    {{ $b['val'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Donut Chart (SVG) --}}
    <div class="col-md-4">
        <div class="card-dark">
            <p style="color:#f5a623;font-family:'Sora',sans-serif;font-size:13px;font-weight:600;margin:0 0 16px;">
                <i class="fas fa-chart-pie me-2"></i>Status Breakdown
            </p>
            @php
                $tot  = $stats['total_qualified'] + $stats['total_prospect']
                      + $stats['total_won']       + $stats['total_lost'];
                $circ = 2 * M_PI * 36;
                $segs = [
                    ['val'=>$stats['total_qualified'], 'color'=>'#2dd4bf', 'label'=>'Qualified'],
                    ['val'=>$stats['total_prospect'],  'color'=>'#f5a623', 'label'=>'Prospect'],
                    ['val'=>$stats['total_won'],       'color'=>'#a78bfa', 'label'=>'Won'],
                    ['val'=>$stats['total_lost'],      'color'=>'#e24b4a', 'label'=>'Lost'],
                ];
                $offset = 0;
            @endphp
            <div style="display:flex;align-items:center;gap:20px;">
                <svg width="110" height="110" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="36" fill="none" stroke="#1d2340" stroke-width="16"/>
                    @foreach($segs as $seg)
                        @php
                            $arc  = ($tot > 0) ? ($seg['val'] / $tot) * $circ : 0;
                            $dash = $arc . ' ' . ($circ - $arc);
                        @endphp
                        <circle cx="50" cy="50" r="36" fill="none"
                            stroke="{{ $seg['color'] }}" stroke-width="16"
                            stroke-dasharray="{{ $dash }}"
                            stroke-dashoffset="{{ -$offset }}"
                            transform="rotate(-90 50 50)"/>
                        @php $offset += $arc; @endphp
                    @endforeach
                    <text x="50" y="46" text-anchor="middle"
                          font-size="14" font-weight="bold" fill="#f5a623" font-family="Sora, sans-serif">
                        {{ $stats['total_leads'] }}
                    </text>
                    <text x="50" y="60" text-anchor="middle" font-size="9" fill="#8d93b0">leads</text>
                </svg>
                <div style="display:flex;flex-direction:column;gap:10px;flex:1;">
                    @foreach($segs as $seg)
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:9px;height:9px;border-radius:50%;
                                    background:{{ $seg['color'] }};flex-shrink:0;"></div>
                        <span style="color:#8d93b0;font-size:12px;flex:1;">{{ $seg['label'] }}</span>
                        <span style="color:#ccc;font-size:12px;font-weight:600;">{{ $seg['val'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Follow-ups Vertical --}}
    <div class="col-md-4">
        <div class="card-dark" style="height:100%;">
            <p style="color:#f5a623;font-family:'Sora',sans-serif;font-size:13px;font-weight:600;margin:0 0 16px;">
                <i class="fas fa-bell me-2"></i>Follow-ups
            </p>

            {{-- Today --}}
            <div style="display:flex;align-items:center;justify-content:space-between;
                        padding:12px;border-radius:8px;background:rgba(245,166,35,0.08);margin-bottom:10px;
                        border:1px solid rgba(245,166,35,0.18);">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:32px;height:32px;background:#2a200a;border-radius:8px;
                                display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-calendar-check" style="color:#f5a623;font-size:13px;"></i>
                    </div>
                    <div>
                        <p style="color:#ccc;font-size:12px;margin:0;font-weight:600;">Today's</p>
                        <p style="color:#8d93b0;font-size:11px;margin:0;">Follow-ups</p>
                    </div>
                </div>
                <div style="text-align:right;">
                    <span style="color:#f5a623;font-size:1.4rem;font-weight:bold;display:block;font-family:'Sora',sans-serif;">
                        {{ $followupStats['today'] }}
                    </span>
                    <a href="{{ route('admin.followups.today') }}"
                       style="color:#f5a623;font-size:10px;text-decoration:none;">View →</a>
                </div>
            </div>

            {{-- Overdue --}}
            <div style="display:flex;align-items:center;justify-content:space-between;
                        padding:12px;border-radius:8px;background:rgba(226,75,74,0.08);margin-bottom:10px;
                        border:1px solid rgba(226,75,74,0.18);">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:32px;height:32px;background:#2d1515;border-radius:8px;
                                display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-exclamation-circle" style="color:#e24b4a;font-size:13px;"></i>
                    </div>
                    <div>
                        <p style="color:#ccc;font-size:12px;margin:0;font-weight:600;">Overdue</p>
                        <p style="color:#8d93b0;font-size:11px;margin:0;">Follow-ups</p>
                    </div>
                </div>
                <div style="text-align:right;">
                    <span style="color:#f5a623;font-size:1.4rem;font-weight:bold;display:block;font-family:'Sora',sans-serif;">
                        {{ $followupStats['overdue'] }}
                    </span>
                    <a href="{{ route('admin.followups.today') }}"
                       style="color:#e24b4a;font-size:10px;text-decoration:none;">Fix →</a>
                </div>
            </div>

            {{-- Upcoming --}}
            <div style="display:flex;align-items:center;justify-content:space-between;
                        padding:12px;border-radius:8px;background:rgba(167,139,250,0.08);
                        border:1px solid rgba(167,139,250,0.18);">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:32px;height:32px;background:#211a3a;border-radius:8px;
                                display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-clock" style="color:#a78bfa;font-size:13px;"></i>
                    </div>
                    <div>
                        <p style="color:#ccc;font-size:12px;margin:0;font-weight:600;">Upcoming</p>
                        <p style="color:#8d93b0;font-size:11px;margin:0;">Next 7 Days</p>
                    </div>
                </div>
                <div style="text-align:right;">
                    <span style="color:#f5a623;font-size:1.4rem;font-weight:bold;display:block;font-family:'Sora',sans-serif;">
                        {{ $followupStats['upcoming'] }}
                    </span>
                    <a href="{{ route('admin.followups.today') }}"
                       style="color:#a78bfa;font-size:10px;text-decoration:none;">View →</a>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection