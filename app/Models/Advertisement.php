<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = ['softDeletes','admin_id', 'title', 'description', 'image', 'company_name', 'start_date', 'end_date', 'status'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
