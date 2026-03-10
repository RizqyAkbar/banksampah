<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung Pendapatan & Keuntungan Bulan Ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $stats = Order::where('created_at', '>=', $startOfMonth)
            ->selectRaw('SUM(jumlah_pendapatan) as total_pendapatan, SUM(jumlah_keuntungan) as total_keuntungan')
            ->first();

        // 2. Total Pelanggan Baru Bulan Ini
        $pelangganBaru = Order::where('created_at', '>=', $startOfMonth)
            ->where('status_pelanggan', 'Konsumen Baru')
            ->count();

        // 3. Total Pelanggan Keseluruhan (Berdasarkan Nomor WA yang unik)
        $totalPelanggan = Order::distinct('no_wa')->count();

        // 4. Total Pelanggan Belum Bayar (Order yang pendapatan/rit sudah diisi tapi profit masih nol/tertentu)
        // Sebagai contoh, kita asumsikan 'Belum Bayar' adalah order yang jumlah_pendapatannya masih 0
        $belumBayar = Order::where('jumlah_pendapatan', 0)->count();

        // 5. Ambil Jadwal Hari Ini (Realtime)
        $jadwalHariIni = Order::whereDate('penjadwalan', Carbon::today())
            ->orderBy('penjadwalan', 'asc')
            ->get();

        return view('admin.dashboard', compact('stats', 'pelangganBaru', 'totalPelanggan', 'belumBayar', 'jadwalHariIni'));
    }
}