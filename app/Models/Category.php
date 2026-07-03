<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'active_typebusiness_id'];

    public function activeTypebusiness()
    {
        return $this->belongsTo(ActiveTypebusiness::class);
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
}
