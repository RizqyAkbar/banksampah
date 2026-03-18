<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

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

        // --- LOGIKA EXPORT EXCEL (CSV Format) ---
        if ($request->has('export') && $request->export == 'excel') {
            $driversExport = $query->orderBy('nama_sopir', 'asc')->get();
            $filename = "Master_Data_Sopir_" . date('Ymd_His') . ".csv";
            
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $columns = [
                'No', 'Nama Sopir', 'Nomor WhatsApp', 'Plat Armada Default', 'Tgl Didaftarkan'
            ];

            $callback = function() use($driversExport, $columns) {
                $file = fopen('php://output', 'w');
                // Menambahkan BOM agar Excel membaca format UTF-8
                fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); 
                fputcsv($file, $columns); 

                foreach ($driversExport as $index => $d) {
                    $row['No'] = $index + 1;
                    $row['Nama Sopir'] = $d->nama_sopir;
                    $row['Nomor WhatsApp'] = "+" . $d->no_wa_sopir; 
                    $row['Plat Armada Default'] = $d->plat_armada ?: '-';
                    $row['Tgl Didaftarkan'] = \Carbon\Carbon::parse($d->created_at)->format('Y-m-d H:i');

                    fputcsv($file, array(
                        $row['No'], $row['Nama Sopir'], $row['Nomor WhatsApp'], 
                        $row['Plat Armada Default'], $row['Tgl Didaftarkan']
                    ));
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // --- TAMPILAN NORMAL (MENGGUNAKAN PAGINATE, BUKAN GET) ---
        // Ini yang menyelesaikan error currentPage() does not exist
        $drivers = $query->orderBy('nama_sopir', 'asc')->paginate(15);
        
        // Mempertahankan parameter pencarian saat pindah halaman
        $drivers->appends($request->all());

        return view('admin.master_sopir', compact('drivers'));
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