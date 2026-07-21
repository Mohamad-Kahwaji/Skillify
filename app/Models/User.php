<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['first_name', 'last_name', 'middle_name', 'phone', 'email', 'password', 'password_changed_at', 'birthdate', 'gender', 'city', 'profile_photo', 'status', 'latitude', 'longitude', 'fcm_token'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $guard_name = 'users';
    protected function casts(): array
    {
        return [
            'email_verified_at'   => 'datetime',
            'password'            => 'hashed',
            'password_changed_at' => 'datetime',
        ];
    }

    public function getLocationAttribute()
{
    return [
        'personal' => [
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
        ],
        'business' => $this->business ? [
            'latitude'  => $this->business->latitude,
            'longitude' => $this->business->longitude,
        ] : null,
    ];
}

    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
    Public function tokens(){
        return $this->hasMany(Token::class);
    }
    public function active(){
        return $this->status === 'active';
    }
    public function inactive(){
        return $this->status === 'inactive';
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function businesses()
    {
        return $this->hasOne(Business::class);
    }

    /**
     * يزامن رتبة المستخدم مع حالة حساب الأعمال تبعه: business_owner إذا كان نشطاً، وإلا user.
     */
    public function syncBusinessRole(): void
    {
        $role = $this->businesses()->where('status', 'active')->exists() ? 'business_owner' : 'user';
        $this->syncRoles([$role]);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Deletes all of this user's services (and their stored images) without
     * touching the user or business rows themselves.
     */
    public function deleteServicesWithFiles(): void
    {
        $this->services()->get()->each(function (Service $service) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $service->delete();
        });
    }

    public function identityVerification()
    {
        return $this->hasOne(IdentityVerification::class)->latest();
    }

    public function distanceTo(User $other): float
{
    $lat1 = $this->location['latitude'];
    $lng1 = $this->location['longitude'];
    $lat2 = $other->location['latitude'];
    $lng2 = $other->location['longitude'];

    // Haversine Formula
    $earthRadius = 6371; // كيلومتر

    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLng / 2) * sin($dLng / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return round($earthRadius * $c, 2);
}
}
