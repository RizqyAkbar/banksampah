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
        // Pastikan kodingan form login Anda sebelumnya disimpan 
        // di folder: resources/views/admin/login.blade.php
        return view('admin.login');
    }

    /**
     * Memproses data ketika tombol "Masuk" ditekan
     */
    public function login(Request $request)
    {
        // 1. Validasi inputan (pastikan tidak ada yang kosong dan format email benar)
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Siapkan data kredensial
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        // 3. Coba cocokkan dengan database (Auth::attempt otomatis mengecek password yang di-hash)
        if (Auth::attempt($credentials)) {
            // Jika berhasil: buat sesi baru untuk keamanan
            $request->session()->regenerate();

            // Redirect ke halaman dashboard
            return redirect()->intended(route('admin.dashboard'));
        }

        // 4. Jika gagal: Kembalikan ke halaman login dengan notifikasi error
        // (Sesuai dengan kodingan Blade Anda yang menggunakan session('error'))
        return back()->with('error', 'Email atau kata sandi salah. Silakan coba lagi.');
    }

    /**
     * Memproses logout admin
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus semua sesi
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman login
        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout dari sistem.');
    }
}