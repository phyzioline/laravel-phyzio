<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyModuleVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_profile_id',
        'user_id',
        'module_type', // 'clinic' for companies
        'status', // pending, under_review, approved, rejected
        'admin_note',
        'reviewed_by',
        'reviewed_at',
        'verified_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the company profile
     */
    public function companyProfile()
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who reviewed
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Check if module is verified
     */
    public function isVerified(): bool
    {
        return $this->status === 'approved';
    }
}

