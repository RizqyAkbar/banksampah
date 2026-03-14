<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran; // Pastikan Model sudah dibuat
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    // Menampilkan halaman form input pengeluaran
    public function create()
    {
        return view('admin.pengeluaran_create');
    }

    // Menyimpan data pengeluaran ke database
    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required',
            'kategori_manual' => 'nullable|string',
            'nominal' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);
    
        if ($request->kategori === 'Lainnya' && $request->filled('kategori_manual')) {
            $data['kategori'] = $request->kategori_manual;
        }
    
        Pengeluaran::create($data);
    
        // Gunakan nama route yang konsisten dengan web.php
        return redirect()->route('admin.pengeluaran')->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function index()
    {
        $pengeluarans = Pengeluaran::orderBy('tanggal', 'desc')->get();
        // Pastikan nama view sesuai dengan file tabel kamu (admin/pengeluaran.blade.php)
        return view('admin.pengeluaran', compact('pengeluarans'));
    }

    public function destroy($id)
    {
        \App\Models\Pengeluaran::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data pengeluaran berhasil dihapus.');
    }

    // 1. Fungsi untuk menampilkan halaman edit
    public function edit($id)
    {
        // Mencari data berdasarkan ID, jika tidak ketemu akan muncul 404
        $pengeluaran = Pengeluaran::findOrFail($id);

        // Kirim variabel $pengeluaran ke view
        return view('admin.pengeluaran_edit', compact('pengeluaran'));
    }

    // 2. Fungsi untuk memproses update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required',
            'nominal' => 'required|numeric',
        ]);

        $pengeluaran = Pengeluaran::findOrFail($id);
        
        $data = $request->all();

        // Logika jika kategori adalah 'Lainnya' maka ambil dari input manual
        if ($request->kategori === 'Lainnya' && $request->filled('kategori_manual')) {
            $data['kategori'] = $request->kategori_manual;
        }

        $pengeluaran->update($data);

        return redirect()->route('admin.pengeluaran')->with('success', 'Data pengeluaran berhasil diperbarui!');
    }
}