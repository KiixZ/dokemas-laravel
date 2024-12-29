@extends('layouts.app')

@section('title', 'Articles - Tourism App')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Latest Tourism Articles</h1>

    <div class="row">
        @foreach($articles as $article)
            <div class="col-md-4 mb-4">
                <a href="{{ route('articles.show', $article->slug) }}" class="text-decoration-none text-dark">
                    <div class="card h-100 hover-card">
                        <img src="{{ asset('public/storage/' . $article->image) }}" 
                             class="card-img-top article-img" 
                             alt="{{ $article->title }}"
                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($article->content, 100) }}</p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $article->created_at->format('M d, Y') }}</small>
                                    <span class="btn btn-primary btn-sm">Read More</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $articles->links() }}
    </div>
</div>

<style>
.article-img {
    height: 200px;
    object-fit: cover;
}

.hover-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.card-title {
    color: #333;
    font-weight: 600;
}

.card-text {
    color: #666;
}

.btn-primary {
    transition: background-color 0.2s ease-in-out;
}

.hover-card:hover .btn-primary {
    background-color: #0056b3;
}
</style>
@endsection

