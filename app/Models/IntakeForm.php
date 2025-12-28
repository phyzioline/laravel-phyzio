<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IntakeForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'name',
        'description',
        'form_fields',
        'is_active',
        'is_required',
        'conditional_logic'
    ];

    protected $casts = [
        'form_fields' => 'array',
        'is_active' => 'boolean',
        'is_required' => 'boolean',
        'conditional_logic' => 'array'
    ];

    /**
     * Relationship: Clinic
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Relationship: Responses
     */
    public function responses(): HasMany
    {
        return $this->hasMany(IntakeFormResponse::class, 'intake_form_id');
    }
}

