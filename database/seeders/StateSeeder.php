<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\States;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        States::create(['name' => 'California']);
        States::create(['name' => 'Texas']);
        States::create(['name' => 'New York']);
    }
}
