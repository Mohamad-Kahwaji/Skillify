<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'position', 'department', 'salary', 'hire_date', 'status'];

    protected $casts = [
        'hire_date' => 'date',
    ];
}
