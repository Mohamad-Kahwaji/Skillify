<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $guard_name = 'admins';
    protected $fillable = ['id_number', 'first_name', 'last_name', 'email', 'password', 'phone', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    public function blockedUsers()
    {
        return $this->hasMany(Blocked::class);
    }
}
