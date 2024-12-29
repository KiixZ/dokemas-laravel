<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff'])->except(['index', 'show']);
    }

    public function index()
    {
        if (request()->is('admin/*')) {
            $articles = Article::with('user')->latest()->paginate(10);
            return view('admin.articles.index', compact('articles'));
        }
        
        $articles = Article::with(['user', 'ratings'])->latest()->paginate(6);
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('articles', 'public');
            }

            $article = Article::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'image' => $imagePath ?? null,
                'user_id' => Auth::id(),
                'slug' => Str::slug($validated['title'])
            ]);

            return redirect()->route('admin.articles.index')
                ->with('success', 'Article created successfully.');
        } catch (\Exception $e) {
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            return back()->withInput()->withErrors(['error' => 'Failed to create article.']);
        }
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            if ($request->hasFile('image')) {
                if ($article->image) {
                    Storage::disk('public')->delete($article->image);
                }
                $imagePath = $request->file('image')->store('articles', 'public');
                $article->image = $imagePath;
            }

            $article->title = $validated['title'];
            $article->content = $validated['content'];
            $article->slug = Str::slug($validated['title']);
            $article->save();

            return redirect()->route('admin.articles.index')
                ->with('success', 'Article updated successfully.');
        } catch (\Exception $e) {
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            return back()->withInput()->withErrors(['error' => 'Failed to update article.']);
        }
    }

    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }
        
        $article->delete();
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article deleted successfully.');
    }

    public function show(Article $article)
    {
        $article->load(['user', 'comments.user', 'ratings']);
        return view('articles.show', compact('article'));
    }
    public function rate(Request $request, Article $article)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $article->ratings()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['rating' => $validated['rating']]
        );

        return back()->with('success', 'Rating submitted successfully');
    }

    public function comment(Request $request, Article $article)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $article->comments()->create([
            'content' => $validated['comment'],
            'user_id' => auth()->id()
        ]);

        return back()->with('success', 'Comment added successfully');
    }
}

