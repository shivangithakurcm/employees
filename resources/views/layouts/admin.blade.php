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

        /* Master dropdown styles */
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
        .master-submenu.open {
            max-height: 200px;
        }
        .master-submenu a {
            padding: 9px 20px 9px 38px;
            font-size: 13px;
            color: #999;
            border-left: none !important;
            display: flex; align-items: center; gap: 8px;
        }
        .master-submenu a:hover {
            color: #f0c040;
            background: #1a1a1a;
            border-left: none !important;
        }
        .master-submenu a.active {
            color: #f0c040;
            font-weight: 600;
            background: #1a1a1a;
            border-left: none !important;
        }
        .master-submenu a::before {
            content: '';
            width: 5px; height: 5px;
            border-radius: 50%;
            background: currentColor;
            flex-shrink: 0;
        }
        #masterArrow {
            font-size: 11px;
            transition: transform 0.3s ease;
        }

        .main-content { margin-left: 220px; padding: 20px; transition: margin-left .25s ease; }
        .topbar {
            display: flex; justify-content: space-between;
            align-items: center; background: #111;
            padding: 12px 20px; margin-bottom: 25px;
            border-radius: 8px;
        }
        .stat-card {
            background: #222; border: 1px solid #333;
            border-radius: 10px; padding: 25px;
            text-align: center; margin-bottom: 20px;
        }
        .stat-card h3 { color: #f0c040; font-size: 2rem; margin: 0; }
        .stat-card p { color: #aaa; margin: 5px 0 0; }
        .btn-gold {
            background: #f0c040; color: #000;
            border: none; font-weight: bold;
        }
        .btn-gold:hover { background: #d4a800; color: #000; }
        .card-dark {
            background: #1e1e1e; border: 1px solid #333;
            border-radius: 10px; padding: 25px;
        }
        .table-dark th { background: #222; color: #f0c040; }
        .form-control, .form-select {
            background: #222; border: 1px solid #444; color: #fff;
        }
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

        /* ── Mobile menu toggle button ── */
        .mobile-menu-btn {
            display: none;
            background: #111;
            color: #f0c040;
            border: 1px solid #333;
            font-size: 18px;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
        }
        .mobile-menu-btn:hover { background: #222; }

        /* ── Sidebar overlay (mobile only) ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 999;
        }
        .sidebar-overlay.open { display: block; }

        /* ── Responsive breakpoint: tablet & mobile ── */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.25s ease;
            }
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 20px rgba(0,0,0,.5);
            }
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            .topbar {
                flex-wrap: wrap;
                gap: 10px;
                padding: 10px 14px;
            }
            .mobile-menu-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            /* Tables: horizontal scroll instead of squeezing */
            .table-responsive-wrap {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .stat-card { margin-bottom: 15px; }
            .card-dark { padding: 18px; }
            .step-indicator { flex-wrap: wrap; }
        }

        /* ── Small phones ── */
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

{{-- Mobile overlay — click to close sidebar --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- Sidebar --}}
<div class="sidebar" id="sidebar">
    <div class="brand">ADMIN</div>

    <a href="{{ route('admin.dashboard') }}"
       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
    </a>

    <a href="{{ route('admin.employees.index') }}"
       class="{{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
        <i class="fas fa-id-badge me-2"></i> Employee
    </a>

    <a href="{{ route('admin.lms.index') }}"
       class="{{ request()->routeIs('admin.lms.*') ? 'active' : '' }}">
        <i class="fas fa-chart-line me-2"></i> LMS
    </a>

    <a href="{{ route('admin.customers.index') }}"
       class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
        <i class="fas fa-users me-2"></i> Customers
    </a>

    {{-- Master Dropdown --}}
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

</div>

{{-- Main Content --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="mobile-menu-btn" onclick="toggleSidebar()" type="button" aria-label="Toggle menu">
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
    A
</span>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div id="successToast"
         style="position:fixed; top:20px; right:20px; z-index:999999;
                background:#198754; color:#fff; padding:12px 20px;
                border-radius:8px; font-size:14px; font-weight:500;
                box-shadow:0 4px 15px rgba(0,0,0,0.4);
                display:flex; align-items:center; gap:12px;
                max-width:90vw;">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <span onclick="document.getElementById('successToast').remove()"
              style="cursor:pointer; font-size:16px; line-height:1; opacity:0.8;">✕</span>
    </div>
    <script>
        setTimeout(function() {
            var t = document.getElementById('successToast');
            if (t) t.remove();
        }, 3000);
    </script>
    @endif

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
    var menu   = document.getElementById('masterSubmenu');
    var arrow  = document.getElementById('masterArrow');
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

/* ── Mobile sidebar toggle ── */
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
}

/* Close sidebar automatically if window resized back to desktop */
window.addEventListener('resize', function () {
    if (window.innerWidth > 768) {
        closeSidebar();
    }
});
</script>

@stack('scripts')
</body>
</html>