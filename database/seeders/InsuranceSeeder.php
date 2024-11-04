<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insurance;

class InsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $insurances = [
            ['name' => 'Health First Insurance', 'city' => 'Los Angeles', 'state' => 'CA', 'zip_code' => '90001'],
            ['name' => 'Care Assurance', 'city' => 'San Francisco', 'state' => 'CA', 'zip_code' => '94101'],
            ['name' => 'Wellness Coverage', 'city' => 'New York', 'state' => 'NY', 'zip_code' => '10001'],
            ['name' => 'Life Secure Insurance', 'city' => 'Miami', 'state' => 'FL', 'zip_code' => '33101'],
            ['name' => 'Comprehensive Health Plan', 'city' => 'Chicago', 'state' => 'IL', 'zip_code' => '60601'],
        ];

        foreach ($insurances as $insurance) {
            Insurance::create($insurance);
        }
    }
}
