<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil 6 destinasi terbaru
        $latestDestinations = Destination::with(['category', 'ratings'])
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($destination) {
                $destination->average_rating = $destination->averageRating();
                return $destination;
            });

        // Mengambil 5 destinasi dengan rating tertinggi sebagai trending
        // Karena belum ada kolom views, kita gunakan rating sebagai alternatif
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

        return view('home', compact('latestDestinations', 'trendingDestinations', 'categories'));
    }

    public function about()
    {
        return view('about');
    }
}