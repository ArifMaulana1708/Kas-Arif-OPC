<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = User::where('role', 'user');
        if ($request->filled('search')) {
            $q->where(function ($x) use ($request) {
                $x->where('username', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }
        $users = $q->orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:5',
            'photo_profile' => 'nullable|image|max:2048',
        ]);
        $photo = $request->hasFile('photo_profile')
            ? $request->file('photo_profile')->store('profil', 'public') : null;
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => $request->password,
            'role' => 'user',
            'photo_profile' => $photo,
        ]);
        return back()->with('success', 'User ditambah.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:5',
            'photo_profile' => 'nullable|image|max:2048',
        ]);
        $data = ['name' => $request->name, 'username' => $request->username];
        if ($request->filled('password')) $data['password'] = $request->password;
        if ($request->hasFile('photo_profile')) {
            if ($user->photo_profile) Storage::disk('public')->delete($user->photo_profile);
            $data['photo_profile'] = $request->file('photo_profile')->store('profil', 'public');
        }
        $user->update($data);
        return back()->with('success', 'User diupdate.');
    }

    public function destroy(User $user)
    {
        if ($user->photo_profile) Storage::disk('public')->delete($user->photo_profile);
        $user->delete();
        return back()->with('success', 'User dihapus.');
    }
}
