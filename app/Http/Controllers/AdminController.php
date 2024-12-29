<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Destination;
use App\Models\Category;
use App\Models\User;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff']);
    }

    public function dashboard()
    {
        $userCount = User::count();
        $articleCount = Article::count();
        $destinationCount = Destination::count();
        $visitorCount = User::sum('visitor_count');

        return view('admin.dashboard', compact('userCount', 'articleCount', 'destinationCount', 'visitorCount'));
    }

    // Destinations Methods
    public function destinationsIndex()
    {
        $destinations = Destination::with(['category', 'user'])->paginate(10);
        return view('admin.destinations.index', compact('destinations'));
    }

    public function createDestination()
    {
        $categories = Category::all();
        return view('admin.destinations.create', compact('categories'));
    }

    public function storeDestination(Request $request)
    {
        Log::info('Attempting to store destination');

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

        Log::info('Validation passed');

        DB::beginTransaction();

        try {
            $imagePath = $request->file('image')->store('destinations', 'public');
            $validatedData['image'] = $imagePath;
            $validatedData['user_id'] = Auth::id(); // Add user_id to track ownership

            Log::info('Main image stored: ' . $imagePath);

            if ($request->hasFile('gallery')) {
                $galleryPaths = [];
                foreach ($request->file('gallery') as $image) {
                    $path = $image->store('destinations', 'public');
                    $galleryPaths[] = $path;
                    Log::info('Gallery image stored: ' . $path);
                }
                $validatedData['gallery'] = $galleryPaths;
            }

            $destination = new Destination($validatedData);
            $destination->save();

            Log::info('Destination saved with ID: ' . $destination->id);

            DB::commit();

            return redirect()->route('admin.destinations.index')->with('success', 'Destination created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating destination: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while creating the destination: ' . $e->getMessage());
        }
    }

    public function editDestination(Destination $destination)
    {
        $this->authorize('update', $destination);
        $categories = Category::all();
        return view('admin.destinations.edit', compact('destination', 'categories'));
    }

    public function updateDestination(Request $request, Destination $destination)
    {
        $this->authorize('update', $destination);

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'location' => 'required|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($destination->image);
            $validatedData['image'] = $request->file('image')->store('destinations', 'public');
        }

        if ($request->hasFile('gallery')) {
            foreach ($destination->gallery ?? [] as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            $galleryPaths = [];
            foreach ($request->file('gallery') as $image) {
                $galleryPaths[] = $image->store('destinations', 'public');
            }
            $validatedData['gallery'] = $galleryPaths;
        }

        $destination->update($validatedData);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination updated successfully.');
    }

    public function destroyDestination(Destination $destination)
    {
        // Check if user has permission to delete this destination
        if (!Auth::user()->isAdmin() && Auth::id() !== $destination->user_id) {
            return redirect()->route('admin.destinations.index')
                ->with('error', 'You do not have permission to delete this destination.');
        }

        if ($destination->image) {
            Storage::delete(str_replace('/storage', 'public', $destination->image));
        }
        
        if (!empty($destination->gallery)) {
            foreach ($destination->gallery as $image) {
                Storage::delete(str_replace('/storage', 'public', $image));
            }
        }
        
        $destination->delete();

        return redirect()->route('admin.destinations.index')->with('success', 'Destination deleted successfully.');
    }

    // Rest of the controller methods remain unchanged...
    public function categoriesIndex()
    {
        $categories = Category::withCount('destinations')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'slug' => 'required|string|max:255|unique:categories',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['slug']);

        Category::create($validatedData);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
        ]);

        $validatedData['slug'] = Str::slug($validatedData['slug']);

        $category->update($validatedData);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->destinations()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with existing destinations.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    public function articlesIndex()
    {
        $articles = Article::paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    public function createArticle()
    {
        return view('admin.articles.create');
    }

    public function storeArticle(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/articles');
            $validatedData['image'] = Storage::url($imagePath);
        }

        Article::create($validatedData);

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully.');
    }

    public function editArticle(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function updateArticle(Request $request, Article $article)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::delete(str_replace('/storage', 'public', $article->image));
            }
            $imagePath = $request->file('image')->store('public/articles');
            $validatedData['image'] = Storage::url($imagePath);
        }

        $article->update($validatedData);

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully.');
    }

    public function destroyArticle(Article $article)
    {
        if ($article->image) {
            Storage::delete(str_replace('/storage', 'public', $article->image));
        }
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted successfully.');
    }

    public function usersIndex()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,staff,admin',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,staff,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function staffRegistrations()
    {
        $staffRegistrations = NewsletterSubscriber::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.staff-registrations.index', compact('staffRegistrations'));
    }

    public function approveStaff($id)
    {
        $registration = NewsletterSubscriber::findOrFail($id);
        $registration->is_approved = true;
        $registration->save();

        // Create a new user account for the approved staff
        User::create([
            'name' => $registration->first_name . ' ' . $registration->last_name,
            'email' => $registration->email,
            'password' => Hash::make(Str::random(12)), // Generate a random password
            'role' => 'staff',
            'email_verified_at' => now(),
        ]);

        // TODO: Send email notification to the approved staff with login instructions

        return redirect()->route('admin.staff-registrations')
            ->with('success', 'Staff registration approved successfully.');
    }

    public function rejectStaff($id)
    {
        $registration = NewsletterSubscriber::findOrFail($id);
        $registration->delete();

        // TODO: Send rejection email notification

        return redirect()->route('admin.staff-registrations')
            ->with('success', 'Staff registration rejected.');
    }
}

