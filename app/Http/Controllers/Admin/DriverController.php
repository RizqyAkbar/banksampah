<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    // Tampilan daftar sopir
    public function index()
    {
        $drivers = Driver::orderBy('nama_sopir', 'asc')->get();
        return view('admin.master_sopir', compact('drivers'));
    }

    // Simpan sopir baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_sopir' => 'required',
            'no_wa_sopir' => 'required',
            'plat_armada' => 'required',
        ]);

        Driver::create([
            'nama_sopir' => $request->nama_sopir,
            'no_wa_sopir' => '62' . ltrim($request->no_wa_sopir, '0'),
            'plat_armada' => strtoupper($request->plat_armada),
        ]);

        return back()->with('success', 'Sopir berhasil didaftarkan!');
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);
        $driver->update([
            'nama_sopir' => $request->nama_sopir,
            'no_wa_sopir' => $request->no_wa_sopir,
            'plat_armada' => strtoupper($request->plat_armada),
        ]);

        return back()->with('success', 'Data sopir diperbarui!');
    }

    // Hapus sopir
    public function destroy($id)
    {
        Driver::findOrFail($id)->delete();
        return back()->with('success', 'Sopir telah dihapus dari sistem.');
    }

    // API untuk Pencarian Sopir (Digunakan untuk Autofill nanti)
    public function search(Request $request)
    {
        $query = $request->get('q');
        $drivers = Driver::where('nama_sopir', 'LIKE', "%$query%")->get();

        return response()->json($drivers);
    }
}