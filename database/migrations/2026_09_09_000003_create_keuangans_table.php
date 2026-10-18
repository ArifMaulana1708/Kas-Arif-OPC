<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keuangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_bank')->constrained('banks')->onDelete('restrict');
            $table->foreignId('id_kategori')->constrained('kategoris')->onDelete('restrict');
            $table->date('tanggal_keuangan');
            $table->enum('status', ['Uang Masuk', 'Uang Keluar']);
            $table->bigInteger('jumlah')->unsigned();
            $table->text('catatan')->nullable();
            $table->string('foto_bukti')->nullable();
            $table->timestamps();
            $table->index(['tanggal_keuangan', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangans');
    }
};
