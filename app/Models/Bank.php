<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = ['nama_bank', 'jumlah_total', 'potongan_adm_bulanan', 'tgl_potongan_adm_bulanan', 'last_adm_periode'];

    protected $casts = [
        'jumlah_total' => 'integer',
        'potongan_adm_bulanan' => 'integer',
        'tgl_potongan_adm_bulanan' => 'integer',
    ];

    public function keuangans()
    {
        return $this->hasMany(Keuangan::class, 'id_bank');
    }
}
