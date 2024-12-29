@extends('layouts.app')

@section('title', $destination->name . ' - Tourism App')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<style>
    #map { height: 300px; }
    .gallery-img {
        cursor: pointer;
        transition: opacity 0.3s;
    }
    .gallery-img:hover {
        opacity: 0.8;
    }
    .star-rating {
        color: #ffc107;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">{{ $destination->name }}</h1>

    <div class="row">
        <div class="col-md-8">
            <img src="{{ asset('public/storage/' . $destination->image) }}" alt="{{ $destination->name }}" class="img-fluid mb-4">
            <p>{{ $destination->description }}</p>
            
            <!-- User information -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Posted by</h5>
                    <p class="card-text">
                        @if($destination->user)
                            <strong>{{ $destination->user->name }}</strong>
                        @elseif($destination->user_id)
                            <strong>Deleted User</strong>
                        @else
                            <strong>Unknown User</strong>
                        @endif
                        <br>
                        <small class="text-muted">on {{ $destination->created_at->format('F d, Y') }}</small>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Informasi</h5>
                    <p><strong>Kategori:</strong> {{ $destination->category->name }}</p>
                    <p><strong>Harga:</strong> Rp {{ number_format($destination->price, 0, ',', '.') }}</p>
                    <p><strong>Lokasi:</strong> {{ $destination->location }}</p>
                    <div class="mb-3">
                        <h6>Rating:</h6>
                        <div class="star-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($destination->ratings->avg('rating')))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                            ({{ $destination->ratings->count() }} ulasan)
                        </div>
                    </div>
                </div>
            </div>

            @auth
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Beri Rating</h5>
                        <form action="{{ route('explore.rate', $destination->slug) }}" method="POST">
                            @csrf
                            <select name="rating" class="form-select mb-2" required>
                                <option value="">Pilih rating</option>
                                <option value="1">1 - Buruk</option>
                                <option value="2">2 - Kurang</option>
                                <option value="3">3 - Cukup</option>
                                <option value="4">4 - Bagus</option>
                                <option value="5">5 - Sangat Bagus</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Kirim Rating</button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>

    @if($destination->gallery && count($destination->gallery) > 0)
        <h2 class="mt-4 mb-3">Galeri Foto</h2>
        <div class="row">
            @foreach($destination->gallery as $index => $image)
                <div class="col-md-3 mb-4">
                    <img src="{{ asset('public/storage/' . $image) }}" alt="Gallery image {{ $index + 1 }}" class="img-fluid gallery-img" data-bs-toggle="modal" data-bs-target="#galleryModal" data-bs-slide-to="{{ $index }}">
                </div>
            @endforeach
        </div>

        <!-- Gallery Modal -->
        <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="galleryModalLabel">Galeri Foto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($destination->gallery as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('public/storage/' . $image) }}" class="d-block w-100" alt="Gallery image {{ $index + 1 }}">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <h2 class="mt-4 mb-3">Lokasi</h2>
    <div id="map" class="mb-4"></div>
    <div class="text-center mb-4">
        <a href="https://www.google.com/maps/search/?api=1&query={{ $destination->latitude }},{{ $destination->longitude }}" 
           class="btn btn-primary" 
           target="_blank" 
           rel="noopener noreferrer">
            Buka di Google Maps
        </a>
    </div>

    <h2 class="mt-4 mb-3">Ulasan</h2>
    @forelse($destination->comments as $comment)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">
                    @if($comment->user)
                        {{ $comment->user->name }}
                    @elseif($comment->user_id)
                        Deleted User
                    @else
                        Unknown User
                    @endif
                </h5>
                <h6 class="card-subtitle mb-2 text-muted">{{ $comment->created_at->format('d M Y H:i') }}</h6>
                <p class="card-text">{{ $comment->content }}</p>
            </div>
        </div>
    @empty
        <p>Belum ada ulasan untuk destinasi ini.</p>
    @endforelse

    @auth
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Tambahkan Ulasan</h5>
                <form action="{{ route('explore.comment', $destination->slug) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="comment" rows="3" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                </form>
            </div>
        </div>
    @endauth
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('map').setView([{{ $destination->latitude }}, {{ $destination->longitude }}], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([{{ $destination->latitude }}, {{ $destination->longitude }}]).addTo(map)
            .bindPopup('{{ $destination->name }}')
            .openPopup();

        // Tambahkan event listener untuk membuka Google Maps saat marker diklik
        map.on('click', function(e) {
            window.open(`https://www.google.com/maps/search/?api=1&query=${e.latlng.lat},${e.latlng.lng}`, '_blank');
        });

        // Initialize modal and carousel
        var galleryModal = new bootstrap.Modal(document.getElementById('galleryModal'));
        var galleryCarousel = new bootstrap.Carousel(document.getElementById('galleryCarousel'));

        // Event listener to open modal and show clicked image
        document.querySelectorAll('.gallery-img').forEach(function(img) {
            img.addEventListener('click', function() {
                var slideIndex = this.getAttribute('data-bs-slide-to');
                galleryCarousel.to(parseInt(slideIndex));
                galleryModal.show();
            });
        });
    });
</script>
@endsection

