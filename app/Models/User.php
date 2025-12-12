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
        'expire_at',
        'email_verified_at',
        'provider_token',
        'provider_id',
        'provider_name',
        'bank_name',
        'bank_account_name',
        'iban',
        'swift_code'
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
}
