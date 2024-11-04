<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\cities;
use App\Models\zip_codes;


class ZipCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $losAngeles = cities::where('name', 'Los Angeles')->first();
        zip_codes::create(['zip_code' => '90001', 'city_id' => $losAngeles->id]);
        zip_codes::create(['zip_code' => '90002', 'city_id' => $losAngeles->id]);

        $sanFrancisco = cities::where('name', 'San Francisco')->first();
        zip_codes::create(['zip_code' => '94101', 'city_id' => $sanFrancisco->id]);

        $houston = cities::where('name', 'Houston')->first();
        zip_codes::create(['zip_code' => '77001', 'city_id' => $houston->id]);

        $dallas = cities::where('name', 'Dallas')->first();
        zip_codes::create(['zip_code' => '75201', 'city_id' => $dallas->id]);

        $newYorkCity = cities::where('name', 'New York City')->first();
        zip_codes::create(['zip_code' => '10001', 'city_id' => $newYorkCity->id]);

        $buffalo = cities::where('name', 'Buffalo')->first();
        zip_codes::create(['zip_code' => '14201', 'city_id' => $buffalo->id]);
    }
}
