@extends('layouts.app')

@section('title', 'DOKEMAS - Dolan Keliling Banyumas')

@section('content')
<!-- Hero Section with Background Image -->
<div class="hero-section position-relative">
    <div class="hero-content position-absolute start-0 bottom-0 text-white p-5">
        <div class="container">
            <h1 class="display-3 fw-bold mb-3">DOLAN KELILING BANYUMAS</h1>
            <p class="fs-4 mb-4">Nikmati liburan bersama keluarga melihat indah nya pemandangan dan wisata di sekitar Banyumas</p>
            <a href="{{ route('explore.index') }}" class="btn btn-info text-white btn-lg px-4">Mulai Perjalanan</a>
        </div>
    </div>
</div>

<div class="container mt-5">
    <!-- Main Content Section -->
    <div class="row align-items-center min-vh-75 py-5">
        <div class="col-lg-6 order-lg-1 order-2">
            <img src="https://gowisata.vercel.app/static/media/Journey-amico.55c7d84ad4905eee5d57.webp" alt="Tourism Illustration" class="img-fluid" data-aos="fade-right">
        </div>
        <div class="col-lg-6 order-lg-2 order-1" data-aos="fade-left">
            <h1 class="display-4 fw-bold mb-4">Ingin pergi liburan?</h1>
            <p class="lead mb-4">
                Pernahkah Anda bingung untuk menemukan informasi mengenai tempat wisata yang menarik di Banyumas? atau mencari tempat yang populer dan belum pernah Anda kunjungi di Banyumas? Sekarang DOKEMAS ada untuk menyelesaikan masalah Anda, di DOKEMAS Anda bisa menemukan informasi tentang tempat wisata yang menarik di area Banyumas dan Sekitarnya.
            </p>
            <a href="{{ route('explore.index') }}" class="btn btn-primary btn-lg px-4">Lihat Destinasi</a>
        </div>
    </div>

    <!-- Articles Section -->
    <div class="row align-items-center min-vh-75 py-5">
        <div class="col-lg-6 order-lg-2" data-aos="fade-left">
            <img src="https://gowisata.vercel.app/static/media/article-news.12b3ea90372752a69535.webp" alt="Articles Illustration" class="img-fluid">
        </div>
        <div class="col-lg-6 order-lg-1" data-aos="fade-right">
            <h2 class="display-4 fw-bold mb-4">Berita terbaru tentang wisata?</h2>
            <p class="lead mb-4">
                Di DOKEMAS, ada halaman artikel yang juga memudahkan Anda untuk menemukan berita terbaru tentang tempat wisata.
            </p>
            <a href="{{ route('articles.index') }}" class="btn btn-primary btn-lg px-4">Baca Berita</a>
        </div>
    </div>
    <div class="row align-items-center min-vh-75 py-5">
        <div class="col-lg-6 order-lg-1 order-2">
            <img src="https://gowisata.vercel.app/static/media/Journey-amico.55c7d84ad4905eee5d57.webp" alt="Tourism Illustration" class="img-fluid" data-aos="fade-right">
        </div>
        <div class="col-lg-6 order-lg-2 order-1" data-aos="fade-left">
            <h1 class="display-4 fw-bold mb-4">Kamu punya tempat wisata?</h1>
            <p class="lead mb-4">
            apakah kalian punya tempat wisata didaerah banyumas dan sekitarnya? bingung mau promosiin dimana?DOKEMAS solusinya, kalian bisa mempromosikan tempat wisata kalian agar lebih dikenal oleh masyarakat luas.
            </p>
            <a href="{{ route('newsletter.form') }}" class="btn btn-primary btn-lg px-4">Daftar Disini</a>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
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
    
    .min-vh-75 {
        min-height: 75vh;
    }
    
    .lead {
        font-size: 1.1rem;
        line-height: 1.7;
    }
    
    .btn-primary, .btn-info {
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    
    .btn-primary:hover, .btn-info:hover {
        transform: translateY(-2px);
    }
    
    img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
    }
    
    @media (max-width: 991.98px) {
        .order-lg-1 {
            order: 1 !important;
        }
        .order-lg-2 {
            order: 2 !important;
        }
        .min-vh-75 {
            min-height: 50vh;
        }
        .hero-section {
            height: 70vh;
        }
    }
</style>
@endsection

