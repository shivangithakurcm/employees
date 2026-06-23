{{-- resources/views/layouts/employee.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background:#1a1a1a; color:#fff; }
        .sidebar {
            width:220px; min-height:100vh;
            background:#111; position:fixed; top:0; left:0;
            display:flex; flex-direction:column;
            border-right:1px solid #1e1e1e;
        }
        .sb-brand { padding:16px 16px 12px; border-bottom:1px solid #1e1e1e; }
        .sb-brand-top { display:flex; align-items:center; gap:10px; margin-bottom:12px; }
        .sb-logo { width:34px; height:34px; background:#7F77DD; border-radius:8px;
                   display:flex; align-items:center; justify-content:center;
                   font-weight:900; color:#fff; font-size:14px; flex-shrink:0; }
        .sb-brand-name { color:#f0c040; font-weight:700; font-size:14px; }
        .sb-brand-sub  { color:#555; font-size:10px; }
        .sb-user { display:flex; align-items:center; gap:9px; background:#1a1a1a;
                   border-radius:8px; padding:8px 10px; }
        .sb-avatar { width:28px; height:28px; border-radius:50%; background:#7F77DD;
                     color:#fff; font-size:11px; font-weight:700;
                     display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .sb-uname { color:#ccc; font-size:12px; }
        .sb-urole  { color:#555; font-size:10px; }
        .sb-section { padding:12px 0 4px 18px; color:#444; font-size:10px;
                      letter-spacing:.08em; font-weight:600; }
        .sidebar a { display:flex; align-items:center; gap:10px; color:#888;
                     padding:9px 18px; text-decoration:none; font-size:13px;
                     border-left:3px solid transparent; transition:all .15s; }
        .sidebar a i { font-size:14px; width:16px; text-align:center; }
        .sidebar a:hover  { color:#f0c040; background:#1a1a1a; }
        .sidebar a.active { color:#f0c040; background:#1a1a1a; border-left-color:#f0c040; }
        .sb-bottom { margin-top:auto; padding:12px; border-top:1px solid #1e1e1e; }
        .sb-logout { display:flex; align-items:center; gap:9px; color:#555; font-size:13px;
                     padding:8px 10px; border-radius:7px; cursor:pointer;
                     width:100%; background:none; border:none; transition:all .15s; }
        .sb-logout:hover { color:#E24B4A; background:#1a1a1a; }
        .main-content { margin-left:220px; }
        .topbar { display:flex; justify-content:space-between; align-items:center;
                  background:#111; padding:11px 20px; margin-bottom:24px;
                  border-bottom:1px solid #1e1e1e;
                  position:sticky; top:0; z-index:100; }
        .page-content { padding:0 24px 24px; }
        .stat-card { background:#222; border:1px solid #2a2a2a; border-radius:10px;
                     padding:18px 16px; margin-bottom:0; }
        .card-dark  { background:#222; border:1px solid #2a2a2a; border-radius:10px; padding:18px 16px; }
        .form-control, .form-select { background:#222; border:1px solid #333; color:#fff; }
        .form-control:focus, .form-select:focus { background:#2a2a2a; color:#fff;
            border-color:#f0c040; box-shadow:none; }
        .form-label { color:#aaa; }
        .btn-gold { background:#f0c040; color:#000; border:none; font-weight:600; }
        .btn-gold:hover { background:#d4a800; color:#000; }
        .table-dark td, .table-dark th { border-color:#2a2a2a; }
    </style>
</head>
<body>

@php $emp = \App\Models\Employee::find(auth()->guard('employee')->user()->employee_id); @endphp

<div class="sidebar">
    <div class="sb-brand">
        <div class="sb-brand-top">
            <div class="sb-logo">E</div>
            <div>
                <div class="sb-brand-name">EMPLOYEE</div>
                <div class="sb-brand-sub">CRM Portal</div>
            </div>
        </div>
        <div class="sb-user">
            <div class="sb-avatar">{{ strtoupper(substr($emp->name ?? 'E', 0, 2)) }}</div>
            <div>
                <div class="sb-uname">{{ $emp->name ?? 'Employee' }}</div>
                <div class="sb-urole">{{ $emp->designation ?? 'Staff' }}</div>
            </div>
        </div>
    </div>

    <div class="sb-section">MENU</div>

    <a href="{{ route('employee.dashboard') }}"
       class="{{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="{{ route('employee.leads.index') }}"
       class="{{ request()->routeIs('employee.leads.*') ? 'active' : '' }}">
        <i class="fas fa-crosshairs"></i> My Leads
    </a>
    <a href="{{ route('employee.leads.create') }}">
        <i class="fas fa-plus-circle"></i> Add Lead
    </a>

    <div class="sb-bottom">
        <form action="{{ route('employee.logout') }}" method="POST">
            @csrf
            <button type="submit" class="sb-logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <div style="display:flex;align-items:center;gap:6px;">
            <span style="color:#555;font-size:12px;">Employee</span>
            <span style="color:#333;font-size:12px;">›</span>
            <span style="color:#f0c040;font-size:12px;font-weight:600;">
                @yield('page-title','Dashboard')
            </span>
        </div>
        <div style="display:flex;align-items:center;gap:14px;">
            {{-- Follow-up alert badge --}}
            @php
                $overdueCount = \App\Models\Lead::where('employee_id', $emp->id ?? 0)
                    ->whereDate('followup_date','<', today())
                    ->whereNull('followup_done_at')->count();
            @endphp
            @if($overdueCount)
            <span style="background:#E24B4A;color:#fff;font-size:11px;font-weight:700;
                         padding:3px 8px;border-radius:99px;">
                <i class="fas fa-bell me-1"></i>{{ $overdueCount }} overdue
            </span>
            @endif
            <div class="sb-avatar">{{ strtoupper(substr($emp->name ?? 'E', 0, 1)) }}</div>
        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
        <div id="toast" style="position:fixed;top:20px;right:20px;z-index:9999;
                                background:#198754;color:#fff;padding:12px 20px;
                                border-radius:8px;font-size:14px;font-weight:500;
                                box-shadow:0 4px 20px rgba(0,0,0,.5);
                                display:flex;align-items:center;gap:12px;">
            <i class="fas fa-check-circle"></i>{{ session('success') }}
            <span onclick="this.parentElement.remove()" style="cursor:pointer;opacity:.8;">✕</span>
        </div>
        <script>setTimeout(()=>{var t=document.getElementById('toast');if(t)t.remove();},3000)</script>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>