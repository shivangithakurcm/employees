{{-- resources/views/errors/403.blade.php --}}
@extends('layouts.admin')
@section('content')
<div style="text-align:center; padding:80px 20px;">
    <i class="fas fa-lock" style="color:#E24B4A; font-size:4rem;"></i>
    <h2 style="color:#f0c040; margin:20px 0 10px;">Access Denied</h2>
    <p style="color:#aaa;">You don't have permission to view this page.</p>
    <a href="{{ route('admin.dashboard') }}"
       style="background:#f0c040;color:#000;padding:10px 24px;
              border-radius:8px;text-decoration:none;font-weight:600;display:inline-block;margin-top:16px;">
        ← Back to Dashboard
    </a>
</div>
@endsection