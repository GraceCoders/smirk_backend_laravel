<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class MatchingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonData = '[
            {"title":"Male"},
            {"title":"Female"},
            {"title":"Non Binary"}
        ]';

        DB::table('matchings')->insert(json_decode($jsonData, true));
    }
}