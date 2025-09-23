<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Dashboard Murid</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    
    <style>
        .btn {
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-akun {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }

        .btn-akun:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #002fff 0%, #764ba2 100%);
            transition: all 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar.collapsed {
            width: 80px !important;
        }
        
        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .logo-text {
            display: none;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            margin: 2px 0;
            padding: 10px 15px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.2);
        }
        
        .sidebar.collapsed .nav-link {
            text-align: center;
            padding: 15px 10px;
        }
        
        .sidebar.collapsed .nav-link:hover {
            transform: none;
        }
        
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .main-content {
            min-height: 100vh;
            margin-left: 280px;
            transition: all 0.3s ease;
        }
        
        .main-content.expanded {
            margin-left: 80px;
        }
        
        .dashboard-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .card-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .bg-gradient-success {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        }
        
        .toggle-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .dropdown-menu {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            z-index: 1060 !important; /* Fixed: Menambahkan z-index yang lebih tinggi */
        }

        .navbar {
            z-index: 1040; /* Fixed: Memastikan navbar tidak menghalangi dropdown */
        }

        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .search-container {
            position: relative;
            max-width: 300px;
        }

        .search-section {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .search-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            pointer-events: none; /* Menambahkan ini agar icon tidak menghalangi input */
        }
        
        .table thead th {
            background-color: #f8f9fa;
            border: none;
            font-weight: 600;
            text-align: center;
            padding: 1rem;
            vertical-align: middle;
        }
        
        .table tbody td {
            text-align: center;
            vertical-align: middle;
            padding: 1rem;
            border-color: #e9ecef;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            align-items: center;
        }
        
        .btn-action {
            padding: 0.5rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }
        
        .btn-edit {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .btn-edit:hover {
            background-color: #ffc107;
            color: white;
        }
        
        .btn-delete {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .btn-delete:hover {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-view {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .btn-view:hover {
            background-color: #28a745;
            color: white;
        }

        .badge-hari {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }
        
        .time-display {
            background-color: #e3f2fd;
            color: #1565c0;
            padding: 0.5rem 1rem;
            border-radius: 15px;
            font-weight: 500;
        }
        
        .unit-display {
            background-color: #e8f5e8;
            color: #2e7d32;
            padding: 0.5rem 1rem;
            border-radius: 15px;
            font-weight: 500;
        }
        
        .pelatih-display {
            background-color: #fff3e0;
            color: #f57c00;
            padding: 0.5rem 1rem;
            border-radius: 15px;
            font-weight: 500;
        }
        
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 1rem;

        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .stats-content {
            text-align: left;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .stats-label {
            color: #666;
            font-size: 1rem;
            font-weight: 600;
        }
        
        .welcome-section {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .welcome-section .alert {
            border: none;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 10px;
        }
        
        .filter-section {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px !important;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.expanded {
                margin-left: 0;
            }
            
            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }
            
            .mobile-overlay.show {
                display: block;
            }
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .profile-info{
            padding: 0.8rem;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar" style="width: 280px;">
        <div class="p-3">
            <!-- Header with toggle -->
            <div class="d-flex align-items-center mb-4">
                <button class="toggle-btn d-none d-md-block me-3" id="toggleSidebar">
                    <i class="bi bi-list"></i>
                </button>  
            </div>
            <div class="logo-text d-flex justify-content-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 80px;">
            </div>

            <!-- Navigation -->
            <nav class="nav nav-pills flex-column">
                <a class="nav-link {{ request()->routeIs('murid.dashboard') ? 'active' : '' }}" 
                   href="{{ route('murid.dashboard') }}">
                    <i class="bi bi-house-door me-2"></i>
                    <span class="nav-text">Dashboard</span>
                </a>

                <a class="nav-link {{ request()->routeIs('murid.profile') ? 'active' : '' }}" 
                   href="#">
                    <i class="bi bi-person me-2"></i>
                    <span class="nav-text">Profil Saya</span>
                </a>
                
                <a class="nav-link {{ request()->routeIs('murid.jadwal') ? 'active' : '' }}" 
                   href="#">
                    <i class="bi bi-calendar3 me-2"></i>
                    <span class="nav-text">Jadwal Latihan</span>
                </a>
                
                <a class="nav-link {{ request()->routeIs('murid.jadwal-ujian') ? 'active' : '' }}" 
                   href="#">
                    <i class="bi bi-award me-2"></i>
                    <span class="nav-text">Jadwal Ujian</span>
                </a>
                
                <a class="nav-link {{ request()->routeIs('murid.absensi') ? 'active' : '' }}" 
                   href="#">
                    <i class="bi bi-check2-square me-2"></i>
                    <span class="nav-text">Absensi</span>
                </a>

                <a class="nav-link {{ request()->routeIs('murid.tagihan-iuran') ? 'active' : '' }}" 
                   href="#">
                    <i class="bi bi-wallet me-2"></i>
                    <span class="nav-text">Tagihan Iuran</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <!-- Mobile menu button -->
                <button class="btn btn-outline-primary d-md-none me-3" id="mobileMenuBtn">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="ms-auto">
                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-akun" 
                                type="button" 
                                id="userDropdown" 
                                data-bs-toggle="dropdown" 
                                aria-expanded="false">
                            <i class="bi bi-person-circle me-2 fs-5"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger w-100 text-start border-0 bg-transparent">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="container-fluid">
            <!-- Display Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Display Error Message -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Display Validation Errors -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
        
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle sidebar functionality
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('toggleSidebar');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileOverlay = document.getElementById('mobileOverlay');
        let isCollapsed = false;

        // Desktop toggle
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                isCollapsed = !isCollapsed;
                
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                    sidebar.style.width = '80px';
                    mainContent.classList.add('expanded');
                    toggleBtn.innerHTML = '<i class="bi bi-list"></i>';
                } else {
                    sidebar.classList.remove('collapsed');
                    sidebar.style.width = '280px';
                    mainContent.classList.remove('expanded');
                    toggleBtn.innerHTML = '<i class="bi bi-list"></i>';
                }
            });
        }

        // Mobile menu toggle
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                mobileOverlay.classList.toggle('show');
            });
        }

        // Close mobile menu when overlay is clicked
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                mobileOverlay.classList.remove('show');
            });
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                mobileOverlay.classList.remove('show');
            }
        });

        // Close mobile menu when nav link is clicked
        document.querySelectorAll('.sidebar .nav-link').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('show');
                    mobileOverlay.classList.remove('show');
                }
            });
        });

        // Fixed: Memastikan dropdown berfungsi dengan baik
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap dropdown
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });

        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    @stack('scripts')
    @yield('scripts')
</body>
</html>