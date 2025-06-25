<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DonutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::parse('2025-06-12 10:13:15');

        $donuts = [
            ['name' => 'Moonlit Meringue', 'seal_of_approval' => 4, 'price' => 8],
            ['name' => 'Unicorn Rainbow', 'seal_of_approval' => 5, 'price' => 9.5],
            ['name' => 'Starlight Sprinkle', 'seal_of_approval' => 3, 'price' => 7],
            ['name' => 'Sunfire Glaze', 'seal_of_approval' => 5, 'price' => 8.5],
            ['name' => 'Dragon’s Breath', 'seal_of_approval' => 4, 'price' => 9],
            ['name' => 'Velvet Crème', 'seal_of_approval' => 2, 'price' => 6.5],
            ['name' => 'Aurora Swirl', 'seal_of_approval' => 4, 'price' => 8.2],
            ['name' => 'Midnight Cocoa', 'seal_of_approval' => 3, 'price' => 7.8],
            ['name' => 'Royal Raspberry', 'seal_of_approval' => 5, 'price' => 9.2],
            ['name' => 'Lemon Mist', 'seal_of_approval' => 3, 'price' => 7.3],
            ['name' => 'Caramel Crown', 'seal_of_approval' => 4, 'price' => 8.7],
            ['name' => 'Cherry Blossom', 'seal_of_approval' => 2, 'price' => 6.9],
            ['name' => 'Mint Majesty', 'seal_of_approval' => 5, 'price' => 9.1],
            ['name' => 'Berry Bliss', 'seal_of_approval' => 3, 'price' => 7.5],
            ['name' => 'Golden Honeycomb', 'seal_of_approval' => 4, 'price' => 8.4],
        ];

        foreach ($donuts as $donut) {
            DB::table('donuts')->insert([
                'name' => $donut['name'],
                'seal_of_approval' => $donut['seal_of_approval'],
                'price' => $donut['price'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
