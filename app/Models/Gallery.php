<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['em_id', 'image', 'date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'em_id');
    }
}
