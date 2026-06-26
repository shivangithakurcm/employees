@extends('layouts.admin')
@section('page-title', 'My Dashboard')
@section('content')

<h4 class="mb-4" style="color:#f5a623; font-family:'Sora',sans-serif; font-weight:700;">
    <i class="fas fa-tachometer-alt me-2"></i>My Dashboard
    <span style="font-size:13px; color:#8d93b0; font-weight:400; margin-left:8px; font-family:'Inter',sans-serif;">
        Welcome, {{ auth()->user()->name }}
    </span>
</h4>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4 col-6">
        <div class="stat-card" style="border-left:3px solid #f5a623; text-align:left;">
            <p style="color:#8d93b0; font-size:12px; margin:0 0 6px;">Total Leads</p>
            <h3 style="color:#f5a623;">{{ $stats['total_leads'] }}</h3>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="stat-card" style="border-left:3px solid #5b9bf5; text-align:left;">
            <p style="color:#8d93b0; font-size:12px; margin:0 0 6px;">Follow Up</p>
            <h3 style="color:#5b9bf5;">{{ $stats['follow_up'] }}</h3>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="stat-card" style="border-left:3px solid #2dd4bf; text-align:left;">
            <p style="color:#8d93b0; font-size:12px; margin:0 0 6px;">Qualified</p>
            <h3 style="color:#2dd4bf;">{{ $stats['qualified'] }}</h3>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="stat-card" style="border-left:3px solid #a78bfa; text-align:left;">
            <p style="color:#8d93b0; font-size:12px; margin:0 0 6px;">Proposal Sent</p>
            <h3 style="color:#a78bfa;">{{ $stats['proposal'] }}</h3>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="stat-card" style="border-left:3px solid #1d9e75; text-align:left;">
            <p style="color:#8d93b0; font-size:12px; margin:0 0 6px;">Won</p>
            <h3 style="color:#1d9e75;">{{ $stats['won'] }}</h3>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="stat-card" style="border-left:3px solid #e24b4a; text-align:left;">
            <p style="color:#8d93b0; font-size:12px; margin:0 0 6px;">Lost</p>
            <h3 style="color:#e24b4a;">{{ $stats['lost'] }}</h3>
        </div>
    </div>
</div>

{{-- This Month Trend Chart --}}
<div class="card-dark mb-4">
    <p style="color:#f5a623; font-family:'Sora',sans-serif; font-size:13px; font-weight:600; margin:0 0 14px;">
        <i class="fas fa-chart-line me-2"></i>This Month — Daily Lead Activity
        <span style="color:#8d93b0; font-weight:400; font-size:12px; margin-left:6px; font-family:'Inter',sans-serif;">
            {{ now()->format('F Y') }}
        </span>
    </p>
    <div style="position:relative; width:100%; height:240px;">
        <canvas id="monthChart"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Chart(document.getElementById('monthChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyLabels) !!},
            datasets: [{
                data: {!! json_encode($dailyData) !!},
                borderColor: '#f5a623',
                backgroundColor: 'rgba(245,166,35,0.08)',
                borderWidth: 2,
                pointRadius: 3,
                pointBackgroundColor: '#f5a623',
                pointBorderColor: '#12162a',
                pointBorderWidth: 2,
                fill: true,
                tension: 0.35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#8d93b0', font: { size: 11 }, maxTicksLimit: 10 }
                },
                y: {
                    grid: { color: 'rgba(255,255,255,0.06)' },
                    ticks: { color: '#8d93b0', font: { size: 11 }, stepSize: 1 },
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush