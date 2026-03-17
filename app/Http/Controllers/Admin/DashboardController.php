<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->format('Y-m-d');
        $startOfMonth = $now->copy()->startOfMonth();
        
        // 1. Hitung Pendapatan & Keuntungan (Hanya dari item yang Aktif)
        $stats = Order::where('created_at', '>=', $startOfMonth)
            ->where('status_order', 'Aktif')
            ->selectRaw('SUM(total_pendapatan) as total_pendapatan, SUM(total_keuntungan) as total_keuntungan')
            ->first();

        // 2. Statistik Layanan (Hanya yang status itemnya Aktif)
        $statistikLayanan = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status_order', 'Aktif')
            ->where('order_items.status_item', 'Aktif') 
            ->where('order_items.jenis_layanan', '!=', '-')
            ->selectRaw('order_items.jenis_layanan, count(*) as total')
            ->groupBy('order_items.jenis_layanan')
            ->orderBy('total', 'desc')
            ->get();

        // 3. Statistik Armada (Hanya yang status itemnya Aktif)
        $statistikArmada = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status_order', 'Aktif')
            ->where('order_items.status_item', 'Aktif') 
            ->where('order_items.armada', '!=', '-')
            ->selectRaw('order_items.armada, count(*) as total')
            ->groupBy('order_items.armada')
            ->orderBy('total', 'desc')
            ->get();

        // 4. Statistik Ringkasan
        $pelangganBaru = Order::where('created_at', '>=', $startOfMonth)
            ->where('status_pelanggan', 'Konsumen Baru')
            ->count();
            
        $totalPelanggan = Order::distinct('no_wa')->count();
        
        // REVISI LOGIKA NOTIF SIDEBAR & STATS BELUM LENGKAP:
        // Hanya hitung nota yang PUNYA item Aktif DAN total_pendapatannya masih 0
        $belumBayar = Order::where('status_order', 'Aktif')
            ->where('total_pendapatan', 0)
            ->whereHas('items', function($query) {
                $query->where('status_item', 'Aktif');
            })
            ->count();

        // 5. Jadwal Hari Ini (Hanya tampilkan jika ada layanan yang Aktif)
        $jadwalHariIni = Order::with(['items' => function($query) {
                $query->where('status_item', 'Aktif');
            }])
            ->whereDate('penjadwalan', $today)
            ->where('status_order', 'Aktif')
            ->whereHas('items', function($query) {
                $query->where('status_item', 'Aktif');
            })
            ->get();

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