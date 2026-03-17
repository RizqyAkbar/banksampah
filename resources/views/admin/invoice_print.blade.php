<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice-{{ Str::slug($order->nama_pelanggan) }}-{{ str_replace(['/', '\\'], '-', $order->no_invoice) }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            -webkit-print-color-adjust: exact; 
            margin: 0 !important; 
            padding: 0 !important; 
        }
        
        /* Tampilan Desktop & Default untuk PDF */
        #invoice-card {
            width: 800px !important;
            margin: 0 auto !important;
            background: white !important;
        }

        /* REVISI: Tampilan Mobile agar Proporsional */
        @media (max-width: 768px) {
            #invoice-card {
                width: 95% !important; /* Mengikuti lebar layar HP */
                margin: 20px auto !important;
                padding: 20px !important; /* Perkecil padding agar konten tidak sesak */
                border-radius: 1.5rem !important;
            }
            .grid-cols-2 {
                grid-template-columns: 1fr !important; /* Stack ke bawah jika di HP */
                gap: 20px !important;
            }
            .text-right {
                text-align: left !important; /* Rata kiri agar lebih rapi di HP */
            }
            .flex-col.items-end {
                items-content: flex-start !important;
                align-items: flex-start !important;
            }
        }

        @media print {
            @page { size: A4; margin: 0; }
            body { background: white; }
            .no-print { display: none !important; }
            #invoice-card { 
                box-shadow: none !important; 
                border: none !important; 
                padding: 40px !important; 
                width: 100% !important; 
                margin: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-slate-50">
    
    @if(request('mode') != 'download')
    <div class="max-w-3xl mx-auto py-6 no-print flex justify-between items-center px-4">
        <button onclick="window.close()" class="text-slate-500 hover:text-slate-800 font-bold text-sm flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </button>
        <button onclick="downloadPDF()" class="bg-[#00C264] hover:bg-[#009B4E] text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-green-200 transition-all flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Simpan Sebagai PDF
        </button>
    </div>
    @endif

    <div id="invoice-card" class="bg-white p-10 sm:p-16 shadow-2xl rounded-[2rem] border border-slate-100 relative overflow-hidden my-10">
        
        {{-- Background Logo Watermark --}}
        <div class="absolute top-10 right-10 opacity-[0.03] pointer-events-none">
            <img src="{{ asset('images/logo1.png') }}" class="w-64 rotate-12">
        </div>

        {{-- Header --}}
        <div class="flex justify-between items-start mb-12 relative z-10 flex-wrap gap-4">
            <div class="flex items-center gap-5">
                <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="h-20 w-auto">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tighter uppercase leading-none">Juragan <span class="text-green-600">Urug</span></h1>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Jasa Urug Bangunan & Supplier Material</p>
                </div>
            </div>
            <div class="text-right sm:text-right w-full sm:w-auto">
                <h2 class="text-4xl font-black text-slate-200 tracking-tighter uppercase mb-2">Invoice</h2>
                <div class="text-sm font-bold text-slate-800">No. {{ $order->no_invoice }}</div>
                <div class="text-xs text-slate-500 mt-1">{{ \Carbon\Carbon::parse($order->penjadwalan)->format('d F Y') }}</div>
            </div>
        </div>

        {{-- Pelanggan --}}
        <div class="grid grid-cols-2 gap-10 mb-12 relative z-10">
            <div class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">Kepada Yth:</p>
                <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $order->nama_pelanggan }}</h3>
                <p class="text-xs text-slate-600 leading-relaxed max-w-[250px]">{{ $order->alamat }}</p>
                <p class="text-xs font-bold text-slate-800 mt-2">+{{ $order->no_wa }}</p>
            </div>
            <div class="flex flex-col justify-center text-right p-6 sm:text-right">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Metode Pembayaran:</p>
                <p class="text-sm font-bold text-slate-900 uppercase">Transfer Bank / Cash</p>
                <p class="text-[10px] text-slate-500 italic mt-1">*Pembayaran dilakukan setelah pengerjaan selesai.</p>
            </div>
        </div>

        {{-- Tabel Layanan --}}
        <div class="mb-12 relative z-10 overflow-x-auto">
            <table class="w-full min-w-[600px] sm:min-w-full">
                <thead>
                    <tr class="border-b-2 border-slate-900">
                        <th class="py-4 text-left text-xs font-bold text-slate-900 uppercase tracking-widest">Keterangan Layanan</th>
                        <th class="py-4 text-center text-xs font-bold text-slate-900 uppercase tracking-widest">Volume</th>
                        <th class="py-4 text-right text-xs font-bold text-slate-900 uppercase tracking-widest">Harga</th>
                        <th class="py-4 text-right text-xs font-bold text-slate-900 uppercase tracking-widest">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($order->items->where('status_item', 'Aktif') as $service)
                    <tr>
                        <td class="py-6">
                            <p class="font-bold text-slate-800 text-base">{{ $service->jenis_layanan }}</p>
                            <p class="text-[10px] text-slate-400 uppercase mt-1">Armada: {{ $service->armada }} | Plat: {{ $service->plat_armada ?? '-' }}</p>
                        </td>
                        <td class="py-6 text-center">
                            <span class="inline-block bg-slate-100 px-3 py-1 rounded-full text-xs font-bold text-slate-700">{{ $service->jumlah_rit }} Rit</span>
                        </td>
                        <td class="py-6 text-right text-sm text-slate-600">
                            Rp {{ number_format($service->harga_per_rit, 0, ',', '.') }}
                        </td>
                        <td class="py-6 text-right font-bold text-slate-900 text-base">
                            Rp {{ number_format($service->jumlah_rit * $service->harga_per_rit, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer Tabel (Total) --}}
        <div class="grid grid-cols-2 gap-10 items-start border-t-2 border-slate-900 pt-8 relative z-10">
            <div class="space-y-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi Pembayaran:</p>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-50 text-blue-700 font-bold text-[10px] px-2 py-1 rounded">BRI</div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">664001007976501</p>
                            <p class="text-[10px] text-slate-500 uppercase">a/n ACHMAD RIDWAN</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-600 text-white font-bold text-[10px] px-2 py-1 rounded">BCA</div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">4451858429</p>
                            <p class="text-[10px] text-slate-500 uppercase">a/n ACHMAD RIDWAN</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-end sm:items-end">
                <div class="w-full sm:w-64">
                    <div class="flex justify-between items-center mb-4 px-2">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Subtotal</span>
                        <span class="text-sm font-bold text-slate-900">Rp {{ number_format($order->total_pendapatan, 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-green-600 rounded-2xl p-5 text-white shadow-xl">
                        <p class="text-[10px] font-bold uppercase tracking-widest opacity-80 mb-1 text-center">Total Tagihan</p>
                        <p class="text-2xl font-black text-center tracking-tighter">Rp {{ number_format($order->total_pendapatan, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tanda Tangan --}}
        <div class="mt-16 flex justify-between items-end relative z-10 gap-4 flex-wrap sm:flex-nowrap">
            <div class="max-w-[300px]">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Catatan Tambahan:</p>
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 text-[11px] text-slate-600 leading-relaxed italic">
                    "{{ $order->catatan ?? 'Terima kasih telah menggunakan jasa kami. Kepuasan Anda adalah prioritas kami.' }}"
                </div>
            </div>
            <div class="text-center w-full sm:w-auto">
                <p class="text-xs text-slate-400 mb-16 italic uppercase tracking-widest font-bold">Hormat Kami,</p>
                <div class="border-t-2 border-slate-900 pt-2">
                    <p class="text-sm font-bold text-slate-900 uppercase">Juragan Urug Admin</p>
                </div>
            </div>
        </div>
    </div>

    @if(request('mode') != 'download')
    <div class="text-center mt-10 mb-20 no-print">
        <p class="text-xs text-slate-400 font-medium">© 2026 JuraganUrug System. Powered by Ridwan Tech.</p>
    </div>
    @endif

    <script>
        const safeFilename = 'Invoice-{{ Str::slug($order->nama_pelanggan) }}-{{ str_replace(['/', '\\'], '-', $order->no_invoice) }}.pdf';

        function downloadPDF() {
            const element = document.getElementById('invoice-card');
            
            const options = {
                margin: [0.3, 0.3, 0.3, 0.3],
                filename: safeFilename,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { 
                    scale: 2, 
                    useCORS: true, 
                    letterRendering: true,
                    width: 800 
                },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
            };
            
            html2pdf().set(options).from(element).save().then(() => {
                if (window.self !== window.top) {
                    window.parent.postMessage('download_complete', '*');
                }
            });
        }

        @if(request('mode') == 'download')
            window.addEventListener('load', () => {
                setTimeout(() => {
                    downloadPDF();
                }, 2500);
            });
        @endif
    </script>
</body>
</html>