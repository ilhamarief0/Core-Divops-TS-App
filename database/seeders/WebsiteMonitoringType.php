<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteMonitoringType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('website_monitoring_types')->insert([
            [
                'name' => 'Production',
                'slug' => 'prod',
            ],
            [
                'name' => 'Development',
                'slug' => 'dev',
            ],
            [
                'name' => 'Stagging',
                'slug' => 'stg',
            ],
            // tambahkan data lainnya di sini
        ]);
    }
}
