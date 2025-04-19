<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'name' => 'Pemandu wisatawan domestik',
            'slug' => 'pemandu-wisatawan-domestik',
            'description' => 'Pemandu wisata berbahasa Indonesia.',
            'is_free' => false,
            'price' => 100000,
        ]);

        Service::create([
            'name' => 'Pemandu wisata internasional',
            'slug' => 'pemandu-wisata-internasional',
            'description' => 'Pemandu wisata berbahasa Inggris.',
            'is_free' => false,
            'price' => 100000,
        ]);

        Service::create([
            'name' => 'Sewa tenda',
            'slug' => 'sewa-tenda',
            'description' => 'Menyediakan tenda bagi wisatawan yang ingin berkemah di pinggir pantai.',
            'is_free' => false,
            'price' => 10000,
        ]);

        Service::create([
            'name' => 'Sewa alat memasak',
            'slug' => 'sewa-alat-memasak',
            'description' => 'Alat memasak bagi wisatawan yang berkemah (kompor portabel, nesting, dll.).',
            'is_free' => false,
            'price' => 10000,
        ]);
    }
}
