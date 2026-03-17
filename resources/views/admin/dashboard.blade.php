@extends('admin.layout.app')

@section('title', 'Admin Dashboard')
@section('header_title', 'Dashboard Overview')

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Dashboard</h1>

    {{-- TOP STATS (5 KOTAK) --}}
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
    </div> 
    
    {{-- POPULASI LAYANAN --}}
    <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] mb-6">
        <div class="flex items-center justify-between mb-6 border-b border-slate-50 pb-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                    <i class="fa-solid fa-chart-pie text-lg"></i> 
                </div>
                <h3 class="text-lg font-bold text-slate-900 font-heading">Populasi Layanan</h3>
            </div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-2 py-1 bg-slate-50 rounded-md border border-slate-100">Global</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-8">
            @forelse($statistikLayanan as $layanan)
            <div class="group">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-sm font-bold text-slate-700 group-hover:text-orange-600 transition-colors truncate max-w-[150px]" title="{{ $layanan->jenis_layanan }}">
                        {{ $layanan->jenis_layanan }}
                    </span>
                    <div class="text-right shrink-0">
                        <span class="text-lg font-black text-slate-900">{{ $layanan->total }}</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase ml-1">Order</span>
                    </div>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden shadow-inner">
                    @php 
                        $maxLayanan = $statistikLayanan->max('total') ?: 1;
                        $persenLayanan = ($layanan->total / $maxLayanan) * 100;
                    @endphp
                    <div class="bg-gradient-to-r from-orange-400 to-orange-500 h-full rounded-full transition-all duration-1000 group-hover:brightness-110" style="width: {{ $persenLayanan }}%"></div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-6">
                <p class="text-xs text-slate-400 italic">Belum ada data layanan yang tercatat.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- DISTRIBUSI ARMADA --}}
    <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] mb-8">
        <div class="flex items-center justify-between mb-6 border-b border-slate-50 pb-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-truck-moving text-lg"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 font-heading">Distribusi Armada</h3>
            </div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-2 py-1 bg-slate-50 rounded-md border border-slate-100">Global</span>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse($statistikArmada as $armada)
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-blue-300 hover:bg-white hover:shadow-md transition-all group">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-blue-500 transition-colors">{{ $armada->armada }}</p>
                <div class="flex items-baseline gap-1 mt-2">
                    <span class="text-3xl font-black text-slate-800 tracking-tight">{{ $armada->total }}</span>
                    <span class="text-xs font-bold text-slate-400 uppercase">Kali</span>
                </div>
                <div class="mt-3 w-8 h-1 bg-blue-500 rounded-full opacity-20 group-hover:w-full group-hover:opacity-100 transition-all duration-500"></div>
            </div>
            @empty
            <div class="col-span-full text-center py-6">
                <p class="text-xs text-slate-400 italic">Belum ada data armada yang tercatat.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- TABEL JADWAL HARI INI --}}
    <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
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
                <tbody id="jadwal-tbody" class="divide-y divide-slate-100">
                    @include('admin.partials.dashboard_table', ['jadwalHariIni' => $jadwalHariIni])
                </tbody>
            </table>
        </div>
    </div>
@endsection