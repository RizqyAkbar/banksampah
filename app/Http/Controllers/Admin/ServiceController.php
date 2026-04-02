<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller;
use App\Models\Service; 
use Illuminate\Http\Request;
use App\Exports\ServiceExport;
use Maatwebsite\Excel\Facades\Excel;

class ServiceController extends Controller
{
    public function index(Request $request) {
        $query = Service::query();

        // --- LOGIKA PENCARIAN (Search) ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_layanan', 'LIKE', "%$search%")
                  ->orWhere('armada', 'LIKE', "%$search%");
            });
        }

        // --- LOGIKA EXPORT EXCEL ---
        // Saat tombol Export diklik (membawa payload export=excel), sistem akan memanggil ServiceExport
        if ($request->has('export') && $request->export == 'excel') {
            return Excel::download(new ServiceExport, 'Master_Layanan_' . date('d-m-Y') . '.xlsx');
        }

        // --- TAMPILAN VIEW NORMAL DENGAN PAGINATION ---
        $services = $query->orderBy('nama_layanan', 'asc')->paginate(15);
        $services->appends($request->all());

        return view('admin.master_layanan', compact('services'));
    }

    // Method ini bisa dibiarkan saja (sebagai cadangan)
    public function exportExcel()
    {
        return Excel::download(new ServiceExport, 'Master_Layanan_' . date('d-m-Y') . '.xlsx');
    }

    public function store(Request $request) {
        $request->validate([
            'nama_layanan' => 'required',
            'armada' => 'required', 
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