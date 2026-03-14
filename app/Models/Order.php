<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pelanggan',
        'no_wa',
        'alamat',
        'penjadwalan',
        'jenis_layanan',
        'armada',
        'catatan',
        // Tambahan Field Rekap
        'status_pelanggan',
        'jumlah_rit',
        'harga_per_rit',
        'hpp_per_rit',
        'jumlah_pendapatan',
        'jumlah_keuntungan',

        // TAMBAHKAN 3 BARIS INI:
        'status_pembayaran',
        'metode_pembayaran',
        'tanggal_pelunasan',
        'jumlah_dibayar',
        'catatan_pembayaran',
        'no_invoice',

        'status_order',
        'alasan_cancel',

        'link_maps', 
        'nama_sopir', 
        'no_wa_sopir', 
        'plat_armada',
    ];

    // Tambahkan ini di dalam class Order
    public function logs()
    {
        return $this->hasMany(OrderLog::class)->orderBy('created_at', 'desc');
    }
}