<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('profil.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:5|confirmed',
            'photo_profile' => 'nullable|image|max:2048',
            'hapus_foto' => 'nullable|boolean',
        ]);

        $data = ['name' => $request->name, 'username' => $request->username];
        if ($request->filled('password')) $data['password'] = $request->password;

        if ($request->input('hapus_foto') && $user->photo_profile) {
            Storage::disk('public')->delete($user->photo_profile);
            $data['photo_profile'] = null;
        } elseif ($request->hasFile('photo_profile')) {
            if ($user->photo_profile) Storage::disk('public')->delete($user->photo_profile);
            $data['photo_profile'] = $request->file('photo_profile')->store('profil', 'public');
        }

        $user->update($data);
        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
