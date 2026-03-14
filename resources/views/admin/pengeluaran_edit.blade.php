@extends('admin.layout.app')

@section('title', 'Edit Pengeluaran')
@section('header_title', 'Manajemen Pengeluaran')

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Edit Pengeluaran</h1>

    {{-- Gunakan data dari $pengeluaran untuk inisialisasi Alpine.js --}}
    <div class="max-w-2xl mx-auto w-full" 
         x-data="{ 
            kategori: '{{ in_array($pengeluaran->kategori, ['Operasional', 'Cashback']) ? $pengeluaran->kategori : 'Lainnya' }}', 
            kategoriLainnya: '{{ !in_array($pengeluaran->kategori, ['Operasional', 'Cashback']) ? $pengeluaran->kategori : '' }}' 
         }">
        
        <div class="bg-white rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-green-50 rounded-full opacity-50"></div>

            <div class="relative">
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-slate-900 mb-1 font-heading">Edit Catatan Pengeluaran</h3>
                    <p class="text-sm text-slate-500">Perbarui detail pengeluaran yang sudah tercatat.</p>
                </div>
                
                {{-- UPDATE: Gunakan route update dan method PUT --}}
                <form action="{{ route('admin.pengeluaran.update', $pengeluaran->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Tanggal Pengeluaran</label>
                        <input type="date" name="tanggal" value="{{ $pengeluaran->tanggal }}" 
                            class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-semibold focus:ring-2 focus:ring-green-500 transition-all outline-none" 
                            required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Kategori</label>
                        <div class="relative">
                            <select name="kategori" x-model="kategori"
                                class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-semibold focus:ring-2 focus:ring-green-500 transition-all outline-none appearance-none">
                                <option value="Operasional">Operasional (Solar, Makan, dll)</option>
                                <option value="Cashback">Cashback Pelanggan</option>
                                <option value="Lainnya">Lainnya (Ketik Manual)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div x-show="kategori === 'Lainnya'" 
                         x-transition:enter="transition ease-out duration-300"
                         class="mt-2">
                        <label class="block text-xs font-bold text-green-600 uppercase tracking-widest mb-2">Nama Kategori Manual</label>
                        <input type="text" name="kategori_manual" x-model="kategoriLainnya" 
                            class="w-full bg-green-50/50 border border-green-100 rounded-2xl p-4 text-sm font-semibold focus:ring-2 focus:ring-green-500 transition-all outline-none"
                            :required="kategori === 'Lainnya'">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nominal (Rp)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 font-bold text-sm">
                                Rp
                            </div>
                            <input type="number" name="nominal" value="{{ $pengeluaran->nominal }}"
                                class="w-full bg-slate-50 border-none rounded-2xl p-4 pl-12 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-green-500 transition-all outline-none" 
                                required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Keterangan / Catatan</label>
                        <textarea name="keterangan" rows="3"
                            class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-semibold focus:ring-2 focus:ring-green-500 transition-all outline-none resize-none">{{ $pengeluaran->keterangan }}</textarea>
                    </div>

                    <div class="flex gap-4 pt-4">
                        {{-- PERBAIKAN: Tombol Batal arahkan ke halaman utama (index) --}}
                        <a href="{{ route('admin.pengeluaran') }}" 
                            class="flex-1 px-4 py-4 rounded-2xl text-sm font-bold text-slate-400 text-center hover:bg-slate-100 transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                            class="flex-[1.5] px-4 py-4 bg-green-600 text-white rounded-2xl text-sm font-bold shadow-[0_10px_20px_rgba(22,163,74,0.2)] hover:bg-green-700 transition-all active:scale-95">
                            <i class="fa-solid fa-floppy-disk mr-2"></i> Update Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection