<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurposeOfVisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $purposes = [
            ['name' => 'Routine Checkup'],
            ['name' => 'Follow Up'],
            ['name' => 'Consultation'],
            ['name' => 'Emergency'],
            ['name' => 'Specialist Referral'],
        ];
        DB::table('purpose_of_visit')->insert($purposes);

    }
}
