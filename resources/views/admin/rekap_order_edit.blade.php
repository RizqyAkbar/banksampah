@extends('admin.layout.app')

@section('title', 'Lengkapi Rekap')

{{-- NAVBAR CUSTOM SESUAI PERINTAH --}}
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
            <h1 class="font-heading text-2xl font-bold text-slate-900 hidden sm:block">Edit Data Rekap</h1>
        </div>
    </div>

    {{-- Tanggal di pojok kanan agar tetap rapi --}}
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
        width: 100%;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        color: #0f172a;
        transition: all 0.2s;
    }
    .form-input:focus {
        outline: none;
        border-color: #00C264;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(0, 194, 100, 0.1);
    }
    .form-input-icon-left {
        padding-left: 2.75rem !important;
    }
</style>
@endsection

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-heading text-2xl font-bold text-slate-900 sm:hidden">Lengkapi Rekap</h1>
        <span class="px-3 py-1 bg-{{ $order->status_pelanggan == 'Pelanggan Lama' ? 'blue' : 'green' }}-100 text-{{ $order->status_pelanggan == 'Pelanggan Lama' ? 'blue' : 'green' }}-700 font-bold rounded-full text-xs">
            {{ $order->status_pelanggan }}
        </span>
    </div>

    <div class="max-w-4xl mx-auto w-full">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">
            <form action="{{ route('admin.rekap_order.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 bg-slate-50 p-5 rounded-xl border border-slate-100">
                    <div>
                        <p class="text-xs text-slate-500 font-semibold mb-1 uppercase tracking-wider">Nama Pelanggan</p>
                        <p class="font-bold text-slate-800 text-lg">{{ $order->nama_pelanggan }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-semibold mb-1 uppercase tracking-wider">No WhatsApp</p>
                        <p class="font-bold text-slate-800 text-lg"><i class="fa-brands fa-whatsapp text-green-500 mr-1.5"></i>{{ $order->no_wa }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-semibold mb-1 uppercase tracking-wider">Penjadwalan</p>
                        <p class="font-bold text-slate-800 text-lg"><i class="fa-regular fa-calendar text-blue-500 mr-1.5"></i>{{ \Carbon\Carbon::parse($order->penjadwalan)->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Layanan</label>
                        <input type="text" name="jenis_layanan" value="{{ $order->jenis_layanan }}" class="form-input">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Armada</label>
                        <select name="armada" class="form-input">
                            <option value="Pickup" {{ $order->armada == 'Pickup' ? 'selected' : '' }}>Unit Pickup</option>
                            <option value="Truk Dump" {{ $order->armada == 'Truk Dump' ? 'selected' : '' }}>Truk Dump</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 py-2"><hr class="border-slate-100"></div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Rit</label>
                        <input type="number" id="jumlah_rit" name="jumlah_rit" value="{{ $order->jumlah_rit }}" class="form-input hitung-otomatis">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Per Rit (Rp)</label>
                        <input type="number" id="harga_per_rit" name="harga_per_rit" value="{{ $order->harga_per_rit }}" class="form-input hitung-otomatis">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">HPP Per Rit (Modal - Rp)</label>
                        <input type="number" id="hpp_per_rit" name="hpp_per_rit" value="{{ $order->hpp_per_rit }}" class="form-input hitung-otomatis">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan Khusus</label>
                        <textarea name="catatan" rows="2" class="form-input">{{ $order->catatan }}</textarea>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-100 pt-8">
                    <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-5">
                        <p class="text-blue-600 font-semibold text-sm mb-1 uppercase tracking-wider">Estimasi Pendapatan</p>
                        <h3 class="text-3xl font-bold text-blue-900" id="hasil_pendapatan">Rp 0</h3>
                    </div>
                    <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-5">
                        <p class="text-emerald-600 font-semibold text-sm mb-1 uppercase tracking-wider">Estimasi Laba Bersih</p>
                        <h3 class="text-3xl font-bold text-emerald-900" id="hasil_keuntungan">Rp 0</h3>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('admin.rekap_order') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</a>
                    <button type="submit" class="px-8 py-2.5 bg-[#00C264] text-white rounded-xl text-sm font-bold shadow-lg hover:bg-[#009B4E]">Simpan Rekap</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('extra_js')
<script>
    const inputRit = document.getElementById('jumlah_rit');
    const inputHarga = document.getElementById('harga_per_rit');
    const inputHpp = document.getElementById('hpp_per_rit');
    const txtPendapatan = document.getElementById('hasil_pendapatan');
    const txtKeuntungan = document.getElementById('hasil_keuntungan');

    function hitungOtomatis() {
        const rit = parseFloat(inputRit.value) || 0;
        const harga = parseFloat(inputHarga.value) || 0;
        const hpp = parseFloat(inputHpp.value) || 0;
        const pendapatan = rit * harga;
        const keuntungan = pendapatan - (rit * hpp);

        txtPendapatan.innerText = 'Rp ' + pendapatan.toLocaleString('id-ID');
        txtKeuntungan.innerText = 'Rp ' + keuntungan.toLocaleString('id-ID');
    }

    hitungOtomatis();
    document.querySelectorAll('.hitung-otomatis').forEach(input => {
        input.addEventListener('input', hitungOtomatis);
    });
</script>
@endsection