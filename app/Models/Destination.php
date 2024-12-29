<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'category_id',
        'price',
        'location',
        'latitude',
        'longitude',
        'gallery',
        'user_id'
    ];

    protected $casts = [
        'gallery' => 'array',
        'price' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($destination) {
            $destination->slug = $destination->generateUniqueSlug($destination->name);
            if (!$destination->user_id) {
                $destination->user_id = auth()->id();
            }
        });

        static::updating(function ($destination) {
            if ($destination->isDirty('name')) {
                $destination->slug = $destination->generateUniqueSlug($destination->name);
            }
        });
    }

    public function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 2;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

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

    public function averageRating()
    {
        return $this->ratings()->avg('rating') ?: 0;
    }
}

