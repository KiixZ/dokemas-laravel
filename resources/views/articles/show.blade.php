@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" class="card-img-top" alt="{{ $article->title }}">
                @endif
                <div class="card-body">
                    <h1 class="card-title">{{ $article->title }}</h1>
                    <div class="card-text">{!! $article->content !!}</div>
                    
                    @if($article->ratings->count() > 0)
                        <div class="mb-3">
                            <h5>Average Rating</h5>
                            <p>{{ number_format($article->ratings->avg('rating'), 1) }} / 5 ({{ $article->ratings->count() }} ratings)</p>
                        </div>
                    @endif

                    @auth
                        <form action="{{ route('articles.rate', $article) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="form-group">
                                <label for="rating">Rate this article:</label>
                                <select name="rating" id="rating" class="form-control">
                                    <option value="1">1 - Poor</option>
                                    <option value="2">2 - Fair</option>
                                    <option value="3">3 - Good</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Submit Rating</button>
                        </form>
                    @endauth

                    <h5>Comments</h5>
                    @forelse($article->comments as $comment)
                        <div class="card mb-2">
                            <div class="card-body">
                                <p class="card-text">{{ $comment->content }}</p>
                                <p class="card-text"><small class="text-muted">By {{ $comment->user->name }} on {{ $comment->created_at->format('M d, Y') }}</small></p>
                            </div>
                        </div>
                    @empty
                        <p>No comments yet.</p>
                    @endforelse

                    @auth
                        <form action="{{ route('articles.comment', $article) }}" method="POST" class="mt-3">
                            @csrf
                            <div class="form-group">
                                <label for="comment">Add a comment:</label>
                                <textarea name="comment" id="comment" rows="3" class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Submit Comment</button>
                        </form>
                    @endauth

                    <div class="mt-3">
                        <a href="{{ route('articles.index') }}" class="btn btn-secondary">Back to Articles</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

