@extends('layouts.app')

@section('title', 'Explore Destinations - Tourism App')

@section('content')
<div class="container">
    <h1 class="mb-4">Explore Destinations</h1>

    <div class="row mb-4">
        <!-- Search Bar -->
        <div class="col-md-6 mb-3">
            <form action="{{ route('explore.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search destinations..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <!-- Category Filters -->
    <div class="category-filters mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('explore.index') }}" 
               class="btn {{ !request('category') ? 'btn-primary' : 'btn-warning' }}">
                SEMUA
            </a>
            @foreach($categories as $category)
                <a href="{{ route('explore.index', ['category' => $category->id]) }}" 
                   class="btn {{ request('category') == $category->id ? 'btn-primary' : 'btn-warning' }}">
                    {{ strtoupper($category->name) }}
                </a>
            @endforeach
        </div>
    </div>

    @if(request('search'))
        <div class="mb-3">
            <h5>Search results for "{{ request('search') }}"</h5>
            <a href="{{ route('explore.index') }}" class="btn btn-sm btn-outline-secondary">Clear search</a>
        </div>
    @endif

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse($destinations as $destination)
            <div class="col">
                <a href="{{ route('explore.show', $destination->slug) }}" class="text-decoration-none text-reset">
                    <div class="card h-100 clickable-card">
                        <div class="card-img-wrapper">
                            <img src="{{ asset('public/storage/' . $destination->image) }}" 
                                 alt="{{ $destination->name }}" 
                                 class="card-img-top">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $destination->name }}</h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($destination->description, 100) }}</p>
                            <p class="card-text"><small class="text-muted">Category: {{ $destination->category->name }}</small></p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <span class="btn btn-primary">Learn More</span>
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
            <div class="col-12">
                <p>No destinations found. Try adjusting your search criteria.</p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $destinations->appends(request()->query())->links() }}
    </div>
</div>

<style>
.category-filters .btn {
    font-weight: 600;
    min-width: 120px;
}

.gap-2 {
    gap: 0.5rem !important;
}

.card {
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
    padding-top: 56.25%; /* 16:9 Aspect Ratio */
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
</style>
@endsection

