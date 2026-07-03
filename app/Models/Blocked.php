<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blocked extends Model
{
    protected $table    = 'blocked';
    protected $fillable = ['admin_id', 'user_id', 'reason', 'blocker_date', 'status'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
