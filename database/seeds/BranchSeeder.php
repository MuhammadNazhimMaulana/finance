<?php

use Illuminate\Database\Seeder;
use App\Helpers\CitiesHelper;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $res = CitiesHelper::list();
        foreach ($res as $item) {
            if ($item->country_id === 102) {
                DB::table('branches')->insert(['name' => $item->name]);
            }
        }
    }
}
