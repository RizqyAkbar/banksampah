@extends('admin.layout.app')

@section('title', 'Input Order')
@section('header_title', 'Input Order Baru')

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
        /* Style untuk input group agar Rp tidak menimpa angka */
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
        .form-input-readonly {
            background-color: #f1f5f9; color: #64748b; cursor: not-allowed;
        }
        .rounded-l-none { border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; }
        .layanan-item { position: relative; transition: all 0.3s ease; }
        .btn-remove { position: absolute; top: -10px; right: -10px; z-index: 10; }

        /* REVISI: Penyesuaian ketebalan input khusus untuk Mobile */
        @media (max-width: 640px) {
            .form-input { padding: 0.6rem 0.75rem; font-size: 0.8rem; }
            .input-group-text-custom { padding: 0 0.5rem; font-size: 0.7rem; }
            
            /* REVISI: Mengecilkan pop-up SweetAlert (Toast) di layar HP */
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
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Input Order</h1>

    {{-- Pemisahan Data Layanan (Pickup vs Dump) --}}
    @php
        $pickupServices = [];
        $dumpServices = [];
        foreach($masterLayanan as $l) {
            if(stripos($l->armada, 'dump') !== false || stripos($l->armada, 'truk') !== false) {
                $dumpServices[] = $l;
            } else {
                $pickupServices[] = $l;
            }
        }
    @endphp

    <div class="max-w-4xl mx-auto">
        <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            
            <div class="border-b border-slate-100 bg-slate-50/50 p-4 sm:p-6 flex items-center gap-3 sm:gap-4">
                <div class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
                    <i class="fa-solid fa-file-pen text-lg sm:text-xl"></i>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Detail Pemesanan</h2>
                    <p class="text-xs sm:text-sm text-slate-500">Masukkan informasi pelanggan dan pilih layanan.</p>
                </div>
            </div>

            <form action="{{ route('admin.input_order.store') }}" method="POST" class="p-4 sm:p-8" id="formInputOrder">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                    <div class="md:col-span-1">
                        <label for="nama_pelanggan" class="mb-1.5 sm:mb-2 block text-xs sm:text-sm font-semibold text-slate-700">Nama Pelanggan <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_pelanggan" name="nama_pelanggan" placeholder="Ex: Bapak Budi Santoso" class="form-input" required>
                    </div>

                    <div class="md:col-span-1">
                        <label for="no_wa" class="mb-1.5 sm:mb-2 block text-xs sm:text-sm font-semibold text-slate-700">No. WhatsApp <span class="text-red-500">*</span></label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-3 sm:px-4 rounded-l-xl border border-r-0 border-slate-200 bg-slate-100 text-slate-500 font-bold text-xs sm:text-sm">+62</span>
                            <input type="tel" id="no_wa" name="no_wa" placeholder="81234567890" class="form-input rounded-l-none" oninput="formatPhoneNumber(this)" required>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat" class="mb-1.5 sm:mb-2 block text-xs sm:text-sm font-semibold text-slate-700">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea id="alamat" name="alamat" rows="2" placeholder="Masukkan alamat lengkap lokasi..." class="form-input" required></textarea>
                    </div>

                    <div class="md:col-span-1">
                        <label for="penjadwalan" class="mb-1.5 sm:mb-2 block text-xs sm:text-sm font-semibold text-slate-700">Penjadwalan (Tanggal) <span class="text-red-500">*</span></label>
                        <input type="date" id="penjadwalan" name="penjadwalan" class="form-input" required>
                    </div>

                    <div class="md:col-span-1">
                        <label for="link_maps" class="mb-1.5 sm:mb-2 block text-xs sm:text-sm font-semibold text-slate-700">Link Google Maps <span class="text-slate-400 font-normal ml-1">(Opsional)</span></label>
                        <input type="url" id="link_maps" name="link_maps" placeholder="Paste link maps..." class="form-input">
                    </div>

                    <div class="md:col-span-2">
                        <label for="catatan" class="mb-1.5 sm:mb-2 block text-xs sm:text-sm font-semibold text-slate-700">Catatan Khusus <span class="text-slate-400 font-normal ml-1">(Opsional)</span></label>
                        <textarea id="catatan" name="catatan" rows="2" placeholder="Ex: Request diambil sebelum jam 3 sore..." class="form-input"></textarea>
                    </div>
                </div>

                <div class="md:col-span-2 py-1 sm:py-2 mb-4">
                    <hr class="border-slate-100">
                </div>

                {{-- MULTIPLE LAYANAN --}}
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <h3 class="text-xs sm:text-sm font-bold text-slate-400 uppercase tracking-widest">Daftar Layanan Jasa</h3>
                        <button type="button" id="add-layanan" class="text-[10px] sm:text-xs font-bold text-green-600 hover:text-green-700 px-3 py-1.5 bg-green-50 rounded-lg transition-colors">
                            <i class="fa-solid fa-plus mr-1"></i> Tambah Jasa
                        </button>
                    </div>

                    <div id="layanan-container" class="space-y-4 sm:space-y-5">
                        {{-- Baris Pertama Default --}}
                        <div class="layanan-item p-4 sm:p-5 rounded-2xl border border-slate-100 bg-slate-50/50">
                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-3">*Pilih salah satu jasa (Pickup ATAU Truk Dump)</p>
                            
                            {{-- REVISI: Dua Pilihan Select Bersandingan --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 mb-4">
                                <div class="jasa-select-wrapper">
                                    <label class="mb-1.5 sm:mb-2 block text-[10px] font-bold text-blue-600 uppercase">1. Jasa Pickup</label>
                                    <select class="form-input select-jasa-pickup border-blue-100 focus:border-blue-400" onchange="syncJasa(this, 'pickup')">
                                        <option value="">-- Pilih Jasa Pickup --</option>
                                        @foreach($pickupServices as $layanan)
                                            <option value="{{ $layanan->nama_layanan }}" 
                                                    data-armada="{{ $layanan->armada }}"
                                                    data-dasar="{{ (int)$layanan->harga_dasar }}" 
                                                    data-jual="{{ (int)$layanan->harga_jual }}">
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
                                                    data-jual="{{ (int)$layanan->harga_jual }}">
                                                {{ $layanan->nama_layanan }} ({{ $layanan->armada }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Hidden Input untuk dikirim ke Backend --}}
                                <input type="text" name="layanan[]" class="real-jasa-input absolute w-0 h-0 opacity-0 pointer-events-none" tabindex="-1">
                            </div>

                            {{-- Hasil Auto Fill --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 bg-white/50 p-3 sm:p-4 rounded-xl border border-slate-200/50 items-end">
                                <div class="w-full">
                                    <label class="mb-1.5 sm:mb-2 block text-[10px] font-bold text-slate-400 uppercase">Jenis Armada</label>
                                    <input type="text" name="armada[]" class="form-input input-armada form-input-readonly" placeholder="Otomatis mengikuti jasa..." readonly required>
                                </div>
                                <div class="w-full">
                                    <label class="mb-1.5 sm:mb-2 block text-[10px] font-bold text-slate-400 uppercase">Harga Dasar (HPP)</label>
                                    <div class="input-group-custom">
                                        <span class="input-group-text-custom">Rp</span>
                                        <input type="number" name="harga_dasar[]" class="form-input form-input-grouped input-harga-dasar" placeholder="0" required>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <label class="mb-1.5 sm:mb-2 block text-[10px] font-bold text-slate-400 uppercase">Harga Jual</label>
                                    <div class="input-group-custom">
                                        <span class="input-group-text-custom">Rp</span>
                                        <input type="number" name="harga_jual[]" class="form-input form-input-grouped input-harga-jual" placeholder="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row items-center justify-end gap-2 sm:gap-3 pt-4 sm:pt-6 border-t border-slate-100">
                    <button type="reset" class="w-full sm:w-auto px-4 sm:px-6 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 bg-white hover:bg-slate-50 transition-colors">
                        Bersihkan Form
                    </button>
                    <button type="submit" class="w-full sm:w-auto px-4 sm:px-6 py-3 sm:py-2.5 rounded-xl bg-[#00C264] text-white text-xs sm:text-sm font-semibold hover:bg-[#009B4E] shadow-[0_8px_20px_rgba(0,194,100,0.2)] transition-all transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-floppy-disk mr-1.5 sm:mr-2"></i> Simpan Order
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        const container = document.getElementById('layanan-container');
        const addButton = document.getElementById('add-layanan');

        // REVISI: Fungsi baru untuk mengelola dua select saling melengkapi
        function syncJasa(selectElement, type) {
            const wrapper = selectElement.closest('.layanan-item');
            const pickupSelect = wrapper.querySelector('.select-jasa-pickup');
            const dumpSelect = wrapper.querySelector('.select-jasa-dump');
            const realInput = wrapper.querySelector('.real-jasa-input');
            const inputArmada = wrapper.querySelector('.input-armada');
            const inputHpp = wrapper.querySelector('.input-harga-dasar');
            const inputJual = wrapper.querySelector('.input-harga-jual');

            // Reset select yang lain jika salah satu dipilih
            if (type === 'pickup' && selectElement.value !== "") {
                dumpSelect.value = "";
            } else if (type === 'dump' && selectElement.value !== "") {
                pickupSelect.value = "";
            }

            // Tentukan mana yang sedang aktif
            const activeSelect = (pickupSelect.value !== "") ? pickupSelect : ((dumpSelect.value !== "") ? dumpSelect : null);

            if (!activeSelect || activeSelect.value === "") {
                realInput.value = '';
                inputArmada.value = '';
                inputHpp.value = '';
                inputJual.value = '';
            } else {
                const selectedOption = activeSelect.selectedOptions[0];
                realInput.value = activeSelect.value;
                inputArmada.value = selectedOption.getAttribute('data-armada') || '-';
                inputHpp.value = selectedOption.getAttribute('data-dasar') || 0;
                inputJual.value = selectedOption.getAttribute('data-jual') || 0;
            }
        }

        addButton.addEventListener('click', () => {
            const newItem = document.createElement('div');
            newItem.className = 'layanan-item p-4 sm:p-5 rounded-2xl border border-green-100 bg-green-50/30 animate-in fade-in slide-in-from-top-4 duration-300';
            
            newItem.innerHTML = `
                <button type="button" class="btn-remove h-6 w-6 rounded-full bg-rose-500 text-white flex items-center justify-center hover:bg-rose-600 shadow-sm transition-all" onclick="this.parentElement.remove()">
                    <i class="fa-solid fa-xmark text-[10px]"></i>
                </button>
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
                    <input type="text" name="layanan[]" class="real-jasa-input absolute w-0 h-0 opacity-0 pointer-events-none" tabindex="-1">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 bg-white/50 p-3 sm:p-4 rounded-xl border border-slate-200/50 items-end">
                    <div class="w-full">
                        <label class="mb-1.5 sm:mb-2 block text-[10px] font-bold text-slate-400 uppercase">Jenis Armada</label>
                        <input type="text" name="armada[]" class="form-input input-armada form-input-readonly" placeholder="Otomatis..." readonly required>
                    </div>
                    <div class="w-full">
                        <label class="mb-1.5 sm:mb-2 block text-[10px] font-bold text-slate-400 uppercase">Harga Dasar (HPP)</label>
                        <div class="input-group-custom">
                            <span class="input-group-text-custom">Rp</span>
                            <input type="number" name="harga_dasar[]" class="form-input form-input-grouped input-harga-dasar" placeholder="0" required>
                        </div>
                    </div>
                    <div class="w-full">
                        <label class="mb-1.5 sm:mb-2 block text-[10px] font-bold text-slate-400 uppercase">Harga Jual</label>
                        <div class="input-group-custom">
                            <span class="input-group-text-custom">Rp</span>
                            <input type="number" name="harga_jual[]" class="form-input form-input-grouped input-harga-jual" placeholder="0" required>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
        });

        function formatPhoneNumber(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.startsWith('0')) value = value.substring(1);
            if (value.startsWith('62')) value = value.substring(2);
            input.value = value;
        }

        // REVISI: Validasi sebelum submit agar memastikan jasa sudah dipilih
        document.getElementById('formInputOrder').addEventListener('submit', function(e) {
            const inputs = document.querySelectorAll('.real-jasa-input');
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
            }
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}',
                timer: 3000, toast: true, position: 'top-end', showConfirmButton: false,
                customClass: { popup: 'rounded-2xl shadow-lg border border-green-100', title: 'font-heading font-bold text-slate-900' }
            });
        @endif
    </script>
@endsection