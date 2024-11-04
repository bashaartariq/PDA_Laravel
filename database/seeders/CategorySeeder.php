<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $categories = [
            ['name' => 'General'],
            ['name' => 'Pediatrics'],
            ['name' => 'Orthopedics'],
            ['name' => 'Cardiology'],
            ['name' => 'Dermatology'],
            // Add more categories as needed
        ];

        DB::table('categories')->insert($categories);


    }
}
