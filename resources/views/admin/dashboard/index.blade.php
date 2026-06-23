@extends('layouts.admin')
@section('page-title', 'Dashboard')

@section('content')

<h4 class="mb-4" style="color:#f0c040;">
    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
</h4>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">

    @php
        $cards = [
            ['label'=>'Total Employees', 'key'=>'total_employees', 'icon'=>'fas fa-users',       'bg'=>'#1a2233','ic'=>'#378ADD', 'badge'=>'+4%',  'up'=>true],
            ['label'=>'Total Leads',     'key'=>'total_leads',     'icon'=>'fas fa-crosshairs',  'bg'=>'#261a40','ic'=>'#7F77DD', 'badge'=>'+12%', 'up'=>true],
            ['label'=>'Total Qualified', 'key'=>'total_qualified', 'icon'=>'fas fa-check-circle','bg'=>'#0f2a1a','ic'=>'#1D9E75', 'badge'=>'+8%',  'up'=>true],
            ['label'=>'Total Prospect',  'key'=>'total_prospect',  'icon'=>'fas fa-eye',         'bg'=>'#2a1f0a','ic'=>'#EF9F27', 'badge'=>'+5%',  'up'=>true],
            ['label'=>'Total Lost',      'key'=>'total_lost',      'icon'=>'fas fa-times-circle','bg'=>'#2d1515','ic'=>'#E24B4A', 'badge'=>'-3%',  'up'=>false],
            ['label'=>'Total Won',       'key'=>'total_won',       'icon'=>'fas fa-trophy',      'bg'=>'#0a2a1a','ic'=>'#1D9E75', 'badge'=>'+18%', 'up'=>true],
        ];
    @endphp

    @foreach($cards as $c)
    <div class="col-md-4 col-6">
        <div class="stat-card" style="border-left: 3px solid {{ $c['ic'] }};">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                <div style="width:38px;height:38px;background:{{ $c['bg'] }};border-radius:8px;
                            display:flex;align-items:center;justify-content:center;">
                    <i class="{{ $c['icon'] }}" style="color:{{ $c['ic'] }};font-size:16px;"></i>
                </div>
                <span style="font-size:11px;padding:2px 8px;border-radius:99px;font-weight:600;
                    background:{{ $c['up'] ? '#1a3322' : '#2d1515' }};
                    color:{{ $c['up'] ? '#4ade80' : '#f87171' }};">
                    <i class="fas fa-arrow-{{ $c['up'] ? 'up' : 'down' }}" style="font-size:9px;"></i>
                    {{ $c['badge'] }}
                </span>
            </div>
            <h3 style="color:#f0c040; font-size:2rem; margin:0; font-weight:bold;">
                {{ $stats[$c['key']] }}
            </h3>
            <p style="color:#aaa; margin:5px 0 0; font-size:13px;">{{ $c['label'] }}</p>
        </div>
    </div>
    @endforeach

</div>

{{-- Bottom Row: Pipeline + Donut --}}
<div class="row g-3">

    {{-- Pipeline Bars --}}
    <div class="col-md-6">
        <div class="card-dark">
            <p style="color:#f0c040;font-size:13px;font-weight:600;margin:0 0 16px;">
                <i class="fas fa-chart-bar me-2"></i>Lead Pipeline
            </p>
            @php
                $mx = max($stats['total_leads'], 1);
                $bars = [
                    ['label'=>'Leads',     'val'=>$stats['total_leads'],     'color'=>'#7F77DD'],
                    ['label'=>'Qualified', 'val'=>$stats['total_qualified'], 'color'=>'#1D9E75'],
                    ['label'=>'Prospect',  'val'=>$stats['total_prospect'],  'color'=>'#EF9F27'],
                    ['label'=>'Won',       'val'=>$stats['total_won'],       'color'=>'#1D9E75'],
                    ['label'=>'Lost',      'val'=>$stats['total_lost'],      'color'=>'#E24B4A'],
                ];
            @endphp
            @foreach($bars as $b)
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <span style="color:#aaa;font-size:12px;width:68px;flex-shrink:0;">{{ $b['label'] }}</span>
                <div style="flex:1;height:7px;background:#333;border-radius:99px;overflow:hidden;">
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
    <div class="col-md-6">
        <div class="card-dark">
            <p style="color:#f0c040;font-size:13px;font-weight:600;margin:0 0 16px;">
                <i class="fas fa-chart-pie me-2"></i>Status Breakdown
            </p>
            @php
                $tot  = $stats['total_qualified'] + $stats['total_prospect']
                      + $stats['total_won']       + $stats['total_lost'];
                $circ = 2 * M_PI * 36;
                $segs = [
                    ['val'=>$stats['total_qualified'], 'color'=>'#1D9E75', 'label'=>'Qualified'],
                    ['val'=>$stats['total_prospect'],  'color'=>'#EF9F27', 'label'=>'Prospect'],
                    ['val'=>$stats['total_won'],       'color'=>'#7F77DD', 'label'=>'Won'],
                    ['val'=>$stats['total_lost'],      'color'=>'#E24B4A', 'label'=>'Lost'],
                ];
                $offset = 0;
            @endphp
            <div style="display:flex;align-items:center;gap:20px;">
                <svg width="110" height="110" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="36" fill="none" stroke="#333" stroke-width="16"/>
                    @foreach($segs as $seg)
                        @php
                            $arc    = ($tot > 0) ? ($seg['val'] / $tot) * $circ : 0;
                            $dash   = $arc . ' ' . ($circ - $arc);
                        @endphp
                        <circle cx="50" cy="50" r="36" fill="none"
                            stroke="{{ $seg['color'] }}" stroke-width="16"
                            stroke-dasharray="{{ $dash }}"
                            stroke-dashoffset="{{ -$offset }}"
                            transform="rotate(-90 50 50)"/>
                        @php $offset += $arc; @endphp
                    @endforeach
                    <text x="50" y="46" text-anchor="middle"
                          font-size="14" font-weight="bold" fill="#f0c040">
                        {{ $stats['total_leads'] }}
                    </text>
                    <text x="50" y="60" text-anchor="middle" font-size="9" fill="#888">leads</text>
                </svg>
                <div style="display:flex;flex-direction:column;gap:10px;flex:1;">
                    @foreach($segs as $seg)
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:9px;height:9px;border-radius:50%;background:{{ $seg['color'] }};flex-shrink:0;"></div>
                        <span style="color:#aaa;font-size:12px;flex:1;">{{ $seg['label'] }}</span>
                        <span style="color:#ccc;font-size:12px;font-weight:600;">{{ $seg['val'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

@endsection