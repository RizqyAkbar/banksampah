<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique(); // Otomatisasi Invoice (Revisi 9)
            $table->string('nama_pelanggan');
            $table->string('no_wa');
            $table->text('alamat');
            $table->date('penjadwalan');
            $table->string('status_pelanggan'); // Pelanggan Baru/Lama (Revisi 3)
            $table->string('status_order')->default('Aktif'); // Untuk Monitor Cancel (Revisi 2)
            $table->text('link_maps')->nullable(); // Sharelok (Revisi 5)
            $table->text('catatan')->nullable();
            
            // Total Keuangan (Hasil penjumlahan dari semua item nanti)
            $table->decimal('total_pendapatan', 15, 2)->default(0);
            $table->decimal('total_keuntungan', 15, 2)->default(0);
            
            // Data Pembayaran (Revisi 7 & 8)
            $table->decimal('jumlah_dibayar', 15, 2)->default(0);
            $table->string('status_pembayaran')->default('Belum Lunas');
            $table->text('catatan_pembayaran')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
