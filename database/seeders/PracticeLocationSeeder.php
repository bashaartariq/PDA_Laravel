<?php

namespace Database\Seeders;

use App\Models\PracticeLocation;
use Illuminate\Database\Seeder;

class PracticeLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $practiceLocations = [
            'Downtown Medical Center',
            'Northside Family Clinic',
            'Westview Health Services',
            'Sunrise Community Health',
            'Riverbend Urgent Care'
        ];

        foreach ($practiceLocations as $location) {
            PracticeLocation::create(['name' => $location]);
        }
    }
}
