<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan');
            $table->string('no_wa')->unique(); // Nomor WA dibuat unik biar ga dobel
            $table->text('alamat');
            $table->text('link_maps')->nullable();
            $table->string('status_pelanggan')->default('Konsumen Baru'); 
            $table->integer('jumlah_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};