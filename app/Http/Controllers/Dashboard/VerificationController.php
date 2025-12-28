<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDocument;
use App\Models\TherapistProfile;
use App\Models\TherapistModuleVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompanyAccountApprovedEmail;
use App\Mail\CompanyAccountRejectedEmail;

class VerificationController extends Controller
{
    /**
     * Show pending verifications
     */
    public function index(Request $request)
    {
        $query = User::whereIn('type', ['vendor', 'company', 'therapist'])
            ->where('verification_status', '!=', 'approved');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('verification_status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        $users = $query->with('documents')->latest()->paginate(20);

        return view('dashboard.pages.verifications.index', compact('users'));
    }

    /**
     * Show user documents for review
     */
    public function show($userId)
    {
        $user = User::with('documents')->findOrFail($userId);
        $requiredDocuments = DB::table('required_documents')
            ->where('role', $user->type)
            ->orderBy('order')
            ->get();

        $userDocuments = $user->documents()->get()->keyBy('document_type');

        // Get module verifications for therapists
        $moduleVerifications = null;
        $therapistProfile = null;
        if ($user->type === 'therapist') {
            $therapistProfile = TherapistProfile::where('user_id', $user->id)->first();
            if ($therapistProfile) {
                $moduleVerifications = TherapistModuleVerification::where('therapist_profile_id', $therapistProfile->id)
                    ->get()
                    ->keyBy('module_type');
            }
        }

        return view('dashboard.pages.verifications.show', compact('user', 'requiredDocuments', 'userDocuments', 'moduleVerifications', 'therapistProfile'));
    }

    /**
     * Approve or reject a document
     */
    public function reviewDocument(Request $request, $documentId)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $document = UserDocument::findOrFail($documentId);
        $user = $document->user;

        $document->update([
            'status' => $request->action === 'approve' ? 'approved' : 'rejected',
            'admin_note' => $request->admin_note,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // If document is module-specific, check module verification
        if ($document->module_type && $user->type === 'therapist') {
            $this->checkModuleVerification($user, $document->module_type);
        } else {
            // Check if all mandatory documents are approved (for general verification)
            $this->checkUserVerificationStatus($user);
        }

        return back()->with('success', __('Document ' . $request->action . 'd successfully.'));
    }

    /**
     * Approve or reject entire user account
     */
    public function reviewUser(Request $request, $userId)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $user = User::findOrFail($userId);

        $user->update([
            'verification_status' => $request->action === 'approve' ? 'approved' : 'rejected',
            'profile_visibility' => $request->action === 'approve' ? 'visible' : 'hidden',
            'status' => $request->action === 'approve' ? 'active' : 'inactive',
        ]);

        // If approved, mark all uploaded documents as approved
        if ($request->action === 'approve') {
            $user->documents()->where('status', 'uploaded')->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            // Also update therapist profile status if user is a therapist
            // When initial documents are approved, grant home_visit access only
            if ($user->type === 'therapist') {
                $therapistProfile = TherapistProfile::where('user_id', $user->id)->first();
                if ($therapistProfile) {
                    $therapistProfile->update([
                        'status' => 'approved',
                        'verified_at' => now(),
                        'home_visit_verified' => true, // Grant home visit access
                        'home_visit_verified_at' => now(),
                    ]);

                    // Create or update module verification record
                    TherapistModuleVerification::updateOrCreate(
                        [
                            'therapist_profile_id' => $therapistProfile->id,
                            'user_id' => $user->id,
                            'module_type' => 'home_visit',
                        ],
                        [
                            'status' => 'approved',
                            'verified_at' => now(),
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                        ]
                    );
                }
            }

            // Send approval email for companies
            if ($user->type === 'company') {
                Mail::to($user->email)->send(new CompanyAccountApprovedEmail($user));
            }
        } else {
            // Send rejection email for companies
            if ($user->type === 'company') {
                Mail::to($user->email)->send(new CompanyAccountRejectedEmail($user, $request->admin_note));
            }
        }

        return redirect()->route('dashboard.verifications.index')
            ->with('success', __('User account ' . $request->action . 'd successfully.'));
    }

    /**
     * Check and update user verification status based on documents
     */
    private function checkUserVerificationStatus(User $user)
    {
        $requiredDocs = DB::table('required_documents')
            ->where('role', $user->type)
            ->where('mandatory', true)
            ->get();

        $allApproved = true;
        foreach ($requiredDocs as $doc) {
            $userDoc = $user->documents()->where('document_type', $doc->document_type)->first();
            if (!$userDoc || $userDoc->status !== 'approved') {
                $allApproved = false;
                break;
            }
        }

        if ($allApproved && $requiredDocs->count() > 0) {
            $user->update([
                'verification_status' => 'approved',
                'profile_visibility' => 'visible',
                'status' => 'active',
            ]);

            // Also update therapist profile status if user is a therapist
            // When initial documents are approved, grant home_visit access only
            if ($user->type === 'therapist') {
                $therapistProfile = TherapistProfile::where('user_id', $user->id)->first();
                if ($therapistProfile) {
                    $therapistProfile->update([
                        'status' => 'approved',
                        'verified_at' => now(),
                        'home_visit_verified' => true, // Grant home visit access
                        'home_visit_verified_at' => now(),
                    ]);

                    // Create or update module verification record
                    TherapistModuleVerification::updateOrCreate(
                        [
                            'therapist_profile_id' => $therapistProfile->id,
                            'user_id' => $user->id,
                            'module_type' => 'home_visit',
                        ],
                        [
                            'status' => 'approved',
                            'verified_at' => now(),
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                        ]
                    );
                }
            }

            // Send approval email for companies when auto-approved
            if ($user->type === 'company') {
                Mail::to($user->email)->send(new CompanyAccountApprovedEmail($user));
            }
        } else {
            // Check if any documents are under review
            $hasUnderReview = $user->documents()->where('status', 'under_review')->exists();
            if ($hasUnderReview) {
                $user->update(['verification_status' => 'under_review']);
            }
        }
    }

    /**
     * Approve or reject a therapist module
     */
    public function reviewModule(Request $request, $userId, $moduleType)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $user = User::findOrFail($userId);
        
        if ($user->type !== 'therapist') {
            return back()->with('error', 'Only therapists can have module verifications.');
        }

        $therapistProfile = TherapistProfile::where('user_id', $user->id)->firstOrFail();
        
        $moduleVerification = TherapistModuleVerification::updateOrCreate(
            [
                'therapist_profile_id' => $therapistProfile->id,
                'user_id' => $user->id,
                'module_type' => $moduleType,
            ],
            [
                'status' => $request->action === 'approve' ? 'approved' : 'rejected',
                'admin_note' => $request->admin_note,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'verified_at' => $request->action === 'approve' ? now() : null,
            ]
        );

        // Update therapist profile module verification status
        $fieldMap = [
            'home_visit' => 'home_visit_verified',
            'courses' => 'courses_verified',
            'clinic' => 'clinic_verified',
        ];

        $dateFieldMap = [
            'home_visit' => 'home_visit_verified_at',
            'courses' => 'courses_verified_at',
            'clinic' => 'clinic_verified_at',
        ];

        if (isset($fieldMap[$moduleType])) {
            $therapistProfile->update([
                $fieldMap[$moduleType] => $request->action === 'approve',
                $dateFieldMap[$moduleType] => $request->action === 'approve' ? now() : null,
            ]);
        }

        return back()->with('success', __('Module ' . $moduleType . ' ' . $request->action . 'd successfully.'));
    }

