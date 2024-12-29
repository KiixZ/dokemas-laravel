<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - DOKEMAS')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 60px;
            --header-height: 60px;
            --primary-color: #000;
            --hover-color: #333;
            --text-color: #fff;
            --muted-color: #666;
            --border-color: #333;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #fafafa;
            margin: 0;
            min-height: 100vh;
        }

        #wrapper {
            display: flex;
            min-height: 100vh;
        }

        #sidebar {
            width: var(--sidebar-width);
            background: var(--primary-color);
            color: var(--text-color);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        #sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        #content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            background: #fff;
            width: calc(100% - var(--sidebar-width));
        }

        #content.collapsed {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        .sidebar-header {
            height: var(--header-height);
            padding: 0 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
        }

        .nav-section {
            padding: 1rem 0;
        }

        .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--muted-color);
            padding: 0.5rem 1rem;
            margin: 0;
        }

        .nav-item {
            padding: 0.25rem 1rem;
            position: relative;
        }

        .nav-link {
            color: var(--text-color);
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
        }

        .nav-link:hover {
            background: var(--hover-color);
            color: var(--text-color);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        .nav-dropdown {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: var(--hover-color);
            border-radius: 6px;
            margin: 0.25rem 0;
        }

        .nav-dropdown.show {
            max-height: 500px;
        }

        .nav-dropdown-item {
            padding: 0.5rem 1rem 0.5rem 2.75rem;
            color: var(--text-color);
            text-decoration: none;
            display: block;
            font-size: 0.875rem;
            transition: background 0.2s;
        }

        .nav-dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-color);
        }

        .dropdown-icon {
            margin-left: auto;
            transition: transform 0.3s;
        }

        .collapsed .dropdown-icon {
            display: none;
        }

        .nav-item.active .dropdown-icon {
            transform: rotate(180deg);
        }

        .collapsed .nav-link span,
        .collapsed .section-title,
        .collapsed .sidebar-header h3 {
            display: none;
        }

        #sidebar.collapsed .nav-dropdown {
            position: static;
            width: auto;
            display: none;
        }

        #sidebar.collapsed .nav-item:hover .nav-dropdown {
            display: none;
        }

        .toggle-sidebar {
            background: transparent;
            border: none;
            color: var(--text-color);
            cursor: pointer;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 4px;
        }

        .toggle-sidebar:hover {
            background: var(--hover-color);
        }

        /* Form Styling */
        .form-container {
            background: #fff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
        }

        .form-control {
            border-radius: 6px;
            border: 1px solid #eaeaea;
            padding: 0.75rem 1rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0,0,0,0.1);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: var(--hover-color);
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }

            #sidebar.collapsed {
                margin-left: 0;
            }

            #content {
                margin-left: 0;
                width: 100%;
            }

            #content.collapsed {
                margin-left: var(--sidebar-width);
            }
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3 class="m-0">Dokemas</h3>
                <button class="toggle-sidebar" id="sidebarCollapse">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="nav-section">
                <h6 class="section-title">MAIN</h6>
                <div class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>

            <div class="nav-section">
                <h6 class="section-title">CONTENT</h6>
                
                <!-- Articles Dropdown -->
                <div class="nav-item">
                    <div class="nav-link" onclick="toggleDropdown(this.parentElement)">
                        <i class="fas fa-newspaper"></i>
                        <span>Articles</span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <div class="nav-dropdown">
                        <a href="{{ route('admin.articles.index') }}" class="nav-dropdown-item">All Articles</a>
                        <a href="{{ route('admin.articles.create') }}" class="nav-dropdown-item">Add New</a>
                    </div>
                </div>

                <!-- Categories Dropdown -->
                <div class="nav-item">
                    <div class="nav-link" onclick="toggleDropdown(this.parentElement)">
                        <i class="fas fa-tags"></i>
                        <span>Categories</span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <div class="nav-dropdown">
                        <a href="{{ route('admin.categories.index') }}" class="nav-dropdown-item">All Categories</a>
                        <a href="{{ route('admin.categories.create') }}" class="nav-dropdown-item">Add New</a>
                    </div>
                </div>

                <!-- Destinations Dropdown -->
                <div class="nav-item">
                    <div class="nav-link" onclick="toggleDropdown(this.parentElement)">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Destinations</span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <div class="nav-dropdown">
                        <a href="{{ route('admin.destinations.index') }}" class="nav-dropdown-item">All Destinations</a>
                        <a href="{{ route('admin.destinations.create') }}" class="nav-dropdown-item">Add New</a>
                    </div>
                </div>
            </div>
            @if(Auth::user()->role === 'admin')
            <div class="nav-section">
                <h6 class="section-title">ADMIN</h6>
                <div class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.staff-registrations') }}" class="nav-link">
                        <i class="fas fa-user-plus"></i>
                        <span>Staff Registrations</span>
                    </a>
                </div>
            </div>
            @endif
            <div class="nav-section mt-auto">
                <div class="nav-item">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Sign Out</span>
                    </a>
                </div>
            </div>
        </nav>

        <div id="content">
            <main class="p-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleDropdown(navItem) {
            const sidebar = document.getElementById('sidebar');
            // If sidebar is collapsed, don't do anything
            if (sidebar.classList.contains('collapsed')) {
                return;
            }

            const dropdown = navItem.querySelector('.nav-dropdown');
            const allDropdowns = document.querySelectorAll('.nav-dropdown');
            const allNavItems = document.querySelectorAll('.nav-item');

            // Close all other dropdowns
            allDropdowns.forEach(d => {
                if (d !== dropdown) {
                    d.classList.remove('show');
                }
            });
            allNavItems.forEach(item => {
                if (item !== navItem) {
                    item.classList.remove('active');
                }
            });

            // Toggle current dropdown
            dropdown.classList.toggle('show');
            navItem.classList.toggle('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const sidebarCollapse = document.getElementById('sidebarCollapse');

            sidebarCollapse.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('collapsed');
            });

            // Handle mobile responsiveness
            function checkWidth() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.add('collapsed');
                    content.classList.remove('collapsed');
                }
            }

            window.addEventListener('resize', checkWidth);
            checkWidth(); // Initial check
        });
    </script>
    @yield('scripts')
</body>
</html>

