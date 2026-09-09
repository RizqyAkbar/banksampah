<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Menampilkan halaman form login
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Memproses data ketika tombol "Masuk" ditekan
     */
    public function login(Request $request)
    {
        // 1. Validasi inputan
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Siapkan data kredensial
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        // 3. Coba cocokkan dengan database
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        // 4. Teks error diubah sesuai arahan bos untuk memperjelas jika ada salah input atau sesi idle
        return back()->withInput()->with('error', 'Email/Kata sandi salah, atau sesi login Anda telah habis. Silakan coba lagi.');
    }

    /**
     * Memproses logout admin
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout dari sistem.');
    }
}