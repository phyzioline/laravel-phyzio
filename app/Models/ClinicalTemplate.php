<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicalTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'name',
        'specialty',
        'note_type',
        'description',
        'fields_schema',
        'default_values',
        'validation_rules',
        'coding_rules',
        'decision_support_rules',
        'evidence_based_guidelines',
        'is_active',
        'is_system_template',
        'usage_count'
    ];

    protected $casts = [
        'fields_schema' => 'array',
        'default_values' => 'array',
        'validation_rules' => 'array',
        'coding_rules' => 'array',
        'decision_support_rules' => 'array',
        'evidence_based_guidelines' => 'array',
        'is_active' => 'boolean',
        'is_system_template' => 'boolean',
        'usage_count' => 'integer'
    ];

    /**
     * Relationship: Clinic
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Relationship: Notes using this template
     */
    public function notes(): HasMany
    {
        return $this->hasMany(ClinicalNote::class, 'template_id');
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Get template for specialty and note type
     */
    public static function getTemplate(string $specialty, string $noteType, ?int $clinicId = null): ?self
    {
        $query = static::where('specialty', $specialty)
            ->where('note_type', $noteType)
            ->where('is_active', true);

        // Prefer clinic-specific templates, fallback to system templates
        if ($clinicId) {
            $clinicTemplate = (clone $query)
                ->where('clinic_id', $clinicId)
                ->first();
            
            if ($clinicTemplate) {
                return $clinicTemplate;
            }
        }

        return $query->where('is_system_template', true)->first();
    }

    /**
     * Scope: System templates
     */
    public function scopeSystemTemplates($query)
    {
        return $query->where('is_system_template', true);
    }

    /**
     * Scope: Clinic templates
     */
    public function scopeClinicTemplates($query, int $clinicId)
    {
        return $query->where('clinic_id', $clinicId)
            ->where('is_system_template', false);
    }

    /**
     * Scope: For specialty
     */
    public function scopeForSpecialty($query, string $specialty)
    {
        return $query->where('specialty', $specialty);
    }
}

