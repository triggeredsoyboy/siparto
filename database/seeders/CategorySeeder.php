<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Destinasi',
            'slug' => 'destinasi',
            'description' => ''
        ]);

        Category::create([
            'name' => 'Kebudayaan',
            'slug' => 'kebudayaan',
            'description' => ''
        ]);

        Category::create([
            'name' => 'Kuliner',
            'slug' => 'kuliner',
            'description' => ''
        ]);

        Category::create([
            'name' => 'Penginapan',
            'slug' => 'penginapan',
            'description' => ''
        ]);

        Category::create([
            'name' => 'Berita',
            'slug' => 'berita',
            'description' => ''
        ]);
    }
}
