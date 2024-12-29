<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Destination::with(['category', 'ratings', 'user']);

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $destinations = $query->paginate(9);
        
        return view('explore.index', compact('destinations', 'categories'));
    }

    public function show(Destination $destination)
    {
        $destination->load('ratings', 'comments.user', 'category', 'user');
        $destination->gallery = $destination->gallery ?? [];
        
        return view('explore.show', compact('destination'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'location' => 'required|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validatedData['user_id'] = Auth::id();

        $destination = Destination::create($validatedData);

        return redirect()->route('explore.show', $destination->slug)->with('success', 'Destination created successfully.');
    }

    public function rate(Request $request, Destination $destination)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $destination->ratings()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['rating' => $request->rating]
        );

        return redirect()->back()->with('success', 'Rating submitted successfully!');
    }

    public function comment(Request $request, Destination $destination)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $destination->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Comment added successfully!');
    }
}

