<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['business_id', 'image', 'date'];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
