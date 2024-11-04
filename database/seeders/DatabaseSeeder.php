<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            StateSeeder::class
        ]);
        $this->call([
            CitySeeder::class
        ]);
        $this->call([
            ZipCodeSeeder::class
        ]);
        $this->call([
            PracticeLocationSeeder::class
        ]);

        $this->call([
            CategorySeeder::class
        ]);
        $this->call([
            FirmSeeder::class
        ]);
        $this->call([
            InsuranceSeeder::class
        ]);
        $this->call([
            SpecialitySeeder::class
        ]);
        $this->call([
            PurposeOfVisitSeeder::class
        ]);
        $this->call([
            CaseTypeSeeder::class
        ]);
        $this->call([
            AppointmentTypesTableSeeder::class
        ]);
        $this->call(RolesTableSeeder::class);
        $this->call([
            GenderSeeder::class,
        ]);
    }
}
