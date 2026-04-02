<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Exports\DriverExport;
use Maatwebsite\Excel\Facades\Excel;

class DriverController extends Controller
{
    // Tampilan daftar sopir dengan fitur Search, Export Excel, & Pagination
    public function index(Request $request)
    {
        $query = Driver::query();

        // --- LOGIKA PENCARIAN (Search) ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_sopir', 'LIKE', "%$search%")
                  ->orWhere('no_wa_sopir', 'LIKE', "%$search%")
                  ->orWhere('plat_armada', 'LIKE', "%$search%");
            });
        }

        // --- LOGIKA EXPORT EXCEL ---
        if ($request->has('export') && $request->export == 'excel') {
            return Excel::download(new DriverExport, 'Data_Master_Sopir_' . date('d-m-Y') . '.xlsx');
        }

        // --- TAMPILAN NORMAL (MENGGUNAKAN PAGINATE, BUKAN GET) ---
        $drivers = $query->orderBy('nama_sopir', 'asc')->paginate(15);
        
        // Mempertahankan parameter pencarian saat pindah halaman
        $drivers->appends($request->all());

        return view('admin.master_sopir', compact('drivers'));
    }

    public function exportExcel()
    {
        return Excel::download(new DriverExport, 'Data_Master_Sopir_' . date('Y-m-d') . '.xlsx');
    }

    // Simpan sopir baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_sopir' => 'required',
            'no_wa_sopir' => 'required',
            'plat_armada' => 'nullable|string',
        ]);

        Driver::create([
            'nama_sopir' => $request->nama_sopir,
            'no_wa_sopir' => '62' . ltrim($request->no_wa_sopir, '0'),
            'plat_armada' => $request->plat_armada ? strtoupper($request->plat_armada) : null,
        ]);

        return back()->with('success', 'Sopir berhasil didaftarkan!');
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);
        
        $request->validate([
            'nama_sopir' => 'required',
            'no_wa_sopir' => 'required',
            'plat_armada' => 'nullable|string', 
        ]);

        $driver->update([
            'nama_sopir' => $request->nama_sopir,
            'no_wa_sopir' => $request->no_wa_sopir,
            'plat_armada' => $request->plat_armada ? strtoupper($request->plat_armada) : null,
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