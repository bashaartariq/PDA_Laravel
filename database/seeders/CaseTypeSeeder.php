<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class CaseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $caseTypes = [
            ['name' => 'WC'],
            ['name' => 'NF'],
        ];
        DB::table('case_types')->insert($caseTypes);
    }
}