<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskDetail extends Model
{
    const Low = 0,Medium = 1,High = 2;
    protected $fillable = [
        'task_title', 'description','date_time' ,'priority','user_id'
    ];
}
