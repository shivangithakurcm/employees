<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #0b0e1a;
            --panel: #12162a;
            --panel-light: #181d35;
            --sidebar: #0e1124;
            --line: #262c4a;
            --text-dim: #8d93b0;
            --amber: #f5a623;
            --amber-deep: #d68a0f;
            --teal: #2dd4bf;
            --red: #e24b4a;
            --green: #1d9e75;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--ink);
            color: #fff;
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3, h4, h5, .brand, .topbar span {
            font-family: 'Sora', sans-serif;
        }

        .sidebar {
            width: 220px; min-height: 100vh;
            background: var(--sidebar); position: fixed;
            top: 0; left: 0; padding: 20px 0;
            overflow-y: auto;
            z-index: 1000;
            border-right: 1px solid var(--line);
        }
        .sidebar .brand {
            color: var(--amber); font-weight: 700;
            font-size: 1.25rem; padding: 10px 20px 26px;
            letter-spacing: -0.01em;
            display: flex; align-items: center; gap: 9px;
        }
        .sidebar .brand .glyph {
            width: 28px; height: 28px; border-radius: 8px;
            background: linear-gradient(155deg, var(--amber), var(--amber-deep));
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar .brand .glyph i { color: var(--ink); font-size: 12px; }

        .sidebar a {
            display: block; color: var(--text-dim);
            padding: 13px 20px; text-decoration: none;
            font-size: 1rem;
            border-left: 3px solid transparent;
            transition: color .15s ease, background .15s ease, border-color .15s ease;
        }
        .sidebar a i { font-size: 1rem; width: 18px; text-align: center; }
        .sidebar a:hover, .sidebar a.active {
            color: var(--amber); background: var(--panel);
            border-left-color: var(--amber);
        }
        .master-toggle {
            display: flex; align-items: center;
            justify-content: space-between;
            cursor: pointer;
        }
        .master-toggle.active-parent {
            color: var(--amber) !important;
            background: var(--panel);
            border-left-color: var(--amber);
        }
        .master-submenu {
            background: #0a0c1a;
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease;
        }
        .master-submenu.open { max-height: 200px; }
        .master-submenu a {
            padding: 10px 20px 10px 38px;
            font-size: 0.92rem; color: #6b7090;
            border-left: none !important;
            display: flex; align-items: center; gap: 8px;
        }
        .master-submenu a:hover {
            color: var(--amber); background: var(--panel-light);
            border-left: none !important;
        }
        .master-submenu a.active {
            color: var(--amber); font-weight: 600;
            background: var(--panel-light); border-left: none !important;
        }
        .master-submenu a::before {
            content: ''; width: 5px; height: 5px;
            border-radius: 50%; background: currentColor; flex-shrink: 0;
        }
        #masterArrow { font-size: 11px; transition: transform 0.3s ease; }

        .main-content { margin-left: 220px; padding: 20px; transition: margin-left .25s ease; }

        .topbar {
            display: flex; justify-content: space-between;
            align-items: center; background: var(--panel);
            border: 1px solid var(--line);
            padding: 12px 20px; margin-bottom: 25px; border-radius: 10px;
        }
        .topbar span { color: var(--amber); font-weight: 600; font-size: 0.98rem; }

        .stat-card {
            background: var(--panel); border: 1px solid var(--line);
            border-radius: 10px; padding: 25px;
            text-align: center; margin-bottom: 20px;
        }
        .stat-card h3 { color: var(--amber); font-size: 2rem; margin: 0; font-family: 'Sora', sans-serif; font-weight: 700; }
        .stat-card p { color: var(--text-dim); margin: 5px 0 0; }

        .btn-gold {
            background: linear-gradient(155deg, var(--amber), var(--amber-deep));
            color: var(--ink); border: none; font-weight: 700;
        }
        .btn-gold:hover { filter: brightness(1.05); color: var(--ink); }

        .card-dark { background: var(--panel); border: 1px solid var(--line); border-radius: 10px; padding: 25px; }
        .table-dark th { background: var(--panel-light); color: var(--amber); font-family: 'JetBrains Mono', monospace; font-size: 0.78rem; letter-spacing: 0.03em; text-transform: uppercase; }

        .form-control, .form-select {
            background: var(--panel-light); border: 1px solid var(--line); color: #fff;
        }
        .form-control:focus, .form-select:focus {
            background: #1d2340; color: #fff;
            border-color: var(--amber); box-shadow: 0 0 0 3px rgba(245,166,35,0.14);
        }
        .form-label { color: var(--text-dim); font-size: 0.82rem; font-weight: 600; }

        .step-indicator { display: flex; gap: 10px; margin-bottom: 25px; }
        .step-indicator .step {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--panel-light); color: #fff;
            display: flex; align-items: center;
            justify-content: center; font-weight: bold;
            border: 1px solid var(--line);
        }
        .step-indicator .step.active { background: var(--amber); color: var(--ink); border-color: var(--amber); }
        .step-indicator .step.done { background: var(--green); border-color: var(--green); }

        .role-badge {
            font-size: 10px; padding: 2px 8px; border-radius: 99px;
            font-weight: 600; margin: 0 20px 16px;
            display: inline-block;
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: 0.02em;
        }

        .mobile-menu-btn {
            display: none; background: var(--panel); color: var(--amber);
            border: 1px solid var(--line); font-size: 18px;
            padding: 6px 12px; border-radius: 6px; cursor: pointer;
        }
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.6); z-index: 999;
        }
        .sidebar-overlay.open { display: block; }

        .alert-danger {
            background: rgba(226, 75, 74, 0.1);
            border: 1px solid rgba(226, 75, 74, 0.35);
            color: #ff9b9b;
            border-radius: 9px;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.25s ease; }
            .sidebar.open { transform: translateX(0); box-shadow: 4px 0 20px rgba(0,0,0,.5); }
            .main-content { margin-left: 0; padding: 15px; }
            .topbar { flex-wrap: wrap; gap: 10px; padding: 10px 14px; }
            .mobile-menu-btn { display: inline-flex; align-items: center; justify-content: center; }
            .table-responsive-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .stat-card { margin-bottom: 15px; }
            .card-dark { padding: 18px; }
            .step-indicator { flex-wrap: wrap; }
        }
        @media (max-width: 480px) {
            .topbar span { font-size: 14px; }
            .card-dark { padding: 14px; }
            .sidebar { width: 240px; }
            .stat-card h3 { font-size: 1.6rem; }
            .step-indicator .step { width: 28px; height: 28px; font-size: 13px; }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ═══ SIDEBAR ═══ --}}
