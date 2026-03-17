<?php

namespace App\Http\Controllers\Admin; // Baris ini SANGAT PENTING

use App\Http\Controllers\Controller;
use App\Models\Service; // Pastikan model Service sudah dibuat
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index() {
        $services = Service::latest()->get();
        // Sesuai Sidebar Anda, arahkan ke file admin/master_layanan.blade.php
        return view('admin.master_layanan', compact('services'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama_layanan' => 'required',
            'armada' => 'required', // Tambahkan validasi ini
            'harga_dasar' => 'required|numeric',
            'harga_jual' => 'required|numeric',
        ]);
    
        Service::create($request->all());
        return back()->with('success', 'Layanan berhasil ditambah');
    }

    public function update(Request $request, $id) {
        $service = Service::findOrFail($id);
        $service->update($request->all());
        return back()->with('success', 'Layanan berhasil diupdate');
    }

    public function destroy($id) {
        $service = Service::findOrFail($id);
        $service->delete();
        return back()->with('success', 'Layanan dihapus');
    }
}