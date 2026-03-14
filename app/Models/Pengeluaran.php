<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluarans'; // Memastikan model pakai tabel ini
    
    protected $fillable = [
        'tanggal',
        'kategori',
        'keterangan',
        'nominal'
    ];
}
