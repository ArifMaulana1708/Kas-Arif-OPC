<?php

namespace App\Services;

use App\Models\Bank;
use App\Models\Kategori;
use App\Models\Keuangan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BankAdminFeeService
{
    public const AUTO_CATEGORY = 'Biaya Admin Bank';

    public const NOTE_PREFIX = '[AUTO-ADM]';

    /**
     * Proses potongan admin bulanan untuk semua bank yang jatuh tempo.
     *
     * Aturan:
     * - potongan_adm_bulanan <= 0  => skip (nonaktif)
     * - tgl_potongan_adm_bulanan null => skip (belum dijadwalkan)
     * - tanggal berjalan < jatuh tempo bulan ini => skip (belum saatnya),
     *   kecuali $force = true (tombol manual / testing tanggal maju)
     * - last_adm_periode == periode berjalan => skip (anti double-input)
     * - tgl 29-31 di bulan pendek => jatuh tempo = hari terakhir bulan itu
     *
     * @return array{charged: int, skipped: int, details: array}
     */
    public static function process(Carbon $date, bool $force = false): array
    {
        $date = $date->copy()->startOfDay();
        $periode = $date->format('Y-m');
        $daysInMonth = $date->daysInMonth;

        $kategori = Kategori::firstOrCreate(
            ['nama_kategori' => self::AUTO_CATEGORY],
            ['tipe' => 'Keluar']
        );

        $charged = 0;
        $skipped = 0;
        $details = [];

        $banks = Bank::where('potongan_adm_bulanan', '>', 0)
            ->whereNotNull('tgl_potongan_adm_bulanan')
            ->get();

        foreach ($banks as $bank) {
            $jatuhTempo = min((int) $bank->tgl_potongan_adm_bulanan, $daysInMonth);

            if ($bank->last_adm_periode === $periode) {
                $skipped++;
                $details[] = ['bank' => $bank->nama_bank, 'status' => 'skip', 'reason' => "sudah dipotong periode {$periode}"];
                continue;
            }

            if (! $force && $date->day < $jatuhTempo) {
                $skipped++;
                $details[] = ['bank' => $bank->nama_bank, 'status' => 'skip', 'reason' => "belum jatuh tempo (tgl {$jatuhTempo})"];
                continue;
            }

            try {
                DB::transaction(function () use ($bank, $kategori, $date, $periode) {
                    /** @var Bank $locked */
                    $locked = Bank::lockForUpdate()->find($bank->id);

                    // Cek ulang di dalam transaksi: cegah double saat
                    // scheduler + tombol manual / dashboard hook jalan bersamaan.
                    if ($locked->last_adm_periode === $periode) {
                        return;
                    }

                    Keuangan::create([
                        'id_bank' => $locked->id,
                        'id_kategori' => $kategori->id,
                        'tanggal_keuangan' => $date->toDateString(),
                        'status' => 'Uang Keluar',
                        'jumlah' => (int) $locked->potongan_adm_bulanan,
                        'catatan' => self::NOTE_PREFIX . " Biaya admin bulanan {$locked->nama_bank} periode {$periode}",
                        'foto_bukti' => null,
                    ]);

                    $locked->decrement('jumlah_total', (int) $locked->potongan_adm_bulanan);
                    $locked->update(['last_adm_periode' => $periode]);
                });

                // Pastikan transaksi benar-benar menandai periode (bukan skip karena balapan).
                if (Bank::find($bank->id)->last_adm_periode === $periode) {
                    $charged++;
                    $details[] = ['bank' => $bank->nama_bank, 'status' => 'charged', 'reason' => "periode {$periode}"];
                } else {
                    $skipped++;
                    $details[] = ['bank' => $bank->nama_bank, 'status' => 'skip', 'reason' => "sudah dipotong periode {$periode} (race)"];
                }
            } catch (\Throwable $e) {
                Log::error('Gagal potong adm bank ' . $bank->nama_bank . ': ' . $e->getMessage());
                $skipped++;
                $details[] = ['bank' => $bank->nama_bank, 'status' => 'error', 'reason' => $e->getMessage()];
            }
        }

        Log::info("BankAdminFeeService periode {$periode}: {$charged} charged, {$skipped} skipped.");

        return compact('charged', 'skipped', 'details');
    }
}
