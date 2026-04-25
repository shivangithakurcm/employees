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
        .main-content { margin-left: 220px; padding: 20px; }
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
    </style>
</head>
<body>

{{-- Sidebar --}}
<div class="sidebar">
    <div class="brand">ADMIN</div>
    <a href="{{ route('admin.dashboard') }}"
       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
    </a>
    <a href="{{ route('admin.employees.index') }}"
   class="{{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
    <i class="fas fa-users me-2"></i> Employee
</a>
  {{-- ✅ Sahi --}}
<a href="{{ route('admin.lms.index') }}"
   class="{{ request()->routeIs('admin.lms.*') ? 'active' : '' }}">
    <i class="fas fa-chart-line me-2"></i> LMS
</a>
</div>

{{-- Main Content --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
    <span style="color:#f0c040">@yield('page-title', 'Dashboard')</span>

    <div class="d-flex align-items-center gap-3">
        
        <!-- Dropdown -->
        <div class="dropdown">
            <span class="rounded-circle bg-warning text-dark px-2 py-1 fw-bold dropdown-toggle"
                  role="button" data-bs-toggle="dropdown" aria-expanded="false"
                  style="cursor:pointer;">
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
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
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
@stack('scripts')
</body>
</html>