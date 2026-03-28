<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Admin Dashboard') }}</title>

    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        :root {
            --sidebar-width: 280px;
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.4);
            --secondary: #0ea5e9;
            --bg-body: #020617;
            --bg-card: #0f172a;
            --bg-sidebar: rgba(15, 23, 42, 0.95);
            --border-soft: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #a0aec0;
            --radius-xl: 24px;
            --radius-lg: 16px;
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        body {
            font-size: 0.88rem;
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            overflow-x: auto;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .outfit {
            font-family: 'Outfit', sans-serif;
        }

        .app-container { display: flex; min-height: 100vh; }

        /* Sidebar: Premium Glassmorphism */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--border-soft);
            height: 100vh;
            position: fixed;
            left: 0; top: 0; z-index: 1050;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            box-shadow: 15px 0 50px rgba(0,0,0,0.5);
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                left: -var(--sidebar-width);
                box-shadow: none;
            }
            .sidebar.show {
                left: 0;
                box-shadow: 15px 0 50px rgba(0,0,0,0.8);
            }
            #sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.6);
                backdrop-filter: blur(4px);
                z-index: 1040;
            }
            #sidebar-overlay.show { display: block; }
        }

        .sidebar-header {
            padding: 2.5rem 1.75rem;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .sidebar-logo {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 25px var(--primary-glow);
            transform: rotate(-3deg);
        }

        .nav-link {
            display: flex; align-items: center; gap: 14px;
            padding: 0.8rem 1.25rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 14px;
            margin: 0.25rem 1rem;
            transition: var(--transition);
            font-weight: 500;
            font-size: 0.85rem;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
            transform: translateX(8px);
        }

        .nav-link.active {
            background: linear-gradient(90deg, var(--primary), #4f46e5);
            color: #fff;
            box-shadow: 0 10px 20px -5px var(--primary-glow);
        }

        /* Top Navbar */
        .navbar-top {
            height: 80px;
            background: rgba(2, 6, 23, 0.85);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-soft);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2.5rem;
            position: sticky; top: 0; z-index: 1000;
            overflow-x: auto;
        }

        /* Main Content */
        .main-panel {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.05), transparent 40%);
            min-width: 0; /* Prevents flex items from overflowing kawan */
        }

        .content-body { padding: 2rem; overflow-x: auto; }

        /* Premium Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-xl);
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
            overflow: hidden;
            transition: var(--transition);
        }

        .card:hover { border-color: rgba(255,255,255,0.15); }

        .card-body p { color: var(--text-muted); }

        /* Modern Tables - ALWAYS RESPONSIVE kawan */
        .table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
            border-radius: var(--radius-lg);
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-soft);
        }

        .table { --bs-table-bg: transparent; color: var(--text-main); margin-bottom: 0; min-width: 800px; }
        
        .table thead th {
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-muted);
            font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em;
            padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-soft);
            font-weight: 700;
        }

        .table td {
            padding: 1.5rem !important;
            border-bottom: 1px solid var(--border-soft);
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background: rgba(255,255,255,0.02) !important;
        }

        /* Pill Badges */
        .badge-pill {
            padding: 0.4rem 1rem; border-radius: 50px;
            font-weight: 600; font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        /* Form Styling (Not too big kawan!) */
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 12px !important;
            padding: 0.65rem 1rem !important;
            color: #fff !important;
            transition: var(--transition);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3) !important;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 4px var(--primary-glow) !important;
        }

        .btn { border-radius: 12px; font-weight: 600; transition: var(--transition); }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #4338ca);
            border: none;
            box-shadow: 0 4px 15px var(--primary-glow);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--primary-glow);
        }

        /* Utility */
        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-lg);
        }

        /* Global Contrast Fixes kawan */
        .text-dark, .text-black { color: #e2e8f0 !important; }
        .bg-white { background-color: rgba(255, 255, 255, 0.05) !important; color: #fff !important; }

        /* Custom Pagination (Premium Glassmorphism) */
        .pagination { gap: 8px; border: none; margin-bottom: 0; }
        .page-item .page-link {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid var(--border-soft) !important;
            color: var(--text-muted) !important;
            border-radius: 12px !important;
            padding: 0.6rem 1rem !important;
            transition: var(--transition);
            font-weight: 600;
        }
        .page-item .page-link:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
            transform: translateY(-2px);
        }
        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary), var(--secondary)) !important;
            border: none !important;
            color: #fff !important;
            box-shadow: 0 5px 15px var(--primary-glow);
        }
        .page-item.disabled .page-link {
            background: rgba(255, 255, 255, 0.02) !important;
            border-color: transparent !important;
            color: rgba(255, 255, 255, 0.1) !important;
        }
        
        @media (max-width: 991.98px) {
            .main-panel { margin-left: 0; }
            .content-body { padding: 1.5rem; }
        }

        /* Prevent button text from being hidden on light bgs kawan */
        .btn-outline-light:hover, .btn-light { color: var(--bg-body) !important; }
        
        /* Font size reductions kawan */
        h1 { font-size: 1.75rem !important; }
        h2 { font-size: 1.5rem !important; }
        h3 { font-size: 1.25rem !important; }
        .display-5 { font-size: 2rem !important; }

        /* Select2 Premium Dark Theme Global kawan */
        .select2-container--default .select2-selection--single {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            height: 44px !important;
            border-radius: 12px !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.3s ease;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #ffffff !important;
            padding-left: 15px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
            right: 10px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: rgba(255,255,255,0.5) transparent transparent transparent !important;
        }
        
        /* THE ULTIMATE DARK OVERRIDE kawan */
        .select2-dropdown, 
        .select2-results, 
        .select2-results__options, 
        .select2-search,
        .select2-search__field,
        .select2-results__option {
            background-color: #0f172a !important; /* Biru Gelap kawan */
            color: #ffffff !important;
        }

        .select2-dropdown {
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.8) !important;
            z-index: 9999 !important;
            margin-top: 5px;
            overflow: hidden !important;
        }

        .select2-search__field {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
        }

        .select2-results__option {
            padding: 10px 15px !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03) !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected],
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: var(--primary) !important;
            color: #ffffff !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: var(--primary) !important;
        }
        
        /* Fix scrollbar Select2 kawan */
        .select2-results__options::-webkit-scrollbar {
            width: 5px;
        }
        .select2-results__options::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
    </style>

