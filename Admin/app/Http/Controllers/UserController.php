<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua user kecuali user yang sedang login, agar tidak bisa mengubah role diri sendiri
        $users = User::where('id', '!=', Auth::id())->paginate(10);
        
        // Ambil semua konstanta role dari model User
        $roles = [
            User::ROLE_SUPERADMIN,
            User::ROLE_ADMIN,
            User::ROLE_USER,
        ];

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Update the specified user's role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRole(Request $request, User $user)
    {
        // Validasi input
        $request->validate([
            'role' => ['required', Rule::in([User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_USER])],
        ]);

        // Larangan: Admin tidak boleh mengubah role Superadmin
        if ($user->isSuperAdmin() && Auth::user()->isAdmin()) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengubah role Super Admin.');
        }

        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'Role pengguna berhasil diperbarui.');
    }

    /**
     * Activate or deactivate the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(User $user)
    {
        // Larangan: Tidak bisa menonaktifkan diri sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = !$user->is_active;
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        $user->save();

        return back()->with('success', "Pengguna berhasil {$status}.");
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Larangan: Tidak bisa menghapus diri sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Larangan: Admin tidak boleh menghapus Superadmin
        if ($user->isSuperAdmin() && Auth::user()->isAdmin()) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus akun Super Admin.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus secara permanen.');
    }
}