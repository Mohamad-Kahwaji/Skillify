<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = ['name', 'description', 'location', 'activity', 'image', 'status','user_id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
