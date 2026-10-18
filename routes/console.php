<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Potongan admin bank bulanan: cek harian, tiap bank punya tanggal sendiri.
// Idempotent via banks.last_adm_periode, jadi aman walau jalan 2x sehari.
Schedule::command('bank:charge-admin-fee')->dailyAt('00:05');