</head>
<body>
    <div id="sidebar-overlay"></div>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="bi bi-rocket-takeoff-fill text-white fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fw-bold tracking-tight text-white">PROJEK TWO</h5>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.6rem; letter-spacing: 1px;">Admin Dashboard</small>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                @include('layouts.sidebar')
            </nav>

            <div class="p-4 border-top border-secondary border-opacity-10 mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 rounded-3 py-2 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Panel -->
        <div class="main-panel">
            <header class="navbar-top">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-link text-white p-0 d-lg-none" id="sidebar-toggle">
                        <i class="bi bi-list fs-3"></i>
                    </button>
                    <div class="d-none d-md-block">
                        <h6 class="mb-0 fw-semibold">{{ $header ?? 'Dashboard' }}</h6>
                    </div>
                </div>

                <div class="ms-auto d-flex align-items-center gap-3">
                    @if(isset($header_actions))
                        <div class="d-flex align-items-center gap-2 me-2">
                            {{ $header_actions }}
                        </div>
                    @endif
                    
                    <div class="d-flex align-items-center gap-4">
                        <div class="text-end d-none d-sm-block">
                            <div class="small fw-bold text-white">{{ Auth::user()->name }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ strtoupper(Auth::user()->role) }}</div>
                        </div>
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6, #2563eb);">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <main class="content-body">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3 p-3" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important;">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        <div class="text-success fw-medium">{{ session('success') }}</div>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    @stack('js')

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggle = document.getElementById('sidebar-toggle');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
        }

        toggle?.addEventListener('click', toggleSidebar);
        overlay?.addEventListener('click', toggleSidebar);
    </script>

</body>
</html>
