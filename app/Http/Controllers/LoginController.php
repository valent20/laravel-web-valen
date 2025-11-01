<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // <-- Sesuai modul
use App\Models\User; // <-- Sesuai modul

class LoginController extends Controller
{
    /**
     * Menampilkan halaman/form login.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Memproses data login (Sesuai instruksi modul).
     */
    public function authenticate(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cek apakah email ada
        $user = User::where('email', $credentials['email'])->first();

        //  3.  Hash::check($request->password, $user->password)
        if ($user && Hash::check($credentials['password'], $user->password)) {

            // 4. Jika sama, login & tampilkan Dashboard
            Auth::login($user); // Login-kan user
            $request->session()->regenerate(); // Buat session baru

            return redirect()->route('dashboard'); // Arahkan ke dashboard
        }

        // 5. Jika tidak, kembali ke login + error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email'); // Kembalikan input email saja
    }

    /**
     * Memproses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
