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
        $startOfMonth = Carbon::now()->startOfMonth();
        
        // 1. Hitung Pendapatan & Keuntungan Bulan Ini
        $stats = Order::where('created_at', '>=', $startOfMonth)
            ->selectRaw('SUM(jumlah_pendapatan) as total_pendapatan, SUM(jumlah_keuntungan) as total_keuntungan')
            ->first();

        // 2. Statistik Layanan (Global) - Menghitung berapa kali tiap jasa digunakan
        $statistikLayanan = Order::where('status_order', 'Aktif')
            ->where('jenis_layanan', '!=', '-')
            ->selectRaw('jenis_layanan, count(*) as total')
            ->groupBy('jenis_layanan')
            ->orderBy('total', 'desc')
            ->get();

        // 3. Statistik Armada (Global) - Menghitung frekuensi penggunaan kendaraan
        $statistikArmada = Order::where('status_order', 'Aktif')
            ->where('armada', '!=', '-')
            ->selectRaw('armada, count(*) as total')
            ->groupBy('armada')
            ->orderBy('total', 'desc')
            ->get();

        // Data pendukung lainnya tetap sama
        $pelangganBaru = Order::where('created_at', '>=', $startOfMonth)->where('status_pelanggan', 'Konsumen Baru')->count();
        $totalPelanggan = Order::distinct('no_wa')->count();
        $belumBayar = Order::where('jumlah_pendapatan', 0)->where('status_order', 'Aktif')->count();
        $jadwalHariIni = Order::whereDate('penjadwalan', Carbon::today())->where('status_order', 'Aktif')->get();

        return view('admin.dashboard', compact(
            'stats', 
            'pelangganBaru', 
            'totalPelanggan', 
            'belumBayar', 
            'jadwalHariIni',
            'statistikLayanan',
            'statistikArmada'
        ));
    }
}