@extends('layouts.app')

@section('title', $article->title . ' - Tourism App')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">{{ $article->title }}</h1>

    <div class="row">
        <div class="col-md-8">
            <img src="{{ asset('public/storage/' . $article->image) }}" alt="{{ $article->title }}" class="img-fluid mb-4">
            <p>{{ $article->content }}</p>
            
            <!-- User information -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Posted by</h5>
                    <p class="card-text">
                        @if($article->user)
                            <strong>{{ $article->user->name }}</strong>
                        @elseif($article->user_id)
                            <strong>Deleted User</strong>
                        @else
                            <strong>Unknown User</strong>
                        @endif
                        <br>
                        <small class="text-muted">on {{ $article->created_at->format('F d, Y') }}</small>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Information</h5>
                    <div class="mb-3">
                        <h6>Rating:</h6>
                        <div class="star-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($article->ratings->avg('rating')))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                            ({{ $article->ratings->count() }} ratings)
                        </div>
                    </div>
                </div>
            </div>

            @auth
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Rate this Article</h5>
                        <form action="{{ route('articles.rate', $article) }}" method="POST">
                            @csrf
                            <select name="rating" class="form-select mb-2" required>
                                <option value="">Select rating</option>
                                <option value="1">1 - Poor</option>
                                <option value="2">2 - Fair</option>
                                <option value="3">3 - Good</option>
                                <option value="4">4 - Very Good</option>
                                <option value="5">5 - Excellent</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Submit Rating</button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <h2 class="mt-4 mb-3">Comments</h2>
    @forelse($article->comments as $comment)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <h5 class="card-title">
                        @if($comment->user)
                            {{ $comment->user->name }}
                        @elseif($comment->user_id)
                            Deleted User
                        @else
                            Unknown User
                        @endif
                    </h5>
                    <small class="text-muted">{{ $comment->created_at->format('Y-m-d') }}</small>
                </div>
                <p class="card-text">{{ $comment->content }}</p>
                @if(auth()->check() && (auth()->id() == $comment->user_id || auth()->user()->hasRole(['admin', 'staff'])))
                    <form action="{{ route('articles.comments.delete', ['article' => $article->slug, 'comment' => $comment->id]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <p>No comments yet.</p>
    @endforelse

    @auth
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Add a Comment</h5>
                <form action="{{ route('articles.comment', $article) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="comment" rows="3" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Comment</button>
                </form>
            </div>
        </div>
    @endauth
</div>
@endsection

