<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdentityVerification extends Model
{
    protected $fillable = [
        'user_id', 'full_name', 'id_number', 'id_type',
        'front_image', 'back_image', 'selfie_image',
        'extracted_data', 'match_score',
        'status', 'rejection_reason', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at'    => 'datetime',
        'extracted_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }
}
