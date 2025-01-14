<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    @yield('styles')
    <style>
        .dropdown-menu {
            right: 0;
            left: auto;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-nav {
            align-items: center;
        }
        .social-icon {
    transition: all 0.3s ease;
    display: inline-block;
}

.social-icon:hover {
    transform: translateY(-3px);
    color: #0d6efd !important;
}

/* Hover effects for contact links */
.contact-link {
    transition: all 0.3s ease;
}

.contact-link:hover {
    transform: translateX(5px);
    color: #212529 !important;
}

.contact-link:hover i {
    transform: scale(1.1);
}

/* WhatsApp button hover effect */
.whatsapp-btn {
    transition: all 0.3s ease;
}

.whatsapp-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
}
        @media (min-width: 992px) {
            .navbar-nav {
                flex-direction: row;
            }
            .navbar-nav .nav-item {
                margin-left: 1rem;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('explore.index') }}">
                        <i class="fas fa-compass"></i> Explore
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('articles.index') }}">
                        <i class="fas fa-newspaper"></i> Articles
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">
                        <i class="fas fa-circle-info"></i> About Us
                    </a>
                </li>
                @guest
                    @if (Route::has('newsletter.form'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('newsletter.form') }}">
                                <i class="fas fa-user-plus"></i> Daftar Pengelola
                            </a>
                        </li>
                    @endif
                    
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'staff')
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
        </div>
</nav>

        <main class="py-4">
            @yield('content')
        </main>
<footer class="text-dark">
    <div class="container py-5">
        <!-- Main Footer Content -->
        <div class="row py-4">
            <!-- Brand Column -->
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h3 class="h2 mb-4 position-relative">
                    About Us
                    <span class="d-block bg-primary" style="width: 80px; height: 4px; margin-top: 10px;"></span>
                </h3>
                <p class="text-muted mb-4">
                    Jelajahi keindahan wisata Banyumas bersama kami. Temukan destinasi menarik dan pengalaman tak terlupakan.
                </p>
                
                <!-- Social Media Links with hover effect -->
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="https://facebook.com/dokemas.id" class="text-muted social-icon">
                        <i class="fab fa-facebook fs-4"></i>
                    </a>
                    <a href="https://twitter.com/dokemas" class="text-muted social-icon">
                        <i class="fab fa-twitter fs-4"></i>
                    </a>
                    <a href="https://instagram.com/dolananbanyumas" class="text-muted social-icon">
                        <i class="fab fa-instagram fs-4"></i>
                    </a>
                    <a href="https://tiktok.com/@dolananbanyumas" class="text-muted social-icon">
                        <i class="fab fa-tiktok fs-4"></i>
                    </a>
                </div>
            </div>

            <!-- Connect With Us - Moved to right -->
            <div class="col-lg-4">
                <h4 class="h5 mb-4 position-relative">
                    Connect With Us
                    <span class="d-block bg-primary" style="width: 50px; height: 4px; margin-top: 10px;"></span>
                </h4>
                <div class="mb-4">
                    <a href="mailto:info@dokemas.saputraa.com" class="d-flex align-items-center text-muted text-decoration-none mb-3 contact-link">
                        <i class="fas fa-envelope text-primary me-3"></i>
                        <span>info@dokemas.saputraa.com</span>
                    </a>
                    <a href="tel:+62851xxxxxxx" class="d-flex align-items-center text-muted text-decoration-none mb-3 contact-link">
                        <i class="fas fa-phone text-primary me-3"></i>
                        <span>+62851 - xxxx - xxxx</span>
                    </a>
                    <a href="https://wa.me/62851xxxxxxx" target="_blank" class="btn btn-success d-flex align-items-center justify-content-center gap-2 py-2 whatsapp-btn">
                        <i class="fab fa-whatsapp fs-5"></i>
                        <span>Chat on WhatsApp</span>
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-top border-secondary pt-4 mt-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0 small">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="text-muted mb-0 small">
                        Designed and developed with 
                        <i class="fas fa-heart text-danger mx-1"></i>
                        in Indonesia
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 1000,
                once: true,
                offset: 100,
                easing: 'ease-in-out'
            });
        });
    </script>
    @yield('scripts')
</body>
</html>

