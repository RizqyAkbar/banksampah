<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'nama_layanan',
        'harga_dasar',
        'harga_jual', // Kolom baru untuk fitur cancel per item
        'armada',
    ];
}
