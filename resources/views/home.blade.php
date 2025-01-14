@extends('layouts.app')

@section('title', 'DOKEMAS - Dolan Keliling Banyumas')

@section('content')
<!-- Hero Section with Background Image -->
<div class="hero-section position-relative">
    <div class="hero-content position-absolute start-0 bottom-0 text-white p-5">
        <div class="container">
            <h1 class="display-3 fw-bold mb-3">DOLAN KELILING BANYUMAS</h1>
            <p class="fs-4 mb-4">Nikmati liburan bersama keluarga melihat indah nya pemandangan dan wisata di sekitar Banyumas</p>
        </div>
    </div>
</div>

<div class="container mt-5">
    <!-- Latest Destinations Section -->
    <h2 class="mb-4 fw-bold">Destinasi Terbaru</h2>
    <div class="row" data-aos="fade-up" data-aos-duration="1000" data-aos-once="false">
        <div class="col-lg-8">
            <div class="row g-4">
                @foreach($latestDestinations as $destination)
                <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100" data-aos-once="false">
                    
                    <a href="{{ route('explore.show', $destination) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm">
                            @if($destination->image)
                                <img src="{{ asset('public/storage/' . $destination->image) }}" class="card-img-top" alt="{{ $destination->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" class="card-img-top" alt="{{ $destination->name }}" style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <span class="badge bg-dark mb-2">{{ $destination->category->name }}</span>
                                <h5 class="card-title fw-bold text-dark">{{ $destination->name }}</h5>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-calendar-event"></i> 
                                    {{ $destination->created_at->format('d M Y') }}
                                </p>
                                <p class="card-text text-muted">{{ Str::limit($destination->description, 100) }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Trending Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="card-title fw-bold mb-3">
                        <i class="bi bi-fire text-danger"></i> Trending
                    </h3>
                    <div class="trending-list">
                        @foreach($trendingDestinations as $index => $trending)
                        <a href="{{ route('explore.show', $trending) }}" class="text-decoration-none">
                            <div class="d-flex align-items-center mb-3">
                                <span class="h5 fw-bold text-muted mb-0 me-3">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <div>
                                    <h6 class="mb-0 text-dark">{{ $trending->name }}</h6>
                                    <small class="text-muted">{{ $trending->views }} views</small>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Categories Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title fw-bold mb-3">Kategori</h3>
                    <div class="categories-list">
                        @foreach($categories as $category)
                        <a href="{{ route('explore.index', ['category' => $category->id]) }}" class="text-decoration-none">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-dark">{{ $category->name }}</span>
                                <span class="badge bg-light text-dark">{{ $category->destinations_count }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Sections -->
    <div class="row align-items-center min-vh-75 py-5">
        <div class="col-lg-6 order-lg-1 order-2" 
             data-aos="fade-right"
             data-aos-offset="300"
             data-aos-duration="800"
             data-aos-easing="ease-in-out"
             data-aos-mirror="true"
             data-aos-once="false">
            <img src="https://gowisata.vercel.app/static/media/Journey-amico.55c7d84ad4905eee5d57.webp" 
                 alt="Tourism Illustration" 
                 class="img-fluid">
        </div>
        <div class="col-lg-6 order-lg-2 order-1"
             data-aos="fade-left"
             data-aos-offset="300"
             data-aos-duration="800"
             data-aos-easing="ease-in-out"
             data-aos-mirror="true"
             data-aos-once="false">
            <h1 class="display-4 fw-bold mb-4">Kamu punya tempat wisata?</h1>
            <p class="lead mb-4">
                apakah kalian punya tempat wisata didaerah banyumas dan sekitarnya? bingung mau promosiin dimana? DOKEMAS solusinya, kalian bisa mempromosikan tempat wisata kalian agar lebih dikenal oleh masyarakat luas.
            </p>
            <a href="{{ route('newsletter.form') }}" class="btn btn-dark btn-lg px-4">Daftar Disini</a>
        </div>
    </div>

    <!-- Rest of your existing sections -->
    <!-- ... -->
</div>
@endsection

@section('styles')
<style>
    /* Existing styles */
    body {
        background-color: #f8f9fa;
    }
    
    .hero-section {
        height: 100vh;
        background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)),
                    url('https://images.unsplash.com/photo-1587474260584-136574528ed5?auto=format&fit=crop&q=80') no-repeat center center;
        background-size: cover;
        position: relative;
    }
    
    .hero-content {
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        width: 100%;
        padding-top: 100px !important;
    }
    
    /* New styles for latest destinations and sidebar */
    .card {
        transition: transform 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .trending-list a:hover h6 {
        color: #007bff !important;
    }
    
    .categories-list a:hover span {
        color: #007bff !important;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.5em 1em;
    }
    
    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .sidebar {
            margin-top: 2rem;
        }
    }
</style>
@endsection