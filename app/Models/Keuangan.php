<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $fillable = [
        'id_bank', 'id_kategori', 'tanggal_keuangan',
        'status', 'jumlah', 'catatan', 'foto_bukti',
    ];

    protected $casts = [
        'tanggal_keuangan' => 'date',
        'jumlah' => 'integer',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'id_bank');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
}
