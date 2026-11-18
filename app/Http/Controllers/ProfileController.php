<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Update the authenticated user's profile information and password.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:25'],
            'position' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'string', 'in:profil_men.webp,profil_wmn.webp'],
        ];

        if ($request->filled('new_password') || $request->filled('current_password')) {
            $rules['current_password'] = ['required', 'string'];
            $rules['new_password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $validated = $request->validate($rules, [
            'avatar.in' => 'Avatar yang dipilih tidak valid.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'current_password.required' => 'Password saat ini wajib diisi jika ingin mengganti password.',
        ]);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => 'Password saat ini tidak sesuai.',
                ]);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->name = $validated['name'];
        $user->phone_number = $validated['phone_number'] ?? null;
        $user->position = $validated['position'] ?? null;

        if (!empty($validated['avatar'])) {
            $user->avatar = $validated['avatar'];
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.')->with('profile_updated', true);
    }
}
