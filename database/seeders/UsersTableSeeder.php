<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => '沖野',
                'email' => 'johndoe@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => '八木下さん',    // ここを実在のユーザー名や表示したい名前に
                'email' => 'yagishita@example.com',
                'password' => bcrypt('password'),
            ],
        ]);
    }
}
