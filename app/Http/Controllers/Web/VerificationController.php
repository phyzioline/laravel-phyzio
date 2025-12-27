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
        $userDocuments = $user->documents()->get()->keyBy('document_type');
        $progress = $user->getVerificationProgress();

        return view('web.auth.verification-center', [
            'requiredDocuments' => $requiredDocuments,
            'userDocuments' => $userDocuments,
            'progress' => $progress,
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
        ]);

        $user = Auth::user();
        $documentType = $request->input('document_type');

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
            ],
            [
                'file_path' => $filePath,
                'status' => 'uploaded', // Will be reviewed by admin
            ]
        );

        // If user was rejected, reset to under_review
        if ($user->verification_status === 'rejected') {
            $user->update(['verification_status' => 'under_review']);
        }

        return back()->with('success', __('Document uploaded successfully. It will be reviewed by our team.'));
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

