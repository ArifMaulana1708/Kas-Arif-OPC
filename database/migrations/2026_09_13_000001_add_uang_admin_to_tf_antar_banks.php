<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tf_antar_banks', function (Blueprint $table) {
            $table->bigInteger('uang_admin')->default(0)->after('jumlah_tf');
        });
    }

    public function down(): void
    {
        Schema::table('tf_antar_banks', function (Blueprint $table) {
            $table->dropColumn('uang_admin');
        });
    }
};
