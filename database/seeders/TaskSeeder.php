<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->insert([
            'user_id' => 1,
            'user_name' => '沖野',
            'task_name' => 'ご飯',
            'description' => 'ごはん',
            'importance' => 4,
            'deadline' => '14:30',
            'estimated_time' => 60,
            'priority' => 1,
            'manual_priority' => null,
            'status' => '未完了'
        ]);
        $param = [
            [
                'user_id' => 1,
                'user_name' => '沖野',
                'task_name' => 'めも',
                'description' => 'たすく',
                'importance' => 1,
                'deadline' => now()->addDays(5),
                'estimated_time' => 70,
                'priority' => 3,
                'manual_priority' => null,
                'status' => '未完了'            
            ],
            [
                'user_id' => 2,
                'user_name' => '八木下さん',
                'task_name' => 'ご飯',
                'description' => 'ごはん',
                'importance' => 2,
                'deadline' => now()->addDays(5),
                'estimated_time' => 8,
                'priority' => 2,
                'manual_priority' => null,
                'status' => '未完了'        
            ]
        ];
        DB::table('tasks')->insert($param);    
    }    
}
