<?php

namespace App\Http\Controllers\Admin; // Baris ini SANGAT PENTING

use App\Http\Controllers\Controller;
use App\Models\Service; // Pastikan model Service sudah dibuat
use Illuminate\Http\Request;

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

        // --- LOGIKA EXPORT EXCEL (CSV Format) ---
        if ($request->has('export') && $request->export == 'excel') {
            $servicesExport = $query->orderBy('nama_layanan', 'asc')->get();
            $filename = "Master_Data_Layanan_" . date('Ymd_His') . ".csv";
            
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $columns = [
                'No', 'Nama Layanan', 'Jenis Armada', 'Harga Dasar (HPP)', 'Harga Jual'
            ];

            $callback = function() use($servicesExport, $columns) {
                $file = fopen('php://output', 'w');
                // Menambahkan BOM agar Microsoft Excel bisa membaca format UTF-8
                fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); 
                fputcsv($file, $columns); 

                foreach ($servicesExport as $index => $s) {
                    $row['No'] = $index + 1;
                    $row['Nama Layanan'] = $s->nama_layanan;
                    $row['Jenis Armada'] = $s->armada ?: '-'; 
                    $row['Harga Dasar (HPP)'] = $s->harga_dasar;
                    $row['Harga Jual'] = $s->harga_jual;

                    fputcsv($file, array(
                        $row['No'], $row['Nama Layanan'], $row['Jenis Armada'], 
                        $row['Harga Dasar (HPP)'], $row['Harga Jual']
                    ));
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // --- TAMPILAN VIEW NORMAL DENGAN PAGINATION ---
        $services = $query->orderBy('nama_layanan', 'asc')->paginate(15);
        $services->appends($request->all());

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