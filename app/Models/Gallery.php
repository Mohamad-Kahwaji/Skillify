<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['title', 'image', 'description', 'user_id', 'views'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
