@extends('admin.layout.app')

@section('title', 'Laporan Pendapatan')
@section('header_title', 'Laporan Performa Jasa')

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-4 sm:mb-6 sm:hidden">Laporan Performa Jasa</h1>
    
    {{-- FORM FILTER PENCARIAN --}}
    <div class="rounded-2xl border border-slate-100 bg-white p-4 sm:p-6 shadow-sm mb-6 sm:mb-8">
        <form action="{{ route('admin.laporan') }}" method="GET" class="space-y-3 sm:space-y-4">
            {{-- Grid Utama --}}
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 items-end">
                
                {{-- 1. Nama Pelanggan --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 sm:mb-2">Nama Pelanggan</label>
                    <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari pelanggan..." 
                        class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
                </div>

                {{-- 2. Nama Sopir --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 sm:mb-2">Nama Sopir</label>
                    <input type="text" name="sopir" value="{{ request('sopir') }}" placeholder="Cari sopir..." 
                        class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
                </div>

                {{-- 3. Nomor Plat --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 sm:mb-2">Nomor Plat</label>
                    <input type="text" name="plat" value="{{ request('plat') }}" placeholder="Cari plat..." 
                        class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
                </div>

                {{-- 4. Jenis Jasa --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 sm:mb-2">Jenis Jasa</label>
                    <select name="layanan" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
                        <option value="">Semua Layanan</option>
                        @foreach($daftarLayanan as $l)
                            <option value="{{ $l }}" {{ request('layanan') == $l ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 5. Periode Mulai --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 sm:mb-2">Periode Mulai</label>
                    <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" 
                        class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
                </div>

                {{-- 6. Sampai Tanggal --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 sm:mb-2">Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" 
                        class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
                </div>
            </div>

            {{-- Baris Tombol Aksi --}}
            <div class="flex flex-col sm:flex-row justify-end gap-2 pt-2">
                <a href="{{ route('admin.laporan') }}" class="h-11 px-6 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider w-full sm:w-auto">
                    <i class="fa-solid fa-arrows-rotate"></i> Reset
                </a>
                <button type="submit" class="h-11 px-8 bg-slate-900 text-white rounded-xl hover:bg-slate-800 transition shadow-lg flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider w-full sm:w-auto">
                    <i class="fa-solid fa-magnifying-glass"></i> Filter Laporan
                </button>
            </div>
        </form>
    </div>

    {{-- RINGKASAN OUTPUT --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        {{-- Kotak Total Nominal --}}
        <div class="bg-emerald-600 rounded-3xl sm:rounded-[2rem] p-5 sm:p-6 text-white shadow-lg shadow-emerald-100 relative overflow-hidden group">
            <i class="fa-solid fa-sack-dollar absolute right-6 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl opacity-20 group-hover:scale-110 transition-transform duration-500"></i>
            <p class="text-[11px] sm:text-sm font-medium text-emerald-100 mb-1 uppercase tracking-wider">Total Nominal (Periode Ini)</p>
            <h2 class="text-2xl sm:text-3xl font-black">Rp {{ number_format($totalNominal, 0, ',', '.') }}</h2>
        </div>

        {{-- Kotak Jumlah Layanan --}}
        <div class="bg-blue-600 rounded-3xl sm:rounded-[2rem] p-5 sm:p-6 text-white shadow-lg shadow-blue-100 relative overflow-hidden group">
            <i class="fa-solid fa-truck-fast absolute right-6 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl opacity-20 group-hover:scale-110 transition-transform duration-500"></i>
            <p class="text-[11px] sm:text-sm font-medium text-blue-100 mb-1 uppercase tracking-wider">Jumlah Layanan Jasa</p>
            <h2 class="text-2xl sm:text-3xl font-black">{{ $totalLayanan }} <span class="text-sm sm:text-lg font-normal opacity-70">Kali</span></h2>
        </div>
    </div>

    {{-- RINGKASAN OUTPUT - LABA BERSIH --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        {{-- 1. Laba Kotor --}}
        <div class="bg-emerald-600 rounded-3xl sm:rounded-[2rem] p-5 sm:p-6 text-white shadow-lg relative overflow-hidden group">
            <i class="fa-solid fa-chart-line absolute right-6 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl opacity-20 group-hover:scale-110 transition-transform"></i>
            <p class="text-[10px] sm:text-xs font-medium text-emerald-100 mb-1 uppercase tracking-wider">Total Laba Kotor</p>
            <h2 class="text-xl sm:text-2xl font-black">Rp {{ number_format($totalLabaKotor, 0, ',', '.') }}</h2>
        </div>

        {{-- 2. Total Pengeluaran --}}
        <div class="bg-rose-600 rounded-3xl sm:rounded-[2rem] p-5 sm:p-6 text-white shadow-lg relative overflow-hidden group">
            <i class="fa-solid fa-money-bill-transfer absolute right-6 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl opacity-20 group-hover:scale-110 transition-transform"></i>
            <p class="text-[10px] sm:text-xs font-medium text-rose-100 mb-1 uppercase tracking-wider">Total Pengeluaran</p>
            <h2 class="text-xl sm:text-2xl font-black">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h2>
        </div>

        {{-- 3. LABA BERSIH --}}
        <div class="bg-indigo-600 rounded-3xl sm:rounded-[2rem] p-5 sm:p-6 text-white shadow-lg shadow-indigo-100 relative overflow-hidden group">
            <i class="fa-solid fa-trophy absolute right-6 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl opacity-20 group-hover:scale-110 transition-transform duration-500"></i>
            <p class="text-[10px] sm:text-xs font-medium text-indigo-100 mb-1 uppercase tracking-wider">Laba Bersih (Murni)</p>
            <h2 class="text-2xl sm:text-3xl font-black">Rp {{ number_format($labaBersih, 0, ',', '.') }}</h2>
        </div>
    </div>

    {{-- TABEL DATA --}}
    <div class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b">
                    <tr>
                        {{-- REVISI: Tambahkan whitespace-nowrap & min-w agar tabel tidak tergencet di mobile --}}
                        <th class="px-6 py-4 font-bold text-center whitespace-nowrap w-16">No</th>
                        <th class="px-6 py-4 whitespace-nowrap min-w-[130px]">Tanggal Jual</th>
                        <th class="px-6 py-4 whitespace-nowrap min-w-[200px]">Nama Customer</th>
                        <th class="px-6 py-4 whitespace-nowrap min-w-[150px]">Jenis Layanan</th>
                        <th class="px-6 py-4 whitespace-nowrap min-w-[150px]">Sopir & Plat</th>
                        <th class="px-6 py-4 text-right whitespace-nowrap min-w-[130px]">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-center font-bold text-slate-400">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item->penjadwalan)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800">{{ $item->nama_pelanggan }}</p>
                            <p class="text-[10px] text-slate-400">{{ $item->no_wa }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-[10px] font-bold text-slate-600 uppercase tracking-tight whitespace-nowrap">
                                {{ $item->jenis_layanan }}
                            </span>
                            <p class="text-[9px] text-slate-400 mt-1">{{ $item->jumlah_rit }} Rit | {{ $item->armada }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-bold text-slate-700 whitespace-nowrap">{{ $item->nama_sopir ?? '-' }}</p>
                            <p class="text-[10px] text-blue-600 font-medium uppercase">{{ $item->plat_armada ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-slate-900 whitespace-nowrap">
                            Rp {{ number_format($item->subtotal_pendapatan, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">Data tidak ditemukan untuk filter ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-slate-100 bg-slate-50/30">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
@endsection