<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        //创建城市表，并填充数据
        DB::unprepared(file_get_contents(database_path().'/sql/city.sql'));

        //创建城市缓存

    }
}
