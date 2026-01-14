<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua user beserta role-nya.
     * Requirement: Menampilkan daftar user beserta nama role-nya.
     */
    public function index()
    {
        // Eager Loading ('role') digunakan agar hemat query database (N+1 Problem)
        $users = User::with('role')->latest()->get();
        
        // Kita juga butuh daftar semua Role untuk ditampilkan di Dropdown nanti
        $roles = Role::all();

        return Inertia::render('Users/Index', [
            'users' => $users,
            'roles' => $roles
        ]);
    }

    /**
     * Mengubah Role User tertentu.
     * Requirement: User hanya bisa diubah ke role yang valid.
     */
    public function updateRole(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'role_id' => 'required|exists:roles,id', 
        ]);

        // 2. Cari User
        $user = User::findOrFail($id);

        // 3. Update Role
        $user->role_id = $request->role_id;
        $user->save();

        // 4. Kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Role pengguna berhasil diperbarui!');
    }
}