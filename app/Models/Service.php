<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'user_id', 'business_id', 'name', 'description',
        'category', 'subcategory', 'city', 'latitude', 'longitude', 'image',
        'price', 'price_type', 'is_active', 'status',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    // Match by Arabic name string
    public function category()
    {
        return $this->belongsTo(Category::class, 'category', 'name_ar');
    }

    public function subCategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory', 'name_ar');
    }
}
