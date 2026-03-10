@extends('admin.layout.app')

@section('title', 'Admin Dashboard')
@section('header_title', 'Dashboard Overview')

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Dashboard</h1>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 mb-8">
        
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Pendapatan (Bulan Ini)</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-green-600">
                    <i class="fa-solid fa-sack-dollar text-lg"></i>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-xl font-bold text-slate-900">Rp {{ number_format($stats->total_pendapatan ?? 0, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-400 mt-1">Omzet kotor masuk</p>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Keuntungan (Bulan Ini)</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <i class="fa-solid fa-chart-line text-lg"></i>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-xl font-bold text-emerald-600">Rp {{ number_format($stats->total_keuntungan ?? 0, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-400 mt-1">Laba bersih (Murni)</p>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Pelanggan Baru</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-user-plus text-lg"></i>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-bold text-slate-900">{{ $pelangganBaru ?? 0 }} <span class="text-sm font-normal text-slate-500">Orang</span></h3>
                <p class="text-xs text-slate-400 mt-1">Bulan ini</p>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Pelanggan</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-bold text-slate-900">{{ $totalPelanggan ?? 0 }} <span class="text-sm font-normal text-slate-500">Orang</span></h3>
                <p class="text-xs text-slate-400 mt-1">Keseluruhan (Unik)</p>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Belum Lengkap</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 text-red-600">
                    <i class="fa-solid fa-file-circle-exclamation text-lg"></i>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-bold text-red-600">{{ $belumBayar ?? 0 }} <span class="text-sm font-normal text-red-400">Order</span></h3>
                <p class="text-xs text-red-400 mt-1">Butuh rekap data</p>
            </div>
        </div>

    </div> <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="flex items-center justify-between border-b border-slate-100 p-6">
            <div class="flex items-center gap-3">
                <div class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Jadwal Hari Ini</h2>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold bg-slate-50 text-slate-600 px-3 py-2 rounded-lg border hover:bg-slate-100 transition-colors">
                <i class="fa-solid fa-arrows-rotate mr-1"></i> Refresh
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-y border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Pelanggan</th>
                        <th class="px-6 py-4 font-semibold">Layanan / Armada</th>
                        <th class="px-6 py-4 font-semibold">Status Data</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($jadwalHariIni as $order)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">{{ $order->nama_pelanggan }}</p>
                            <p class="text-xs text-slate-400">{{ $order->no_wa }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-800">{{ $order->jenis_layanan }}</p>
                            <p class="text-xs text-slate-500">{{ $order->armada }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($order->jumlah_pendapatan > 0)
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-100">
                                    <i class="fa-solid fa-check mr-1.5"></i> Rekap Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-orange-50 px-2.5 py-1 text-xs font-semibold text-orange-700 border border-orange-100">
                                    <i class="fa-solid fa-clock mr-1.5"></i> Tunggu Rekap
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <a href="{{ route('admin.rekap_order.edit', $order->id) }}" class="text-slate-400 hover:text-green-600 transition-colors" title="Lengkapi Data">
                                <i class="fa-solid fa-pen-to-square text-lg"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                            Tidak ada jadwal pesanan untuk hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection