<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('appointments_types')->insert([
            [
                'name' => 'Routine Check-up',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Follow-up Visit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emergency Consultation',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Annual Physical',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Specialist Referral',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
