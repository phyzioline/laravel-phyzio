<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'name',
        'condition_code',
        'description',
        'specialty',
        'subjective_fields',
        'objective_fields',
        'pain_scale_config',
        'rom_config',
        'special_tests',
        'treatment_plan_suggestions',
        'is_system_template',
        'is_active',
        'usage_count'
    ];

    protected $casts = [
        'subjective_fields' => 'array',
        'objective_fields' => 'array',
        'pain_scale_config' => 'array',
        'rom_config' => 'array',
        'special_tests' => 'array',
        'treatment_plan_suggestions' => 'array',
        'is_system_template' => 'boolean',
        'is_active' => 'boolean',
        'usage_count' => 'integer'
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get system templates (available to all clinics)
     */
    public static function getSystemTemplates()
    {
        return static::where('is_system_template', true)
            ->where('is_active', true)
            ->get();
    }

    /**
     * Get clinic-specific templates
     */
    public static function getClinicTemplates($clinicId)
    {
        return static::where('clinic_id', $clinicId)
            ->where('is_active', true)
            ->get();
    }

    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }
}

