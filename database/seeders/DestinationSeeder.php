<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\DestinationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Destination::factory()->count(10)->recycle(DestinationType::all())->create();
    }
}
