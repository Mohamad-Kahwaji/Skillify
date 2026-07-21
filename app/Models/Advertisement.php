<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertisement extends Model
{
    protected $fillable = ['admin_id', 'super_admin_id', 'title', 'description', 'image', 'company_name', 'start_date', 'end_date', 'status'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function superAdmin()
    {
        return $this->belongsTo(SuperAdmin::class);
    }

    /**
     * Approved ads whose start/end date (if set) covers today.
     */
    public function scopeActive($query)
    {
        $today = now()->toDateString();

        return $query->where('status', 'approved')
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            });
    }
}
