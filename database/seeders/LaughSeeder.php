<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class LaughSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonData = '[
            {"title":"Just Friends"},
            {"title":"Romantic Interest"}
        ]';

        DB::table('laughs')->insert(json_decode($jsonData, true));
    }
}