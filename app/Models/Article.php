<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image',
        'user_id',
        'slug',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'ratable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
