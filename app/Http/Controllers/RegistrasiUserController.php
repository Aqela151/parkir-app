<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'penempatan' => 'required|string|max:255',
            'role' => 'required|in:Admin,Petugas,Owner',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'penempatan' => $request->penempatan,
            'role' => $request->role,
            'status' => 'aktif', // default aktif
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:8',
            'penempatan' => 'required|string|max:255',
            'role' => 'required|in:Admin,Petugas,Owner',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'penempatan' => $request->penempatan,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'User berhasil diupdate.');
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
}