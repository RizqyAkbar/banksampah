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
    ];
}