<?php

namespace App\Console\Commands;

use App\Services\BankAdminFeeService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ChargeBankAdminFee extends Command
{
    protected $signature = 'bank:charge-admin-fee
                            {--date= : Tanggal acuan YYYY-MM-DD (default: hari ini)}
                            {--force : Potong walau tanggal berjalan belum sampai jatuh tempo}';

    protected $description = 'Otomatis input biaya admin bank bulanan sebagai Uang Keluar';

    public function handle(): int
    {
        try {
            $date = $this->option('date')
                ? Carbon::createFromFormat('Y-m-d', $this->option('date'))->startOfDay()
                : now()->startOfDay();
        } catch (\Throwable) {
            $this->error('Format --date tidak valid. Gunakan YYYY-MM-DD.');
            return self::FAILURE;
        }

        $result = BankAdminFeeService::process($date, (bool) $this->option('force'));

        $this->info("Periode {$date->format('Y-m')}: {$result['charged']} charged, {$result['skipped']} skipped.");
        foreach ($result['details'] as $d) {
            $this->line("- {$d['bank']}: {$d['status']} ({$d['reason']})");
        }

        return self::SUCCESS;
    }
}
