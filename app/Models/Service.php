<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\City;

class Service extends Model
{
    protected $fillable = [
        'user_id', 'business_id', 'name', 'description',
        'category_id', 'subcategory_id', 'city_id', 'latitude', 'longitude', 'image',
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
