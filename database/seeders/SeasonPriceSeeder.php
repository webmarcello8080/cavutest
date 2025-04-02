<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SeasonPriceSeeder extends Seeder
{
    public function run()
    {
        $seasons = [
            ['name' => 'Spring', 'from' => '2024-03-01', 'to' => '2024-05-31', 'price' => 100.00],
            ['name' => 'Summer', 'from' => '2024-06-01', 'to' => '2024-08-31', 'price' => 150.00],
            ['name' => 'Autumn', 'from' => '2024-09-01', 'to' => '2024-11-30', 'price' => 120.00],
            ['name' => 'Winter', 'from' => '2024-12-01', 'to' => '2025-02-28', 'price' => 130.00],
        ];

        foreach ($seasons as $season) {
            // regular season prices
            DB::table('prices')->insert([
                'name' => $season['name'],
                'price' => $season['price'],
                'from' => $season['from'],
                'to' => $season['to'],
                'is_weekend' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // weekend prices 20% more expensive on weekends
            DB::table('prices')->insert([
                'name' => $season['name'] . ' - Weekend',
                'price' => $season['price'] * 1.2,
                'from' => $season['from'],
                'to' => $season['to'],
                'is_weekend' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
