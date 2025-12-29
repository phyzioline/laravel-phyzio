<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\UserDocument;
use App\Traits\HasImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    use HasImage;

    /**
     * Show the "Complete Your Account" intermediate page
     */
    public function completeAccount()
    {
        $user = Auth::user();

        // Buyer skips this page - redirect to home
        if ($user->type === 'buyer') {
            return redirect()->route('home.' . app()->getLocale());
        }

        // If already verified, redirect to dashboard
        if ($user->isVerified()) {
            return redirect()->route('dashboard.home');
        }

        $requiredDocuments = $user->getRequiredDocuments();
        $userDocuments = $user->documents()->get()->keyBy('document_type');
        
        // Calculate progress
        $progress = $user->getVerificationProgress();

        return view('web.auth.complete-account', [
            'user' => $user,
            'requiredDocuments' => $requiredDocuments,
            'userDocuments' => $userDocuments,
            'progress' => $progress,
        ]);
    }

    /**
     * Show verification center (document upload/management)
     */
    public function verificationCenter()
    {
        $user = Auth::user();

        $requiredDocuments = $user->getRequiredDocuments();
        $userDocuments = $user->documents()->get()->keyBy(function($doc) {
            return $doc->document_type . '_' . ($doc->module_type ?? 'general');
        });
        $progress = $user->getVerificationProgress();

        // Get module verification status for therapists
        $moduleVerifications = null;
        $therapistProfile = null;
        if ($user->type === 'therapist') {
            $therapistProfile = \App\Models\TherapistProfile::where('user_id', $user->id)->first();
            if ($therapistProfile) {
                $moduleVerifications = \App\Models\TherapistModuleVerification::where('therapist_profile_id', $therapistProfile->id)
                    ->get()
                    ->keyBy('module_type');
            }
        }

        // Get module verification status for companies
        $companyModuleVerifications = null;
        $companyProfile = null;
        if ($user->type === 'company') {
            $companyProfile = \App\Models\CompanyProfile::where('user_id', $user->id)->first();
            if ($companyProfile) {
                $companyModuleVerifications = \App\Models\CompanyModuleVerification::where('company_profile_id', $companyProfile->id)
                    ->get()
                    ->keyBy('module_type');
            }
        }

        return view('web.auth.verification-center', [
            'requiredDocuments' => $requiredDocuments,
            'userDocuments' => $userDocuments,
            'progress' => $progress,
            'therapistProfile' => $therapistProfile,
            'moduleVerifications' => $moduleVerifications,
            'companyProfile' => $companyProfile,
            'companyModuleVerifications' => $companyModuleVerifications,
        ]);
    }

    /**
     * Upload or update a document
     */
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'module_type' => 'nullable|string|in:home_visit,courses,clinic',
        ]);

        $user = Auth::user();
        $documentType = $request->input('document_type');
        $moduleType = $request->input('module_type');

        // Verify document type is required for this user
        $requiredDoc = DB::table('required_documents')
            ->where('role', $user->type)
            ->where('document_type', $documentType)
            ->first();

        if (!$requiredDoc) {
            return back()->withErrors(['document' => 'Invalid document type for your account.']);
        }

        // Save file
        $filePath = $this->saveImage($request->file('document'), 'documents');

        // Create or update document record
        $userDocument = UserDocument::updateOrCreate(
            [
                'user_id' => $user->id,
                'document_type' => $documentType,
                'module_type' => $moduleType, // Store module type if provided
            ],
            [
                'file_path' => $filePath,
                'status' => 'uploaded', // Will be reviewed by admin
            ]
        );

        // If this is a module-specific document, create/update module verification request
        if ($moduleType && $user->type === 'therapist') {
            $therapistProfile = \App\Models\TherapistProfile::where('user_id', $user->id)->first();
            if ($therapistProfile) {
                \App\Models\TherapistModuleVerification::updateOrCreate(
                    [
                        'therapist_profile_id' => $therapistProfile->id,
                        'user_id' => $user->id,
                        'module_type' => $moduleType,
                    ],
                    [
                        'status' => 'under_review', // Set to under_review when documents are uploaded
                    ]
                );
            }
        }

        // If this is a clinic document for a company, create/update company module verification request
        if ($moduleType === 'clinic' && $user->type === 'company') {
            $companyProfile = \App\Models\CompanyProfile::where('user_id', $user->id)->first();
            if ($companyProfile) {
                \App\Models\CompanyModuleVerification::updateOrCreate(
                    [
                        'company_profile_id' => $companyProfile->id,
                        'user_id' => $user->id,
                        'module_type' => 'clinic',
                    ],
                    [
                        'status' => 'under_review', // Set to under_review when documents are uploaded
                    ]
                );
            }
        }

        // Update user verification status to 'under_review' when documents are uploaded
        // This applies to: pending, rejected, or null status
        if (in_array($user->verification_status, ['pending', 'rejected', null])) {
            $user->update(['verification_status' => 'under_review']);
        }

        $moduleName = $moduleType ? ucfirst(str_replace('_', ' ', $moduleType)) : '';
        $message = $moduleName 
            ? __('Document uploaded successfully for :module access. It will be reviewed by our team.', ['module' => $moduleName])
            : __('Document uploaded successfully. It will be reviewed by our team.');

        return back()->with('success', $message);
    }

    /**
     * Delete a document
     */
    public function deleteDocument(Request $request, $documentId)
    {
        $user = Auth::user();
        $document = UserDocument::where('id', $documentId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Only allow deletion if not approved
        if ($document->status === 'approved') {
            return back()->withErrors(['error' => 'Cannot delete an approved document.']);
        }

        $document->delete();

        return back()->with('success', __('Document deleted successfully.'));
    }
}

