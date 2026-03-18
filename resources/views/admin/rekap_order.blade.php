@extends('admin.layout.app')

@section('title', 'Rekap Order')
@section('header_title', 'Data Rekap Order')

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Rekap Order</h1>

    {{-- FITUR BARU: FORM FILTER & EXPORT --}}
    <div class="rounded-2xl border border-slate-100 bg-white p-4 sm:p-6 shadow-sm mb-6">
        <form action="{{ route('admin.rekap_order') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:flex-1">
                <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase mb-1.5">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, WA, armada..." class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
            </div>
            <div class="w-full md:w-auto">
                <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase mb-1.5">Mulai Tanggal</label>
                <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
            </div>
            <div class="w-full md:w-auto">
                <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase mb-1.5">Sampai Tanggal</label>
                <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
            </div>
            <div class="w-full md:w-auto flex flex-col sm:flex-row gap-2">
                <button type="submit" class="h-11 px-5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 transition shadow-lg flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider w-full sm:w-auto">
                    <i class="fa-solid fa-magnifying-glass"></i> Filter
                </button>
                {{-- Tombol Export akan mengirimkan value "excel" untuk ditangkap oleh Controller --}}
                <button type="submit" name="export" value="excel" class="h-11 px-5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition shadow-lg flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider w-full sm:w-auto">
                    <i class="fa-solid fa-file-excel"></i> Export
                </button>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:justify-between items-start sm:items-center gap-2 sm:gap-0">
            <h2 class="text-lg font-bold text-slate-900">Daftar Order Aktif</h2>
            <p class="text-[11px] sm:text-xs text-slate-500 italic leading-tight">*Pembatalan atau penghapusan kini bisa dilakukan per item layanan</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-center w-16 whitespace-nowrap">No</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[140px]">Tgl Penjadwalan</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[220px]">Pelanggan & Lokasi</th>
                        <th class="px-6 py-4 font-semibold text-center whitespace-nowrap min-w-[100px]">Status</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[180px]">Layanan & Detail</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[150px]">Pendapatan</th>
                        <th class="px-6 py-4 font-semibold text-right whitespace-nowrap min-w-[120px]">Aksi Utama</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-500 text-center">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                        
                        <td class="px-6 py-4 font-medium text-slate-900 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item->penjadwalan)->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-start justify-between gap-2 min-w-[200px]">
                                <div>
                                    <p class="font-bold text-slate-900 leading-tight">{{ $item->nama_pelanggan }}</p>
                                    <a href="https://wa.me/{{ $item->no_wa }}" target="_blank" class="text-xs text-green-600 hover:text-green-700 font-medium flex items-center gap-1 mt-1">
                                        <i class="fa-brands fa-whatsapp"></i> +{{ $item->no_wa }}
                                    </a>
                                    @if($item->alamat)
                                        <p class="text-[10px] text-slate-400 mt-1 line-clamp-1"><i class="fa-solid fa-map-pin mr-1"></i>{{ $item->alamat }}</p>
                                    @endif
                                </div>
                                @if($item->link_maps)
                                <a href="{{ $item->link_maps }}" target="_blank" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Buka Google Maps">
                                    <i class="fa-solid fa-location-dot text-sm"></i>
                                </a>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($item->status_pelanggan == 'Pelanggan' || $item->status_pelanggan == 'Pelanggan Lama')
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold text-blue-700 border border-blue-100 uppercase tracking-wider">
                                    {{ $item->status_pelanggan }}
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-[10px] font-bold text-green-700 border border-green-100 uppercase tracking-wider">
                                    Baru
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col items-start gap-1.5 min-w-[150px]">
                                @foreach($item->items->where('status_item', 'Aktif') as $index => $service)
                                    <span class="inline-block bg-slate-100 text-slate-700 px-2 py-1 rounded text-[10px] font-bold border border-slate-200 tracking-tight">
                                        {{ $service->jenis_layanan }} ({{ $service->jumlah_rit ?? 0 }} Rit)
                                    </span>
                                @endforeach
                                
                                <button type="button" onclick="openModalDetail({{ $item->id }})" class="mt-1 flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg text-[10px] font-bold transition-colors shadow-sm">
                                    <i class="fa-solid fa-list-ul"></i> Lihat Detail Armada
                                </button>
                            </div>

                            @if($item->catatan)
                                <div class="mt-2">
                                    <p class="text-[9px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 w-fit italic line-clamp-2" title="{{ $item->catatan }}">
                                        *Keterangan: {{ $item->catatan }}
                                    </p>
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 font-bold text-slate-900 whitespace-nowrap">
                            Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-nowrap items-center justify-end gap-2">
                                <a href="{{ route('admin.rekap_order.edit', $item->id) }}" class="inline-flex items-center justify-center rounded-lg bg-green-50 text-green-700 px-3 py-1.5 text-xs font-bold hover:bg-green-600 hover:text-white transition-colors shadow-sm">
                                    <i class="fa-solid fa-pen-to-square mr-1.5"></i> Edit
                                </a>

                                <form action="{{ route('admin.rekap_order.destroy', $item->id) }}" method="POST" class="m-0 p-0 form-hapus-total">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-hapus-total inline-flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors shadow-sm">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data order untuk filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-100 bg-slate-50/30">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>

    {{-- Form Rahasia untuk Delete/Cancel Item --}}
    <form id="form-item-action" method="POST" style="display: none;">
        @csrf @method('PUT')
        <input type="hidden" name="alasan_cancel" id="input-item-alasan">
    </form>

    {{-- MODAL DETAIL ARMADA --}}
    @foreach($orders as $item)
    <div id="modalDetail-{{ $item->id }}" class="fixed inset-0 z-[60] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl max-w-lg w-full overflow-hidden animate-in zoom-in duration-200 flex flex-col max-h-[90vh]">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50 shrink-0">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 font-heading">Detail Layanan & Armada</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $item->nama_pelanggan }} - {{ \Carbon\Carbon::parse($item->penjadwalan)->format('d M Y') }}</p>
                </div>
                <button type="button" onclick="closeModalDetail({{ $item->id }})" class="text-slate-400 hover:text-rose-500 p-1 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <div class="p-4 sm:p-6 overflow-y-auto space-y-4 bg-slate-50/30">
                @foreach($item->items->where('status_item', 'Aktif') as $index => $service)
                    @php
                        $masterDriver = \App\Models\Driver::where('nama_sopir', $service->nama_sopir)->first();
                        $finalWa = $masterDriver ? $masterDriver->no_wa_sopir : $service->no_wa_sopir;
                        $finalPlat = $masterDriver ? $masterDriver->plat_armada : $service->plat_armada;
                    @endphp
                    
                    <div class="bg-white border border-slate-200 p-4 sm:p-5 rounded-xl shadow-sm relative">
                        <div class="absolute -top-2.5 left-4 bg-slate-800 text-white px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-widest">
                            Layanan #{{ $index + 1 }}
                        </div>
                        
                        <div class="mt-1 mb-3">
                            <p class="font-bold text-slate-800 text-sm sm:text-base">
                                {{ $service->jenis_layanan }} 
                                <span class="text-slate-500 font-normal">({{ $service->jumlah_rit ?? 0 }} Rit)</span>
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-50 p-3 rounded-lg border border-slate-100">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase mb-1">Armada & Plat</p>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-bold text-slate-700">
                                        <i class="fa-solid fa-truck-pickup text-slate-400 mr-1"></i>{{ $service->armada ?? '-' }}
                                    </span>
                                    @if($finalPlat)
                                        <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 text-[9px] font-bold text-slate-700 uppercase">{{ $finalPlat }}</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase mb-1">Nama Sopir</p>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-bold text-slate-700">
                                        <i class="fa-solid fa-user-tie text-slate-400 mr-1"></i>{{ $service->nama_sopir ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex flex-wrap items-center justify-end gap-2 pt-4 border-t border-slate-100">
                            @if($finalWa)
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $finalWa) }}" target="_blank" class="flex-1 sm:flex-none items-center justify-center flex gap-1.5 px-3 py-2 rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-colors text-xs font-bold">
                                <i class="fa-brands fa-whatsapp text-sm"></i> Hubungi
                            </a>
                            @endif

                            <button type="button" onclick="closeModalDetail({{ $item->id }}); setTimeout(() => confirmCancelItem('{{ $service->id }}', '{{ $service->jenis_layanan }}'), 300)" 
                                class="flex-1 sm:flex-none items-center justify-center flex gap-1.5 px-3 py-2 rounded-lg bg-orange-50 text-orange-500 hover:bg-orange-500 hover:text-white transition-colors text-xs font-bold">
                                <i class="fa-solid fa-ban"></i> Batal
                            </button>
                            
                            <button type="button" onclick="closeModalDetail({{ $item->id }}); setTimeout(() => confirmDeleteItem('{{ $service->id }}'), 300)" 
                                class="flex-1 sm:flex-none items-center justify-center flex gap-1.5 px-3 py-2 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors text-xs font-bold">
                                <i class="fa-solid fa-trash-can"></i> Hapus
                            </button>
                        </div>

                        @php
                            $searchKey = "Layanan #" . ($index + 1);
                            $specificLog = $item->logs->filter(function($log) use ($searchKey) {
                                return str_contains($log->detail, $searchKey);
                            })->first();

                            $labelType = "REVISI";
                            if ($specificLog) {
                                if (str_contains($specificLog->detail, 'REVISI UNIT & PERSONEL')) { $labelType = "REVISI UNIT & PERSONEL"; } 
                                elseif (str_contains($specificLog->detail, 'REVISI UNIT ARMADA')) { $labelType = "REVISI UNIT ARMADA"; } 
                                elseif (str_contains($specificLog->detail, 'REVISI PERSONEL')) { $labelType = "REVISI PERSONEL"; }
                            }
                        @endphp

                        @if($specificLog)
                        <div class="mt-4 bg-orange-50 border border-orange-200 p-2.5 rounded-lg">
                            <div class="flex items-center gap-1 text-orange-700 font-bold text-[9px] uppercase mb-1">
                                <i class="fa-solid fa-clock-rotate-left"></i> {{ $labelType }}
                            </div>
                            <p class="text-[10px] text-orange-600 italic leading-tight">
                                "{{ $specificLog->alasan }}"
                            </p>
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
            
            <div class="p-4 border-t border-slate-100 bg-white shrink-0 flex justify-end">
                <button onclick="closeModalDetail({{ $item->id }})" class="px-6 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors w-full sm:w-auto">Tutup Detail</button>
            </div>
        </div>
    </div>
    @endforeach
