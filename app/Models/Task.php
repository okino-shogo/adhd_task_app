<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks'; // テーブル名
    protected $primaryKey = 'task_id'; // タスクIDをプライマリキーとして指定

    protected $fillable = [
        'user_id',
        'user_name',
        'task_name',
        'description',
        'importance',
        'deadline',
        'estimated_time',
        'priority',
        'manual_priority',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
