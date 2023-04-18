<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'due_date',
        'start_time',
        'end_time',
        'remarks',
        'is_completed',
        'deleted_at'
    ];
}