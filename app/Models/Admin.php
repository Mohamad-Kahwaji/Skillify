<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = ['id_number', 'first_name', 'last_name', 'email', 'password', 'phone', 'role'];

    protected $hidden = ['password'];

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    public function blockedUsers()
    {
        return $this->hasMany(Blocked::class);
    }
}
