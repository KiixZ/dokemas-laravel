@extends('layouts.app')

@section('title', 'Explore Destinations - DOKEMAS')

@section('content')
<div class="container">
    <h1 class="mb-4">Explore Destinations</h1>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <form action="{{ route('explore.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search destinations..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-dark">Search</button>
            </form>
        </div>
    </div>

    <!-- Category Section -->
    <div class="category-wrapper mb-4">
        <h2 class="mb-4">Browse by Category</h2>
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="{{ route('explore.index') }}" class="text-decoration-none">
                <div class="category-pill {{ !request('category') ? 'active' : '' }}">
                    All
                </div>
            </a>
            @foreach($categories as $category)
                <a href="{{ route('explore.index', ['category' => $category->id]) }}" class="text-decoration-none">
                    <div class="category-pill {{ request('category') == $category->id ? 'active' : '' }}">
                        {{ $category->name }}
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Search Results Info -->
    @if(request('search'))
        <div class="mb-3">
            <h5>Search results for "{{ request('search') }}"</h5>
            <a href="{{ route('explore.index') }}" class="btn btn-sm btn-outline-secondary">Clear search</a>
        </div>
    @endif

    <!-- Destinations Grid -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse($destinations as $destination)
            <div class="col">
                <a href="{{ route('explore.show', $destination->slug) }}" class="text-decoration-none text-reset">
                    <div class="card h-100 clickable-card">
                        <div class="card-img-wrapper">
                            @if($destination->image)
                                <img src="{{ asset('public/storage/' . $destination->image) }}" 
                                     alt="{{ $destination->name }}" 
                                     class="card-img-top">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" 
                                     alt="{{ $destination->name }}" 
                                     class="card-img-top">
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $destination->name }}</h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($destination->description, 100) }}</p>
                            <p class="card-text"><small class="text-muted">Category: {{ $destination->category->name }}</small></p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="text-warning">
                                    @php
                                        $rating = $destination->ratings->avg('rating') ?? 0;
                                    @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= round($rating))
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-content">
                        <div class="mb-4">
                            <i class="bi bi-file-text text-secondary" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-dark mb-2">Belum ada destinasi</h4>
                        <p class="text-muted">Destinasi untuk kategori ini sedang dalam proses pembuatan.</p>
                    </div>
                </div>
            @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $destinations->appends(request()->query())->links() }}
    </div>
</div>

@endsection

@section('styles')
<style>
  .empty-state {
    min-height: calc(100vh - 400px); /* Adjust based on your header/footer height */
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 2rem;
}

.empty-state-content {
    text-align: center;
    max-width: 400px;
    margin: 0 auto;
}
/* Category Styles */
.category-wrapper {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.category-pill {
    padding: 8px 24px;
    border-radius: 50px;
    background-color: #f8f9fa;
    color: #212529;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    white-space: nowrap;
}

.category-pill:hover {
    background-color: #e9ecef;
}

.category-pill.active {
    background-color: black;
    color: white;
}

.gap-2 {
    gap: 0.75rem !important;
}

/* Card Styles */
.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.clickable-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card-img-wrapper {
    position: relative;
    width: 100%;
    padding-top: 66.67%; /* 3:2 Aspect Ratio */
    overflow: hidden;
}

.card-img-wrapper img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.card-text {
    flex-grow: 1;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .category-wrapper {
        padding: 1rem;
    }
    
    .category-pill {
        padding: 6px 16px;
        font-size: 0.85rem;
    }
}
</style>
@endsection