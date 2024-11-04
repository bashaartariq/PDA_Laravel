<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Speciality;
class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $specialities = [
            'Cardiology',
            'Dermatology',
            'Neurology',
            'Pediatrics',
            'Radiology',
            'Surgery',
        ];

        foreach ($specialities as $speciality) {
            Speciality::create(['name' => $speciality]);
        }

    }
}
