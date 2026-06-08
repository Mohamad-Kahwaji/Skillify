<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasRoles;

    protected $guard_name = 'admins';
    protected $fillable = ['id_number', 'first_name', 'last_name', 'email', 'password', 'phone', 'role', 'status'];

    protected $hidden = ['password', 'remember_token'];

    public function active(){
        return $this->status === 'active';
    }
    public function inactive(){
        return $this->status === 'inactive';
    }

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
