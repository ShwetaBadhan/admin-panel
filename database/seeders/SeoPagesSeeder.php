<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeoPagesSeeder extends Seeder
{
    public function run()
    {
        $pages = [
            [
                'page' => 'home',
                'is_active' => '1',

            ],
            [
                'page' => 'about-us',
                'is_active' => '1',
            ],
            [
                'page' => 'contact',
                'is_active' => '1',
            ],
           
        ];

        DB::table('seo_pages')->insert($pages);
    }
}
