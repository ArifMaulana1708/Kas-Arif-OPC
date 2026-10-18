<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TfAntarBank extends Model
{
    protected $fillable = [
        'from_id_bank', 'to_id_bank', 'tanggal_tf',
        'jumlah_tf', 'uang_admin', 'remark', 'foto_bukti',
    ];

    protected $casts = [
        'tanggal_tf' => 'date',
        'jumlah_tf' => 'integer',
        'uang_admin' => 'integer',
    ];

    public function fromBank()
    {
        return $this->belongsTo(Bank::class, 'from_id_bank');
    }

    public function toBank()
    {
        return $this->belongsTo(Bank::class, 'to_id_bank');
    }
}
