<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ClinicalTimeline extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clinical_timeline';

    protected $fillable = [
        'patient_id',
        'clinic_id',
        'episode_id',
        'event_type',
        'title',
        'description',
        'metadata',
        'related_type',
        'related_id',
        'created_by',
        'event_date'
    ];

    protected $casts = [
        'metadata' => 'array',
        'event_date' => 'datetime'
    ];

    /**
     * Event types
     */
    public const EVENT_TYPES = [
        'note_created' => 'Clinical Note Created',
        'appointment_completed' => 'Appointment Completed',
        'appointment_scheduled' => 'Appointment Scheduled',
        'assessment_added' => 'Assessment Added',
        'episode_started' => 'Episode Started',
        'episode_closed' => 'Episode Closed',
        'treatment_plan_created' => 'Treatment Plan Created',
        'outcome_measure_recorded' => 'Outcome Measure Recorded',
        'discharge' => 'Patient Discharged'
    ];

    /**
     * Relationship: Patient
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Relationship: Clinic
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Relationship: Episode
     */
    public function episode(): BelongsTo
    {
        // Use EpisodeOfCare if it exists, otherwise fallback to Episode
        if (class_exists(\App\Models\EpisodeOfCare::class)) {
            return $this->belongsTo(\App\Models\EpisodeOfCare::class, 'episode_id');
        }
        return $this->belongsTo(\App\Models\Episode::class, 'episode_id');
    }

    /**
     * Relationship: Created By
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship: Related Record (Polymorphic)
     */
    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Create timeline event
     */
    public static function createEvent(
        int $patientId,
        int $clinicId,
        string $eventType,
        string $title,
        ?string $description = null,
        ?Model $related = null,
        ?int $episodeId = null,
        ?array $metadata = null
    ): self {
        return static::create([
            'patient_id' => $patientId,
            'clinic_id' => $clinicId,
            'episode_id' => $episodeId,
            'event_type' => $eventType,
            'title' => $title,
            'description' => $description,
            'metadata' => $metadata,
            'related_type' => $related ? get_class($related) : null,
            'related_id' => $related ? $related->id : null,
            'created_by' => auth()->id(),
            'event_date' => now()
        ]);
    }

    /**
     * Scope: For patient
     */
    public function scopeForPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId)
            ->orderBy('event_date', 'desc');
    }

    /**
     * Scope: For episode
     */
    public function scopeForEpisode($query, int $episodeId)
    {
        return $query->where('episode_id', $episodeId)
            ->orderBy('event_date', 'desc');
    }
}

