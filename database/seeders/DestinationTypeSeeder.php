<?php

namespace Database\Seeders;

use App\Models\DestinationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DestinationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DestinationType::create([
            'name' => 'Pantai',
            'slug' => 'pantai',
            'description' => 'Nikmati keindahan pantai-pantai berpasir putih di Kalurahan Girikarto.'
        ]);

        DestinationType::create([
            'name' => 'Taman Rekreasi',
            'slug' => 'taman-rekreasi',
            'description' => 'Ingin bermain bersama keluarga maupun pasangan? Kalurahan Girikarto memiliki berbagai wahana seru yang menciptakan pengalaman tak terlupakan.'
        ]);

        DestinationType::create([
            'name' => 'Kuliner',
            'slug' => 'kuliner',
            'description' => 'Menyantap hidangan ditemani keindahan alam Kalurahan Girikarto.'
        ]);
    }
}
