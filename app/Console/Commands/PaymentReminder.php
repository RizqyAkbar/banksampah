<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class PaymentReminder extends Command
{
    // Nama instruksi untuk dipanggil di terminal
    protected $signature = 'payment:reminder';
    
    // Penjelasan perintah
    protected $description = 'Kirim pengingat WA otomatis untuk tagihan Belum Lunas & Cicil';

    public function handle()
    {
        // Cari semua tagihan yang aktif dan belum lunas
        $orders = Order::whereIn('status_pembayaran', ['Belum Lunas', 'Cicil'])
            ->where('status_order', 'Aktif')
            ->where('total_pendapatan', '>', 0)
            ->get();

        foreach ($orders as $order) {
            // Hitung sisa hutang
            $sisaHutang = $order->total_pendapatan - $order->jumlah_dibayar;

            // Format Nomor WA
            $noWaFinal = preg_replace('/[^0-9]/', '', $order->no_wa);
            if (!str_starts_with($noWaFinal, '62')) {
                $noWaFinal = '62' . ltrim($noWaFinal, '0');
            }

            // Merakit Pesan WA (Update Template Client)
            $pesanWA = "Halo " . $order->nama_pelanggan . ",\n\n";
            $pesanWA .= "Kami ingin mengingatkan terkait tagihan layanan dari Juragan Urug yang telah kami kirimkan sebelumnya.\n\n";
            $pesanWA .= "Apabila sudah berkenan, mohon dapat dilakukan pembayarannya.\n";
            $pesanWA .= "Jika sudah dibayarkan, mohon konfirmasi kepada kami.\n\n";
            $pesanWA .= "Terima kasih 🙏";

            // Hit API Fonnte
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
            curl_exec($curl);
            curl_close($curl);

            // Jeda 2 detik agar API WA tidak kena block/spam limit
            sleep(2); 
        }

        $this->info('Semua pengingat tagihan telah berhasil dikirim!');
    }
}