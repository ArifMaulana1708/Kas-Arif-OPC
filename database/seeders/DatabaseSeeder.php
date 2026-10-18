<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['username' => 'admin'], [
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => 'admin123',
            'role' => 'admin',
        ]);

        foreach (['BCA', 'Mandiri', 'DANA', 'OVO', 'Cash'] as $b) {
            Bank::firstOrCreate(['nama_bank' => $b], ['jumlah_total' => 0]);
        }

        $kats = [
            ['Gaji', 'Masuk'], ['Bonus', 'Masuk'], ['Lainnya Masuk', 'Masuk'],
            ['Makanan', 'Keluar'], ['Ngemil', 'Keluar'], ['Pangan Bulanan', 'Keluar'],
            ['Sandang Bulanan', 'Keluar'], ['Transport', 'Keluar'], ['Lainnya Keluar', 'Keluar'],
        ];
        foreach ($kats as [$nama, $tipe]) {
            Kategori::firstOrCreate(['nama_kategori' => $nama], ['tipe' => $tipe]);
        }
    }
}
