<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        return view('pengaturan', ['users' => $users]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,staff,user',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role_id'] = Role::where('slug', $validated['role'])->value('id');

        User::create($validated);

        return redirect()->route('pengaturan')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,staff,user',
        ]);

        $validated['role_id'] = Role::where('slug', $validated['role'])->value('id');

        $user->update($validated);

        return redirect()->route('pengaturan')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('pengaturan')->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        $user->delete();

        return redirect()->route('pengaturan')->with('success', 'Pengguna berhasil dihapus.');
    }
}