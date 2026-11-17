<?php

namespace App\Http\Controllers\Kabag;

use App\Http\Controllers\Controller;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('subDepartment')
            ->whereIn('role', ['kasubag', 'petugas']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('identity_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sub_department_id')) {
            $query->where('sub_department_id', $request->sub_department_id);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        $subDepartments = SubDepartment::orderBy('name')->get();

        $sarprasDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'sarana') || str_contains(strtolower($d->name), 'sarpras'));
        $dekanatDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'dekanat'));
        $keuanganDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'keuangan') || str_contains(strtolower($d->name), 'sdm'));
        $akademikDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'pendidikan') || str_contains(strtolower($d->name), 'kemahasiswaan') || str_contains(strtolower($d->name), 'akademik'));

        $baseUsers = User::whereIn('role', ['kasubag', 'petugas']);
        $totalCount = (clone $baseUsers)->count();
        $sarprasCount = $sarprasDept ? (clone $baseUsers)->where('sub_department_id', $sarprasDept->id)->count() : 0;
        $dekanatCount = $dekanatDept ? (clone $baseUsers)->where('sub_department_id', $dekanatDept->id)->count() : 0;
        $keuanganCount = $keuanganDept ? (clone $baseUsers)->where('sub_department_id', $keuanganDept->id)->count() : 0;
        $akademikCount = $akademikDept ? (clone $baseUsers)->where('sub_department_id', $akademikDept->id)->count() : 0;

        $counts = [
            'total' => $totalCount,
            'sarpras' => $sarprasCount,
            'dekanat' => $dekanatCount,
            'keuangan' => $keuanganCount,
            'akademik' => $akademikCount,
        ];

        return view('kabag.manajemen_pegawai', compact('users', 'subDepartments', 'counts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'ends_with:@unair.ac.id', 'unique:users,email'],
            'identity_number' => ['required', 'string', 'max:50', 'unique:users,identity_number'],
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
            ->with('success', "Akun pegawai {$validated['name']} ({$validated['role']}) berhasil didaftarkan.");
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if (!in_array($user->role, ['kasubag', 'petugas'])) {
            abort(403, 'Hanya akun Kasubag atau Petugas yang dapat dikelola.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'ends_with:@unair.ac.id', Rule::unique('users')->ignore($user->id)],
            'identity_number' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'in:kasubag,petugas'],
            'sub_department_id' => ['required', 'exists:sub_departments,id'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->identity_number = $validated['identity_number'];
        $user->role = $validated['role'];
        $user->sub_department_id = $validated['sub_department_id'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('kabag.users.index')
            ->with('success', "Data pegawai {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if (!in_array($user->role, ['kasubag', 'petugas'])) {
            abort(403, 'Hanya akun Kasubag atau Petugas yang dapat dihapus.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('kabag.users.index')
            ->with('success', "Akun pegawai {$userName} berhasil dihapus dari sistem.");
    }

    public function import(Request $request, \App\Services\UserImportService $importService): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv,txt', 'max:10240'],
        ], [
            'file.required' => 'Silakan pilih berkas Excel atau CSV terlebih dahulu.',
            'file.mimes' => 'Format berkas harus berupa Excel (.xlsx, .xls) atau CSV (.csv).',
            'file.max' => 'Ukuran berkas maksimal adalah 10MB.',
        ]);

        $result = $importService->import($request->file('file'));

        return redirect()->route('kabag.manajemen-pegawai')
            ->with('import_result', $result);
    }

    public function downloadTemplate(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_pegawai.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['Nama', 'Email', 'NIP', 'Role', 'Bidang', 'No_Telp', 'Jabatan', 'Password']);
            fputcsv($handle, ['Budi Santoso, S.Kom.', 'budi.santoso@unair.ac.id', '198501012010121001', 'petugas', 'Sarana dan Prasarana', '081234567890', 'Staff Lapangan', '']);
            fputcsv($handle, ['Siti Rahmawati, S.E.', 'siti.rahma@unair.ac.id', '198802022012012002', 'kasubag', 'Akademik', '089876543210', 'Kasubag Akademik', '']);

            fclose($handle);
        }, 200, $headers);
    }
}