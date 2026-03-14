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
        Schema::create('order_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('perubahan'); // Contoh: "Perubahan Sopir & Armada"
            $table->text('detail');    // Contoh: "Sopir A (AB 123) diganti Sopir B (H 456)"
            $table->string('alasan')->nullable();
            $table->string('admin_name'); // Nama admin yang melakukan edit
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_logs');
    }
};
