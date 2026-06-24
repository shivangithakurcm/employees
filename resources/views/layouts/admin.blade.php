<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #1a1a1a; color: #fff; }
        .sidebar {
            width: 220px; min-height: 100vh;
            background: #111; position: fixed;
            top: 0; left: 0; padding: 20px 0;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar .brand {
            color: #f0c040; font-weight: bold;
            font-size: 1.2rem; padding: 10px 20px 30px;
        }
        .sidebar a {
            display: block; color: #ccc;
            padding: 12px 20px; text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            color: #f0c040; background: #222;
            border-left: 3px solid #f0c040;
        }
        .master-toggle {
            display: flex; align-items: center;
            justify-content: space-between;
            cursor: pointer;
        }
        .master-toggle.active-parent {
            color: #f0c040 !important;
            background: #222;
            border-left: 3px solid #f0c040;
        }
        .master-submenu {
            background: #0d0d0d;
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease;
        }
        .master-submenu.open { max-height: 200px; }
        .master-submenu a {
            padding: 9px 20px 9px 38px;
            font-size: 13px; color: #999;
            border-left: none !important;
            display: flex; align-items: center; gap: 8px;
        }
        .master-submenu a:hover {
            color: #f0c040; background: #1a1a1a;
            border-left: none !important;
        }
        .master-submenu a.active {
            color: #f0c040; font-weight: 600;
            background: #1a1a1a; border-left: none !important;
        }
        .master-submenu a::before {
            content: ''; width: 5px; height: 5px;
            border-radius: 50%; background: currentColor; flex-shrink: 0;
        }
        #masterArrow { font-size: 11px; transition: transform 0.3s ease; }
        .main-content { margin-left: 220px; padding: 20px; transition: margin-left .25s ease; }
        .topbar {
            display: flex; justify-content: space-between;
            align-items: center; background: #111;
            padding: 12px 20px; margin-bottom: 25px; border-radius: 8px;
        }
        .stat-card {
            background: #222; border: 1px solid #333;
            border-radius: 10px; padding: 25px;
            text-align: center; margin-bottom: 20px;
        }
        .stat-card h3 { color: #f0c040; font-size: 2rem; margin: 0; }
        .stat-card p { color: #aaa; margin: 5px 0 0; }
        .btn-gold { background: #f0c040; color: #000; border: none; font-weight: bold; }
        .btn-gold:hover { background: #d4a800; color: #000; }
        .card-dark { background: #1e1e1e; border: 1px solid #333; border-radius: 10px; padding: 25px; }
        .table-dark th { background: #222; color: #f0c040; }
        .form-control, .form-select { background: #222; border: 1px solid #444; color: #fff; }
        .form-control:focus, .form-select:focus {
            background: #2a2a2a; color: #fff;
            border-color: #f0c040; box-shadow: none;
        }
        .form-label { color: #ccc; }
        .step-indicator { display: flex; gap: 10px; margin-bottom: 25px; }
        .step-indicator .step {
            width: 32px; height: 32px; border-radius: 50%;
            background: #444; color: #fff;
            display: flex; align-items: center;
            justify-content: center; font-weight: bold;
        }
        .step-indicator .step.active { background: #f0c040; color: #000; }
        .step-indicator .step.done { background: #28a745; }

        /* Role badge in sidebar */
        .role-badge {
            font-size: 10px; padding: 2px 8px; border-radius: 99px;
            font-weight: 600; margin: 0 20px 16px;
            display: inline-block;
        }

        .mobile-menu-btn {
            display: none; background: #111; color: #f0c040;
            border: 1px solid #333; font-size: 18px;
            padding: 6px 12px; border-radius: 6px; cursor: pointer;
        }
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.6); z-index: 999;
        }
        .sidebar-overlay.open { display: block; }

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
        <i class="fas fa-bolt me-1"></i> ADMIN
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
    @endauth

    {{-- Dashboard — sab dekh sakte hain --}}
    <a href="{{ route('admin.dashboard') }}"
       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
    </a>

    {{-- Employee — sirf Admin --}}
    @hasrole('admin')
    <a href="{{ route('admin.employees.index') }}"
       class="{{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
        <i class="fas fa-id-badge me-2"></i> Employee
    </a>
    @endhasrole

    {{-- LMS — Admin + Manager + Sales --}}
    @hasanyrole('admin|manager|sales')
    <a href="{{ route('admin.lms.index') }}"
       class="{{ request()->routeIs('admin.lms.*') ? 'active' : '' }}">
        <i class="fas fa-chart-line me-2"></i> LMS
    </a>
    @endhasanyrole

    {{-- Customers — Admin + Manager --}}
    @hasanyrole('admin|manager')
    <a href="{{ route('admin.customers.index') }}"
       class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
        <i class="fas fa-users me-2"></i> Customers
    </a>
    @endhasanyrole

    {{-- Follow-ups — sab dekh sakte hain --}}
    @auth
    <a href="{{ route('admin.followups.today') }}"
       class="{{ request()->routeIs('admin.followups.*') ? 'active' : '' }}">
        <i class="fas fa-bell me-2"></i> Follow-ups
        @php
            $pendingCount = \App\Models\FollowUp::whereDate('date', \Carbon\Carbon::today())
                ->where('status', 'pending')->count();
        @endphp
        @if($pendingCount > 0)
            <span style="background:#E24B4A; color:#fff; font-size:10px;
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
            <span style="color:#f0c040">@yield('page-title', 'Dashboard')</span>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                <span style="width:32px; height:32px; border-radius:50%; background:#f0c040;
                             color:#000; font-weight:bold; display:inline-flex;
                             align-items:center; justify-content:center; cursor:pointer;"
                      role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </span>
                <ul class="dropdown-menu dropdown-menu-end"
                    style="background:#1e1e1e; border:1px solid #333;">
                    <li>
                        <span class="dropdown-item-text"
                              style="color:#aaa; font-size:12px; padding:8px 16px;">
                            {{ auth()->user()->name ?? '' }}
                        </span>
                    </li>
                    <li><hr class="dropdown-divider" style="border-color:#333;"></li>
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
                background:#198754; color:#fff; padding:12px 20px;
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