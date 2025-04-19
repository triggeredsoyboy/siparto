<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Facility::create([
            'name' => 'Toilet',
            'slug' => 'toilet',
        ]);

        Facility::create([
            'name' => 'Masjid',
            'slug' => 'masjid',
        ]);

        Facility::create([
            'name' => 'Musholla',
            'slug' => 'musholla',
        ]);

        Facility::create([
            'name' => 'Tempat parkir',
            'slug' => 'tempat-parkir',
        ]);

        Facility::create([
            'name' => 'Warung makan',
            'slug' => 'warung-makan',
        ]);

        Facility::create([
            'name' => 'Restoran',
            'slug' => 'restoran',
        ]);

        Facility::create([
            'name' => 'Akses disabilitas',
            'slug' => 'akses-disabilitas',
        ]);
    }
}
