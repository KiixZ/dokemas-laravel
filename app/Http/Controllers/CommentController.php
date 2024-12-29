<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\Article;

class CommentController extends Controller
{
    public function store(Request $request, $type, $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $model = $this->getModel($type, $id);

        $model->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
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

