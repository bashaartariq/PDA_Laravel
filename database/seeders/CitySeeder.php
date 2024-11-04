<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\cities;
use App\Models\States;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $california = States::where('name', 'California')->first();
        cities::create(['name' => 'Los Angeles', 'state_id' => $california->id]);
        cities::create(['name' => 'San Francisco', 'state_id' => $california->id]);

        $texas = States::where('name', 'Texas')->first();
        cities::create(['name' => 'Houston', 'state_id' => $texas->id]);
        cities::create(['name' => 'Dallas', 'state_id' => $texas->id]);

        $newYork = States::where('name', 'New York')->first();
        cities::create(['name' => 'New York City', 'state_id' => $newYork->id]);
        cities::create(['name' => 'Buffalo', 'state_id' => $newYork->id]);
    }
}
