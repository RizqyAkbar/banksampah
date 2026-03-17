@extends('admin.layout.app')

@section('title', 'Input Pengeluaran')
@section('header_title', 'Manajemen Pengeluaran')

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Input Pengeluaran</h1>

    {{-- Inisialisasi Alpine.js dengan nilai kosong --}}
    <div class="max-w-2xl mx-auto w-full" x-data="{ kategori: 'Operasional', kategoriLainnya: '' }">
        
        {{-- REVISI: Penyesuaian padding dan radius untuk Mobile (sm:...) --}}
        <div class="bg-white rounded-3xl sm:rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-5 sm:p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-green-50 rounded-full opacity-50"></div>

            <div class="relative">
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-1 font-heading">Catat Pengeluaran Baru</h3>
                    <p class="text-xs sm:text-sm text-slate-500">Masukkan detail pengeluaran operasional atau lainnya.</p>
                </div>
                
                {{-- UPDATE: Arahkan ke route STORE dan hapus @method('PUT') --}}
                <form action="{{ route('admin.pengeluaran.store') }}" method="POST" class="space-y-4 sm:space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5 sm:mb-2">Tanggal Pengeluaran</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" 
                            class="w-full bg-slate-50 border-none rounded-xl sm:rounded-2xl p-3 sm:p-4 text-sm font-semibold focus:ring-2 focus:ring-green-500 transition-all outline-none" 
                            required>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5 sm:mb-2">Kategori</label>
                        <div class="relative">
                            <select name="kategori" x-model="kategori"
                                class="w-full bg-slate-50 border-none rounded-xl sm:rounded-2xl p-3 sm:p-4 text-sm font-semibold focus:ring-2 focus:ring-green-500 transition-all outline-none appearance-none">
                                <option value="Operasional">Operasional (Solar, Makan, dll)</option>
                                <option value="Cashback">Cashback Pelanggan</option>
                                <option value="Lainnya">Lainnya (Ketik Manual)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 sm:px-4 text-slate-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Input manual muncul jika pilih 'Lainnya' --}}
                    <div x-show="kategori === 'Lainnya'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         class="mt-2">
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5 sm:mb-2">Nama Kategori Manual</label>
                        <input type="text" name="kategori_manual" x-model="kategoriLainnya" placeholder="Sebutkan kategori..."
                            class="w-full bg-green-50/50 border border-green-100 rounded-xl sm:rounded-2xl p-3 sm:p-4 text-sm font-semibold focus:ring-2 focus:ring-green-500 transition-all outline-none"
                            :required="kategori === 'Lainnya'">
                    </div>

                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5 sm:mb-2">Nominal (Rp)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 sm:pl-4 pointer-events-none text-slate-400 font-bold text-sm">
                                Rp
                            </div>
                            <input type="number" name="nominal" placeholder="Contoh: 50000"
                                class="w-full bg-slate-50 border-none rounded-xl sm:rounded-2xl p-3 sm:p-4 pl-10 sm:pl-12 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-green-500 transition-all outline-none" 
                                required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5 sm:mb-2">Keterangan / Catatan</label>
                        <textarea name="keterangan" rows="3" placeholder="Tulis catatan di sini..."
                            class="w-full bg-slate-50 border-none rounded-xl sm:rounded-2xl p-3 sm:p-4 text-sm font-semibold focus:ring-2 focus:ring-green-500 transition-all outline-none resize-none"></textarea>
                    </div>

                    {{-- REVISI: Perbaikan gap dan padding tombol aksi di layar HP --}}
                    <div class="flex gap-2 sm:gap-4 pt-2 sm:pt-4">
                        <a href="{{ route('admin.pengeluaran') }}" 
                            class="flex-1 px-2 sm:px-4 py-3 sm:py-4 rounded-xl sm:rounded-2xl text-xs sm:text-sm font-bold text-slate-400 text-center hover:bg-slate-100 transition-colors flex items-center justify-center border border-transparent hover:border-slate-200">
                            Batal
                        </a>
                        <button type="submit" 
                            class="flex-[1.5] px-2 sm:px-4 py-3 sm:py-4 bg-green-600 text-white rounded-xl sm:rounded-2xl text-xs sm:text-sm font-bold shadow-[0_10px_20px_rgba(22,163,74,0.2)] hover:bg-green-700 transition-all active:scale-95 flex items-center justify-center text-center">
                            <i class="fa-solid fa-floppy-disk mr-1.5 sm:mr-2"></i> Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection