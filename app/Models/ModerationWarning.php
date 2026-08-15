<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModerationWarning extends Model
{
    protected $fillable = [
        'user_id',
        'issuer_type',
        'issuer_id',
        'warningable_type',
        'warningable_id',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function warningable()
    {
        return $this->morphTo();
    }
}
