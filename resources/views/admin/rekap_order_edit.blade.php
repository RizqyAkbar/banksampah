@extends('admin.layout.app')

@section('title', 'Lengkapi Rekap')

@section('header_custom')
<header class="flex h-20 items-center justify-between border-b border-slate-200/60 bg-white/80 px-6 backdrop-blur-md z-30">
    <div class="flex items-center gap-4">
        <button id="openSidebar" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-900 lg:hidden">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.rekap_order') }}" class="text-slate-400 hover:text-slate-700 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="font-heading text-2xl font-bold text-slate-900 hidden sm:block">Lengkapi Data Rekap</h1>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-1.5 text-sm font-medium text-slate-600 border border-slate-100">
            <i class="fa-regular fa-calendar text-slate-400"></i>
            {{ date('d M Y') }}
        </div>
    </div>
</header>
@endsection

@section('extra_css')
<style>
    .form-input {
        width: 100%; border-radius: 0.75rem; border: 1px solid #e2e8f0;
        background-color: #f8fafc; padding: 0.75rem 1rem; font-size: 0.875rem;
        color: #0f172a; transition: all 0.2s;
    }
    .form-input:focus {
        outline: none; border-color: #00C264; background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(0, 194, 100, 0.1);
    }
    .form-input-readonly {
        background-color: #f1f5f9; color: #64748b; cursor: not-allowed;
    }
    .input-group-custom {
        display: flex; align-items: stretch; width: 100%;
    }
    .input-group-text-custom {
        display: flex; align-items: center; padding: 0 0.75rem;
        font-size: 0.75rem; font-weight: 700; color: #64748b;
        background-color: #f1f5f9; border: 1px solid #e2e8f0;
        border-right: none; border-top-left-radius: 0.75rem; border-bottom-left-radius: 0.75rem;
    }
    .form-input-grouped {
        border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;
    }
    .rounded-l-none { border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; }
    
    .reason-required {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1) !important;
    }
    .layanan-block { position: relative; }
    .btn-remove { position: absolute; top: -10px; right: -10px; z-index: 10; }

    @media (max-width: 640px) {
        .form-input { padding: 0.6rem 0.75rem; font-size: 0.8rem; }
        .input-group-text-custom { padding: 0 0.5rem; font-size: 0.7rem; }
        
        .swal2-popup.swal2-toast {
            padding: 0.5rem 0.75rem !important;
            width: auto !important;
            max-width: 90vw !important;
        }
        .swal2-toast .swal2-title {
            font-size: 0.85rem !important;
            margin-bottom: 0.1rem !important;
        }
        .swal2-toast .swal2-html-container {
            font-size: 0.75rem !important;
            margin: 0 !important;
        }
        .swal2-toast .swal2-icon {
            transform: scale(0.6) !important;
            margin: 0 0.5rem 0 0 !important;
        }
        .swal2-container.swal2-top-end {
            top: 10px !important;
            right: 10px !important;
        }
    }
</style>
@endsection

