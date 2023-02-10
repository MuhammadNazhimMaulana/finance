<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id = DB::table('users')->insertGetId([
            'name' => 'ROOT',
            'email' => 'support@semai.co.id',
            'password' => bcrypt('demodemo123'),
        ]);

        $user = User::find($id);

        $user->assignRole('root');
    }
}
