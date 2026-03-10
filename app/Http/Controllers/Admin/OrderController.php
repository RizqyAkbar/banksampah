<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

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
            'jenis_layanan'  => 'required',
            'armada'         => 'required',
        ]);

        $layananFinal = $request->jenis_layanan === 'Lainnya' ? $request->jenis_layanan_custom : $request->jenis_layanan;

        // LOGIKA CEK PELANGGAN (Jika no WA sudah ada di DB = Lama, jika belum = Baru)
        $isPelangganLama = Order::where('no_wa', $request->no_wa)->exists();
        $statusPelanggan = $isPelangganLama ? 'Pelanggan Lama' : 'Konsumen Baru';

        Order::create([
            'nama_pelanggan'   => $request->nama_pelanggan,
            'no_wa'            => $request->no_wa,
            'alamat'           => $request->alamat,
            'penjadwalan'      => $request->penjadwalan,
            'jenis_layanan'    => $layananFinal,
            'armada'           => $request->armada,
            'catatan'          => $request->catatan,
            'status_pelanggan' => $statusPelanggan,
            // Nilai Rit, Harga, HPP dibiarkan default 0 (nanti diisi di menu Rekap)
        ]);

        return redirect()->back()->with('success', 'Order baru berhasil ditambahkan!');
    }

    // 3. Halaman REKAP ORDER (List Tabel)
    public function index()
    {
        // Mengambil semua order, diurutkan dari yang terbaru
        $orders = Order::orderBy('created_at', 'desc')->get();
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

        $request->validate([
            'jumlah_rit'    => 'required|numeric',
            'harga_per_rit' => 'required|numeric',
            'hpp_per_rit'   => 'required|numeric',
        ]);

        // Hitung Pendapatan & Keuntungan di Backend (untuk keamanan ganda)
        $pendapatan = $request->jumlah_rit * $request->harga_per_rit;
        $total_hpp  = $request->jumlah_rit * $request->hpp_per_rit;
        $keuntungan = $pendapatan - $total_hpp;

        // Update data order
        $order->update([
            'jenis_layanan'     => $request->jenis_layanan, // Fitur bisa ganti/tambah layanan
            'armada'            => $request->armada,
            'jumlah_rit'        => $request->jumlah_rit,
            'harga_per_rit'     => $request->harga_per_rit,
            'hpp_per_rit'       => $request->hpp_per_rit,
            'jumlah_pendapatan' => $pendapatan,
            'jumlah_keuntungan' => $keuntungan,
            'catatan'           => $request->catatan,
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
        // Hanya mengambil order yang sudah diisi harganya (jumlah_pendapatan > 0)
        $orders = Order::where('jumlah_pendapatan', '>', 0)->orderBy('updated_at', 'desc')->get();
        return view('admin.invoice_index', compact('orders'));
    }

    // Ambil data detail untuk modal (JSON)
    public function getDetail($id)
    {
        $order = Order::findOrFail($id);
        return response()->json([
            'no_invoice' => 'INV-' . date('Ymd') . '-' . str_pad($order->id, 3, '0', STR_PAD_LEFT),
            'tanggal' => date('d F Y'),
            'data' => $order
        ]);
    }

    public function printInvoice($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.invoice_print', compact('order'));
    }
}