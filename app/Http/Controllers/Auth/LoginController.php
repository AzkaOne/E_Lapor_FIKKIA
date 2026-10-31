<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['nullable', 'string'],
            'email' => ['nullable', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = $request->input('login') ?? $request->input('email');

        // Check if input is email or identity_number (NIM/NIP)
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'identity_number';

        if (Auth::attempt([$field => $loginInput, 'password' => $request->input('password')], $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            return match ($user->role) {
                'kabag' => redirect()->intended(route('kabag.dashboard')),
                'kasubag' => redirect()->intended(route('kasubag.dashboard')),
                'petugas' => redirect()->intended(route('petugas.dashboard')),
                default => redirect()->intended(route('pelapor.dashboard')),
            };
        }

        return back()->withErrors([
            'email' => 'Identitas atau kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email', 'login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
