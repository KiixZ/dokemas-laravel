@extends('layouts.app')

@section('title', 'Articles - Tourism App')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Latest Tourism Articles</h1>
    <div class="row">
        @foreach($articles as $article)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $article->image) }}" class="card-img-top" alt="{{ $article->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $article->title }}</h5>
                        <p class="card-text">{{ Str::limit($article->content, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-primary">Read More</a>
                            <div class="text-warning">
                                @php
                                    $rating = $article->ratings->avg('rating') ?? 0;
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
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center">
        {{ $articles->links() }}
    </div>
</div>
@endsection

