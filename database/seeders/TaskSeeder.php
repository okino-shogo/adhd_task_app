<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_1 = User::first();
        $user_2 = User::latest()->first();
        DB::table('tasks')->insert([
            'user_id' => $user_1->id, // 必ず指定する
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
                'user_id' => $user_1->id, // 必ず指定する   
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
                'user_id' => $user_2->id,
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
