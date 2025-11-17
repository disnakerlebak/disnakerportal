<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    /**
     * Menampilkan daftar semua admin internal
     */
    public function index()
    {
        $admins = User::whereIn('role', ['superadmin', 'admin_ak1', 'admin_laporan'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.manage_admin.index', compact('admins'));
    }

    /**
     * Menambahkan admin baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:superadmin,admin_ak1,admin_laporan'],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'status'   => 'active', // gunakan kolom ini secara konsisten
        ]);

        return redirect()
            ->route('admin.manage.index')
            ->with('success', 'Admin baru berhasil ditambahkan.');
    }

    /**
     * Memperbarui data admin
     */
    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $admin->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:superadmin,admin_ak1,admin_laporan'],
        ]);

        // update data admin
        $admin->fill([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'role'  => $validated['role'],
        ]);

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()
            ->route('admin.manage.index')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    /**
     * Menghapus admin
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Mengaktifkan / menonaktifkan admin
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // gunakan kolom 'status' sebagai string konsisten
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return back()->with('success', 'Status pengguna berhasil diperbarui.');
    }
}
