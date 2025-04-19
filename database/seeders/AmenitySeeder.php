<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Amenity::create([
            'name' => 'Jaringan',
            'slug' => 'jaringan',
            'is_free' => true,
            'price' => 0,
        ]);

        Amenity::create([
            'name' => 'Wi-Fi',
            'slug' => 'wi-fi',
            'is_free' => true,
            'price' => 0,
        ]);

        Amenity::create([
            'name' => 'Tempat pengisian daya',
            'slug' => 'tempat-pengisian-daya',
            'is_free' => false,
            'price' => 5000,
        ]);

        Amenity::create([
            'name' => 'Loker',
            'slug' => 'loker',
            'is_free' => true,
            'price' => 0,
        ]);
    }
}
