<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // View yang kamu berikan
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'login' => 'required|string', // Bisa username atau email
            'password' => 'required|string|min:6'
        ]);

        // Tentukan apakah login pakai email atau username
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        // Ambil credentials dan cek status aktif
        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
            'is_active' => 1 // Hanya user aktif yang bisa login
        ];

        $remember = $request->has('remember');

        // Coba login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Update last_login
            $user = Auth::user();
            $user->last_login = now();
            $user->save();

            return redirect()->intended('/dashboard'); // Ganti sesuai route dashboard
        }

        return back()->withErrors([
            'login' => 'Login gagal! Periksa username/email dan password Anda, atau akun nonaktif.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
