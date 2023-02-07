<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('positions')->insert(['name' => __('Eksekutif')]);
        DB::table('positions')->insert(['name' => __('Direktur')]);
        DB::table('positions')->insert(['name' => __('Manajer')]);
        DB::table('positions')->insert(['name' => __('Admin')]);
        DB::table('positions')->insert(['name' => __('Staf')]);
        DB::table('positions')->insert(['name' => __('Karyawan')]);
    }
}
