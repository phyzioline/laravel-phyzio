<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('clinic.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'commercial_register' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'tax_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['name', 'phone']);

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        if ($request->hasFile('commercial_register')) {
            $data['commercial_register'] = $request->file('commercial_register')->store('documents', 'public');
        }

        if ($request->hasFile('tax_card')) {
            $data['tax_card'] = $request->file('tax_card')->store('documents', 'public');
        }

        $user->update($data);

        return redirect()->back()->with('success', __('Clinic profile updated successfully.'));
    }
}
