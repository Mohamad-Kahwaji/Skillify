<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class SuperAdmin extends Authenticatable
{
    use HasRoles;

    protected $guard_name = 'super_admins';
    protected $fillable = ['first_name', 'last_name', 'email', 'password'];
    protected $hidden   = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }
}
