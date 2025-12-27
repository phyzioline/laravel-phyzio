<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'account_statement',
        'commercial_register',
        'tax_card',
        'card_image',
        'phone',
        'image',
        'code',
        'type',
        'status',
        'verification_status',
        'profile_visibility',
        'expire_at',
        'email_verified_at',
        'provider_token',
        'provider_id',
        'provider_name',
        'bank_name',
        'bank_account_name',
        'iban',
        'swift_code',
        'currency',
        'country_code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * Get the therapist profile associated with the user.
     */
    public function therapistProfile()
    {
        return $this->hasOne(\App\Models\TherapistProfile::class);
    }

    public function homeVisits()
    {
        return $this->hasMany(HomeVisit::class, 'patient_id');
    }

    public function therapistVisits()
    {
        return $this->hasMany(HomeVisit::class, 'therapist_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Get user documents
     */
    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

    /**
     * Check if user is verified
     */
    public function isVerified(): bool
    {
        return $this->verification_status === 'approved';
    }

    /**
     * Check if user profile is visible
     */
    public function isProfileVisible(): bool
    {
        return $this->profile_visibility === 'visible' && $this->isVerified();
    }

    /**
     * Get document by type
     */
    public function getDocument(string $documentType)
    {
        return $this->documents()->where('document_type', $documentType)->first();
    }

    /**
     * Get required documents for user role
     */
    public function getRequiredDocuments()
    {
        if (!in_array($this->type, ['vendor', 'company', 'therapist'])) {
            return collect([]);
        }

        return \DB::table('required_documents')
            ->where('role', $this->type)
            ->orderBy('order')
            ->get();
    }

    /**
     * Get verification progress percentage
     */
    public function getVerificationProgress(): int
    {
        $requiredDocs = $this->getRequiredDocuments();
        if ($requiredDocs->isEmpty()) {
            return 100; // Buyer has no required documents
        }

        $mandatoryDocs = $requiredDocs->where('mandatory', true);
        $approvedMandatory = 0;

        foreach ($mandatoryDocs as $doc) {
            $userDoc = $this->getDocument($doc->document_type);
            if ($userDoc && $userDoc->status === 'approved') {
                $approvedMandatory++;
            }
        }

        return $mandatoryDocs->count() > 0 
            ? (int)(($approvedMandatory / $mandatoryDocs->count()) * 100)
            : 0;
    }

    /**
     * Get profile photo URL accessor
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->image) {
            // If image path already includes storage/, use it as is
            if (str_starts_with($this->image, 'storage/')) {
                return asset($this->image);
            }
            // If it's a full URL, return as is
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            // Otherwise, assume it's a relative path and add storage/
            return asset('storage/' . $this->image);
        }
        
        // Return default avatar
        return asset('dashboard/images/Frame 127.svg');
    }
}
