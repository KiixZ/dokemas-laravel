<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\Article;

class RatingController extends Controller
{
    public function store(Request $request, $type, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $model = $this->getModel($type, $id);

        $model->ratings()->updateOrCreate(
            ['user_id' => $request->user()->id],
            ['rating' => $request->rating]
        );

        return back()->with('success', 'Rating berhasil ditambahkan.');
    }

    private function getModel($type, $id)
    {
        switch ($type) {
            case 'destination':
                return Destination::findOrFail($id);
            case 'article':
                return Article::findOrFail($id);
            default:
                abort(404);
        }
    }
}