    /**
     * Check module verification based on module-specific documents
     */
    private function checkModuleVerification(User $user, string $moduleType)
    {
        if ($user->type !== 'therapist') {
            return;
        }

        $therapistProfile = TherapistProfile::where('user_id', $user->id)->first();
        if (!$therapistProfile) {
            return;
        }

        // Get all module-specific documents for this module
        $moduleDocuments = $user->documents()
            ->where('module_type', $moduleType)
            ->get();

        // Check if all module documents are approved
        $allApproved = $moduleDocuments->count() > 0 && 
                      $moduleDocuments->every(fn($doc) => $doc->status === 'approved');

        if ($allApproved) {
            // Update module verification
            $moduleVerification = TherapistModuleVerification::updateOrCreate(
                [
                    'therapist_profile_id' => $therapistProfile->id,
                    'user_id' => $user->id,
                    'module_type' => $moduleType,
                ],
                [
                    'status' => 'approved',
                    'verified_at' => now(),
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                ]
            );

            // Update therapist profile
            $fieldMap = [
                'home_visit' => 'home_visit_verified',
                'courses' => 'courses_verified',
                'clinic' => 'clinic_verified',
            ];

            $dateFieldMap = [
                'home_visit' => 'home_visit_verified_at',
                'courses' => 'courses_verified_at',
                'clinic' => 'clinic_verified_at',
            ];

            if (isset($fieldMap[$moduleType])) {
                $therapistProfile->update([
                    $fieldMap[$moduleType] => true,
                    $dateFieldMap[$moduleType] => now(),
                ]);
            }
        } else {
            // Update status to under_review if documents are uploaded
            $hasUploaded = $moduleDocuments->contains(fn($doc) => in_array($doc->status, ['uploaded', 'under_review']));
            if ($hasUploaded) {
                TherapistModuleVerification::updateOrCreate(
                    [
                        'therapist_profile_id' => $therapistProfile->id,
                        'user_id' => $user->id,
                        'module_type' => $moduleType,
                    ],
                    [
                        'status' => 'under_review',
                    ]
                );
            }
        }
    }
}

