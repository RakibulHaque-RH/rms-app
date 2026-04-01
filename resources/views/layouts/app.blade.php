<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — RestaurantOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 272px;
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #8b5cf6;
            --accent: #f59e0b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #06b6d4;
            --dark: #0f172a;
            --content-bg: #f1f5f9;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --radius: 12px;
            --radius-sm: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--content-bg);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            font-size: 20px;
            color: #fff;
            box-shadow: 0 4px 12px rgba(99, 102, 241, .4);
            flex-shrink: 0;
        }

        .brand-text {
            color: #fff;
            font-size: 19px;
            font-weight: 700;
            letter-spacing: -.5px;
        }

        .brand-sub {
            color: rgba(255, 255, 255, .45);
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            padding: 16px 12px;
            flex: 1;
        }

        .menu-label {
            color: rgba(255, 255, 255, .3);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 16px 16px 8px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 11px 16px;
            color: rgba(255, 255, 255, .6);
            text-decoration: none;
            border-radius: var(--radius-sm);
            margin-bottom: 2px;
            transition: all .2s;
            font-size: 14px;
            font-weight: 500;
            position: relative;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, .08);
            color: #fff;
            transform: translateX(3px);
        }

        .menu-item.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, .3), rgba(139, 92, 246, .2));
            color: #fff;
            box-shadow: 0 0 20px rgba(99, 102, 241, .12);
        }

        .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 22px;
            background: var(--primary-light);
            border-radius: 0 3px 3px 0;
        }

        .menu-item i {
            width: 22px;
            text-align: center;
            font-size: 15px;
        }

        .menu-item .badge {
            margin-left: auto;
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255, 255, 255, .08);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-footer .avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            font-size: 14px;
            font-weight: 600;
        }

        .sidebar-footer .user-name {
            color: #fff;
            font-size: 13px;
            font-weight: 600;
        }

        .sidebar-footer .user-role {
            color: rgba(255, 255, 255, .45);
            font-size: 11px;
            text-transform: capitalize;
        }

        /* Main */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
        }

        .top-navbar {
            background: rgba(255, 255, 255, .85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -.5px;
        }

        .page-subtitle {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 2px;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all .2s;
            position: relative;
        }

        .nav-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .nav-btn .notif-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: var(--danger);
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .content-area {
            padding: 28px 32px;
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 1px 2px rgb(0 0 0/.05);
        }

        .card-header {
            padding: 18px 24px;
            background: transparent;
            border-bottom: 1px solid var(--border);
            font-weight: 600;
        }

        .card-body {
            padding: 24px;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: all .3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgb(0 0 0/.08);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 16px;
        }

        .stat-card.primary .stat-icon {
            background: rgba(99, 102, 241, .1);
            color: var(--primary);
        }

        .stat-card.success .stat-icon {
            background: rgba(16, 185, 129, .1);
            color: var(--success);
        }

        .stat-card.warning .stat-icon {
            background: rgba(245, 158, 11, .1);
            color: var(--warning);
        }

        .stat-card.danger .stat-icon {
            background: rgba(239, 68, 68, .1);
            color: var(--danger);
        }

        .stat-card.info .stat-icon {
            background: rgba(6, 182, 212, .1);
            color: var(--info);
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 6px;
            font-weight: 500;
        }

        /* Table */
        .table thead th {
            background: #f8fafc;
            border-bottom: 2px solid var(--border);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 14px 16px;
        }

        .table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        /* Status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge::before {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 50%;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.pending::before {
            background: #f59e0b;
        }

        .status-badge.preparing {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.preparing::before {
            background: #3b82f6;
        }

        .status-badge.ready {
            background: #e0e7ff;
            color: #3730a3;
        }

        .status-badge.ready::before {
            background: #6366f1;
        }

        .status-badge.served,
        .status-badge.completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.served::before,
        .status-badge.completed::before {
            background: #10b981;
        }

        .status-badge.cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-badge.cancelled::before {
            background: #ef4444;
        }

        .status-badge.available {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.available::before {
            background: #10b981;
        }

        .status-badge.occupied {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-badge.occupied::before {
            background: #ef4444;
        }

        .status-badge.reserved {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.reserved::before {
            background: #3b82f6;
        }

        .status-badge.maintenance {
            background: #f3f4f6;
            color: #374151;
        }

        .status-badge.maintenance::before {
            background: #6b7280;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(99, 102, 241, .3);
            transition: all .3s;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), #4338ca);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(99, 102, 241, .4);
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 13px;
            border-radius: var(--radius-sm);
        }

        .form-control,
        .form-select {
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 10px 14px;
            font-size: 14px;
            transition: all .2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
        }

        /* Alert */
        .alert-dismissible {
            border-radius: var(--radius-sm);
            border: none;
            font-size: 14px;
            font-weight: 500;
        }

        /* Table cards (restaurant tables) */
        .table-card {
            border-radius: var(--radius);
            padding: 20px;
            text-align: center;
            transition: all .3s;
            border: 2px solid var(--border);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .table-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgb(0 0 0/.08);
        }

        .table-card.available {
            border-color: #86efac;
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        }

        .table-card.occupied {
            border-color: #fca5a5;
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
        }

        .table-card.reserved {
            border-color: #93c5fd;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
        }

        .table-card.maintenance {
            border-color: #d1d5db;
            background: linear-gradient(135deg, #f9fafb, #f3f4f6);
        }

        .table-card .table-num {
            font-size: 24px;
            font-weight: 800;
            margin: 8px 0 4px;
        }

        .table-card .table-cap {
            font-size: 12px;
            color: var(--text-secondary);
        }

        /* Mobile */
        .sidebar-toggle {
            display: none;
        }

        @media(max-width:991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: flex;
            }

            .content-area {
                padding: 20px 16px;
            }

            .top-navbar {
                padding: 12px 16px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .fade-in {
            animation: fadeIn .4s ease forwards;
        }

        .fade-in-delay-1 {
            animation-delay: .1s;
            opacity: 0;
        }

        .fade-in-delay-2 {
            animation-delay: .2s;
            opacity: 0;
        }

        .fade-in-delay-3 {
            animation-delay: .3s;
            opacity: 0;
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="fas fa-utensils"></i></div>
            <div>
                <div class="brand-text">RestaurantOS</div>
                <div class="brand-sub">Management System</div>
            </div>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-label">Main</div>
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <div class="menu-label">Operations</div>
            <a href="{{ route('orders.index') }}"
                class="menu-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i> Orders
            </a>
            <a href="{{ route('kitchen.index') }}"
                class="menu-item {{ request()->routeIs('kitchen.*') ? 'active' : '' }}">
                <i class="fas fa-fire"></i> Kitchen KDS
            </a>
            <a href="{{ route('tables.index') }}"
                class="menu-item {{ request()->routeIs('tables.*') ? 'active' : '' }}">
                <i class="fas fa-chair"></i> Tables
            </a>
            <a href="{{ route('menu.index') }}" class="menu-item {{ request()->routeIs('menu.*') ? 'active' : '' }}">
                <i class="fas fa-book-open"></i> Menu
            </a>

            @if (in_array(auth()->user()->role ?? '', ['admin', 'manager']))
                <div class="menu-label">Management</div>
                <a href="{{ route('inventory.index') }}"
                    class="menu-item {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                    <i class="fas fa-boxes-stacked"></i> Inventory
                </a>
                <a href="{{ route('staff.index') }}"
                    class="menu-item {{ request()->routeIs('staff.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Staff
                </a>
                <a href="{{ route('reports.index') }}"
                    class="menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i> Reports
                </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="user-role">{{ auth()->user()->role ?? 'admin' }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="ms-auto">
                @csrf
                <button type="submit" class="nav-btn"
                    style="width:32px;height:32px;border:none;background:rgba(255,255,255,.1);color:rgba(255,255,255,.5);"
                    title="Logout">
                    <i class="fas fa-sign-out-alt" style="font-size:13px"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <header class="top-navbar">
            <div>
                <button class="nav-btn sidebar-toggle"
                    onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="page-title">@yield('title', 'Dashboard')</div>
                <div class="page-subtitle">@yield('subtitle', '')</div>
            </div>
            <div class="navbar-actions">
                <a href="{{ route('notifications.index') }}" class="nav-btn" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notif-dot"></span>
                </a>
                <a href="{{ route('settings.index') }}" class="nav-btn" title="Settings"><i class="fas fa-cog"></i></a>
            </div>
        </header>

        <div class="content-area">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @stack('scripts')
</body>

</html>
