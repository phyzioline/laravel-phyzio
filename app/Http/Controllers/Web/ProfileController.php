<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('web.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            // Vendor / Company specifics
            'image' => 'nullable|image|max:2048', // Profile Image
            'commercial_register' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'tax_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'account_statement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'card_image' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096', // ID Card
            'license_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'id_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $data = $request->only(['name', 'phone']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle File Uploads
        $files = [
            'image' => 'profile_images',
            'commercial_register' => 'documents',
            'tax_card' => 'documents',
            'account_statement' => 'documents',
            'card_image' => 'documents',
            'license_document' => 'documents',
            'id_document' => 'documents',
        ];

        foreach ($files as $field => $folder) {
            if ($request->hasFile($field)) {
                try {
                    // Delete old file if exists
                    if ($user->$field) {
                        $oldPath = $user->$field;
                        // Handle different path formats
                        if (str_starts_with($oldPath, 'storage/')) {
                            $oldPath = str_replace('storage/', '', $oldPath);
                        }
                        Storage::disk('public')->delete($oldPath);
                    }
                    
                    // Store new file
                    $path = $request->file($field)->store($folder, 'public');
                    $data[$field] = $path; // Store relative path
                    
                    \Log::info("File uploaded for {$field}: {$path}");
                } catch (\Exception $e) {
                    \Log::error("File upload error for {$field}: " . $e->getMessage());
                    return redirect()->back()
                        ->with('error', __('Failed to upload :file. Please try again.', ['file' => $field]))
                        ->withInput();
                }
            }
        }

        try {
            $user->update($data);
            \Log::info("Profile updated for user: {$user->id}", $data);
            
            // For company users, also create UserDocument records for verification
            if ($user->type === 'company' && !empty($data)) {
                $documentTypes = ['commercial_register', 'tax_card'];
                foreach ($documentTypes as $docType) {
                    if (isset($data[$docType])) {
                        \App\Models\UserDocument::updateOrCreate(
                            [
                                'user_id' => $user->id,
                                'document_type' => $docType,
                            ],
                            [
                                'file_path' => $data[$docType],
                                'status' => 'uploaded',
                            ]
                        );
                    }
                }
            }
            
            return redirect()->back()->with('success', __('Profile updated successfully.'));
        } catch (\Exception $e) {
            \Log::error("Profile update error: " . $e->getMessage());
            return redirect()->back()
                ->with('error', __('Failed to update profile. Please try again.'))
                ->withInput();
        }
    }
}
