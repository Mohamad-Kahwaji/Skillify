<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = ['title', 'description', 'image', 'user_id', 'price', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
