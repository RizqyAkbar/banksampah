@extends('admin.layout.app')

@section('title', 'Payment')
@section('header_title', 'Manajemen Pembayaran')

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Pembayaran</h1>

    {{-- FITUR BARU: FORM FILTER & EXPORT --}}
    <div class="rounded-2xl border border-slate-100 bg-white p-4 sm:p-6 shadow-sm mb-6">
        <form action="{{ route('admin.payment_index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:flex-1">
                <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase mb-1.5">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelanggan, invoice, atau metode bayar..." class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
            </div>
            
            <div class="w-full md:w-auto">
                <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase mb-1.5">Mulai Tanggal</label>
                {{-- Kita filter berdasarkan penjadwalan order --}}
                <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
            </div>
            
            <div class="w-full md:w-auto">
                <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase mb-1.5">Sampai Tanggal</label>
                <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
            </div>
            
            {{-- Pertahankan parameter filter status jika ada (Lunas/Belum Lunas) --}}
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif

            <div class="w-full md:w-auto flex flex-col sm:flex-row gap-2">
                <a href="{{ route('admin.payment_index') }}" class="h-11 px-5 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition shadow-sm flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider w-full sm:w-auto">
                    <i class="fa-solid fa-arrows-rotate"></i> Reset
                </a>
                <button type="submit" class="h-11 px-5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 transition shadow-lg flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider w-full sm:w-auto">
                    <i class="fa-solid fa-magnifying-glass"></i> Filter
                </button>
                <button type="submit" name="export" value="excel" class="h-11 px-5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition shadow-lg flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider w-full sm:w-auto">
                    <i class="fa-solid fa-file-excel"></i> Export
                </button>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden" x-data="{ isModalOpen: false, selectedOrder: { total_pendapatan: 0, jumlah_dibayar: 0, nama_pelanggan: '', catatan_pembayaran: '', metode_pembayaran: '' } }">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Riwayat & Status Bayar</h2>
                <p class="text-xs text-slate-500 mt-1">Pantau pelunasan tagihan pelanggan secara realtime.</p>
            </div>

            {{-- TOMBOL FILTER KHUSUS STATUS --}}
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                <a href="{{ route('admin.payment_index') }}" class="px-4 py-2 text-xs font-bold rounded-xl border {{ !request('filter') ? 'bg-slate-900 text-white' : 'bg-white text-slate-600' }}">Semua</a>
                <a href="{{ route('admin.payment_index', ['filter' => 'lunas']) }}" class="px-4 py-2 text-xs font-bold rounded-xl border {{ request('filter') == 'lunas' ? 'bg-emerald-600 text-white' : 'bg-white text-emerald-600 border-emerald-100' }}">Lunas</a>
                <a href="{{ route('admin.payment_index', ['filter' => 'belum_lunas']) }}" class="px-4 py-2 text-xs font-bold rounded-xl border {{ request('filter') == 'belum_lunas' ? 'bg-rose-600 text-white' : 'bg-white text-rose-600 border-rose-100' }}">Belum Lunas</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-center w-16 whitespace-nowrap">No</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Pelanggan</th>
                        <th class="px-6 py-4 font-semibold text-center whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Total Tagihan</th>
                        <th class="px-6 py-4 font-semibold text-emerald-600 whitespace-nowrap">Sudah Bayar</th>
                        <th class="px-6 py-4 font-semibold text-rose-600 whitespace-nowrap">Sisa Hutang</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Metode & Tgl Pelunasan</th>
                        <th class="px-6 py-4 font-semibold text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-center font-bold text-slate-400">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="font-bold text-slate-900">{{ $item->nama_pelanggan }}</p>
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            @php
                                $color = 'bg-rose-50 text-rose-600 border-rose-100';
                                if($item->status_pembayaran == 'Lunas') $color = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                if($item->status_pembayaran == 'Cicil') $color = 'bg-amber-50 text-amber-600 border-amber-100';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $color }} border">
                                <i class="fa-solid {{ $item->status_pembayaran == 'Lunas' ? 'fa-check-double' : 'fa-clock' }} mr-1.5"></i>
                                {{ $item->status_pembayaran }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900 whitespace-nowrap">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-600 whitespace-nowrap">Rp {{ number_format($item->jumlah_dibayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-rose-600 whitespace-nowrap">Rp {{ number_format($item->total_pendapatan - $item->jumlah_dibayar, 0, ',', '.') }}</td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                    <i class="fa-solid fa-credit-card text-slate-400"></i>
                                    {{ $item->metode_pembayaran ?? 'N/A' }}
                                </span>
                                
                                @if($item->catatan_pembayaran)
                                    <div class="group relative w-fit">
                                        <span class="text-[10px] text-blue-600 font-bold bg-blue-50 px-2 py-0.5 rounded border border-blue-100 flex items-center gap-1 cursor-help" title="{{ $item->catatan_pembayaran }}">
                                            <i class="fa-solid fa-file-invoice text-[9px]"></i>
                                            {{ Str::limit($item->catatan_pembayaran, 15) }}
                                        </span>
                                    </div>
                                @endif

                                @if($item->tanggal_pelunasan)
                                    <span class="text-[10px] text-slate-400 flex items-center gap-1.5 mt-1">
                                        <i class="fa-solid fa-calendar-check text-slate-300"></i>
                                        {{ \Carbon\Carbon::parse($item->tanggal_pelunasan)->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-[10px] text-rose-400 italic flex items-center gap-1.5 mt-1">
                                        <i class="fa-solid fa-hourglass-start text-[9px]"></i>
                                        Belum Lunas
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <button @click="isModalOpen = true; selectedOrder = {{ json_encode($item) }}" class="inline-flex items-center justify-center rounded-lg bg-green-50 text-green-700 px-3 py-1.5 text-xs font-bold hover:bg-green-600 hover:text-white transition-colors shadow-sm whitespace-nowrap">
                                <i class="fa-solid fa-wallet mr-2"></i> Update Bayar
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-6 py-12 text-center text-slate-400 italic">Belum ada data pembayaran untuk filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-100 bg-slate-50/30">
            {{ $orders->appends(request()->query())->links() }}
        </div>

        {{-- MODAL UPDATE PEMBAYARAN REVISI RESPONSIVE (TANPA SCROLL) --}}
        <div x-show="isModalOpen" 
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" 
            x-cloak 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
        
            <div @click.away="isModalOpen = false" class="bg-white rounded-[2rem] shadow-2xl max-w-md w-full p-5 sm:p-8 relative overflow-x-hidden overflow-y-auto max-h-[100dvh] sm:max-h-[90vh] text-left">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-green-50 rounded-full opacity-50 pointer-events-none"></div>

                <div class="relative">
                    <div class="flex justify-between items-start mb-4 sm:mb-6">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-1 font-heading">Update Status</h3>
                            <p class="text-xs sm:text-sm text-slate-500" x-text="'Pelanggan: ' + selectedOrder.nama_pelanggan"></p>
                        </div>
                        <button @click="isModalOpen = false" class="text-slate-400 hover:text-rose-500 transition-colors p-1">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <form :action="'/admin/payment/' + selectedOrder.id" method="POST" class="space-y-3 sm:space-y-5">
                        @csrf @method('PUT')

                        {{-- Info Tagihan (Read Only) --}}
                        <div class="p-3 sm:p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-0.5 sm:mb-1">Total Tagihan (Rekap)</p>
                            <p class="text-base sm:text-lg font-bold text-slate-900" x-text="'Rp ' + Number(selectedOrder.total_pendapatan).toLocaleString('id-ID')"></p>
                        </div>

                        {{-- Input Jumlah Bayar --}}
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 sm:mb-1.5">Jumlah Yang Dibayar (Rp)</label>
                            <input type="number" 
                                name="jumlah_dibayar" 
                                x-model="selectedOrder.jumlah_dibayar" 
                                class="w-full bg-slate-50 border border-emerald-200 rounded-xl p-2.5 sm:p-3.5 text-base sm:text-lg font-bold text-emerald-700 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all outline-none"
                                placeholder="Contoh: 500000">
                            <p class="text-[9px] text-slate-400 mt-1 sm:mt-1.5 italic leading-tight">*Penuh = Lunas. Kurang = Cicil.</p>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 sm:mb-1.5">Metode Pembayaran</label>
                            <select name="metode_pembayaran" x-model="selectedOrder.metode_pembayaran" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 sm:p-3.5 text-sm font-semibold focus:ring-2 focus:ring-green-500 transition-all outline-none">
                                <option value="">Pilih Metode...</option>
                                <option value="BRI">Transfer BRI</option>
                                <option value="BCA">Transfer BCA</option>
                                <option value="Mandiri">Transfer Mandiri</option>
                                <option value="Cash Sopir">Tunai (Sopir)</option>
                                <option value="Cash Owner">Tunai (Owner)</option>
                            </select>
                        </div>

                        {{-- Catatan / Ref --}}
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 sm:mb-1.5">No. Invoice / Ref Transfer</label>
                            <textarea name="catatan_pembayaran" 
                                    x-model="selectedOrder.catatan_pembayaran" 
                                    rows="2" 
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 sm:p-3.5 text-sm font-semibold focus:ring-2 focus:ring-green-500 transition-all outline-none resize-none"
                                    placeholder="Contoh: INV-2024001..."></textarea>
                        </div>

                        {{-- Sisa Hutang Realtime --}}
                        <div class="flex justify-between items-center px-1 py-1.5 sm:py-2 border-t border-slate-100">
                            <span class="text-[10px] sm:text-xs font-bold text-slate-400">SISA HUTANG:</span>
                            <span class="text-sm sm:text-base font-bold text-rose-500" x-text="'Rp ' + Number(selectedOrder.total_pendapatan - selectedOrder.jumlah_dibayar).toLocaleString('id-ID')"></span>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex gap-2 sm:gap-3 pt-1 sm:pt-2">
                            <button type="button" @click="isModalOpen = false" class="flex-1 px-4 py-2.5 sm:py-3 rounded-xl text-xs sm:text-sm font-bold text-slate-500 hover:bg-slate-100 border border-slate-200 transition-colors">Batal</button>
                            <button type="submit" class="flex-[1.5] px-4 py-2.5 sm:py-3 bg-emerald-600 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md hover:bg-emerald-700 transition-all">
                                Simpan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                position: 'top-end',
                toast: true,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-2xl shadow-lg border border-green-100',
                    title: 'font-heading font-bold text-slate-900',
                }
            });
        @endif
    </script>
@endsection