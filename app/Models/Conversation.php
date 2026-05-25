<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['user_id_1', 'user_id_2', 'last_message', 'last_message_at'];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_id_1');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_id_2');
    }
}
