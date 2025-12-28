<?php

namespace App\Http\Controllers\Clinic;

use App\Models\ClinicalNote;
use App\Models\ClinicalTemplate;
use App\Models\ClinicalTimeline;
use App\Models\EpisodeOfCare;
use App\Services\Clinical\VoiceToTextService;
use App\Services\Clinical\CodingValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicalNoteController extends BaseClinicController
{
    protected $voiceToTextService;
    protected $codingValidationService;

    public function __construct(
        VoiceToTextService $voiceToTextService,
        CodingValidationService $codingValidationService
    ) {
        $this->voiceToTextService = $voiceToTextService;
        $this->codingValidationService = $codingValidationService;
    }

    /**
     * Display a listing of clinical notes
     */
    public function index(Request $request)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            $notes = collect();
            return view('web.clinic.clinical_notes.index', compact('notes', 'clinic'));
        }

        $query = ClinicalNote::where('clinic_id', $clinic->id)
            ->with(['patient', 'therapist', 'appointment', 'episode']);

        // Filters
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('specialty')) {
            $query->where('specialty', $request->specialty);
        }

        if ($request->filled('note_type')) {
            $query->where('note_type', $request->note_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('therapist_id')) {
            $query->where('therapist_id', $request->therapist_id);
        }

        $notes = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $patients = \App\Models\Patient::where('clinic_id', $clinic->id)->get();
        $therapists = \App\Models\User::where('type', 'therapist')
            ->whereHas('clinics', function($q) use ($clinic) {
                $q->where('clinics.id', $clinic->id);
            })
            ->get();

        return view('web.clinic.clinical_notes.index', compact(
            'notes',
            'patients',
            'therapists',
            'clinic'
        ));
    }

    /**
     * Show the form for creating a new clinical note
     */
    public function create(Request $request)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

        $patientId = $request->get('patient_id');
        $appointmentId = $request->get('appointment_id');
        $episodeId = $request->get('episode_id');
        $specialty = $request->get('specialty', $clinic->primary_specialty ?? 'general');
        $noteType = $request->get('note_type', 'soap');

        // Get template
        $template = ClinicalTemplate::getTemplate($specialty, $noteType, $clinic->id);

        // Get related data
        $patient = $patientId ? \App\Models\Patient::find($patientId) : null;
        $appointment = $appointmentId ? \App\Models\ClinicAppointment::find($appointmentId) : null;
        $episode = $episodeId ? EpisodeOfCare::find($episodeId) : null;

        // Get patients and episodes for dropdowns
        $patients = \App\Models\Patient::where('clinic_id', $clinic->id)->get();
        $episodes = $patientId 
            ? EpisodeOfCare::where('patient_id', $patientId)->get()
            : collect();

        // Get voice-to-text config
        $voiceConfig = $this->voiceToTextService->getWebSpeechConfig();

        return view('web.clinic.clinical_notes.create', compact(
            'clinic',
            'patients',
            'episodes',
            'patient',
            'appointment',
            'episode',
            'specialty',
            'noteType',
            'template',
            'voiceConfig'
        ));
    }

    /**
     * Store a newly created clinical note
     */
    public function store(Request $request)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return redirect()->back()->with('error', 'Clinic not found.');
        }

        $validator = \Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:clinic_appointments,id',
            'episode_id' => 'nullable|exists:episodes,id',
            'specialty' => 'required|string',
            'note_type' => 'required|in:soap,evaluation,progress,discharge,re_evaluation',
            'template_id' => 'nullable|exists:clinical_templates,id',
            'subjective' => 'nullable|string',
            'objective' => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan' => 'nullable|string',
            'diagnosis_codes' => 'nullable|array',
            'procedure_codes' => 'nullable|array',
            'voice_transcription' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $note = ClinicalNote::create([
                'clinic_id' => $clinic->id,
                'patient_id' => $request->patient_id,
                'appointment_id' => $request->appointment_id,
                'episode_id' => $request->episode_id,
                'therapist_id' => Auth::id(),
                'specialty' => $request->specialty,
                'note_type' => $request->note_type,
                'template_id' => $request->template_id,
                'subjective' => $request->subjective,
                'objective' => $request->objective,
                'assessment' => $request->assessment,
                'plan' => $request->plan,
                'diagnosis_codes' => $request->diagnosis_codes ?? [],
                'procedure_codes' => $request->procedure_codes ?? [],
                'voice_transcription' => $request->voice_transcription,
                'status' => 'draft',
            ]);

            // Validate coding
            $codingValidation = $this->codingValidationService->validateNoteCoding($note);
            $note->update([
                'coding_validated' => $codingValidation['valid'],
                'coding_errors' => !empty($codingValidation['errors']) 
                    ? implode('; ', $codingValidation['errors']) 
                    : null,
                'compliance_checks' => $codingValidation['compliance'] ?? []
            ]);

            // Create timeline event
            ClinicalTimeline::createEvent(
                $note->patient_id,
                $note->clinic_id,
                'note_created',
                'Clinical Note Created',
                "{$note->note_type} note created for patient",
                $note,
                $note->episode_id
            );

            // Increment template usage
            if ($note->template) {
                $note->template->incrementUsage();
            }

            return redirect()->route('clinic.clinical-notes.show', $note->id)
                ->with('success', 'Clinical note created successfully.');

        } catch (\Exception $e) {
            \Log::error('Failed to create clinical note', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to create clinical note: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified clinical note
     */
    public function show($id)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

        $note = ClinicalNote::where('clinic_id', $clinic->id)
            ->with(['patient', 'therapist', 'appointment', 'episode', 'template', 'signer'])
            ->findOrFail($id);

        // Get timeline events for this patient
        $timeline = ClinicalTimeline::forPatient($note->patient_id)
            ->where('episode_id', $note->episode_id)
            ->limit(20)
            ->get();

        // Get coding validation status
        $codingValidation = $this->codingValidationService->validateNoteCoding($note);

        return view('web.clinic.clinical_notes.show', compact(
            'note',
            'timeline',
            'codingValidation',
            'clinic'
        ));
    }

    /**
     * Show the form for editing the specified clinical note
     */
    public function edit($id)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

        $note = ClinicalNote::where('clinic_id', $clinic->id)
            ->where('status', '!=', 'signed') // Can't edit signed notes
            ->findOrFail($id);

        $template = $note->template;
        $voiceConfig = $this->voiceToTextService->getWebSpeechConfig();

        return view('web.clinic.clinical_notes.edit', compact(
            'note',
            'template',
            'voiceConfig',
            'clinic'
        ));
    }

    /**
     * Update the specified clinical note
     */
    public function update(Request $request, $id)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return redirect()->back()->with('error', 'Clinic not found.');
        }

        $note = ClinicalNote::where('clinic_id', $clinic->id)
            ->where('status', '!=', 'signed')
            ->findOrFail($id);

        $validator = \Validator::make($request->all(), [
            'subjective' => 'nullable|string',
            'objective' => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan' => 'nullable|string',
            'diagnosis_codes' => 'nullable|array',
            'procedure_codes' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $note->update([
                'subjective' => $request->subjective ?? $note->subjective,
                'objective' => $request->objective ?? $note->objective,
                'assessment' => $request->assessment ?? $note->assessment,
                'plan' => $request->plan ?? $note->plan,
                'diagnosis_codes' => $request->diagnosis_codes ?? $note->diagnosis_codes,
                'procedure_codes' => $request->procedure_codes ?? $note->procedure_codes,
            ]);

            // Re-validate coding
            $codingValidation = $this->codingValidationService->validateNoteCoding($note);
            $note->update([
                'coding_validated' => $codingValidation['valid'],
                'coding_errors' => !empty($codingValidation['errors']) 
                    ? implode('; ', $codingValidation['errors']) 
                    : null,
                'compliance_checks' => $codingValidation['compliance'] ?? []
            ]);

            return redirect()->route('clinic.clinical-notes.show', $note->id)
                ->with('success', 'Clinical note updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Failed to update clinical note', [
                'note_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update clinical note: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Sign the clinical note
     */
    public function sign(Request $request, $id)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return response()->json([
                'success' => false,
                'message' => 'Clinic not found.'
            ], 404);
        }

        $note = ClinicalNote::where('clinic_id', $clinic->id)
            ->findOrFail($id);

        if ($note->isSigned()) {
            return response()->json([
                'success' => false,
                'message' => 'Note is already signed.'
            ], 400);
        }

        // Validate coding before signing
        $codingValidation = $this->codingValidationService->validateNoteCoding($note);
        if (!$codingValidation['valid'] && !empty($codingValidation['errors'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot sign note with coding errors. Please fix errors first.',
                'errors' => $codingValidation['errors']
            ], 422);
        }

        $signed = $note->sign(Auth::id());

        if ($signed) {
            return response()->json([
                'success' => true,
                'message' => 'Clinical note signed successfully.',
                'note' => $note->fresh()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to sign note.'
        ], 500);
    }

    /**
     * Get templates for AJAX request
     */
    public function getTemplates(Request $request)
    {
        $specialty = $request->get('specialty');
        $noteType = $request->get('note_type', 'soap');
        $clinicId = $this->getUserClinic()?->id;

        $template = ClinicalTemplate::getTemplate($specialty, $noteType, $clinicId);

        return response()->json([
            'success' => true,
            'template' => $template
        ]);
    }

    /**
     * Validate coding for AJAX request
     */
    public function validateCoding(Request $request)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return response()->json([
                'success' => false,
                'message' => 'Clinic not found.'
            ], 404);
        }

        $diagnosisCodes = $request->get('diagnosis_codes', []);
        $procedureCodes = $request->get('procedure_codes', []);

        $validation = [
            'icd10' => $this->codingValidationService->validateICD10Codes($diagnosisCodes),
            'cpt' => $this->codingValidationService->validateCPTCodes($procedureCodes),
            'ncci' => $this->codingValidationService->checkNCCIEdits($procedureCodes),
        ];

        return response()->json([
            'success' => true,
            'validation' => $validation
        ]);
    }
}

