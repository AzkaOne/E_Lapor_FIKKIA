<?php

namespace App\Http\Controllers\Kabag;

use App\Http\Controllers\Controller;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $users = User::with('subDepartment')
            ->whereIn('role', ['kasubag', 'petugas'])
            ->latest()
            ->paginate(15);

        $subDepartments = SubDepartment::all();

        return view('kabag.users.index', compact('users', 'subDepartments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'ends_with:@unair.ac.id', 'unique:users,email'],
            'identity_number' => ['required', 'string', 'max:50'],
            'role' => ['required', 'in:kasubag,petugas'],
            'sub_department_id' => ['required', 'exists:sub_departments,id'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'identity_number' => $validated['identity_number'],
            'role' => $validated['role'],
            'sub_department_id' => $validated['sub_department_id'],
            'position' => 'tendik',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('kabag.users.index')
            ->with('success', "Akun {$validated['name']} ({$validated['role']}) berhasil didaftarkan.");
    }
}