<div class="sidebar" id="sidebar">
    <div class="brand">
        <span class="glyph"><i class="fas fa-bolt"></i></span>
        @hasrole('admin') ADMIN @endhasrole
        @hasrole('employee') EMPLOYEE @endhasrole
    </div>

    {{-- Role Badge --}}
    @auth
        @hasrole('admin')
            <span class="role-badge" style="background:#1a3322; color:#4ade80;">
                <i class="fas fa-shield-alt me-1"></i> Admin
            </span>
        @endhasrole
        @hasrole('manager')
            <span class="role-badge" style="background:#261a40; color:#a78bfa;">
                <i class="fas fa-user-tie me-1"></i> Manager
            </span>
        @endhasrole
        @hasrole('sales')
            <span class="role-badge" style="background:#2a1f0a; color:#EF9F27;">
                <i class="fas fa-user me-1"></i> Sales
            </span>
        @endhasrole
        @hasrole('employee')
            <span class="role-badge" style="background:#0a1a2a; color:#60a5fa;">
                <i class="fas fa-user me-1"></i> Employee
            </span>
        @endhasrole
    @endauth

    {{-- Dashboard --}}
    @hasrole('admin')
    <a href="{{ route('admin.dashboard') }}"
       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
    </a>
    @endhasrole

    @hasrole('employee')
    <a href="{{ route('admin.employee.dashboard') }}"
       class="{{ request()->routeIs('admin.employee.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
    </a>
    @endhasrole

    {{-- Employee — sirf Admin --}}
    @hasrole('admin')
    <a href="{{ route('admin.employees.index') }}"
       class="{{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
        <i class="fas fa-id-badge me-2"></i> Employee
    </a>
    @endhasrole

    {{-- LMS — Admin + Employee --}}
    @hasanyrole('admin|manager|sales|employee')
    <a href="{{ route('admin.lms.index') }}"
       class="{{ request()->routeIs('admin.lms.*') ? 'active' : '' }}">
        <i class="fas fa-chart-line me-2"></i>
        @hasrole('employee') My Leads @else LMS @endhasrole
    </a>
    @endhasanyrole

    {{-- Customers — Admin + Manager --}}
    @hasanyrole('admin|manager')
    <a href="{{ route('admin.customers.index') }}"
       class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
        <i class="fas fa-users me-2"></i> Customers
    </a>
    @endhasanyrole

    {{-- Follow-ups --}}
    @auth
    <a href="{{ route('admin.followups.today') }}"
       class="{{ request()->routeIs('admin.followups.*') ? 'active' : '' }}">
        <i class="fas fa-bell me-2"></i> Follow-ups
        @php
            $pendingCount = \App\Models\FollowUp::whereHas('lead', function($q) {
                                if(auth()->user()->hasRole('employee')) {
                                    $q->where('assigned_to', auth()->id());
                                }
                            })
                            ->whereDate('date', \Carbon\Carbon::today())
                            ->where('status', 'pending')
                            ->count();
        @endphp
        @if($pendingCount > 0)
            <span style="background:var(--red); color:#fff; font-size:10px;
                         padding:1px 6px; border-radius:99px; margin-left:4px;">
                {{ $pendingCount }}
            </span>
        @endif
    </a>
    @endauth

    {{-- Master — sirf Admin --}}
    @hasrole('admin')
    <a class="master-toggle {{ request()->is('admin/master/*') ? 'active-parent' : '' }}"
       onclick="toggleMaster()">
        <span><i class="fas fa-layer-group me-2"></i> Master</span>
        <i class="fas fa-chevron-down" id="masterArrow"
           style="transform: {{ request()->is('admin/master/*') ? 'rotate(180deg)' : 'rotate(0deg)' }};"></i>
    </a>
    <div class="master-submenu {{ request()->is('admin/master/*') ? 'open' : '' }}" id="masterSubmenu">
        <a href="{{ route('admin.master.designation.index') }}"
           class="{{ request()->routeIs('admin.master.designation.*') ? 'active' : '' }}">
            Designation
        </a>
        <a href="{{ route('admin.master.project_type.index') }}"
           class="{{ request()->routeIs('admin.master.project_type.*') ? 'active' : '' }}">
            Project Type
        </a>
        <a href="{{ route('admin.master.shift.index') }}"
           class="{{ request()->routeIs('admin.master.shift.*') ? 'active' : '' }}">
            Shift
        </a>
    </div>
    @endhasrole

