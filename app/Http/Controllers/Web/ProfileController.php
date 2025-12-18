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
                // Delete old file if exists (optional, good practice)
                if ($user->$field) {
                    Storage::disk('public')->delete($user->$field);
                }
                $data[$field] = $request->file($field)->store($folder, 'public');
            }
        }

        $user->update($data);

        return redirect()->back()->with('success', __('Profile updated successfully.'));
    }
}
