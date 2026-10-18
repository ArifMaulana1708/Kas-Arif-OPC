<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->unsignedBigInteger('potongan_adm_bulanan')->default(0)->after('jumlah_total');
            $table->unsignedTinyInteger('tgl_potongan_adm_bulanan')->nullable()->after('potongan_adm_bulanan');
            $table->string('last_adm_periode', 7)->nullable()->after('tgl_potongan_adm_bulanan');
        });
    }

    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn(['potongan_adm_bulanan', 'tgl_potongan_adm_bulanan', 'last_adm_periode']);
        });
    }
};
