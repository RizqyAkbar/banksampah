@extends('admin.layout.app')

@section('title', 'Payment')
@section('header_title', 'Manajemen Pembayaran')

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Pembayaran</h1>

    <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden" x-data="{ isModalOpen: false, selectedOrder: { catatan_pembayaran: '' } }">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Riwayat & Status Bayar</h2>
                <p class="text-xs text-slate-500 mt-1">Pantau pelunasan tagihan pelanggan secara realtime.</p>
            </div>

            {{-- TOMBOL FILTER KHUSUS --}}
            <div class="flex gap-2">
                <a href="{{ route('admin.payment_index') }}" class="px-4 py-2 text-xs font-bold rounded-xl border {{ !request('filter') ? 'bg-slate-900 text-white' : 'bg-white text-slate-600' }}">Semua</a>
                <a href="{{ route('admin.payment_index', ['filter' => 'lunas']) }}" class="px-4 py-2 text-xs font-bold rounded-xl border {{ request('filter') == 'lunas' ? 'bg-emerald-600 text-white' : 'bg-white text-emerald-600 border-emerald-100' }}">Sudah Lunas</a>
                <a href="{{ route('admin.payment_index', ['filter' => 'belum_lunas']) }}" class="px-4 py-2 text-xs font-bold rounded-xl border {{ request('filter') == 'belum_lunas' ? 'bg-rose-600 text-white' : 'bg-white text-rose-600 border-rose-100' }}">Belum Lunas</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                        <th class="px-6 py-4 font-semibold">Pelanggan</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold">Total Tagihan</th>
                        <th class="px-6 py-4 font-semibold text-emerald-600">Sudah Bayar</th>
                        <th class="px-6 py-4 font-semibold text-rose-600">Sisa Hutang</th>
                        <th class="px-6 py-4 font-semibold">Metode & Tgl Pelunasan</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">{{ $item->nama_pelanggan }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{-- Badge Status --}}
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
                        <td class="px-6 py-4 font-bold text-slate-900">Rp {{ number_format($item->jumlah_pendapatan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-600">Rp {{ number_format($item->jumlah_dibayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-rose-600">Rp {{ number_format($item->jumlah_pendapatan - $item->jumlah_dibayar, 0, ',', '.') }}</td>
                        
                        {{-- ISI KOLOM BARU: METODE & TANGGAL --}}
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                {{-- Baris 1: Metode Pembayaran --}}
                                <span class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                    <i class="fa-solid fa-credit-card text-slate-400"></i>
                                    {{ $item->metode_pembayaran ?? 'N/A' }}
                                </span>
                                
                                {{-- Baris 2: Catatan/Referensi (Hanya muncul jika ada isinya) --}}
                                @if($item->catatan_pembayaran)
                                    <div class="group relative w-fit">
                                        <span class="text-[10px] text-blue-600 font-bold bg-blue-50 px-2 py-0.5 rounded border border-blue-100 flex items-center gap-1 cursor-help" title="{{ $item->catatan_pembayaran }}">
                                            <i class="fa-solid fa-file-invoice text-[9px]"></i>
                                            {{ Str::limit($item->catatan_pembayaran, 15) }} {{-- Membatasi teks agar tabel tidak melar --}}
                                        </span>
                                    </div>
                                @endif

                                {{-- Baris 3: Tanggal Pelunasan --}}
                                @if($item->tanggal_pelunasan)
                                    <span class="text-[10px] text-slate-400 flex items-center gap-1.5">
                                        <i class="fa-solid fa-calendar-check text-slate-300"></i>
                                        {{ \Carbon\Carbon::parse($item->tanggal_pelunasan)->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-[10px] text-rose-400 italic flex items-center gap-1.5">
                                        <i class="fa-solid fa-hourglass-start text-[9px]"></i>
                                        Belum Lunas
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <button @click="isModalOpen = true; selectedOrder = {{ json_encode($item) }}" class="inline-flex items-center justify-center rounded-lg bg-green-50 text-green-700 px-3 py-1.5 text-xs font-bold hover:bg-green-600 hover:text-white transition-colors">
                                <i class="fa-solid fa-wallet mr-2"></i> Update Bayar
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-6 py-12 text-center text-slate-400 italic">Belum ada data pembayaran yang masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div x-show="isModalOpen" 
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" 
            x-cloak 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">
        
            <div @click.away="isModalOpen = false" class="bg-white rounded-[2.5rem] shadow-2xl max-w-md w-full p-8 relative overflow-hidden text-left">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-green-50 rounded-full opacity-50"></div>

                <div class="relative">
                    <h3 class="text-2xl font-bold text-slate-900 mb-1 font-heading">Update Status</h3>
                    <p class="text-sm text-slate-500 mb-8" x-text="'Pelanggan: ' + selectedOrder.nama_pelanggan"></p>

                    <form :action="'/admin/payment/' + selectedOrder.id" method="POST" class="space-y-5">
                        @csrf @method('PUT')

                        {{-- Info Tagihan (Read Only) --}}
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Total Tagihan</p>
                            <p class="text-lg font-bold text-slate-900" x-text="'Rp ' + Number(selectedOrder.jumlah_pendapatan).toLocaleString('id-ID')"></p>
                        </div>

                        {{-- Input Jumlah Bayar --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Jumlah Yang Dibayar (Rp)</label>
                            <input type="number" 
                                name="jumlah_dibayar" 
                                x-model="selectedOrder.jumlah_dibayar" 
                                class="w-full bg-slate-50 border-2 border-emerald-100 rounded-2xl p-4 text-lg font-bold text-emerald-700 focus:ring-2 focus:ring-green-500 transition-all outline-none"
                                placeholder="Contoh: 500000">
                            <p class="text-[10px] text-slate-400 mt-2 ml-1 italic">*Jika diisi full sesuai tagihan, status otomatis <b>Lunas</b>. Jika kurang, status jadi <b>Cicil</b>.</p>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Metode Pembayaran</label>
                            <select name="metode_pembayaran" x-model="selectedOrder.metode_pembayaran" class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-semibold focus:ring-2 focus:ring-green-500 transition-all outline-none">
                                <option value="">Pilih Metode...</option>
                                <option value="BRI">Transfer BRI</option>
                                <option value="BCA">Transfer BCA</option>
                                <option value="Mandiri">Transfer Mandiri</option>
                                <option value="Cash Sopir">Tunai (Sopir)</option>
                                <option value="Cash Owner">Tunai (Owner)</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">No. Invoice / Referensi Transfer</label>
                            <textarea name="catatan_pembayaran" 
                                    x-model="selectedOrder.catatan_pembayaran" 
                                    rows="2" 
                                    class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-semibold focus:ring-2 focus:ring-green-500 transition-all outline-none"
                                    placeholder="Contoh: INV-2024001 / Ref: 99283129"></textarea>
                            <p class="text-[10px] text-slate-400 mt-2 italic">*Gunakan untuk mencatat kode unik pada bukti transfer bank.</p>
                        </div>

                        {{-- Sisa Hutang Realtime (Informasi saja) --}}
                        <div class="flex justify-between items-center px-2">
                            <span class="text-xs font-bold text-slate-400">SISA HUTANG:</span>
                            <span class="text-sm font-bold text-rose-500" x-text="'Rp ' + Number(selectedOrder.jumlah_pendapatan - selectedOrder.jumlah_dibayar).toLocaleString('id-ID')"></span>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="button" @click="isModalOpen = false" class="flex-1 px-4 py-4 rounded-2xl text-sm font-bold text-slate-400 hover:bg-slate-200 transition-colors">Batal</button>
                            <button type="submit" class="flex-[1.5] px-4 py-4 bg-emerald-600 text-white rounded-2xl text-sm font-bold shadow-lg hover:bg-emerald-700 transition-all">
                                Simpan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Pagination --}}
        <div class="p-6 border-t border-slate-100 bg-slate-50/30">
            {{ $orders->links() }}
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
                    htmlContainer: 'text-xs text-slate-500'
                }
            });
        @endif
    </script>
@endsection