<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengeluaran;
use App\Models\Service; // Ganti dengan nama model Master Layanan Anda
use Carbon\Carbon;
use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentExport;
use App\Exports\LaporanExport;

class OrderController extends Controller
{
    // 1. Menampilkan Form Input Order (DENGAN FITUR RECALL)
    public function create(Request $request)
    {
        $masterLayanan = Service::orderBy('nama_layanan', 'asc')->get();

        // Tangkap request recall dari Database Pelanggan
        $recallCustomer = null;
        if ($request->has('recall_wa')) {
            $recallCustomer = \App\Models\Customer::where('no_wa', $request->recall_wa)->first();
        }

        return view('admin.input_order', compact('masterLayanan', 'recallCustomer'));
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date',
        ]);

        $namaFile = 'Rekap_Order_' . $request->tgl_mulai . '_to_' . $request->tgl_selesai . '.xlsx';

        return Excel::download(new OrderExport($request->tgl_mulai, $request->tgl_selesai), $namaFile);
    }

    public function exportPaymentExcel(Request $request)
    {
        $request->validate([
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date',
        ]);

        return Excel::download(
            new PaymentExport($request->tgl_mulai, $request->tgl_selesai),
            'Laporan_Pembayaran_' . $request->tgl_mulai . '_to_' . $request->tgl_selesai . '.xlsx'
        );
    }

    public function exportLaporan(Request $request)
    {
        $tgl = date('d-m-Y');
        return Excel::download(new LaporanExport($request), "Laporan_Performa_Jasa_{$tgl}.xlsx");
    }

    // 2. Menyimpan Input Order & KIRIM WA
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required',
            'no_wa'          => 'required',
            'alamat'         => 'required',
            'penjadwalan'    => 'required|date',
            'layanan'        => 'required|array',
            'armada'         => 'required|array',
            'jumlah_rit'     => 'required|array',
        ]);

        $nomorBersih = ltrim($request->no_wa, '0');
        $noWaFinal = '62' . $nomorBersih;

        $tanggalFormat = date('Ymd');

        $lastOrderToday = Order::whereDate('created_at', Carbon::today())->latest()->first();
        if ($lastOrderToday && $lastOrderToday->no_invoice) {
            $lastIncrement = (int) substr($lastOrderToday->no_invoice, -3);
            $nextIncrement = $lastIncrement + 1;
        } else {
            $nextIncrement = 1;
        }

        $nomorUrut = str_pad($nextIncrement, 3, '0', STR_PAD_LEFT);
        $noInvoiceFinal = "INV-$tanggalFormat-$nomorUrut";

        $customer = \App\Models\Customer::where('no_wa', $noWaFinal)->first();

        if ($customer) {
            $newJumlahOrder = $customer->jumlah_order + 1;
            $newStatus = ($newJumlahOrder >= 2) ? 'Pelanggan' : 'Konsumen Baru';

            $customer->update([
                'nama_pelanggan'   => $request->nama_pelanggan, 
                'alamat'           => $request->alamat, 
                'link_maps'        => $request->link_maps, 
                'jumlah_order'     => $newJumlahOrder,
                'status_pelanggan' => $newStatus,
            ]);
            
            $statusPelanggan = $newStatus;
        } else {
            \App\Models\Customer::create([
                'nama_pelanggan'   => $request->nama_pelanggan,
                'no_wa'            => $noWaFinal,
                'alamat'           => $request->alamat,
                'link_maps'        => $request->link_maps,
                'jumlah_order'     => 1,
                'status_pelanggan' => 'Konsumen Baru',
            ]);
            
            $statusPelanggan = 'Konsumen Baru';
        }

        $order = Order::create([
            'nama_pelanggan'   => $request->nama_pelanggan,
            'no_wa'            => $noWaFinal,
            'alamat'           => $request->alamat,
            'penjadwalan'      => $request->penjadwalan,
            'catatan'          => $request->catatan,
            'status_pelanggan' => $statusPelanggan,
            'status_order'     => 'Aktif',
            'link_maps'        => $request->link_maps,
            'no_invoice'       => $noInvoiceFinal,
            'total_pendapatan' => 0,
            'total_keuntungan' => 0,
        ]);

        $listLayanan = [];
        $listArmada = [];
        $listEstimasi = [];

        foreach ($request->layanan as $index => $jenisLayanan) {
            $armadaFinal = $request->armada[$index];

            if (empty($armadaFinal)) {
                $service = Service::where('nama_layanan', $jenisLayanan)->first();
                $armadaFinal = $service ? $service->armada : 'Umum'; 
            }

            $namaJasaFinal = ($jenisLayanan === 'LAINNYA')
                ? ($request->layanan_manual[$index] ?? 'Jasa Tidak Diketahui')
                : $jenisLayanan;

            $rit = $request->jumlah_rit[$index] ?? 0;

            $order->items()->create([
                'jenis_layanan' => $namaJasaFinal,
                'armada'        => $armadaFinal, 
                'jumlah_rit'    => $rit,
                'harga_per_rit' => 0,
                'hpp_per_rit'   => 0,
                'status_item'   => 'Aktif',
            ]);

            $listLayanan[] = $namaJasaFinal;
            $listArmada[] = $armadaFinal;
            $listEstimasi[] = $rit . " " . $armadaFinal;
        }

        if ($request->action === 'save_and_wa') {
            Carbon::setLocale('id');
            $tanggalIndo = Carbon::parse($request->penjadwalan)->translatedFormat('l, d F Y');

            $pesanWA = "Formulir Order *JURAGAN URUG*\n";
            $pesanWA .= "_______\n";
            $pesanWA .= "*Tanggal :* " . $tanggalIndo . "\n";
            $pesanWA .= "*Nama :* " . $request->nama_pelanggan . "\n";
            $pesanWA .= "*Lokasi :* " . $request->alamat . "\n";
            
            if ($request->link_maps) {
                $pesanWA .= trim($request->link_maps) . "\n";
            }
            
            $pesanWA .= "_______\n";
            $pesanWA .= "*Jenis Layanan :* " . implode(', ', array_unique($listLayanan)) . "\n";
            $pesanWA .= "*Armada :* " . implode(', ', array_unique($listArmada)) . "\n";
            $pesanWA .= "*Estimasi :* " . implode(', ', $listEstimasi) . "\n";
            $pesanWA .= "*Foto Proyek :* _Terlampir_\n";
            $pesanWA .= "_______\n";
            $pesanWA .= "*Catatan penting:*\n" . ($request->catatan ?? '-') . "\n\n";
            $pesanWA .= "> _Pesan dikirim otomatis dari sistem Juragan Urug_"; // Teks tambahan baru

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $noWaFinal,
                    'message' => $pesanWA, 
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: 3ujshugpoSMCyDpKjVtJ'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);

            $waLink = 'https://wa.me/' . $noWaFinal . '?text=' . urlencode($pesanWA);

            return redirect()->back()->with([
                'success' => 'Order tersimpan & Formulir Order sedang dikirim otomatis ke WA Pelanggan!',
                'wa_link' => $waLink
            ]);
        }

        return redirect()->back()->with([
            'success' => 'Order berhasil disimpan (Tanpa Kirim WA)!'
        ]);
    }

    // 3. Halaman REKAP ORDER
    public function index(Request $request)
    {
        if ($request->has('export') && $request->export == 'excel') {
            $tgl_mulai = $request->tgl_mulai ?: '2023-01-01'; 
            $tgl_selesai = $request->tgl_selesai ?: \Carbon\Carbon::now()->addYears(5)->format('Y-m-d');
            
            return Excel::download(
                new OrderExport($tgl_mulai, $tgl_selesai), 
                'Data_Rekap_Order_' . date('Ymd_His') . '.xlsx'
            );
        }

        $query = Order::with(['items' => function ($query) {
                $query->where('status_item', 'Aktif');
            }])
            ->where('status_order', 'Aktif')
            ->whereHas('items', function ($query) {
                $query->where('status_item', 'Aktif');
            });

        // --- LOGIKA PENCARIAN (Search) ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'LIKE', "%$search%")
                  ->orWhere('no_wa', 'LIKE', "%$search%")
                  ->orWhere('alamat', 'LIKE', "%$search%")
                  ->orWhere('no_invoice', 'LIKE', "%$search%")
                  ->orWhereHas('items', function ($sq) use ($search) {
                      $sq->where('nama_sopir', 'LIKE', "%$search%")
                         ->orWhere('jenis_layanan', 'LIKE', "%$search%")
                         ->orWhere('plat_armada', 'LIKE', "%$search%");
                  });
            });
        }

        // --- LOGIKA FILTER TANGGAL (Date Range) ---
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('penjadwalan', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        // --- FITUR BARU: FILTER BELUM REKAP ---
        if ($request->filled('filter') && $request->filter == 'belum_rekap') {
            $query->where('total_pendapatan', 0);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        $orders->appends($request->all());

        return view('admin.rekap_order', compact('orders'));
    }

    // 4. Halaman EDIT REKAP
    public function edit($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('admin.rekap_order_edit', compact('order'));
    }

    // 5. UPDATE REKAP (SINKRONISASI NAMBAH/KURANG JASA)
    public function update(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);

        $request->validate([
            'item_id' => 'required|array',
            'jumlah_rit' => 'required|array',
            'harga_per_rit' => 'required|array',
            'hpp_per_rit' => 'required|array',
            'alasan_perubahan' => 'nullable|string|max:255', 
        ]);

        $totalPendapatanOrder = 0;
        $totalKeuntunganOrder = 0;
        $perubahanKolektif = [];

        $submittedItemIds = collect($request->item_id)->filter(function ($val) {
            return $val !== 'new'; 
        })->toArray();

        $itemsToDelete = $order->items()->whereNotIn('id', $submittedItemIds)->get();
        foreach($itemsToDelete as $delItem) {
            $perubahanKolektif[] = "Layanan Dihapus: [" . $delItem->jenis_layanan . " - " . $delItem->armada . "]";
            $delItem->delete();
        }

        foreach ($request->item_id as $index => $itemId) {
            $jenisLayananInput = $request->jenis_layanan[$index];
            $jenisLayananFinal = ($jenisLayananInput === 'LAINNYA')
                ? ($request->layanan_manual[$index] ?? $jenisLayananInput)
                : $jenisLayananInput;

            $armadaFinal = $request->armada[$index];
            $sopirFinal  = $request->nama_sopir[$index];
            $platFinal   = $request->plat_armada[$index];
            $ritFinal    = $request->jumlah_rit[$index] ?? 0;
            $hargaFinal  = $request->harga_per_rit[$index] ?? 0;
            $hppFinal    = $request->hpp_per_rit[$index] ?? 0;

            $noWaInput = $request->no_wa_sopir[$index];
            $noWaFinal = null;
            if ($noWaInput) {
                $nomorBersih = ltrim($noWaInput, '0');
                if (!str_starts_with($nomorBersih, '62')) {
                    $noWaFinal = '62' . $nomorBersih;
                } else {
                    $noWaFinal = $nomorBersih;
                }
            }

            $subPendapatan = $ritFinal * $hargaFinal;
            $subKeuntungan = $subPendapatan - ($ritFinal * $hppFinal);

            if ($itemId === 'new') {
                $order->items()->create([
                    'jenis_layanan' => $jenisLayananFinal,
                    'armada'        => $armadaFinal,
                    'nama_sopir'    => $sopirFinal,
                    'no_wa_sopir'   => $noWaFinal,
                    'plat_armada'   => $platFinal,
                    'jumlah_rit'    => $ritFinal,
                    'harga_per_rit' => $hargaFinal,
                    'hpp_per_rit'   => $hppFinal,
                    'subtotal_pendapatan' => $subPendapatan,
                    'subtotal_keuntungan' => $subKeuntungan,
                    'status_item'   => 'Aktif',
                ]);
                $perubahanKolektif[] = "Layanan Baru Ditambahkan: [$jenisLayananFinal - $armadaFinal]";
                
                $totalPendapatanOrder += $subPendapatan;
                $totalKeuntunganOrder += $subKeuntungan;
            } else {
                $item = OrderItem::findOrFail($itemId);

                $sopirLama = $item->nama_sopir;
                $platLama  = $item->plat_armada;
                $armadaLama = $item->armada;
                $layananLama = $item->jenis_layanan;

                $isArmadaChanged = ($armadaLama != $armadaFinal);
                $isLayananChanged = ($layananLama != $jenisLayananFinal);
                $isPersonelChanged = ($sopirLama != $sopirFinal) || ($platLama != $platFinal);

                $changes = [];

                if ($isArmadaChanged || $isLayananChanged) {
                    if ($isLayananChanged) $changes[] = "Jasa [$layananLama] -> [$jenisLayananFinal]";
                    if ($isArmadaChanged) $changes[] = "Armada [$armadaLama] -> [$armadaFinal]";
                }

                if ($isPersonelChanged && !empty($sopirLama)) {
                    $changes[] = "Sopir [" . ($sopirLama ?: '-') . " (" . ($platLama ?: '-') . ")] -> [" . ($sopirFinal ?: '-') . " (" . ($platFinal ?: '-') . ")]";
                }

                if (count($changes) > 0) {
                    $labelRevisi = "REVISI OPERASIONAL";
                    if ($isPersonelChanged && ($isArmadaChanged || $isLayananChanged)) $labelRevisi = "REVISI UNIT & PERSONEL";
                    elseif ($isPersonelChanged) $labelRevisi = "REVISI PERSONEL";
                    elseif ($isArmadaChanged || $isLayananChanged) $labelRevisi = "REVISI UNIT ARMADA";

                    $detailLog = "Layanan #" . ($index + 1) . " [$labelRevisi]: " . implode(", ", $changes);
                    $perubahanKolektif[] = $detailLog;
                }

                $item->update([
                    'jenis_layanan' => $jenisLayananFinal,
                    'armada'        => $armadaFinal,
                    'nama_sopir'    => $sopirFinal,
                    'no_wa_sopir'   => $noWaFinal, 
                    'plat_armada'   => $platFinal,
                    'jumlah_rit'    => $ritFinal,
                    'harga_per_rit' => $hargaFinal,
                    'hpp_per_rit'   => $hppFinal,
                    'subtotal_pendapatan' => $subPendapatan,
                    'subtotal_keuntungan' => $subKeuntungan,
                ]);

                $totalPendapatanOrder += $subPendapatan;
                $totalKeuntunganOrder += $subKeuntungan;
            }
        }

        if (count($perubahanKolektif) > 0) {
            foreach ($perubahanKolektif as $logItem) {
                OrderLog::create([
                    'order_id'   => $order->id,
                    'perubahan'  => 'Update Operasional',
                    'detail'     => $logItem,
                    'alasan'     => $request->alasan_perubahan ?: 'Perubahan data operasional', 
                    'admin_name' => Auth::user()->name ?? 'Admin Juragan',
                ]);
            }
        }

        $order->update([
            'total_pendapatan' => $totalPendapatanOrder,
            'total_keuntungan' => $totalKeuntunganOrder,
            'alamat'           => $request->alamat,
            'link_maps'        => $request->link_maps,
            'catatan'          => $request->catatan,
        ]);

        return redirect()->route('admin.rekap_order')->with('success', 'Data Rekap dan Layanan Berhasil diperbarui!');
    }

    public function invoiceIndex(Request $request)
    {
        $query = Order::with(['items' => function ($query) {
            $query->where('status_item', 'Aktif');
        }])
        ->where('status_order', 'Aktif')
        ->where('total_pendapatan', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'LIKE', "%$search%")
                  ->orWhere('no_wa', 'LIKE', "%$search%")
                  ->orWhere('no_invoice', 'LIKE', "%$search%")
                  ->orWhereHas('items', function ($sq) use ($search) {
                      $sq->where('jenis_layanan', 'LIKE', "%$search%")
                         ->orWhere('armada', 'LIKE', "%$search%");
                  });
            });
        }

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('penjadwalan', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        $orders = $query->orderBy('updated_at', 'desc')->paginate(10);
        $orders->appends($request->all());

        return view('admin.invoice_index', compact('orders'));
    }

    public function exportInvoice(Request $request)
    {
        return Excel::download(
            new \App\Exports\InvoiceExport($request), 
            "Laporan_Tagihan_" . date('Ymd_His') . ".xlsx"
        );
    }

    public function sendInvoiceWA($id)
    {
        $order = Order::with(['items' => function ($query) {
            $query->where('status_item', 'Aktif');
        }])->findOrFail($id);

        $noWaFinal = preg_replace('/[^0-9]/', '', $order->no_wa);
        if (!str_starts_with($noWaFinal, '62')) {
            $noWaFinal = '62' . ltrim($noWaFinal, '0');
        }

        $pesanWA = "Halo *" . $order->nama_pelanggan . "*,\n\n";
        $pesanWA .= "Berikut kami sampaikan rincian tagihan layanan dari *Juragan Urug*:\n\n";
        
        foreach($order->items as $srv) {
            $pesanWA .= "• " . $srv->jenis_layanan . " (" . $srv->jumlah_rit . " rit): Rp " . number_format($srv->jumlah_rit * $srv->harga_per_rit, 0, ',', '.') . "\n";
        }
        
        $pesanWA .= "\n*Total Tagihan: Rp " . number_format($order->total_pendapatan, 0, ',', '.') . "*\n\n";
        $pesanWA .= "Mohon untuk dapat dilakukan pembayaran sesuai dengan total tagihan di atas.\n\n";
        $pesanWA .= "Terima kasih atas kepercayaan Anda menggunakan layanan *Juragan Urug*.\n\n";
        $pesanWA .= "> _Pesan dikirim otomatis dari sistem Juragan Urug_"; // Teks tambahan baru

        // URL untuk membuka WA Web / Mobile
        $waLink = 'https://wa.me/' . $noWaFinal . '?text=' . urlencode($pesanWA);

        return redirect()->back()->with([
            'success' => 'Template pesan WA siap. Silakan klik tombol di bawah untuk diarahkan ke WhatsApp.',
            'wa_link' => $waLink
        ]);
    }

    public function getDetail($id)
    {
        $order = Order::with(['items' => function ($query) {
            $query->where('status_item', 'Aktif');
        }])->findOrFail($id);

        return response()->json([
            'data' => $order,
            'items' => $order->items,
            'tanggal' => $order->created_at->format('d M Y'),
            'no_invoice' => $order->no_invoice
        ]);
    }

    public function invoicePrint($id)
    {
        $order = Order::with(['items' => function ($query) {
            $query->where('status_item', 'Aktif');
        }])->findOrFail($id);

        return view('admin.invoice_print', compact('order'));
    }

    public function paymentIndex(Request $request)
    {
        if ($request->has('export') && $request->export == 'excel') {
            $tgl_mulai = $request->tgl_mulai ?: '2023-01-01'; 
            $tgl_selesai = $request->tgl_selesai ?: date('Y-m-d');
            
            return Excel::download(
                new PaymentExport($tgl_mulai, $tgl_selesai), 
                'Rekap_Pembayaran_' . date('Ymd_His') . '.xlsx'
            );
        }

        $query = Order::where('total_pendapatan', '>', 0)->where('status_order', 'Aktif');

        // REVISI: Logika Default Filter 'Belum Lunas'
        // Jika tidak ada parameter filter di URL sama sekali ATAU filternya 'belum_lunas'
        if (!$request->has('filter') || $request->filter == 'belum_lunas') {
            $query->whereIn('status_pembayaran', ['Belum Lunas', 'Cicil']);
        } elseif ($request->filter == 'lunas') {
            $query->where('status_pembayaran', 'Lunas');
        }
        // Jika filternya 'semua', query tidak perlu di-where lagi (tampil semua)

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'LIKE', "%$search%")
                  ->orWhere('no_invoice', 'LIKE', "%$search%")
                  ->orWhere('catatan_pembayaran', 'LIKE', "%$search%")
                  ->orWhere('metode_pembayaran', 'LIKE', "%$search%");
            });
        }

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('penjadwalan', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        $orders = $query->orderByRaw("CASE 
                                        WHEN status_pembayaran = 'Belum Lunas' THEN 1
                                        WHEN status_pembayaran = 'Cicil' THEN 2
                                        WHEN status_pembayaran = 'Lunas' THEN 3
                                        ELSE 4 
                                      END")
                        ->orderBy('updated_at', 'desc')
                        ->paginate(10);
        $orders->appends($request->all());

        return view('admin.payment_index', compact('orders'));
    }

    public function paymentUpdate(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate(['jumlah_dibayar' => 'required|numeric|min:0']);
        $totalTagihan = $order->total_pendapatan;
        $uangMasuk = $request->jumlah_dibayar;
        if ($uangMasuk >= $totalTagihan) {
            $statusBaru = 'Lunas';
            $tglLunas = $request->tanggal_pelunasan ?? now();
        } elseif ($uangMasuk > 0) {
            $statusBaru = 'Cicil';
            $tglLunas = null;
        } else {
            $statusBaru = 'Belum Lunas';
            $tglLunas = null;
        }
        $order->update(['jumlah_dibayar' => $uangMasuk, 'status_pembayaran' => $statusBaru, 'metode_pembayaran' => $request->metode_pembayaran, 'tanggal_pelunasan' => $tglLunas, 'catatan_pembayaran' => $request->catatan_pembayaran]);
        return redirect()->route('admin.payment_index')->with('success', 'Status: ' . $statusBaru);
    }

    public function laporan(Request $request)
    {
        if ($request->has('export') && $request->export == 'excel') {
            $tgl = date('d-m-Y');
            return Excel::download(new LaporanExport($request), "Laporan_Performa_Jasa_{$tgl}.xlsx");
        }

        $query = OrderItem::with('order')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status_order', 'Aktif')
            ->where('order_items.status_item', 'Aktif')
            ->where('order_items.subtotal_pendapatan', '>', 0);

        if ($request->filled('nama')) {
            $query->where('orders.nama_pelanggan', 'like', '%' . $request->nama . '%');
        }
        if ($request->filled('sopir')) {
            $query->where('order_items.nama_sopir', 'like', '%' . $request->sopir . '%');
        }
        if ($request->filled('plat')) {
            $query->where('order_items.plat_armada', 'like', '%' . $request->plat . '%');
        }
        if ($request->filled('layanan')) {
            $query->where('order_items.jenis_layanan', $request->layanan);
        }
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('orders.penjadwalan', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        $totalNominal = $query->sum('order_items.subtotal_pendapatan');
        $totalLabaKotor = $query->sum('order_items.subtotal_keuntungan');
        $totalLayanan = $query->count();

        $queryPengeluaran = Pengeluaran::query();
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $queryPengeluaran->whereBetween('tanggal', [$request->tgl_mulai, $request->tgl_selesai]);
        }
        $totalPengeluaran = $queryPengeluaran->sum('nominal');
        $labaBersih = $totalLabaKotor - $totalPengeluaran;

        $orders = $query->select('order_items.*', 'orders.penjadwalan', 'orders.nama_pelanggan', 'orders.no_wa')
            ->orderBy('orders.penjadwalan', 'desc')
            ->paginate(25);
            
        $orders->appends($request->all());

        $daftarLayanan = Service::distinct()->pluck('nama_layanan');

        return view('admin.laporan', compact(
            'orders',
            'totalNominal',
            'totalLabaKotor',
            'totalLayanan',
            'totalPengeluaran',
            'labaBersih',
            'daftarLayanan'
        ));
    }

    public function getRealtimeDashboard()
    {
        $badgeCount = Order::where('status_order', 'Aktif')
            ->where('total_pendapatan', 0)
            ->whereHas('items', function ($query) {
                $query->where('status_item', 'Aktif');
            })
            ->count();

        $today = \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $jadwalHariIni = Order::with(['items' => function ($query) {
            $query->where('status_item', 'Aktif');
        }])
            ->whereDate('penjadwalan', $today)
            ->where('status_order', 'Aktif')
            ->whereHas('items', function ($query) {
                $query->where('status_item', 'Aktif');
            })
            ->get();

        return response()->json([
            'badge' => $badgeCount,
            'html' => view('admin.partials.dashboard_table', compact('jadwalHariIni'))->render()
        ]);
    }

    public function indexCancel(Request $request)
    {
        $query = OrderItem::with('order')->where('status_item', 'Batal');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jenis_layanan', 'LIKE', "%$search%")
                  ->orWhere('alasan_cancel', 'LIKE', "%$search%")
                  ->orWhereHas('order', function ($sq) use ($search) {
                      $sq->where('nama_pelanggan', 'LIKE', "%$search%");
                  });
            });
        }

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $start = $request->tgl_mulai . " 00:00:00";
            $end = $request->tgl_selesai . " 23:59:59";
            $query->whereBetween('updated_at', [$start, $end]);
        }

        $orders = $query->orderBy('updated_at', 'desc')->paginate(10);
        $orders->appends($request->all());

        return view('admin.rekap_cancel', compact('orders'));
    }

    public function exportCancel(Request $request)
    {
        return Excel::download(
            new \App\Exports\CancelExport($request), 
            "Monitoring_Cancel_" . date('Ymd_His') . ".xlsx"
        );
    }

    public function cancel(Request $request, $id)
    {
        Order::findOrFail($id)->update(['status_order' => 'Batal', 'alasan_cancel' => $request->alasan_cancel]);
        return back()->with('success', 'Order berhasil dibatalkan.');
    }

    public function restore($id)
    {
        Order::findOrFail($id)->update(['status_order' => 'Aktif', 'alasan_cancel' => null]);
        return redirect()->route('admin.rekap_order')->with('success', 'Order dikembalikan ke daftar aktif.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->items()->delete();
        $order->logs()->delete();
        $order->delete();

        return redirect()->route('admin.rekap_order')->with('success', 'Data order telah dihapus secara permanen!');
    }

    public function cancelItem(Request $request, $id)
    {
        $item = OrderItem::findOrFail($id);
        $item->update([
            'status_item' => 'Batal',
            'alasan_cancel' => $request->alasan_cancel
        ]);

        $this->recalculateOrder($item->order_id);

        return back()->with('success', 'Layanan berhasil dibatalkan.');
    }

    public function destroyItem($id)
    {
        $item = OrderItem::findOrFail($id);
        $orderId = $item->order_id;
        $item->delete();

        $this->recalculateOrder($orderId);

        return back()->with('success', 'Layanan dihapus permanen.');
    }

    public function restoreItem($id)
    {
        $item = OrderItem::findOrFail($id);
        $item->update(['status_item' => 'Aktif', 'alasan_cancel' => null]);

        $this->recalculateOrder($item->order_id);

        return back()->with('success', 'Layanan diaktifkan kembali.');
    }

    private function recalculateOrder($orderId)
    {
        $order = Order::with('items')->find($orderId);
        $totalPendapatan = $order->items->where('status_item', 'Aktif')->sum('subtotal_pendapatan');
        $totalKeuntungan = $order->items->where('status_item', 'Aktif')->sum('subtotal_keuntungan');

        $order->update([
            'total_pendapatan' => $totalPendapatan,
            'total_keuntungan' => $totalKeuntungan
        ]);
    }
}