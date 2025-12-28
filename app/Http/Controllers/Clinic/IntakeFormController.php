<?php

namespace App\Http\Controllers\Clinic;

use App\Models\IntakeForm;
use App\Models\IntakeFormResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntakeFormController extends BaseClinicController
{
    /**
     * Display a listing of intake forms
     */
    public function index()
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            $forms = collect();
            return view('web.clinic.intake_forms.index', compact('forms', 'clinic'));
        }

        $forms = IntakeForm::where('clinic_id', $clinic->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('web.clinic.intake_forms.index', compact('forms', 'clinic'));
    }

    /**
     * Show the form for creating a new intake form
     */
    public function create()
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

        return view('web.clinic.intake_forms.create', compact('clinic'));
    }

    /**
     * Store a newly created intake form
     */
    public function store(Request $request)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return redirect()->back()->with('error', 'Clinic not found.');
        }

        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'form_fields' => 'required|array',
            'is_required' => 'boolean',
            'conditional_logic' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $form = IntakeForm::create([
                'clinic_id' => $clinic->id,
                'name' => $request->name,
                'description' => $request->description,
                'form_fields' => $request->form_fields,
                'is_active' => true,
                'is_required' => $request->boolean('is_required'),
                'conditional_logic' => $request->conditional_logic ?? [],
            ]);

            return redirect()->route('clinic.intake-forms.show', $form->id)
                ->with('success', 'Intake form created successfully.');

        } catch (\Exception $e) {
            \Log::error('Failed to create intake form', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to create intake form: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified intake form
     */
    public function show($id)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

        $form = IntakeForm::where('clinic_id', $clinic->id)
            ->findOrFail($id);

        $responses = IntakeFormResponse::where('intake_form_id', $form->id)
            ->with('patient')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('web.clinic.intake_forms.show', compact('form', 'responses', 'clinic'));
    }

    /**
     * Show the form for editing the specified intake form
     */
    public function edit($id)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

        $form = IntakeForm::where('clinic_id', $clinic->id)
            ->findOrFail($id);

        return view('web.clinic.intake_forms.edit', compact('form', 'clinic'));
    }

    /**
     * Update the specified intake form
     */
    public function update(Request $request, $id)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return redirect()->back()->with('error', 'Clinic not found.');
        }

        $form = IntakeForm::where('clinic_id', $clinic->id)
            ->findOrFail($id);

        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'form_fields' => 'required|array',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'conditional_logic' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $form->update([
                'name' => $request->name,
                'description' => $request->description,
                'form_fields' => $request->form_fields,
                'is_required' => $request->boolean('is_required'),
                'is_active' => $request->boolean('is_active'),
                'conditional_logic' => $request->conditional_logic ?? [],
            ]);

            return redirect()->route('clinic.intake-forms.show', $form->id)
                ->with('success', 'Intake form updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Failed to update intake form', [
                'form_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update intake form: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Toggle form active status
     */
    public function toggleActive($id)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return response()->json([
                'success' => false,
                'message' => 'Clinic not found.'
            ], 404);
        }

        $form = IntakeForm::where('clinic_id', $clinic->id)
            ->findOrFail($id);

        $form->update(['is_active' => !$form->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Form status updated.',
            'is_active' => $form->is_active
        ]);
    }
}