@endsection

@section('extra_js')
    <script>
        function openModalDetail(id) {
            document.getElementById('modalDetail-' + id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModalDetail(id) {
            document.getElementById('modalDetail-' + id).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function confirmCancelItem(itemId, layananName) {
            Swal.fire({
                title: 'Batalkan Layanan?',
                text: `Alasan pembatalan ${layananName}:`,
                input: 'textarea',
                inputPlaceholder: 'Contoh: Sopir berhalangan...',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f97316', 
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Kembali',
                reverseButtons: true,
                customClass: { popup: 'rounded-2xl shadow-lg border border-orange-100' },
                inputValidator: (value) => {
                    if (!value) { return 'Alasan wajib diisi!' }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('form-item-action');
                    form.action = `/admin/order-item/${itemId}/cancel`;
                    document.getElementById('input-item-alasan').value = result.value;
                    form.submit();
                }
            });
        }

        function confirmDeleteItem(itemId) {
            Swal.fire({
                title: 'Hapus Layanan?',
                text: "Data layanan ini akan dihapus permanen!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Hapus!',
                reverseButtons: true,
                customClass: { popup: 'rounded-2xl shadow-lg border border-red-100' }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('form-item-action');
                    form.action = `/admin/order-item/${itemId}/destroy`;
                    
                    const oldMethod = form.querySelector('input[name="_method"]');
                    if (oldMethod) oldMethod.remove();

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    form.submit();
                }
            });
        }

        document.addEventListener('click', function (e) {
            if (e.target.closest('.btn-hapus-total')) {
                const button = e.target.closest('.btn-hapus-total');
                const form = button.closest('.form-hapus-total');
                Swal.fire({
                    title: 'Hapus Seluruh Nota?',
                    text: "Semua layanan dalam nota ini akan dihapus permanen!",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Hapus Semua!',
                    reverseButtons: true,
                    customClass: { popup: 'rounded-2xl shadow-lg border border-red-100' }
                }).then((result) => { if (result.isConfirmed) form.submit(); });
            }
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}',
                timer: 3000, toast: true, position: 'top-end', showConfirmButton: false,
                customClass: { popup: 'rounded-2xl shadow-lg border border-green-100' }
            });
        @endif
    </script>
@endsection