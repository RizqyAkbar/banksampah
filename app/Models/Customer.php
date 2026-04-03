<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Kolom-kolom yang diizinkan untuk diisi
    protected $fillable = [
        'nama_pelanggan',
        'no_wa',
        'alamat',
        'link_maps',
        'status_pelanggan',
        'jumlah_order',
    ];
}