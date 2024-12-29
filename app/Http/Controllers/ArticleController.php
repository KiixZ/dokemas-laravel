<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('ratings')->paginate(9);
        return view('articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        $article->load('ratings', 'comments.user');
        return view('articles.show', compact('article'));
    }

    public function rate(Request $request, Article $article)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $article->ratings()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['rating' => $request->rating]
        );

        return redirect()->back()->with('success', 'Rating submitted successfully!');
    }

    public function comment(Request $request, Article $article)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $article->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Comment added successfully!');
    }
}

