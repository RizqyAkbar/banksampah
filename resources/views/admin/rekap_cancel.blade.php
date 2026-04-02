@extends('admin.layout.app')

@section('title', 'Monitoring Cancel')
@section('header_title', 'Evaluasi Pembatalan')

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-4 sm:mb-6 sm:hidden">Monitoring Cancel</h1>

    {{-- FITUR BARU: FORM FILTER & EXPORT --}}
    <div class="rounded-2xl border border-slate-100 bg-white p-4 sm:p-6 shadow-sm mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:flex-1">
                <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase mb-1.5">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelanggan, jasa, alasan..." class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
            </div>
            <div class="w-full md:w-auto">
                <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase mb-1.5">Mulai Tgl Batal</label>
                <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
            </div>
            <div class="w-full md:w-auto">
                <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase mb-1.5">Sampai Tgl Batal</label>
                <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
            </div>
            <div class="w-full md:w-auto flex flex-col sm:flex-row gap-2">
                {{-- Tombol Filter Normal --}}
                <button type="submit" formaction="{{ route('admin.rekap_cancel') }}" class="h-11 px-5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 transition shadow-lg flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider w-full sm:w-auto">
                    <i class="fa-solid fa-magnifying-glass"></i> Filter
                </button>
                {{-- Tombol Export Khusus --}}
                <button type="submit" formaction="{{ route('admin.rekap_cancel.export') }}" class="h-11 px-5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition shadow-lg flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider w-full sm:w-auto">
                    <i class="fa-solid fa-file-excel"></i> Export
                </button>
            </div>
        </form>
    </div>

    <div class="flex items-center justify-between mb-4 sm:mb-6">
        <h2 class="text-lg font-bold text-slate-900 hidden sm:block">Daftar Layanan Dibatalkan</h2>
        <div class="px-4 py-2 bg-orange-50 border border-orange-100 rounded-xl w-full sm:w-auto text-center">
            <p class="text-xs font-bold text-orange-600 uppercase tracking-wider">Total Layanan Batal: {{ $orders->total() }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 hidden sm:block">
            <p class="text-xs text-slate-500">Gunakan data ini untuk mengevaluasi penyebab hilangnya potensi pendapatan.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-center w-16 whitespace-nowrap">No</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[120px]">Tgl Batal</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[200px]">Pelanggan & Jasa</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[250px]">Alasan Pembatalan</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[150px]">Potensi Pendapatan</th>
                        <th class="px-6 py-4 font-semibold text-right whitespace-nowrap min-w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $item)
                    <tr class="hover:bg-orange-50/30 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-400 text-center">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($item->updated_at)->format('d M Y') }}</p>
                            <p class="text-[10px] text-slate-400 italic">Jam: {{ $item->updated_at->format('H:i') }}</p>
                        </td>

                        <td class="px-6 py-4 min-w-[200px]">
                            <p class="font-bold text-slate-900">{{ $item->order->nama_pelanggan ?? 'N/A' }}</p>
                            <p class="text-xs text-blue-600 font-medium">{{ $item->jenis_layanan }} ({{ $item->armada }})</p>
                        </td>

                        <td class="px-6 py-4 min-w-[250px]">
                            <div class="bg-orange-50 border border-orange-100 rounded-xl p-3">
                                <p class="text-xs text-orange-800 font-medium leading-relaxed line-clamp-2" title="{{ $item->alasan_cancel }}">
                                    <i class="fa-solid fa-quote-left mr-1 opacity-50"></i>
                                    {{ $item->alasan_cancel ?? 'Tidak ada alasan dicatat' }}
                                </p>
                            </div>
                        </td>

                        <td class="px-6 py-4 font-bold text-slate-400 whitespace-nowrap">
                            <span class="line-through decoration-rose-300">
                                Rp {{ number_format($item->subtotal_pendapatan, 0, ',', '.') }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.item.restore', $item->id) }}" method="POST" class="inline-block">
                                @csrf @method('PUT')
                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 px-3 py-1.5 text-xs font-bold hover:bg-green-600 hover:text-white transition-all shadow-sm" title="Pulihkan Layanan">
                                    <i class="fa-solid fa-rotate-left mr-1.5"></i> Pulihkan
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-face-smile text-4xl text-slate-200 mb-3"></i>
                                <p class="text-slate-400 italic">Luar biasa! Belum ada data layanan batal untuk filter ini.</p>
                            </div>
                        </td>
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