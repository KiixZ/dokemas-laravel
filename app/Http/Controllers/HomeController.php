<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Destination;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil 3 destinasi terbaru untuk featured destinations
        $featuredDestinations = Destination::with(['category', 'ratings'])
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($destination) {
                $destination->average_rating = $destination->averageRating();
                return $destination;
            });

        // Mengambil 5 destinasi dengan rating tertinggi sebagai trending
        $trendingDestinations = Destination::with(['ratings'])
            ->get()
            ->sortByDesc(function ($destination) {
                return $destination->averageRating();
            })
            ->take(5);

        // Mengambil semua kategori dengan jumlah destinasi
        $categories = Category::withCount('destinations')
            ->orderBy('destinations_count', 'desc')
            ->get();

        // Mengambil 4 artikel terbaru dengan pagination
        $latestArticles = Article::latest()->paginate(4);

        return view('home', compact('featuredDestinations', 'trendingDestinations', 'categories', 'latestArticles'));
    }


    public function about()
    {
        return view('about');
    }
}

