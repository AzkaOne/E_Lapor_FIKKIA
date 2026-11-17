<?php

namespace App\Http\Controllers\Kasubag;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $subDeptId = auth()->user()->sub_department_id;

        $query = User::with('subDepartment')
            ->where('sub_department_id', $subDeptId)
            ->whereIn('role', ['kasubag', 'petugas']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('identity_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('kasubag.manajemen_petugas', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'ends_with:@unair.ac.id', 'unique:users,email'],
            'identity_number' => ['required', 'string', 'max:50', 'unique:users,identity_number'],
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
            'avatar' => 'profil_men.webp',
        ]);

        return redirect()->route('kasubag.petugas.index')
            ->with('success', "Petugas {$validated['name']} berhasil didaftarkan.");
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if ($user->role !== 'petugas' || $user->sub_department_id !== auth()->user()->sub_department_id) {
            abort(403, 'Hanya akun Petugas pada bidang Anda yang dapat diedit.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'ends_with:@unair.ac.id', Rule::unique('users')->ignore($user->id)],
            'identity_number' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->identity_number = $validated['identity_number'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('kasubag.petugas.index')
            ->with('success', "Data petugas {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->role !== 'petugas' || $user->sub_department_id !== auth()->user()->sub_department_id) {
            abort(403, 'Hanya akun Petugas pada bidang Anda yang dapat dihapus.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('kasubag.petugas.index')
            ->with('success', "Akun petugas {$userName} berhasil dihapus dari sistem.");
    }

    public function import(Request $request, UserImportService $importService): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv,txt', 'max:10240'],
        ], [
            'file.required' => 'Silakan pilih berkas Excel atau CSV terlebih dahulu.',
            'file.mimes' => 'Format berkas harus berupa Excel (.xlsx, .xls) atau CSV (.csv).',
            'file.max' => 'Ukuran berkas maksimal adalah 10MB.',
        ]);

        $result = $importService->import(
            $request->file('file'),
            auth()->user()->sub_department_id,
            'petugas'
        );

        return redirect()->route('kasubag.petugas.index')
            ->with('import_result', $result);
    }

    public function downloadTemplate(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_petugas.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['Nama', 'Email', 'NIP', 'No_Telp', 'Jabatan', 'Password']);
            fputcsv($handle, ['Budi Santoso, S.Kom.', 'budi.santoso@unair.ac.id', '198501012010121001', '081234567890', 'Staff Lapangan', '']);
            fputcsv($handle, ['Ahmad Dani, A.Md.', 'ahmad.dani@unair.ac.id', '199203042018021003', '085712345678', 'Teknisi Pemeliharaan', '']);

            fclose($handle);
        }, 200, $headers);
    }
}
