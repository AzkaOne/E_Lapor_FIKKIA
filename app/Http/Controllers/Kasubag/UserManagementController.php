<?php

namespace App\Http\Controllers\Kasubag;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $petugasList = User::where('role', 'petugas')
            ->where('sub_department_id', auth()->user()->sub_department_id)
            ->latest()
            ->paginate(15);

        return view('kasubag.users.index', compact('petugasList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'ends_with:@unair.ac.id', 'unique:users,email'],
            'identity_number' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'identity_number' => $validated['identity_number'],
            'role' => 'petugas',
            'sub_department_id' => auth()->user()->sub_department_id,
            'position' => 'tendik',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('kasubag.petugas.index')
            ->with('success', "Petugas {$validated['name']} berhasil ditambahkan ke sub bagian.");
    }
}