@section('content')
    @php
        $allServices = \App\Models\Service::all();
        $pickupServices = [];
        $dumpServices = [];
        foreach($allServices as $l) {
            if(stripos($l->armada, 'dump') !== false || stripos($l->armada, 'truk') !== false) {
                $dumpServices[] = $l;
            } else {
                $pickupServices[] = $l;
            }
        }
    @endphp

    <div class="flex items-center justify-between mb-6">
        <h1 class="font-heading text-2xl font-bold text-slate-900 sm:hidden">Lengkapi Rekap</h1>
        <span class="px-3 py-1 {{ $order->status_pelanggan == 'Pelanggan' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }} font-bold rounded-full text-xs">
            {{ $order->status_pelanggan }}
        </span>
    </div>

    <div class="max-w-4xl mx-auto w-full">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-8">
            <form action="{{ route('admin.rekap_order.update', $order->id) }}" method="POST" id="formRekap">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-6 mb-6 sm:mb-8 bg-slate-50 p-4 sm:p-5 rounded-xl border border-slate-100">
                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-500 font-semibold mb-0.5 sm:mb-1 uppercase tracking-wider">Nama Pelanggan</p>
                        <p class="font-bold text-slate-800 text-base sm:text-lg">{{ $order->nama_pelanggan }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-500 font-semibold mb-0.5 sm:mb-1 uppercase tracking-wider">No WhatsApp</p>
                        <p class="font-bold text-slate-800 text-base sm:text-lg"><i class="fa-brands fa-whatsapp text-green-500 mr-1.5"></i>+{{ $order->no_wa }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-500 font-semibold mb-0.5 sm:mb-1 uppercase tracking-wider">Penjadwalan</p>
                        <p class="font-bold text-slate-800 text-base sm:text-lg"><i class="fa-regular fa-calendar text-blue-500 mr-1.5"></i>{{ \Carbon\Carbon::parse($order->penjadwalan)->format('d M Y') }}</p>
                    </div>
                </div>

                {{-- TOMBOL TAMBAH JASA --}}
                <div class="flex items-center justify-between mb-4 border-t border-slate-100 pt-6">
                    <h3 class="text-xs sm:text-sm font-bold text-slate-400 uppercase tracking-widest">Daftar Layanan Jasa</h3>
                    <button type="button" id="add-layanan" class="text-[10px] sm:text-xs font-bold text-green-600 hover:text-green-700 px-3 py-1.5 bg-green-50 rounded-lg transition-colors shadow-sm">
                        <i class="fa-solid fa-plus mr-1"></i> Tambah Jasa
                    </button>
                </div>

                {{-- CONTAINER LAYANAN --}}
                <div class="space-y-6 sm:space-y-10" id="layanan-container">
                    @foreach($order->items as $index => $item)
                    <div class="layanan-block p-4 sm:p-6 rounded-2xl border border-slate-100 bg-white shadow-sm relative mt-4 sm:mt-0">
                        {{-- TOMBOL HAPUS LAYANAN --}}
                        <button type="button" class="btn-remove h-6 w-6 rounded-full bg-rose-500 text-white flex items-center justify-center hover:bg-rose-600 shadow-sm transition-all" onclick="removeItemDOM(this)">
                            <i class="fa-solid fa-xmark text-[10px]"></i>
                        </button>

                        <div class="absolute -top-3 left-4 sm:left-6 bg-slate-800 text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">
                            Layanan #{{ $index + 1 }}
                        </div>

                        <input type="hidden" name="item_id[]" value="{{ $item->id }}">
                        <input type="hidden" class="old-armada" name="armada_lama[]" value="{{ $item->armada }}">
                        <input type="hidden" class="old-sopir" name="nama_sopir_lama[]" value="{{ $item->nama_sopir }}">
                        <input type="hidden" class="old-plat" name="plat_armada_lama[]" value="{{ $item->plat_armada }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-2">
                            <div class="md:col-span-2">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-3">*Pilih salah satu jasa (Pickup ATAU Truk Dump)</p>
                                
                                @php
                                    $isDump = stripos($item->armada, 'dump') !== false || stripos($item->armada, 'truk') !== false;
                                @endphp

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 mb-4">
                                    <div class="jasa-select-wrapper">
                                        <label class="mb-1.5 sm:mb-2 block text-[10px] font-bold text-blue-600 uppercase">1. Jasa Pickup</label>
                                        <select class="form-input select-jasa-pickup border-blue-100 focus:border-blue-400" onchange="syncJasa(this, 'pickup')">
                                            <option value="">-- Pilih Jasa Pickup --</option>
                                            @foreach($pickupServices as $layanan)
                                                <option value="{{ $layanan->nama_layanan }}" 
                                                        data-armada="{{ $layanan->armada }}"
                                                        data-dasar="{{ (int)$layanan->harga_dasar }}" 
                                                        data-jual="{{ (int)$layanan->harga_jual }}"
                                                        {{ (!$isDump && $item->jenis_layanan == $layanan->nama_layanan && $item->armada == $layanan->armada) ? 'selected' : '' }}>
                                                    {{ $layanan->nama_layanan }} ({{ $layanan->armada }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="jasa-select-wrapper">
                                        <label class="mb-1.5 sm:mb-2 block text-[10px] font-bold text-orange-600 uppercase">2. Jasa Truk Dump</label>
                                        <select class="form-input select-jasa-dump border-orange-100 focus:border-orange-400" onchange="syncJasa(this, 'dump')">
                                            <option value="">-- Pilih Jasa Truk Dump --</option>
                                            @foreach($dumpServices as $layanan)
                                                <option value="{{ $layanan->nama_layanan }}" 
                                                        data-armada="{{ $layanan->armada }}"
                                                        data-dasar="{{ (int)$layanan->harga_dasar }}" 
                                                        data-jual="{{ (int)$layanan->harga_jual }}"
                                                        {{ ($isDump && $item->jenis_layanan == $layanan->nama_layanan && $item->armada == $layanan->armada) ? 'selected' : '' }}>
                                                    {{ $layanan->nama_layanan }} ({{ $layanan->armada }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="text" name="jenis_layanan[]" value="{{ $item->jenis_layanan }}" class="real-jasa-input absolute w-0 h-0 opacity-0 pointer-events-none" tabindex="-1">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Jenis Armada</label>
                                <input type="text" name="armada[]" value="{{ $item->armada }}" class="form-input input-armada form-input-readonly new-armada" readonly required>
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Nama Sopir</label>
                                <select name="nama_sopir[]" class="form-input new-sopir" onchange="autofillSopirDropdown(this)">
                                    <option value="">-- Pilih Sopir --</option>
                                    @foreach(\App\Models\Driver::all() as $driver)
                                        <option value="{{ $driver->nama_sopir }}" 
                                                data-wa="{{ $driver->no_wa_sopir }}" 
                                                data-plat="{{ $driver->plat_armada }}"
                                                {{ $item->nama_sopir == $driver->nama_sopir ? 'selected' : '' }}>
                                            {{ $driver->nama_sopir }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">No. WA Sopir</label>
                                <div class="relative flex">
                                    <span class="inline-flex items-center px-3 sm:px-4 rounded-l-xl border border-r-0 border-slate-200 bg-slate-100 text-slate-500 text-xs sm:text-sm font-bold">+62</span>
                                    @php
                                        $currentDriver = \App\Models\Driver::where('nama_sopir', $item->nama_sopir)->first();
                                        $displayWa = $currentDriver ? ltrim($currentDriver->no_wa_sopir, '62') : ltrim($item->no_wa_sopir, '62');
                                        $displayPlat = $currentDriver ? $currentDriver->plat_armada : $item->plat_armada;
                                    @endphp
                                    <input type="tel" name="no_wa_sopir[]" value="{{ $displayWa }}" class="form-input rounded-l-none target-wa form-input-readonly" readonly>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Nomor Plat Armada</label>
                                <input type="text" name="plat_armada[]" value="{{ $displayPlat }}" class="form-input uppercase new-plat target-plat form-input-readonly" readonly>
                            </div>

                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4 bg-slate-50 p-3 sm:p-4 rounded-xl border border-slate-100 mt-1 sm:mt-2">
                                <div>
                                    <label class="block text-[10px] sm:text-[11px] font-bold text-slate-500 mb-1 uppercase">Jumlah Rit</label>
                                    <input type="number" name="jumlah_rit[]" value="{{ $item->jumlah_rit }}" class="form-input hitung-rit" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] sm:text-[11px] font-bold text-slate-500 mb-1 uppercase">Harga/Rit (Jual)</label>
                                    <div class="input-group-custom">
                                        <span class="input-group-text-custom">Rp</span>
                                        <input type="number" name="harga_per_rit[]" value="{{ (int)$item->harga_per_rit }}" class="form-input form-input-grouped hitung-harga" placeholder="0">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] sm:text-[11px] font-bold text-slate-500 mb-1 uppercase">HPP/Rit (Modal)</label>
                                    <div class="input-group-custom">
                                        <span class="input-group-text-custom">Rp</span>
                                        <input type="number" name="hpp_per_rit[]" value="{{ (int)$item->hpp_per_rit }}" class="form-input form-input-grouped hitung-hpp" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div id="reasonContainer" class="mt-6 sm:mt-10 p-4 sm:p-5 bg-slate-50 border border-slate-200 rounded-xl sm:rounded-2xl transition-all">
                    <label class="block text-xs sm:text-sm font-bold text-slate-700 mb-2 flex items-center gap-1.5 sm:gap-2" id="reasonLabel">
                        <i class="fa-solid fa-circle-info text-blue-500" id="reasonIcon"></i>
                        Alasan Perubahan <span class="hidden sm:inline">(WAJIB JIKA GANTI ARMADA/SOPIR)</span><span class="inline sm:hidden">(WAJIB JIKA GANTI)</span>
                    </label>
                    <input type="text" name="alasan_perubahan" id="alasanPerubahan" placeholder="Contoh: Tambah armada baru karena order membludak..." class="form-input bg-white">
                    <p id="errorText" class="text-[10px] text-red-500 mt-2 italic hidden font-bold">*Wajib diisi karena terdeteksi perubahan layanan!</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-6 sm:mt-8">
                    <div class="md:col-span-2">
                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Alamat Lengkap Pelanggan</label>
                        <textarea name="alamat" rows="2" class="form-input">{{ $order->alamat }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Link Google Maps</label>
                        <input type="url" name="link_maps" value="{{ $order->link_maps }}" class="form-input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Catatan Khusus Order</label>
                        <textarea name="catatan" rows="2" class="form-input">{{ $order->catatan }}</textarea>
                    </div>
                </div>

                <div class="mt-6 sm:mt-8 grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 border-t border-slate-100 pt-6 sm:pt-8">
                    <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 sm:p-5">
                        <p class="text-blue-600 font-semibold text-xs sm:text-sm mb-1 uppercase tracking-wider">Total Pendapatan</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-blue-900" id="hasil_pendapatan">Rp 0</h3>
                    </div>
                    <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-4 sm:p-5">
                        <p class="text-emerald-600 font-semibold text-xs sm:text-sm mb-1 uppercase tracking-wider">Total Laba Bersih</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-emerald-900" id="hasil_keuntungan">Rp 0</h3>
                    </div>
                </div>

                <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                    <a href="{{ route('admin.rekap_order') }}" class="w-full sm:w-auto text-center px-4 sm:px-6 py-3 sm:py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
                    <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-4 sm:px-8 py-3 sm:py-2.5 bg-[#00C264] text-white rounded-xl text-xs sm:text-sm font-bold shadow-lg hover:bg-[#009B4E] transition">Simpan Semua Rekap</button>
                </div>
            </form>

            {{-- HISTORI PERUBAHAN DATA --}}
            @if($order->logs->count() > 0)
            <div class="mt-10 sm:mt-12 border-t border-slate-100 pt-6 sm:pt-8">
                <h3 class="font-heading text-base sm:text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-orange-500"></i>
                    History Perubahan Data
                </h3>
                <div class="space-y-3 sm:space-y-4">
                    @foreach($order->logs as $log)
                    <div class="bg-slate-50 border-l-4 border-orange-400 p-3 sm:p-4 rounded-r-xl shadow-sm text-xs sm:text-sm">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-1.5 sm:mb-1 gap-1 sm:gap-0">
                            <span class="font-bold text-slate-800">{{ $log->perubahan }}</span>
                            <span class="text-[9px] sm:text-[10px] font-medium text-slate-500 bg-slate-200 px-2 py-0.5 rounded-full w-fit">
                                {{ $log->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                        <p class="text-slate-600 mb-1.5 sm:mb-2 text-xs sm:text-sm">{{ $log->detail }}</p>
                        <div class="text-[9px] sm:text-[10px] text-slate-500 italic">
                            <strong>Alasan:</strong> {{ $log->alasan }} | <strong>Oleh:</strong> {{ $log->admin_name }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('extra_js')
<script>
    const form = document.getElementById('formRekap');
    const container = document.getElementById('layanan-container');
    const addButton = document.getElementById('add-layanan');
    const inputAlasan = document.getElementById('alasanPerubahan');
    const containerAlasan = document.getElementById('reasonContainer');
    const labelAlasan = document.getElementById('reasonLabel');
    const iconAlasan = document.getElementById('reasonIcon');
    const errorText = document.getElementById('errorText');

    let originalItemCount = {{ $order->items->count() }};

    function syncJasa(selectElement, type) {
        const block = selectElement.closest('.layanan-block');
        const pickupSelect = block.querySelector('.select-jasa-pickup');
        const dumpSelect = block.querySelector('.select-jasa-dump');
        const realInput = block.querySelector('.real-jasa-input');
        const inputArmada = block.querySelector('.input-armada');
        
        const inputHpp = block.querySelector('.hitung-hpp');
        const inputJual = block.querySelector('.hitung-harga');

        // Jangan hapus value dropdown sebelah jika ini dipanggil otomatis (saat load)
        // kecuali jika user yang benar-benar mengubah manual
        if (event && event.type === 'change') {
            if (type === 'pickup' && selectElement.value !== "") {
                dumpSelect.value = "";
            } else if (type === 'dump' && selectElement.value !== "") {
                pickupSelect.value = "";
            }
        }

        const activeSelect = (pickupSelect.value !== "") ? pickupSelect : ((dumpSelect.value !== "") ? dumpSelect : null);

        if (!activeSelect || activeSelect.value === "") {
            realInput.value = '';
            inputArmada.value = '';
            
            // Kosongkan harga HANYA JIKA memang user yang memicu (bukan dari default database lama)
            if (inputHpp && inputJual && event && event.type === 'change') {
                inputHpp.value = '0';
                inputJual.value = '0';
            }
        } else {
            const selectedOption = activeSelect.selectedOptions[0];
            realInput.value = activeSelect.value;
            inputArmada.value = selectedOption.getAttribute('data-armada') || '-';
            
            // Tarik harga otomatis jika harga saat ini 0 ATAU user yang mengubah
            if (inputHpp && inputJual) {
                const currentHpp = parseInt(inputHpp.value) || 0;
                const currentJual = parseInt(inputJual.value) || 0;
                
                if ((currentHpp === 0 && currentJual === 0) || (event && event.type === 'change')) {
                    inputHpp.value = selectedOption.getAttribute('data-dasar') || 0;
                    inputJual.value = selectedOption.getAttribute('data-jual') || 0;
                }
            }
        }
        
        hitungTotalOtomatis();
        if (event && event.type === 'change') {
            checkChanges();
        }
    }

    function autofillSopirDropdown(select) {
        const block = select.closest('.layanan-block');
        const inputWa = block.querySelector('.target-wa');
        const inputPlat = block.querySelector('.target-plat');
        const selectedOption = select.options[select.selectedIndex];
        
        if (selectedOption.value !== "") {
            const waFull = selectedOption.getAttribute('data-wa') || '';
            inputWa.value = waFull.replace(/^62/, ''); 
            inputPlat.value = selectedOption.getAttribute('data-plat') || '';
        } else {
            inputWa.value = '';
            inputPlat.value = '';
        }
        checkChanges();
    }

    window.removeItemDOM = function(btn) {
        const item = btn.closest('.layanan-block');
        item.remove();
        hitungTotalOtomatis();
        checkChanges();
    };

    addButton.addEventListener('click', () => {
        const newItem = document.createElement('div');
        newItem.className = 'layanan-block p-4 sm:p-6 rounded-2xl border border-green-200 bg-green-50/20 shadow-sm relative mt-6';
        
        newItem.innerHTML = `
            <button type="button" class="btn-remove h-6 w-6 rounded-full bg-rose-500 text-white flex items-center justify-center hover:bg-rose-600 shadow-sm transition-all" onclick="removeItemDOM(this)">
                <i class="fa-solid fa-xmark text-[10px]"></i>
            </button>
            <div class="absolute -top-3 left-4 sm:left-6 bg-green-600 text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">
                Layanan Tambahan Baru
            </div>

            <input type="hidden" name="item_id[]" value="new">
            <input type="hidden" class="old-armada" name="armada_lama[]" value="">
            <input type="hidden" class="old-sopir" name="nama_sopir_lama[]" value="">
            <input type="hidden" class="old-plat" name="plat_armada_lama[]" value="">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-2">
                <div class="md:col-span-2">
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-3">*Pilih salah satu jasa (Pickup ATAU Truk Dump)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 mb-4">
                        <div class="jasa-select-wrapper">
                            <label class="mb-1.5 sm:mb-2 block text-[10px] font-bold text-blue-600 uppercase">1. Jasa Pickup</label>
                            <select class="form-input select-jasa-pickup border-blue-100 focus:border-blue-400" onchange="syncJasa(this, 'pickup')">
                                <option value="">-- Pilih Jasa Pickup --</option>
                                @foreach($pickupServices as $layanan)
                                    <option value="{{ $layanan->nama_layanan }}" data-armada="{{ $layanan->armada }}" data-dasar="{{ (int)$layanan->harga_dasar }}" data-jual="{{ (int)$layanan->harga_jual }}">{{ $layanan->nama_layanan }} ({{ $layanan->armada }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="jasa-select-wrapper">
                            <label class="mb-1.5 sm:mb-2 block text-[10px] font-bold text-orange-600 uppercase">2. Jasa Truk Dump</label>
                            <select class="form-input select-jasa-dump border-orange-100 focus:border-orange-400" onchange="syncJasa(this, 'dump')">
                                <option value="">-- Pilih Jasa Truk Dump --</option>
                                @foreach($dumpServices as $layanan)
                                    <option value="{{ $layanan->nama_layanan }}" data-armada="{{ $layanan->armada }}" data-dasar="{{ (int)$layanan->harga_dasar }}" data-jual="{{ (int)$layanan->harga_jual }}">{{ $layanan->nama_layanan }} ({{ $layanan->armada }})</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" name="jenis_layanan[]" class="real-jasa-input absolute w-0 h-0 opacity-0 pointer-events-none" tabindex="-1">
                    </div>
                </div>

                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Jenis Armada</label>
                    <input type="text" name="armada[]" class="form-input input-armada form-input-readonly new-armada" readonly required>
                </div>

                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Nama Sopir</label>
                    <select name="nama_sopir[]" class="form-input new-sopir" onchange="autofillSopirDropdown(this)">
                        <option value="">-- Pilih Sopir --</option>
                        @foreach(\App\Models\Driver::all() as $driver)
                            <option value="{{ $driver->nama_sopir }}" data-wa="{{ $driver->no_wa_sopir }}" data-plat="{{ $driver->plat_armada }}">{{ $driver->nama_sopir }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">No. WA Sopir</label>
                    <div class="relative flex">
                        <span class="inline-flex items-center px-3 sm:px-4 rounded-l-xl border border-r-0 border-slate-200 bg-slate-100 text-slate-500 text-xs sm:text-sm font-bold">+62</span>
                        <input type="tel" name="no_wa_sopir[]" class="form-input rounded-l-none target-wa form-input-readonly" readonly>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Nomor Plat Armada</label>
                    <input type="text" name="plat_armada[]" class="form-input uppercase new-plat target-plat form-input-readonly" readonly>
                </div>

                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4 bg-slate-50 p-3 sm:p-4 rounded-xl border border-slate-100 mt-1 sm:mt-2">
                    <div>
                        <label class="block text-[10px] sm:text-[11px] font-bold text-slate-500 mb-1 uppercase">Jumlah Rit</label>
                        <input type="number" name="jumlah_rit[]" class="form-input hitung-rit" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-[10px] sm:text-[11px] font-bold text-slate-500 mb-1 uppercase">Harga/Rit (Jual)</label>
                        <div class="input-group-custom">
                            <span class="input-group-text-custom">Rp</span>
                            <input type="number" name="harga_per_rit[]" class="form-input form-input-grouped hitung-harga" placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] sm:text-[11px] font-bold text-slate-500 mb-1 uppercase">HPP/Rit (Modal)</label>
                        <div class="input-group-custom">
                            <span class="input-group-text-custom">Rp</span>
                            <input type="number" name="hpp_per_rit[]" class="form-input form-input-grouped hitung-hpp" placeholder="0">
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        checkChanges();
    });

    function checkChanges() {
        const currentItemCount = document.querySelectorAll('.layanan-block').length;
        const oldArmadas = document.querySelectorAll('.old-armada');
        const newArmadas = document.querySelectorAll('.new-armada');
        const oldSopirs = document.querySelectorAll('.old-sopir');
        const newSopirs = document.querySelectorAll('.new-sopir');
        const oldPlats = document.querySelectorAll('.old-plat');
        const newPlats = document.querySelectorAll('.new-plat');

        let isChanged = false;

        if (currentItemCount !== originalItemCount) {
            isChanged = true;
        }

        if (!isChanged) {
            for (let i = 0; i < oldArmadas.length; i++) {
                if (oldArmadas[i].value !== newArmadas[i].value) {
                    isChanged = true;
                    break;
                }

                const hasOldSopir = oldSopirs[i].value.trim() !== "";
                if (hasOldSopir) {
                    if (oldSopirs[i].value !== newSopirs[i].value || 
                        oldPlats[i].value.toUpperCase() !== newPlats[i].value.toUpperCase()) {
                        isChanged = true;
                        break;
                    }
                }
            }
        }

        if (isChanged) {
            containerAlasan.classList.add('bg-red-50', 'border-red-200');
            containerAlasan.classList.remove('bg-slate-50', 'border-slate-200');
            labelAlasan.classList.add('text-red-700');
            iconAlasan.className = 'fa-solid fa-circle-exclamation text-red-500';
            inputAlasan.classList.add('reason-required');
            errorText.classList.remove('hidden');
            inputAlasan.setAttribute('required', 'required');
        } else {
            containerAlasan.classList.remove('bg-red-50', 'border-red-200');
            containerAlasan.classList.add('bg-slate-50', 'border-slate-200');
            labelAlasan.classList.remove('text-red-700');
            iconAlasan.className = 'fa-solid fa-circle-info text-blue-500';
            inputAlasan.classList.remove('reason-required');
            errorText.classList.add('hidden');
            inputAlasan.removeAttribute('required');
        }
    }

    function hitungTotalOtomatis() {
        const rits = document.querySelectorAll('.hitung-rit');
        const hargas = document.querySelectorAll('.hitung-harga');
        const hpps = document.querySelectorAll('.hitung-hpp');
        
        let totalPendapatan = 0;
        let totalKeuntungan = 0;

        rits.forEach((ritInput, i) => {
            const rit = parseFloat(ritInput.value) || 0;
            const harga = parseFloat(hargas[i].value) || 0;
            const hpp = parseFloat(hpps[i].value) || 0;

            const subPendapatan = rit * harga;
            const subKeuntungan = subPendapatan - (rit * hpp);

            totalPendapatan += subPendapatan;
            totalKeuntungan += subKeuntungan;
        });

        document.getElementById('hasil_pendapatan').innerText = 'Rp ' + totalPendapatan.toLocaleString('id-ID');
        document.getElementById('hasil_keuntungan').innerText = 'Rp ' + totalKeuntungan.toLocaleString('id-ID');
    }

    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('hitung-rit') || 
            e.target.classList.contains('hitung-harga') || 
            e.target.classList.contains('hitung-hpp')) {
            hitungTotalOtomatis();
        }
        
        if (e.target.classList.contains('new-armada') || e.target.classList.contains('new-plat')) {
            checkChanges();
        }
    });

    // REVISI: Trigger event saat halaman diload untuk mengisi harga yang kosong (0)
    window.onload = function() {
        const selectsPickup = document.querySelectorAll('.select-jasa-pickup');
        const selectsDump = document.querySelectorAll('.select-jasa-dump');
        
        selectsPickup.forEach(select => {
            if(select.value !== "") syncJasa(select, 'pickup');
        });
        
        selectsDump.forEach(select => {
            if(select.value !== "") syncJasa(select, 'dump');
        });

        hitungTotalOtomatis();
        checkChanges();
    };

    form.onsubmit = function(e) {
        checkChanges();

        const inputs = document.querySelectorAll('.real-jasa-input');
        if(inputs.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Data Layanan Kosong',
                text: 'Order harus memiliki minimal 1 jasa layanan!',
                customClass: { popup: 'rounded-2xl shadow-lg border border-red-100', title: 'font-heading font-bold text-slate-900' }
            });
            return false;
        }

        let isValid = true;
        inputs.forEach(input => {
            if(input.value.trim() === '') {
                isValid = false;
            }
        });

        if(!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Form Belum Lengkap',
                text: 'Pastikan Anda telah memilih Jasa (Pickup ATAU Truk Dump) pada setiap baris layanan!',
                customClass: { popup: 'rounded-2xl shadow-lg border border-red-100', title: 'font-heading font-bold text-slate-900' }
            });
            return false;
        }

        if (inputAlasan.hasAttribute('required') && !inputAlasan.value.trim()) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Alasan Wajib Diisi!',
                text: 'Terdeteksi perubahan armada/personel. Mohon isi alasan.',
                confirmButtonColor: '#ef4444',
                customClass: {
                    popup: 'rounded-2xl shadow-lg border border-red-100',
                    title: 'font-heading font-bold text-slate-900',
                    confirmButton: 'rounded-lg px-4 py-2 text-sm font-bold'
                }
            });
            inputAlasan.focus();
            return false;
        }
    };
</script>
@endsection