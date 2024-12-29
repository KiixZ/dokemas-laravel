<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Destination;

class UpdateDestinationSlugsSeeder extends Seeder
{
    public function run()
    {
        $destinations = Destination::all();
        
        foreach ($destinations as $destination) {
            $destination->slug = $destination->generateUniqueSlug($destination->name);
            $destination->save();
        }
    }
}

