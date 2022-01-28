<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class EthnicitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonData = '[
            {"title":"American Indian"},
            {"title":"East Indian"},
            {"title":"Black/African Descent"},
            {"title":"Hispanic/Latino"},
            {"title":"Hispanic/Latino"},
            {"title":"Middle Eastern"},
            {"title":"Pacific Islander"},
            {"title":"South Asian"},
            {"title":"White/Caucasian"}
        ]';

        DB::table('ethnicities')->insert(json_decode($jsonData, true));
    }
}