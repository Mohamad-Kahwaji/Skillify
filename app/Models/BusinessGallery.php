<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessGallery extends Model
{
    protected $table    = 'galleries';
    protected $fillable = ['business_id', 'image', 'date'];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
