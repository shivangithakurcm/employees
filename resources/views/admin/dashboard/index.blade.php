@extends('layouts.admin')
@section('page-title', 'Dashboard')

@section('content')
<h4 class="mb-4" style="color:#f0c040">Dashboard</h4>

<div class="row">
    <div class="col-md-3">
        <div class="stat-card">
            <h3>{{ $stats['total_employees'] }}</h3>
            <p>Total Employees</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <h3>{{ $stats['total_leads'] }}</h3>
            <p>Total Leads</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <h3>{{ $stats['total_qualified'] }}</h3>
            <p>Total Qualified</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <h3>{{ $stats['total_prospect'] }}</h3>
            <p>Total Prospect</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <h3>{{ $stats['total_lost'] }}</h3>
            <p>Total Lost</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <h3>{{ $stats['total_won'] }}</h3>
            <p>Total Won</p>
        </div>
    </div>
</div>
@endsection