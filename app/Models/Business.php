<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'name_job', 'number', 'description', 'latitude', 'longitude', 'activity', 'image', 'status', 'user_id'];

    protected $casts = [
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function distanceTo(User $other): float
{
    // خذ الموقع الصح لكل مستخدم
    $lat1 = $this->location['latitude'];
    $lng1 = $this->location['longitude'];
    $lat2 = $other->location['latitude'];
    $lng2 = $other->location['longitude'];

    $earthRadius = 6371;

    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLng / 2) * sin($dLng / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return round($earthRadius * $c, 2);
}
}
