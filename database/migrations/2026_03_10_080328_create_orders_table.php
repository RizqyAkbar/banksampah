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
            $table->string('nama_pelanggan');
            $table->string('no_wa');
            $table->text('alamat');
            $table->date('penjadwalan');
            $table->string('jenis_layanan'); // Akan menyimpan pilihan dropdown atau input kustom
            $table->string('armada');
            $table->text('catatan')->nullable(); // nullable berarti boleh kosong
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
