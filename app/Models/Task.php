<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Task extends Model
{
    use HasFactory;

    protected $table = "tasks";

    protected $fillable = [
        'task',
        'user_id',
        'line_id',
        'status',
        'deadline',
    ];
    public function tasks(){
        return $this->hasMany(User::class);
    }

    protected $dates = [
        'deadline'
    ];
}
