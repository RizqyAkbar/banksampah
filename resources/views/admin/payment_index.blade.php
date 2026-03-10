@extends('admin.layout.app')

@section('title', 'Payment')
@section('header_title', 'Manajemen Pembayaran')

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Pembayaran</h1>

    <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden" x-data="{ isModalOpen: false, selectedOrder: {} }">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Riwayat & Status Bayar</h2>
                <p class="text-xs text-slate-500 mt-1">Pantau pelunasan tagihan pelanggan secara realtime.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                        <th class="px-6 py-4 font-semibold">Pelanggan</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold">Metode</th>
                        <th class="px-6 py-4 font-semibold">Total Tagihan</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">{{ $item->nama_pelanggan }}</p>
                            <p class="text-[10px] text-slate-400 italic">Lunas: {{ $item->tanggal_pelunasan ? \Carbon\Carbon::parse($item->tanggal_pelunasan)->format('d M Y') : '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $item->status_pembayaran == 'Lunas' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                <i class="fa-solid {{ $item->status_pembayaran == 'Lunas' ? 'fa-check-double' : 'fa-clock' }} mr-1.5"></i>
                                {{ $item->status_pembayaran }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-500">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-credit-card opacity-30"></i>
                                {{ $item->metode_pembayaran ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-900 text-base">Rp {{ number_format($item->jumlah_pendapatan, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button @click="isModalOpen = true; selectedOrder = {{ json_encode($item) }}" class="inline-flex items-center justify-center rounded-lg bg-green-50 text-green-700 px-3 py-1.5 text-xs font-bold hover:bg-green-600 hover:text-white transition-colors">
                                <i class="fa-solid fa-pen-to-square mr-2"></i> Update Status
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">Belum ada data pembayaran yang masuk.</td></tr>
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
                    
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Status Pembayaran</label>
                            <select name="status_pembayaran" x-model="selectedOrder.status_pembayaran" class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-semibold focus:ring-2 focus:ring-green-500 transition-all outline-none">
                                <option value="Belum Lunas">Belum Lunas</option>
                                <option value="Lunas">Lunas (Selesai)</option>
                            </select>
                        </div>

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

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Tanggal Pelunasan</label>
                            <input type="date" name="tanggal_pelunasan" x-model="selectedOrder.tanggal_pelunasan" class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-semibold focus:ring-2 focus:ring-green-500 transition-all outline-none">
                            <p class="text-[10px] text-slate-400 mt-2 ml-1 italic">*Kosongkan jika belum lunas</p>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="button" @click="isModalOpen = false" class="flex-1 px-4 py-4 rounded-2xl text-sm font-bold text-slate-400 hover:bg-slate-200 transition-colors">Batal</button>
                            <button type="submit" class="flex-[1.5] px-4 py-4 bg-green-600 text-white rounded-2xl text-sm font-bold shadow-[0_10px_20px_rgba(22,163,74,0.2)] hover:bg-green-700 transition-all active:scale-95">
                                Simpan Perubahan
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
                    htmlContainer: 'text-xs text-slate-500'
                }
            });
        @endif
    </script>
@endsection