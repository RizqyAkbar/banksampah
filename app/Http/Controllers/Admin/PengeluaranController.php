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
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required',
            'kategori_manual' => 'nullable|string',
            'nominal' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        // Ambil semua input
        $input = $request->all();

        // LOGIKA FIX: Jika pilih Lainnya, ganti nilai kategori dengan input manual
        if ($request->kategori === 'Lainnya' && $request->filled('kategori_manual')) {
            $input['kategori'] = $request->kategori_manual;
        }

        // Gunakan variabel $input (yang sudah dimodifikasi) untuk simpan ke DB
        Pengeluaran::create($input);

        return redirect()->route('admin.pengeluaran')->with('success', 'Pengeluaran berhasil dicatat!');
    }

    // REVISI: Menampilkan halaman list Pengeluaran (Dengan Filter dan Export Excel)
    public function index(Request $request)
    {
        $query = Pengeluaran::query();

        // --- LOGIKA PENCARIAN (Search) ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kategori', 'LIKE', "%$search%")
                  ->orWhere('keterangan', 'LIKE', "%$search%");
            });
        }

        // --- LOGIKA FILTER TANGGAL (Date Range) ---
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('tanggal', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        // --- LOGIKA EXPORT EXCEL (CSV Format) ---
        if ($request->has('export') && $request->export == 'excel') {
            $pengeluaranExport = $query->orderBy('tanggal', 'desc')->get();
            $filename = "Rekap_Pengeluaran_" . date('Ymd_His') . ".csv";
            
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $columns = [
                'No', 'Tanggal', 'Kategori', 'Keterangan', 'Nominal (Rp)'
            ];

            $callback = function() use($pengeluaranExport, $columns) {
                $file = fopen('php://output', 'w');
                // Menambahkan BOM agar Microsoft Excel bisa membaca karakter UTF-8 dengan baik
                fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); 
                fputcsv($file, $columns); 

                foreach ($pengeluaranExport as $index => $item) {
                    $row['No'] = $index + 1;
                    $row['Tanggal'] = \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d');
                    $row['Kategori'] = $item->kategori;
                    $row['Keterangan'] = $item->keterangan ?? '-';
                    $row['Nominal (Rp)'] = $item->nominal;

                    fputcsv($file, array(
                        $row['No'], $row['Tanggal'], $row['Kategori'], 
                        $row['Keterangan'], $row['Nominal (Rp)']
                    ));
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // --- TAMPILAN VIEW NORMAL (MENGGUNAKAN PAGINATE) ---
        $pengeluarans = $query->orderBy('tanggal', 'desc')->paginate(15);
        
        // Mempertahankan query string (filter/search) pada link pagination
        $pengeluarans->appends($request->all()); 

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