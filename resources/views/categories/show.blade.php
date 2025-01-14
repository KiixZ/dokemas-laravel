@extends('layouts.app')

@section('title', $category->name . ' - DOKEMAS')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">{{ $category->name }}</h1>
    
    <div class="row g-4">
        @forelse ($destinations as $destination)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ $destination->image }}" class="card-img-top" alt="{{ $destination->name }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $destination->name }}</h5>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-geo-alt"></i> {{ $destination->location }}
                        </p>
                        <p class="card-text text-muted">{{ Str::limit($destination->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary">Rp {{ number_format($destination->price, 0, ',', '.') }}</span>
                            <a href="{{ route('destinations.show', $destination) }}" class="btn btn-outline-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center">Tidak ada destinasi untuk kategori ini.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $destinations->links() }}
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        transition: transform 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection