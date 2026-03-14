<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengeluaran; // Tambahkan ini

class OrderController extends Controller
{
    // 1. Menampilkan Form Input Order (Data Awal)
    public function create()
    {
        return view('admin.input_order');
    }

    // 2. Menyimpan Input Order Awal
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required',
            'no_wa'          => 'required',
            'alamat'         => 'required',
            'penjadwalan'    => 'required|date',
            'link_maps'      => 'nullable|url',
        ]);

        // --- BAGIAN BARU: OTOMATISASI FORMAT +62 ---
        // ltrim menghapus angka 0 di depan jika admin masih typo mengetiknya
        $nomorBersih = ltrim($request->no_wa, '0');
        $noWaFinal = '62' . $nomorBersih;
        // -------------------------------------------

        // Generate Invoice Permanen
        $tanggal = date('Ymd');
        $nomorUrut = str_pad(Order::count() + 1, 3, '0', STR_PAD_LEFT);
        $noInvoiceFinal = "INV-$tanggal-$nomorUrut";

        // --- REVISI LOGIKA CEK PELANGGAN ---
        // Hitung berapa kali nomor WA ini sudah melakukan order sebelumnya
        $jumlahOrder = Order::where('no_wa', $noWaFinal)->count();

        // Jika sudah ada 2 order sebelumnya, maka order ke-3 ini (dan seterusnya) otomatis jadi 'Pelanggan Lama'
        if ($jumlahOrder >= 2) {
            $statusPelanggan = 'Pelanggan';
        } else {
            $statusPelanggan = 'Konsumen Baru';
        }
        // -----------------------------------

        Order::create([
            'nama_pelanggan'   => $request->nama_pelanggan,
            'no_wa'            => $noWaFinal,
            'alamat'           => $request->alamat,
            'penjadwalan'      => $request->penjadwalan,
            'catatan'          => $request->catatan,
            'status_pelanggan' => $statusPelanggan,
            'status_order'     => 'Aktif', // Pastikan defaultnya Aktif
            'link_maps'        => $request->link_maps,
            'jenis_layanan'    => '-', 
            'armada'           => '-',
            'no_invoice'       => $noInvoiceFinal,
        ]);

        return redirect()->back()->with('success', 'Order baru berhasil ditambahkan!');
    }

    // 3. Halaman REKAP ORDER (List Tabel)
    public function index()
    {
        // Mengambil semua order, diurutkan dari yang terbaru
        $orders = Order::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.rekap_order', compact('orders'));
    }

    // 4. Halaman EDIT REKAP (Melengkapi Harga & Rit)
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.rekap_order_edit', compact('order'));
    }

    // 5. UPDATE REKAP (Menyimpan hasil hitungan otomatis)
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Simpan data lama ke variabel sebelum di-update
        $sopirLama = $order->nama_sopir;
        $platLama  = $order->plat_armada;

        $request->validate([
            'jumlah_rit'    => 'required|numeric',
            'harga_per_rit' => 'required|numeric',
            'hpp_per_rit'   => 'required|numeric',
            'alasan_perubahan' => 'nullable|string|max:255',
        ]);

        // --- LOGIKA JENIS LAYANAN (PENTING!) ---
        // Jika pilih 'Lainnya', ambil dari input kustom. Jika tidak, ambil dari dropdown.
        $jenisLayanan = ($request->jenis_layanan === 'Lainnya') 
                        ? $request->jenis_layanan_kustom 
                        : $request->jenis_layanan;

        
        // FORMAT WA SOPIR (Sama seperti logika WhatsApp Pelanggan)
        $noWaSopir = null;
        if ($request->no_wa_sopir) {
            $nomorBersihSopir = ltrim($request->no_wa_sopir, '0');
            $noWaSopir = '62' . $nomorBersihSopir;
        }

        // Hitung Pendapatan & Keuntungan di Backend (untuk keamanan ganda)
        $pendapatan = $request->jumlah_rit * $request->harga_per_rit;
        $total_hpp  = $request->jumlah_rit * $request->hpp_per_rit;
        $keuntungan = $pendapatan - $total_hpp;

        // Cek apakah ada perubahan Sopir atau Plat
        $apakahBerubah = ($sopirLama != $request->nama_sopir) || ($platLama != $request->plat_armada);

        if ($apakahBerubah && $sopirLama != null) {
            // 3. Catat ke Log History
            OrderLog::create([
                'order_id'   => $order->id,
                'perubahan'  => 'Pergantian Sopir/Armada',
                'detail'     => "Sopir lama: $sopirLama ($platLama) diganti menjadi: {$request->nama_sopir} ({$request->plat_armada})",
                'alasan'     => $request->alasan_perubahan ?? 'Tidak disebutkan',
                'admin_name' => Auth::user()->name ?? 'Admin Juragan',
            ]);
        }

        // Update data order
        $order->update([
            'jenis_layanan'     => $jenisLayanan, // Fitur bisa ganti/tambah layanan
            'armada'            => $request->armada,
            'jumlah_rit'        => $request->jumlah_rit,
            'harga_per_rit'     => $request->harga_per_rit,
            'hpp_per_rit'       => $request->hpp_per_rit,
            'jumlah_pendapatan' => $pendapatan,
            'jumlah_keuntungan' => $keuntungan,
            'catatan'           => $request->catatan,
            'link_maps'         => $request->link_maps,
            'nama_sopir'        => $request->nama_sopir,
            'no_wa_sopir'       => $noWaSopir,
            'plat_armada'       => $request->plat_armada,
            'alamat'            => $request->alamat,
        ]);

        return redirect()->route('admin.rekap_order')->with('success', 'Rekap Order berhasil dilengkapi & dihitung!');
    }

    // Fungsi untuk menghapus data rekap order
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.rekap_order')->with('success', 'Data order berhasil dihapus!');
    }

    // Tampil list order yang siap dibuatkan invoice
    public function invoiceIndex()
    {
        // Hanya mengambil order yang SUDAH DIISI harganya DAN berstatus AKTIF
        $orders = Order::where('jumlah_pendapatan', '>', 0)
                    ->where(function($query) {
                        $query->where('status_order', 'Aktif')
                                ->orWhereNull('status_order')
                                ->orWhere('status_order', '');
                    })
                    ->orderBy('updated_at', 'desc')
                    ->paginate(10);
                    
        return view('admin.invoice_index', compact('orders'));
    }

    // Ambil data detail untuk modal (JSON)
    public function getDetail($id)
    {
        $order = Order::findOrFail($id);
        return response()->json([
            'no_invoice' => $order->no_invoice ?? 'INV-' . $order->created_at->format('Ymd') . '-' . str_pad($order->id, 3, '0', STR_PAD_LEFT),
            'tanggal' => $order->created_at->format('d F Y'),
            'data' => $order
        ]);
    }

    public function printInvoice($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.invoice_print', compact('order'));
    }

    public function paymentIndex(Request $request)
    {
        $query = Order::where('jumlah_pendapatan', '>', 0)
                    ->where(function($query) {
                        $query->where('status_order', 'Aktif')
                        ->orWhereNull('status_order');
                    });

        // LOGIKA FILTER BARU
        if ($request->has('filter')) {
            if ($request->filter == 'lunas') {
                $query->where('status_pembayaran', 'Lunas');
            } elseif ($request->filter == 'belum_lunas') {
                $query->whereIn('status_pembayaran', ['Belum Lunas', 'Cicil']);
            }
        }

        $orders = $query->orderBy('updated_at', 'desc')->paginate(10);

        return view('admin.payment_index', compact('orders'));
    }

    public function paymentUpdate(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // 1. Validasi Input
        $request->validate([
            'jumlah_dibayar'    => 'required|numeric|min:0',
            'metode_pembayaran' => 'nullable',
            'tanggal_pelunasan' => 'nullable|date',
        ]);

        // 2. Logika Matematika Cicilan
        $totalTagihan = $order->jumlah_pendapatan;
        $uangMasuk    = $request->jumlah_dibayar;

        // Tentukan status secara otomatis berdasarkan nominal
        if ($uangMasuk >= $totalTagihan) {
            $statusBaru = 'Lunas';
            $tglLunas = $request->tanggal_pelunasan ?? now();
        } elseif ($uangMasuk > 0) {
            $statusBaru = 'Cicil';
            $tglLunas = null;
        } else {
            $statusBaru = 'Belum Lunas';
            $tglLunas = null;
        }

        // 3. Update ke Database
        $order->update([
            'jumlah_dibayar'    => $uangMasuk,
            'status_pembayaran' => $statusBaru,
            'metode_pembayaran' => $request->metode_pembayaran,
            'tanggal_pelunasan' => $tglLunas,
            'catatan_pembayaran' => $request->catatan_pembayaran,
        ]);

        return redirect()->route('admin.payment_index')
            ->with('success', 'Pembayaran ' . $order->nama_pelanggan . ' berhasil diupdate ke status: ' . $statusBaru);
    }

    // Proses membatalkan order
    public function cancel(Request $request, $id) {
        $order = Order::findOrFail($id);
        $order->update([
            'status_order' => 'Batal',
            'alasan_cancel' => $request->alasan_cancel
        ]);

        return back()->with('success', 'Order berhasil dibatalkan dan dipindahkan ke daftar evaluasi.');
    }

    // Menampilkan daftar yang batal
    public function indexCancel() {
        $orders = Order::where('status_order', 'Batal')->orderBy('updated_at', 'desc')->get();
        return view('admin.rekap_cancel', compact('orders'));
    }

    public function restore($id) {
        Order::findOrFail($id)->update([
            'status_order' => 'Aktif',
            'alasan_cancel' => null
        ]);
        return redirect()->route('admin.rekap_order')->with('success', 'Order telah dikembalikan ke daftar aktif.');
    }

    public function laporan(Request $request)
    {
        // 1. QUERY PENDAPATAN (ORDER) - Tetap sama
        $query = Order::where('status_order', 'Aktif')
                    ->where('jumlah_pendapatan', '>', 0);

        // Filter-filter (Nama, Sopir, Plat, Layanan, Tanggal)
        if ($request->filled('nama')) {
            $query->where('nama_pelanggan', 'like', '%' . $request->nama . '%');
        }
        if ($request->filled('sopir')) {
            $query->where('nama_sopir', 'like', '%' . $request->sopir . '%');
        }
        if ($request->filled('plat')) {
            $query->where('plat_armada', 'like', '%' . $request->plat . '%');
        }
        if ($request->filled('layanan')) {
            $query->where('jenis_layanan', $request->layanan);
        }
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('penjadwalan', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        // --- LOGIKA PENTING: HITUNG TOTAL SEBELUM PAGINATION ---
        // Kita clone query-nya agar perhitungan sum() tidak mengganggu pagination
        $totalNominal     = $query->sum('jumlah_pendapatan'); 
        $totalLabaKotor   = $query->sum('jumlah_keuntungan'); 
        $totalLayanan     = $query->count(); 

        // --- SEKARANG BARU EKSEKUSI PAGINATION UNTUK TABEL ---
        // Gunakan paginate(25) agar cukup panjang namun tetap rapi
        $orders = $query->orderBy('penjadwalan', 'desc')->paginate(25);

        // 3. QUERY PENGELUARAN (Tetap sama)
        $queryPengeluaran = \App\Models\Pengeluaran::query(); 
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $queryPengeluaran->whereBetween('tanggal', [$request->tgl_mulai, $request->tgl_selesai]);
        }
        $totalPengeluaran = $queryPengeluaran->sum('nominal');

        // 4. LOGIKA LABA BERSIH
        $labaBersih = $totalLabaKotor - $totalPengeluaran;

        // 5. DATA UNTUK DROPDOWN
        $daftarLayanan = Order::where('jenis_layanan', '!=', '-')
                            ->distinct()
                            ->pluck('jenis_layanan');

        return view('admin.laporan', compact(
            'orders', 
            'totalNominal', 
            'totalLabaKotor', 
            'totalLayanan', 
            'totalPengeluaran', 
            'labaBersih', 
            'daftarLayanan'
        ));
    }
}