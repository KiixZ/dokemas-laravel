@extends('layouts.app')

@section('title', $article->title . ' - Tourism App')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <img src="{{ asset('public/storage/' . $article->image) }}" 
                     class="card-img-top article-detail-img" 
                     alt="{{ $article->title }}"
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                     
                <div class="card-body">
                    <h1 class="card-title">{{ $article->title }}</h1>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="text-muted">
                            <small>By {{ $article->user->name }}</small>
                        </div>
                        <div class="text-muted">
                            <small>{{ $article->created_at->format('F d, Y') }}</small>
                        </div>
                    </div>

                    <div class="article-content">
                        {{ $article->content }}
                    </div>

                    @if($article->ratings->count() > 0)
                        <div class="mt-4">
                            <h5>Rating Average: {{ number_format($article->ratings->avg('rating'), 1) }}/5</h5>
                            <div class="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($article->ratings->avg('rating')))
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                <span class="ms-2 text-muted">({{ $article->ratings->count() }} ratings)</span>
                            </div>
                        </div>
                    @endif

                    @auth
                        <div class="mt-4">
                            <h5>Rate this Article</h5>
                            <form action="{{ route('articles.rate', $article->slug) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <select name="rating" class="form-select" required>
                                        <option value="">Select Rating</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">{{ $i }} Star{{ $i != 1 ? 's' : '' }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Rating</button>
                            </form>
                        </div>

                        <div class="mt-4">
                            <h5>Leave a Comment</h5>
                            <form action="{{ route('articles.comment', $article->slug) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="content" class="form-control" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Comment</button>
                            </form>
                        </div>
                    @endauth

                    <div class="mt-4">
                        <h5>Comments</h5>
                        @forelse($article->comments as $comment)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="card-subtitle mb-2 text-muted">{{ $comment->user->name }}</h6>
                                        <small class="text-muted">{{ $comment->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    <p class="card-text">{{ $comment->content }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No comments yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.article-detail-img {
    max-height: 400px;
    object-fit: cover;
    width: 100%;
}

.article-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.star-rating {
    font-size: 1.2rem;
}
</style>
@endsection

