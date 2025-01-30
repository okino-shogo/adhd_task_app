<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ローカル環境のみ実行したいなら
        if (config('app.env') === 'local') {
            $this->call([
                UsersTableSeeder::class,  
                TaskSeeder::class,        
            ]);
        }
    }
}
