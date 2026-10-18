<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tf_antar_banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_id_bank')->constrained('banks')->onDelete('restrict');
            $table->foreignId('to_id_bank')->constrained('banks')->onDelete('restrict');
            $table->date('tanggal_tf');
            $table->bigInteger('jumlah_tf')->unsigned();
            $table->text('remark')->nullable();
            $table->string('foto_bukti')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tf_antar_banks');
    }
};
