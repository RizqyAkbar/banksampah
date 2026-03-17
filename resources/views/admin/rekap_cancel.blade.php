@extends('admin.layout.app')

@section('title', 'Monitoring Cancel')
@section('header_title', 'Evaluasi Pembatalan')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-heading text-2xl font-bold text-slate-900">Monitoring Cancel</h1>
        <div class="px-4 py-2 bg-orange-50 border border-orange-100 rounded-xl">
            {{-- Menggunakan count() dari koleksi item yang batal --}}
            <p class="text-xs font-bold text-orange-600 uppercase tracking-wider">Total Layanan Batal: {{ $orders->count() }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-900">Daftar Layanan Dibatalkan</h2>
            <p class="text-xs text-slate-500 mt-1">Gunakan data ini untuk mengevaluasi penyebab hilangnya potensi pendapatan.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                        <th class="px-6 py-4 font-semibold">Tgl Batal</th>
                        <th class="px-6 py-4 font-semibold">Pelanggan & Jasa</th>
                        <th class="px-6 py-4 font-semibold">Alasan Pembatalan</th>
                        <th class="px-6 py-4 font-semibold">Potensi Pendapatan</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $item)
                    <tr class="hover:bg-orange-50/30 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-400 text-center">{{ $loop->iteration }}</td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($item->updated_at)->format('d M Y') }}</p>
                            <p class="text-[10px] text-slate-400 italic">Jam: {{ $item->updated_at->format('H:i') }}</p>
                        </td>

                        <td class="px-6 py-4">
                            {{-- Karena $item sekarang adalah OrderItem, kita panggil relasi order --}}
                            <p class="font-bold text-slate-900">{{ $item->order->nama_pelanggan ?? 'N/A' }}</p>
                            <p class="text-xs text-blue-600 font-medium">{{ $item->jenis_layanan }} ({{ $item->armada }})</p>
                        </td>

                        <td class="px-6 py-4">
                            <div class="bg-orange-50 border border-orange-100 rounded-xl p-3">
                                <p class="text-xs text-orange-800 font-medium leading-relaxed">
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
                                <p class="text-slate-400 italic">Luar biasa! Belum ada layanan yang dibatalkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection