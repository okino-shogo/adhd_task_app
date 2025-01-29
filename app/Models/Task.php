<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = [
        'user_name',
        'task_id',
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
