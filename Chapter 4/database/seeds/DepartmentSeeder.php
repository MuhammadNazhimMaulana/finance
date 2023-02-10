<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert(['name' => __('HRD')]);
        DB::table('departments')->insert(['name' => __('Pembelian')]);
        DB::table('departments')->insert(['name' => __('Produksi')]);
        DB::table('departments')->insert(['name' => __('Penjualan')]);
        DB::table('departments')->insert(['name' => __('Pemasaran')]);
        DB::table('departments')->insert(['name' => __('Jaminan Kualitas (QA)')]);
        DB::table('departments')->insert(['name' => __('Keuangan')]);
        DB::table('departments')->insert(['name' => __('Gudang')]);
        DB::table('departments')->insert(['name' => __('IT')]);
        DB::table('departments')->insert(['name' => __('Umum')]);
    }
}
