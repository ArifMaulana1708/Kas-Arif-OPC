<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function loginForm()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $cred = ['username' => $request->username, 'password' => $request->password];
        if (Auth::attempt($cred, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }
        return back()->withErrors(['username' => 'Username / password salah.'])->onlyInput('username');
    }

    public function registerForm()
    {
        return view('auth.register', ['isAdmin' => false]);
    }

    public function admRegistForm()
    {
        return view('auth.register', ['isAdmin' => true]);
    }

    public function register(Request $request, string $type = 'user')
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:5|confirmed',
            'photo_profile' => 'nullable|image|max:2048',
        ]);

        $photo = null;
        if ($request->hasFile('photo_profile')) {
            $photo = $request->file('photo_profile')->store('profil', 'public');
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => $request->password,
            'role' => $type === 'admin' ? 'admin' : 'user',
            'photo_profile' => $photo,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
