@extends('admin.layout.app')

@section('title', 'Database Pelanggan')
@section('header_title', 'Database Pelanggan')

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Database Pelanggan</h1>

    {{-- FORM FILTER & PENCARIAN --}}
    <div class="rounded-2xl border border-slate-100 bg-white p-4 sm:p-6 shadow-sm mb-6">
        <form action="{{ route('admin.customer_index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:flex-1">
                <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase mb-1.5">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pelanggan, nomor WA, atau alamat..." class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
            </div>
            
            <div class="w-full md:w-auto">
                <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase mb-1.5">Filter Status</label>
                <select name="status" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
                    <option value="">Semua Status</option>
                    <option value="Pelanggan" {{ request('status') == 'Pelanggan' ? 'selected' : '' }}>Pelanggan Lama (≥ 2 Order)</option>
                    <option value="Konsumen Baru" {{ request('status') == 'Konsumen Baru' ? 'selected' : '' }}>Konsumen Baru (1 Order)</option>
                </select>
            </div>
            
            <div class="w-full md:w-auto flex flex-col sm:flex-row gap-2">
                <a href="{{ route('admin.customer_index') }}" class="h-11 px-5 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition shadow-sm flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider w-full sm:w-auto">
                    <i class="fa-solid fa-arrows-rotate"></i> Reset
                </a>
                <button type="submit" class="h-11 px-5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 transition shadow-lg flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider w-full sm:w-auto">
                    <i class="fa-solid fa-magnifying-glass"></i> Filter
                </button>
            </div>
        </form>
    </div>

    {{-- TABEL DATABASE PELANGGAN --}}
    <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Direktori Pelanggan</h2>
                <p class="text-xs text-slate-500 mt-1">Data dikumpulkan secara otomatis dari sistem Input Order.</p>
            </div>
            <div class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg border border-blue-100 text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-users"></i> Total: {{ $customers->total() }} Data
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-center w-16 whitespace-nowrap">No</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Profil Pelanggan</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Status & Riwayat</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Alamat Lengkap</th>
                        <th class="px-6 py-4 font-semibold text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-center font-bold text-slate-400">{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="font-bold text-slate-900 text-base mb-1">{{ $item->nama_pelanggan }}</p>
                            <p class="text-xs font-semibold text-green-600 flex items-center gap-1.5">
                                <i class="fa-brands fa-whatsapp"></i> +{{ $item->no_wa }}
                            </p>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->status_pelanggan == 'Pelanggan')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100 mb-1.5">
                                    <i class="fa-solid fa-star mr-1"></i> Pelanggan Setia
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200 mb-1.5">
                                    <i class="fa-solid fa-user mr-1"></i> Konsumen Baru
                                </span>
                            @endif
                            <p class="text-[11px] font-bold text-slate-500">Riwayat: <span class="text-slate-800">{{ $item->jumlah_order }}x Order</span></p>
                        </td>

                        <td class="px-6 py-4 min-w-[250px] max-w-[350px]">
                            <p class="text-xs text-slate-600 leading-relaxed whitespace-normal break-words mb-1.5">{{ $item->alamat }}</p>
                            @if($item->link_maps)
                                <a href="{{ $item->link_maps }}" target="_blank" class="text-[10px] text-blue-500 hover:text-blue-700 font-semibold flex items-center gap-1">
                                    <i class="fa-solid fa-location-dot"></i> Buka Maps
                                </a>
                            @else
                                <span class="text-[10px] text-slate-400 italic">- Maps tidak tersedia -</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- TOMBOL RECALL / ORDER CEPAT --}}
                                <a href="{{ route('admin.input_order', ['recall_wa' => $item->no_wa]) }}" class="inline-flex items-center justify-center p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Order Cepat (Recall)">
                                    <i class="fa-solid fa-bolt text-sm"></i>
                                </a>

                                <a href="https://wa.me/{{ $item->no_wa }}" target="_blank" class="inline-flex items-center justify-center p-2 rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all shadow-sm" title="Chat WhatsApp">
                                    <i class="fa-brands fa-whatsapp text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada data pelanggan di database.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-100 bg-slate-50/30">
            {{ $customers->appends(request()->query())->links() }}
        </div>
    </div>
@endsection