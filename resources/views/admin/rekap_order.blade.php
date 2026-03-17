@extends('admin.layout.app')

@section('title', 'Rekap Order')
@section('header_title', 'Data Rekap Order')

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Rekap Order</h1>

    <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        {{-- REVISI: flex-col di mobile agar teks tidak bertabrakan --}}
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:justify-between items-start sm:items-center gap-2 sm:gap-0">
            <h2 class="text-lg font-bold text-slate-900">Daftar Order Aktif</h2>
            <p class="text-[11px] sm:text-xs text-slate-500 italic leading-tight">*Pembatalan atau penghapusan kini bisa dilakukan per item layanan</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-100">
                    <tr>
                        {{-- REVISI: Tambah whitespace-nowrap dan min-width agar tabel tidak tergencet di mobile --}}
                        <th class="px-6 py-4 font-semibold text-center w-16 whitespace-nowrap">No</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[140px]">Tgl Penjadwalan</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[220px]">Pelanggan & Lokasi</th>
                        <th class="px-6 py-4 font-semibold text-center whitespace-nowrap min-w-[100px]">Status</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[320px]">Detail Armada & Layanan</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[150px]">Pendapatan</th>
                        <th class="px-6 py-4 font-semibold text-right whitespace-nowrap min-w-[120px]">Aksi Utama</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-500 text-center">{{ $loop->iteration }}</td>
                        
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
                            {{-- REVISI: Lebar responsif menggunakan Tailwind w-[...] menggantikan inline style --}}
                            <div class="flex flex-row flex-nowrap gap-3 overflow-x-auto pb-2 scrollbar-hide w-[280px] sm:w-[350px] md:w-[450px] lg:w-[550px]">
                                @foreach($item->items->where('status_item', 'Aktif') as $index => $service)
                                @php
                                    $masterDriver = \App\Models\Driver::where('nama_sopir', $service->nama_sopir)->first();
                                    $finalWa = $masterDriver ? $masterDriver->no_wa_sopir : $service->no_wa_sopir;
                                    $finalPlat = $masterDriver ? $masterDriver->plat_armada : $service->plat_armada;
                                @endphp

                                <div class="flex flex-col bg-slate-50/80 border border-slate-100 p-2.5 rounded-xl min-w-[200px] shrink-0">
                                    <div class="flex justify-between items-start mb-1">
                                        <p class="font-bold text-slate-800 leading-tight text-[11px]">
                                            {{ $service->jenis_layanan }} 
                                            <span class="text-slate-500 font-normal">({{ $service->jumlah_rit ?? 0 }} Rit)</span>
                                        </p>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] text-slate-500 flex items-center gap-1">
                                            <i class="fa-solid fa-truck-pickup text-slate-400 text-[9px]"></i> 
                                            {{ $service->armada ?? '-' }} 
                                        </span>
                                        @if($finalPlat) 
                                            <span class="bg-white px-1.5 py-0.5 rounded text-[9px] font-bold text-slate-700 border border-slate-200 uppercase tracking-tighter">{{ $finalPlat }}</span> 
                                        @endif
                                    </div>
                                    
                                    <div class="mt-1.5 pt-1.5 border-t border-slate-200/50 flex flex-col gap-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[10px] text-slate-600 font-medium flex items-center gap-1 truncate">
                                                <i class="fa-solid fa-user-tie text-[9px] text-slate-400"></i>
                                                {{ $service->nama_sopir ?? '-' }}
                                            </span>
                                            <div class="flex items-center gap-1">
                                                @if($finalWa)
                                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $finalWa) }}" target="_blank" class="text-green-600 hover:text-green-700 mr-0.5" title="Hubungi Sopir">
                                                    <i class="fa-brands fa-whatsapp text-sm"></i>
                                                </a>
                                                @endif

                                                <button type="button" onclick="confirmCancelItem('{{ $service->id }}', '{{ $service->jenis_layanan }}')" 
                                                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-orange-50 text-orange-500 hover:bg-orange-500 hover:text-white transition-colors" 
                                                    title="Batalkan Layanan Ini">
                                                    <i class="fa-solid fa-ban text-[10px]"></i>
                                                </button>
                                                
                                                <button type="button" onclick="confirmDeleteItem('{{ $service->id }}')" 
                                                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors" 
                                                    title="Hapus Layanan Ini">
                                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                                </button>
                                            </div>
                                        </div>
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
                                    <div class="mt-2 bg-orange-100/50 border border-orange-200 p-1.5 rounded-lg">
                                        <div class="flex items-center gap-1 text-orange-700 font-bold text-[8px] uppercase">
                                            <i class="fa-solid fa-clock-rotate-left"></i> {{ $labelType }}:
                                        </div>
                                        <p class="text-[9px] text-orange-600 italic leading-tight mt-0.5">
                                            "{{ $specificLog->alasan }}"
                                        </p>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>

                            @if($item->catatan)
                                <div class="mt-1.5">
                                    <p class="text-[9px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 w-fit italic">
                                        *Keterangan: {{ $item->catatan }}
                                    </p>
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 font-bold text-slate-900 whitespace-nowrap">
                            Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            {{-- REVISI: Tambahkan flex-nowrap agar tombol tidak turun baris --}}
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
                    <tr><td colspan="7" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data order aktif untuk ditampilkan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-100 bg-slate-50/30">
            {{ $orders->links() }}
        </div>
    </div>

    <form id="form-item-action" method="POST" style="display: none;">
        @csrf @method('PUT')
        <input type="hidden" name="alasan_cancel" id="input-item-alasan">
    </form>
@endsection

@section('extra_js')
    <script>
        function confirmCancelItem(itemId, layananName) {
            Swal.fire({
                title: 'Batalkan Layanan?',
                text: `Alasan pembatalan ${layananName}:`,
                input: 'textarea',
                inputPlaceholder: 'Contoh: Sopir berhalangan / Lokasi tidak terjangkau...',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f97316', 
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Kembali',
                reverseButtons: true,
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
                reverseButtons: true
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

        function confirmRestoreItem(itemId) {
            const form = document.getElementById('form-item-action');
            form.action = `/admin/order-item/${itemId}/restore`;
            let methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) methodInput.value = 'PUT';
            form.submit();
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
                    reverseButtons: true
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