<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProfileController extends BaseClinicController
{
    public function index()
    {
        $user = auth()->user();
        $clinic = $this->getUserClinic($user);
        return view('clinic.profile.index', compact('user', 'clinic'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'commercial_register' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'tax_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:50',
            'swift_code' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update user data
        $userData = $request->only(['name', 'phone', 'bank_name', 'bank_account_name', 'iban', 'swift_code']);

        if ($request->filled('password')) {
            $userData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        if ($request->hasFile('commercial_register')) {
            $userData['commercial_register'] = $request->file('commercial_register')->store('documents', 'public');
        }

        if ($request->hasFile('tax_card')) {
            $userData['tax_card'] = $request->file('tax_card')->store('documents', 'public');
        }

        $user->update($userData);

        // Create or update Clinic record
        $clinic = Clinic::where('company_id', $user->id)->first();
        
        if (!$clinic) {
            // Create new clinic
            $slug = Str::slug($request->name) . '-' . $user->id;
            $clinic = Clinic::create([
                'company_id' => $user->id,
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'address' => $request->address ?? 'Not specified',
                'city' => $request->city ?? 'Cairo',
                'country' => $request->country ?? 'Egypt',
                'phone' => $request->phone,
                'email' => $user->email,
                'is_active' => true,
                'is_deleted' => false,
            ]);
        } else {
            // Update existing clinic
            $clinic->update([
                'name' => $request->name,
                'description' => $request->description,
                'address' => $request->address ?? $clinic->address,
                'city' => $request->city ?? $clinic->city,
                'country' => $request->country ?? $clinic->country,
                'phone' => $request->phone,
                'email' => $user->email,
            ]);
        }

        return redirect()->back()->with('success', __('Clinic profile updated successfully.'));
    }
}
