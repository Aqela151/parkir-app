<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegistrasiUserController extends Controller
{
    // Tampilkan halaman registrasi user
    public function index()
    {
        $users = User::all();
        return view('admin.registrasi-user', compact('users'));
    }

    // Store user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'penempatan' => 'required|string|max:255',
            'role' => 'required|in:Admin,Petugas,Owner',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'penempatan' => $request->penempatan,
            'role' => $request->role,
            'status' => 'aktif', // default aktif
        ]);
        
        // Log activity
        $user = Auth::user();
        LogAktivitas::create([
            'user_id' => $user->id,
            'aktivitas' => "Tambah user {$request->name} ({$request->role})",
            'lokasi' => $request->penempatan,
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:8',
            'penempatan' => 'required|string|max:255',
            'role' => 'required|in:Admin,Petugas,Owner',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'penempatan' => $request->penempatan,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        
        // Log activity
        $currentUser = Auth::user();
        LogAktivitas::create([
            'user_id' => $currentUser->id,
            'aktivitas' => "Update user {$request->name} ({$request->role})",
            'lokasi' => $request->penempatan,
        ]);

        return back()->with('success', 'User berhasil diupdate.');
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name;
        $userRole = $user->role;
        $userPenempatan = $user->penempatan;
        $user->delete();
        
        // Log activity
        $currentUser = Auth::user();
        LogAktivitas::create([
            'user_id' => $currentUser->id,
            'aktivitas' => "Hapus user {$userName} ({$userRole})",
            'lokasi' => $userPenempatan,
        ]);

        return back()->with('success', 'User berhasil dihapus.');
    }
}