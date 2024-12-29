<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Destination;
use Illuminate\Support\Str;

class PopulateDestinationSlugsSeeder extends Seeder
{
    public function run()
    {
        $destinations = Destination::whereNull('slug')->orWhere('slug', '')->get();

        foreach ($destinations as $destination) {
            $slug = Str::slug($destination->name);
            $originalSlug = $slug;
            $count = 2;

            while (Destination::where('slug', $slug)->where('id', '!=', $destination->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $destination->slug = $slug;
            $destination->save();
        }
    }
}