</div>

{{-- ═══ MAIN CONTENT ═══ --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="mobile-menu-btn" onclick="toggleSidebar()" type="button">
                <i class="fas fa-bars"></i>
            </button>
            <span>@yield('page-title', 'Dashboard')</span>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                <span style="width:32px; height:32px; border-radius:50%;
                             background:linear-gradient(155deg, var(--amber), var(--amber-deep));
                             color:var(--ink); font-weight:bold; display:inline-flex;
                             align-items:center; justify-content:center; cursor:pointer;
                             font-family:'Sora',sans-serif;"
                      role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </span>
                <ul class="dropdown-menu dropdown-menu-end"
                    style="background:var(--panel); border:1px solid var(--line);">
                    <li>
                        <span class="dropdown-item-text"
                              style="color:var(--text-dim); font-size:12px; padding:8px 16px;">
                            {{ auth()->user()->name ?? '' }}
                        </span>
                    </li>
                    <li><hr class="dropdown-divider" style="border-color:var(--line);"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item"
                                    style="color:#f87171;">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Flash Success --}}
    @if(session('success'))
    <div id="successToast"
         style="position:fixed; top:20px; right:20px; z-index:999999;
                background:var(--green); color:#fff; padding:12px 20px;
                border-radius:8px; font-size:14px; font-weight:500;
                box-shadow:0 4px 15px rgba(0,0,0,0.4);
                display:flex; align-items:center; gap:12px; max-width:90vw;">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <span onclick="document.getElementById('successToast').remove()"
              style="cursor:pointer; font-size:16px; opacity:0.8;">✕</span>
    </div>
    <script>
        setTimeout(function() {
            var t = document.getElementById('successToast');
            if (t) t.remove();
        }, 3000);
    </script>
    @endif

    {{-- Flash Error --}}
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleMaster() {
    var menu  = document.getElementById('masterSubmenu');
    var arrow = document.getElementById('masterArrow');
    var toggle = document.querySelector('.master-toggle');
    if (menu.classList.contains('open')) {
        menu.classList.remove('open');
        arrow.style.transform = 'rotate(0deg)';
        toggle.classList.remove('active-parent');
    } else {
        menu.classList.add('open');
        arrow.style.transform = 'rotate(180deg)';
        toggle.classList.add('active-parent');
    }
}
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
}
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) closeSidebar();
});
</script>

@stack('scripts')
</body>
</html>