<?php

namespace App\Http\Controllers;
use App\Http\Controllers\AdminController;
use App\Models\Destination;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DestinationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin'])->except(['index', 'show', 'rate', 'comment']);
    }

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
        $destination->incrementViews();
    
        return view('explore.show', compact('destination'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.destinations.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric',
                'location' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gallery.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Handle main image
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $imagePath = 'destinations/' . $request->file('image')->hashName();
                $request->file('image')->move(public_path('storage/destinations'), $imagePath);
            } else {
                throw new \Exception('Main image upload failed');
            }

            // Handle gallery images
            $gallery = [];
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    if ($file->isValid()) {
                        $path = 'destinations/gallery/' . $file->hashName();
                        $file->move(public_path('storage/destinations/gallery'), $path);
                        $gallery[] = $path;
                    }
                }
            }

            $destination = new Destination($validated);
            $destination->image = $imagePath;
            $destination->gallery = $gallery;
            $destination->user_id = Auth::id();
            $destination->slug = Str::slug($request->name);

            if (!$destination->save()) {
                throw new \Exception('Failed to save destination');
            }

            Log::info('Destination created successfully', ['id' => $destination->id]);
            return redirect()->route('admin.destinations.index')->with('success', 'Destination created successfully.');

        } catch (\Exception $e) {
            Log::error('Destination creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Cleanup uploaded files if save failed
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            if (!empty($gallery)) {
                foreach ($gallery as $path) {
                    Storage::disk('public')->delete($path);
                }
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create destination: ' . $e->getMessage()]);
        }
    }


    public function edit(Destination $destination)
    {
        $categories = Category::all();
        return view('admin.destinations.edit', compact('destination', 'categories'));
    }

    public function update(Request $request, Destination $destination)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric',
                'location' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Handle main image update
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Delete old image
                if ($destination->image) {
                    Storage::disk('public')->delete($destination->image);
                }
                $imagePath = 'destinations/' . $request->file('image')->hashName();
                $request->file('image')->move(public_path('storage/destinations'), $imagePath);
                $validated['image'] = $imagePath;
            }

            // Handle gallery images
            if ($request->hasFile('gallery')) {
                $gallery = $destination->gallery ?? [];
                foreach ($request->file('gallery') as $file) {
                    if ($file->isValid()) {
                        $path = 'destinations/gallery/' . $file->hashName();
                        $file->move(public_path('storage/destinations/gallery'), $path);
                        $gallery[] = $path;
                    }
                }
                $validated['gallery'] = $gallery;
            }

            $destination->update($validated);

            Log::info('Destination updated successfully', ['id' => $destination->id]);
            return redirect()->route('admin.destinations.index')
                ->with('success', 'Destination updated successfully.');

        } catch (\Exception $e) {
            Log::error('Destination update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update destination: ' . $e->getMessage()]);
        }
    }



    public function destroy(Destination $destination)
    {
        try {
            // Delete associated images
            if ($destination->image) {
                Storage::delete('public/' . $destination->image);
            }
            
            if (!empty($destination->gallery)) {
                foreach ($destination->gallery as $image) {
                    Storage::delete('public/' . $image);
                }
            }

            $destination->delete();
            
            Log::info('Destination deleted successfully', ['id' => $destination->id]);
            return redirect()->route('admin.destinations.index')
                ->with('success', 'Destination deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Destination deletion failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete destination: ' . $e->getMessage()]);
        }
    }


    public function rate(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $destination->ratings()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['rating' => $validated['rating']]
        );

        return back()->with('success', 'Rating submitted successfully');
    }

    public function comment(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $destination->comments()->create([
            'content' => $validated['comment'],
            'user_id' => auth()->id()
        ]);

        return back()->with('success', 'Comment added successfully');
    }
    
}

