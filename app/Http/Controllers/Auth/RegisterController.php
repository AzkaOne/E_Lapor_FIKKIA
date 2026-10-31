<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@unair\.ac\.id$/i',
            ],
            'identity_number' => ['required', 'string', 'max:50'],
            'position' => ['required', 'in:mahasiswa,dosen,tendik'],
            'password' => ['required', 'confirmed', 'min:6'],
        ], [
            'email.regex' => 'Email harus menggunakan domain resmi sivitas @unair.ac.id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'identity_number' => $validated['identity_number'],
            'position' => $validated['position'],
            'role' => 'pelapor',
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('pelapor.dashboard');
    }
}
