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

        @media (max-width: 640px) {
            .form-input { padding: 0.6rem 0.75rem; font-size: 0.8rem; }
            .input-group-text-custom { padding: 0 0.5rem; font-size: 0.7rem; }
            
            .swal2-popup {
                padding: 1rem !important;
                width: auto !important;
                max-width: 90vw !important;
            }
            .swal2-title {
                font-size: 1.2rem !important;
            }
            .swal2-html-container {
                font-size: 0.85rem !important;
            }
        }

        /* Indikator Loading Auto-fill */
        #loading-indicator {
            display: none;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #00C264;
        }
        .auto-filled {
            animation: pulse-green 1.5s ease-out;
        }
        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(0, 194, 100, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(0, 194, 100, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 194, 100, 0); }
        }

        /* Styling Modal Recall */
        .modal-recall-row:hover { background-color: #f8fafc; }
        .search-recall-input {
            width: 100%; border-radius: 0.75rem; border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem 0.75rem 2.5rem; font-size: 0.875rem; outline: none;
        }
        .search-recall-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    </style>
@endsection

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Input Order</h1>

    <div class="max-w-4xl mx-auto" x-data="recallModal">
        <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            
            <div class="border-b border-slate-100 bg-slate-50/50 p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
                        <i class="fa-solid fa-file-pen text-lg sm:text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Detail Pemesanan</h2>
                        <p class="text-xs sm:text-sm text-slate-500">Masukkan informasi pelanggan dan estimasi layanan.</p>
                    </div>
                </div>

                {{-- TOMBOL RECALL DI HEADER FORM --}}
                <button type="button" @click="openModal()" class="w-full sm:w-auto px-4 py-2 bg-blue-50 text-blue-600 border border-blue-200 rounded-xl text-xs font-bold hover:bg-blue-600 hover:text-white transition-all shadow-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-bolt text-yellow-500"></i> Cari Pelanggan (Recall)
                </button>
            </div>

            <form action="{{ route('admin.input_order.store') }}" method="POST" class="p-4 sm:p-8" id="formInputOrder">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                    
                    <div class="md:col-span-1">
                        <label for="no_wa" class="mb-1.5 sm:mb-2 block text-xs sm:text-sm font-semibold text-slate-700">No. WhatsApp <span class="text-red-500">*</span></label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-3 sm:px-4 rounded-l-xl border border-r-0 border-slate-200 bg-slate-100 text-slate-500 font-bold text-xs sm:text-sm">+62</span>
                            <input type="tel" id="no_wa" name="no_wa" value="{{ isset($recallCustomer) ? ltrim(substr($recallCustomer->no_wa, 2), '0') : '' }}" placeholder="81234567890" class="form-input rounded-l-none" required>
                            <i id="loading-indicator" class="fa-solid fa-circle-notch fa-spin text-sm"></i>
                        </div>
                        @if(isset($recallCustomer))
                            <p id="status-pelanggan-badge" class="text-[10px] mt-1.5 font-bold text-green-600 block animate-in fade-in slide-in-from-top-1">
                                <i class="fa-solid fa-bolt"></i> Data Direcall ({{ $recallCustomer->status_pelanggan }})
                            </p>
                        @else
                            <p id="status-pelanggan-badge" class="text-[10px] mt-1.5 font-bold hidden"></p>
                        @endif
                    </div>

                    <div class="md:col-span-1">
                        <label for="nama_pelanggan" class="mb-1.5 sm:mb-2 block text-xs sm:text-sm font-semibold text-slate-700">Nama Pelanggan <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_pelanggan" name="nama_pelanggan" value="{{ $recallCustomer->nama_pelanggan ?? '' }}" placeholder="Ex: Bapak Budi Santoso" class="form-input" required>
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat" class="mb-1.5 sm:mb-2 block text-xs sm:text-sm font-semibold text-slate-700">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea id="alamat" name="alamat" rows="2" placeholder="Masukkan alamat lengkap lokasi..." class="form-input" required>{{ $recallCustomer->alamat ?? '' }}</textarea>
                    </div>

                    <div class="md:col-span-1">
                        <label for="penjadwalan" class="mb-1.5 sm:mb-2 block text-xs sm:text-sm font-semibold text-slate-700">Penjadwalan (Tanggal) <span class="text-red-500">*</span></label>
                        <input type="date" id="penjadwalan" name="penjadwalan" class="form-input" required>
                    </div>

                    <div class="md:col-span-1">
                        <label for="link_maps" class="mb-1.5 sm:mb-2 block text-xs sm:text-sm font-semibold text-slate-700">Link Google Maps <span class="text-slate-400 font-normal ml-1">(Opsional)</span></label>
                        <input type="url" id="link_maps" name="link_maps" value="{{ $recallCustomer->link_maps ?? '' }}" placeholder="Paste link maps..." class="form-input">
                    </div>

                    <div class="md:col-span-2">
                        <label for="catatan" class="mb-1.5 sm:mb-2 block text-xs sm:text-sm font-semibold text-slate-700">Catatan Khusus <span class="text-slate-400 font-normal ml-1">(Opsional)</span></label>
                        <textarea id="catatan" name="catatan" rows="2" placeholder="Ex: Request diambil sebelum jam 3 sore..." class="form-input"></textarea>
                    </div>
                </div>

                <div class="md:col-span-2 py-1 sm:py-2 mb-4">
                    <hr class="border-slate-100">
                </div>

                <div class="space-y-3 sm:space-y-4">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <h3 class="text-xs sm:text-sm font-bold text-slate-400 uppercase tracking-widest">Jenis Layanan & Estimasi</h3>
                        <button type="button" id="add-layanan" class="text-[10px] sm:text-xs font-bold text-green-600 hover:text-green-700 px-3 py-1.5 bg-green-50 rounded-lg transition-colors shadow-sm">
                            <i class="fa-solid fa-plus mr-1"></i> Tambah Estimasi
                        </button>
                    </div>

                    <div id="layanan-container" class="space-y-4 sm:space-y-5">
                        <div class="layanan-item p-4 sm:p-5 rounded-2xl border border-slate-100 bg-slate-50/50">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-1.5 sm:mb-2 block text-xs font-bold text-slate-700">Pilih Jenis Jasa & Armada <span class="text-red-500">*</span></label>
                                    <select class="form-input border-slate-200 focus:border-green-400 select-jasa" onchange="updateArmada(this)" required>
                                        <option value="">-- Pilih Estimasi Jasa --</option>
                                        @foreach($masterLayanan as $layanan)
                                            <option value="{{ $layanan->nama_layanan }}" data-armada="{{ $layanan->armada }}">
                                                {{ $layanan->nama_layanan }} ({{ $layanan->armada }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="layanan[]" class="input-layanan">
                                    <input type="hidden" name="armada[]" class="input-armada">
                                </div>
                                <div>
                                    <label class="mb-1.5 sm:mb-2 block text-xs font-bold text-slate-700">Estimasi Jumlah (Rit) <span class="text-red-500">*</span></label>
                                    <input type="number" name="jumlah_rit[]" class="form-input" placeholder="Misal: 2" min="1" required>
                                </div>
                            </div>
                            <input type="hidden" name="harga_dasar[]" value="0">
                            <input type="hidden" name="harga_jual[]" value="0">
                        </div>
                    </div>
                </div>

                <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row items-center justify-end gap-2 sm:gap-3 pt-4 sm:pt-6 border-t border-slate-100">
                    <button type="reset" class="w-full sm:w-auto px-4 sm:px-6 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 bg-white hover:bg-slate-50 transition-colors">
                        Bersihkan Form
                    </button>
                    {{-- TOMBOL 1: SIMPAN SAJA --}}
                    <button type="submit" name="action" value="save_only" class="w-full sm:w-auto px-4 sm:px-6 py-3 sm:py-2.5 rounded-xl bg-slate-800 text-white text-xs sm:text-sm font-semibold hover:bg-slate-900 shadow-lg transition-all transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-floppy-disk mr-1.5 sm:mr-2"></i> Simpan Saja
                    </button>
                    {{-- TOMBOL 2: SIMPAN & KIRIM WA --}}
                    <button type="submit" name="action" value="save_and_wa" class="w-full sm:w-auto px-4 sm:px-6 py-3 sm:py-2.5 rounded-xl bg-[#00C264] text-white text-xs sm:text-sm font-semibold hover:bg-[#009B4E] shadow-[0_8px_20px_rgba(0,194,100,0.2)] transition-all transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-paper-plane mr-1.5 sm:mr-2"></i> Simpan & Kirim WA
                    </button>
                </div>
            </form>
        </div>

        {{-- MODAL RECALL CUSTOMER (REVISI X-SHOW) --}}
        <div x-show="isOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-0 bg-slate-900/60 backdrop-blur-sm" x-transition.opacity x-cloak>
            <div @click.away="closeModal()" class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full sm:w-[90%] max-h-[90vh] overflow-hidden flex flex-col relative"
                 x-show="isOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95">
                
                {{-- Header Modal --}}
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                            <i class="fa-solid fa-users text-blue-500"></i> Cari Database Pelanggan
                        </h3>
                        <p class="text-[11px] text-slate-500 mt-1">Pilih pelanggan untuk auto-fill form order.</p>
                    </div>
                    <button @click="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                {{-- Search Bar --}}
                <div class="p-4 border-b border-slate-100 bg-white relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" x-model="searchQuery" @input="searchCustomers()" placeholder="Ketik nama atau nomor WA..." class="search-recall-input">
                </div>

                {{-- Loading State --}}
                <div x-show="isLoading" class="p-10 flex flex-col items-center justify-center gap-3">
                    <i class="fa-solid fa-circle-notch fa-spin text-3xl text-blue-500"></i>
                    <p class="text-xs font-bold text-slate-400">Mencari data...</p>
                </div>

                {{-- Daftar Customer --}}
                <div x-show="!isLoading" class="overflow-y-auto flex-1 p-2 bg-slate-50/50 min-h-[300px]">
                    
                    <template x-if="customers.length === 0 && searchQuery.length > 0">
                        <div class="text-center p-8">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-user-xmark text-2xl text-slate-400"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-600">Pelanggan tidak ditemukan</p>
                            <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci lain.</p>
                        </div>
                    </template>

                    <template x-if="customers.length === 0 && searchQuery.length === 0">
                        <div class="text-center p-8 opacity-60">
                            <i class="fa-solid fa-keyboard text-4xl text-slate-300 mb-3"></i>
                            <p class="text-sm font-bold text-slate-500">Ketik untuk mencari pelanggan...</p>
                        </div>
                    </template>

                    <template x-for="cust in customers" :key="cust.id">
                        <div class="modal-recall-row flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 border-b border-slate-100/60 bg-white mx-2 my-1 rounded-xl shadow-sm transition-colors cursor-pointer" @click="selectCustomer(cust)">
                            <div class="flex-1 min-w-0 pr-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="text-sm font-bold text-slate-900 truncate" x-text="cust.nama_pelanggan"></p>
                                    <span x-show="cust.status_pelanggan === 'Pelanggan'" class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-[9px] font-bold rounded uppercase tracking-wider"><i class="fa-solid fa-star text-[8px]"></i> Pelanggan</span>
                                </div>
                                <p class="text-xs font-bold text-green-600 mb-1">+<span x-text="cust.no_wa"></span></p>
                                <p class="text-[11px] text-slate-500 truncate" x-text="cust.alamat"></p>
                            </div>
                            <button type="button" class="mt-3 sm:mt-0 px-4 py-2 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg hover:bg-blue-600 hover:text-white transition-colors border border-blue-100 whitespace-nowrap">
                                Pilih Pelanggan
                            </button>
                        </div>
                    </template>

                </div>
            </div>
        </div>

    </div>
@endsection

@section('extra_js')
    <script>
        const container = document.getElementById('layanan-container');
        const addButton = document.getElementById('add-layanan');

        window.updateArmada = function(selectElement) {
            const wrapper = selectElement.closest('.grid');
            const inputLayanan = wrapper.querySelector('.input-layanan');
            const inputArmada = wrapper.querySelector('.input-armada');
            
            if (selectElement.value === "") {
                inputLayanan.value = "";
                inputArmada.value = "";
            } else {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                inputLayanan.value = selectElement.value;
                inputArmada.value = selectedOption.getAttribute('data-armada');
            }
        };

        addButton.addEventListener('click', () => {
            const newItem = document.createElement('div');
            newItem.className = 'layanan-item p-4 sm:p-5 rounded-2xl border border-green-100 bg-green-50/30 animate-in fade-in slide-in-from-top-4 duration-300 mt-4 relative';
            
            newItem.innerHTML = `
                <button type="button" class="btn-remove h-6 w-6 rounded-full bg-rose-500 text-white flex items-center justify-center hover:bg-rose-600 shadow-sm transition-all" onclick="this.parentElement.remove()">
                    <i class="fa-solid fa-xmark text-[10px]"></i>
                </button>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1.5 sm:mb-2 block text-xs font-bold text-slate-700">Pilih Jenis Jasa & Armada <span class="text-red-500">*</span></label>
                        <select class="form-input border-slate-200 focus:border-green-400 select-jasa" onchange="updateArmada(this)" required>
                            <option value="">-- Pilih Estimasi Jasa --</option>
                            @foreach($masterLayanan as $layanan)
                                <option value="{{ $layanan->nama_layanan }}" data-armada="{{ $layanan->armada }}">
                                    {{ $layanan->nama_layanan }} ({{ $layanan->armada }})
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="layanan[]" class="input-layanan">
                        <input type="hidden" name="armada[]" class="input-armada">
                    </div>
                    <div>
                        <label class="mb-1.5 sm:mb-2 block text-xs font-bold text-slate-700">Estimasi Jumlah (Rit) <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_rit[]" class="form-input" placeholder="Misal: 2" min="1" required>
                    </div>
                </div>
                
                <input type="hidden" name="harga_dasar[]" value="0">
                <input type="hidden" name="harga_jual[]" value="0">
            `;
            container.appendChild(newItem);
        });

        // LOGIKA AUTO-FILL INPUT BIASA
        const inputNoWa = document.getElementById('no_wa');
        const inputNama = document.getElementById('nama_pelanggan');
        const inputAlamat = document.getElementById('alamat');
        const inputMaps = document.getElementById('link_maps');
        const loadingIndicator = document.getElementById('loading-indicator');
        const badgeStatus = document.getElementById('status-pelanggan-badge');
        let typingTimer;

        inputNoWa.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.startsWith('0')) value = value.substring(1);
            if (value.startsWith('62')) value = value.substring(2);
            this.value = value;

            clearTimeout(typingTimer);
            badgeStatus.classList.add('hidden');
            
            if (value.length >= 9) { 
                loadingIndicator.style.display = 'block';
                
                typingTimer = setTimeout(async () => {
                    try {
                        const response = await fetch(`{{ route('admin.customer.search') }}?no_wa=${value}`);
                        const result = await response.json();

                        if (result.success && result.data) {
                            inputNama.value = result.data.nama_pelanggan;
                            inputAlamat.value = result.data.alamat;
                            if(result.data.link_maps) inputMaps.value = result.data.link_maps;
                            
                            badgeStatus.innerHTML = `<i class="fa-solid fa-circle-check"></i> Data Ditemukan (${result.data.status_pelanggan})`;
                            badgeStatus.className = 'text-[10px] mt-1.5 font-bold text-green-600 block animate-in fade-in slide-in-from-top-1';

                            [inputNama, inputAlamat, inputMaps].forEach(el => {
                                el.classList.remove('auto-filled');
                                void el.offsetWidth;
                                el.classList.add('auto-filled');
                            });
                        } else {
                            badgeStatus.innerHTML = '<i class="fa-solid fa-circle-info"></i> Nomor Baru';
                            badgeStatus.className = 'text-[10px] mt-1.5 font-bold text-slate-400 block animate-in fade-in slide-in-from-top-1';
                        }
                    } catch (error) {
                        console.error('Error fetching customer data:', error);
                    } finally {
                        loadingIndicator.style.display = 'none';
                    }
                }, 800);
            } else {
                loadingIndicator.style.display = 'none';
            }
        });

        // REVISI: LOGIKA ALPINE JS UNTUK MODAL RECALL (Lebih Stabil)
        document.addEventListener('alpine:init', () => {
            Alpine.data('recallModal', () => ({
                isOpen: false,
                searchQuery: '',
                customers: [],
                isLoading: false,
                searchTimeout: null,

                openModal() {
                    this.isOpen = true;
                    this.searchQuery = '';
                    this.customers = [];
                    
                    setTimeout(() => {
                        const searchInput = document.querySelector('.search-recall-input');
                        if (searchInput) searchInput.focus();
                    }, 100);
                },

                closeModal() {
                    this.isOpen = false;
                },

                async searchCustomers() {
                    clearTimeout(this.searchTimeout);
                    
                    if (this.searchQuery.length < 2) {
                        this.customers = [];
                        return;
                    }

                    this.isLoading = true;
                    
                    this.searchTimeout = setTimeout(async () => {
                        try {
                            const response = await fetch(`/admin/customers/api-search?q=${encodeURIComponent(this.searchQuery)}`);
                            const result = await response.json();
                            this.customers = result.data || [];
                        } catch (error) {
                            console.error('Gagal mencari pelanggan', error);
                            this.customers = [];
                        } finally {
                            this.isLoading = false;
                        }
                    }, 500);
                },

                selectCustomer(cust) {
                    let noWa = cust.no_wa;
                    if (noWa.startsWith('62')) noWa = noWa.substring(2);
                    
                    document.getElementById('no_wa').value = noWa;
                    document.getElementById('nama_pelanggan').value = cust.nama_pelanggan;
                    document.getElementById('alamat').value = cust.alamat;
                    document.getElementById('link_maps').value = cust.link_maps || '';

                    const badge = document.getElementById('status-pelanggan-badge');
                    badge.innerHTML = `<i class="fa-solid fa-bolt"></i> Data Direcall (${cust.status_pelanggan})`;
                    badge.className = 'text-[10px] mt-1.5 font-bold text-blue-600 block animate-in fade-in slide-in-from-top-1';

                    [document.getElementById('nama_pelanggan'), document.getElementById('alamat'), document.getElementById('link_maps')].forEach(el => {
                        el.classList.remove('auto-filled');
                        void el.offsetWidth;
                        el.classList.add('auto-filled');
                    });

                    this.closeModal();
                    
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success',
                        title: 'Data Pelanggan Berhasil Dimuat!', showConfirmButton: false, timer: 3000,
                        customClass: { popup: 'rounded-xl shadow-md border border-green-100', title: 'font-bold text-sm' }
                    });
                }
            }));
        });

        // Alert Notifikasi WA
        @if(session('success'))
            Swal.fire({
                icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}',
                showCancelButton: true, confirmButtonColor: '#25D366', cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fa-brands fa-whatsapp mr-1"></i> Buka di WhatsApp', cancelButtonText: 'Tutup',
                customClass: { popup: 'rounded-2xl shadow-lg border border-green-100', title: 'font-heading font-bold text-slate-900' }
            }).then((result) => {
                @if(session('wa_link'))
                if (result.isConfirmed) { window.open('{!! session('wa_link') !!}', '_blank'); }
                @endif
            });
        @endif
    </script>
@endsection