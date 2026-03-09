<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    // Menampilkan halaman form login
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Proses ketika tombol login ditekan
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Nanti logika autentikasi (Auth::attempt) ditaruh di sini
        // Sementara kita redirect kembali ke login
        return back()->with('error', 'Sistem login sedang dikembangkan.');
    }
}