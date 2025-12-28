<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ClinicalNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'appointment_id',
        'episode_id',
        'therapist_id',
        'note_type',
        'specialty',
        'template_id',
        'subjective',
        'objective',
        'assessment',
        'plan',
        'chief_complaint',
        'history_of_present_illness',
        'review_of_systems',
        'physical_examination',
        'functional_assessment',
        'outcome_measures',
        'treatment_performed',
        'patient_response',
        'clinical_impression',
        'plan_of_care',
        'diagnosis_codes',
        'procedure_codes',
        'modifiers',
        'coding_validated',
        'coding_errors',
        'compliance_checks',
        'voice_transcription',
        'ai_generated_notes',
        'ai_assisted',
        'ai_suggestions',
        'clinical_recommendations',
        'status',
        'signed_at',
        'signed_by',
        'notes',
        'custom_fields',
        'is_locked'
    ];

    protected $casts = [
        'chief_complaint' => 'array',
        'history_of_present_illness' => 'array',
        'review_of_systems' => 'array',
        'physical_examination' => 'array',
        'functional_assessment' => 'array',
        'outcome_measures' => 'array',
        'treatment_performed' => 'array',
        'patient_response' => 'array',
        'diagnosis_codes' => 'array',
        'procedure_codes' => 'array',
        'modifiers' => 'array',
        'compliance_checks' => 'array',
        'ai_suggestions' => 'array',
        'clinical_recommendations' => 'array',
        'custom_fields' => 'array',
        'coding_validated' => 'boolean',
        'ai_assisted' => 'boolean',
        'is_locked' => 'boolean',
        'signed_at' => 'datetime'
    ];

    /**
     * Available note types
     */
    public const NOTE_TYPES = [
        'soap' => 'SOAP Note',
        'evaluation' => 'Initial Evaluation',
        'progress' => 'Progress Note',
        'discharge' => 'Discharge Summary',
        're_evaluation' => 'Re-evaluation'
    ];

    /**
     * Available specialties
     */
    public const SPECIALTIES = [
        'pediatric' => 'Pediatric',
        'neuro' => 'Neurological',
        'ortho' => 'Orthopedic',
        'geriatrics' => 'Geriatric',
        'women_health' => "Women's Health",
        'sports' => 'Sports Medicine',
        'general' => 'General'
    ];

    /**
     * Relationship: Clinic
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Relationship: Patient
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Relationship: Appointment
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(ClinicAppointment::class, 'appointment_id');
    }

    /**
     * Relationship: Episode
     */
    public function episode(): BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }

    /**
     * Relationship: Therapist
     */
    public function therapist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

    /**
     * Relationship: Template
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(ClinicalTemplate::class, 'template_id');
    }

    /**
     * Relationship: Signed By
     */
    public function signer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    /**
     * Relationship: Timeline Events
     */
    public function timelineEvents(): MorphMany
    {
        return $this->morphMany(ClinicalTimeline::class, 'related');
    }

    /**
     * Check if note is signed
     */
    public function isSigned(): bool
    {
        return $this->status === 'signed' && $this->signed_at !== null;
    }

    /**
     * Sign the note
     */
    public function sign(int $userId): bool
    {
        if ($this->is_locked) {
            return false;
        }

        return $this->update([
            'status' => 'signed',
            'signed_at' => now(),
            'signed_by' => $userId,
            'is_locked' => true
        ]);
    }

    /**
     * Get formatted SOAP note
     */
    public function getFormattedSOAP(): string
    {
        $soap = [];
        
        if ($this->subjective) {
            $soap[] = "SUBJECTIVE:\n" . $this->subjective;
        }
        
        if ($this->objective) {
            $soap[] = "OBJECTIVE:\n" . $this->objective;
        }
        
        if ($this->assessment) {
            $soap[] = "ASSESSMENT:\n" . $this->assessment;
        }
        
        if ($this->plan) {
            $soap[] = "PLAN:\n" . $this->plan;
        }
        
        return implode("\n\n", $soap);
    }

    /**
     * Scope: For specialty
     */
    public function scopeForSpecialty($query, string $specialty)
    {
        return $query->where('specialty', $specialty);
    }

    /**
     * Scope: For note type
     */
    public function scopeForNoteType($query, string $noteType)
    {
        return $query->where('note_type', $noteType);
    }

    /**
     * Scope: Signed notes
     */
    public function scopeSigned($query)
    {
        return $query->where('status', 'signed');
    }

    /**
     * Scope: Draft notes
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}

