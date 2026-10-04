<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';
    protected $primaryKey = 'id_pembayaran';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pembayaran',
        'id_member',
        'id_petugas',
        'tgl_bayar',
        'durasi_bulan',
        'tgl_mulai',
        'tgl_kadaluarsa',
        'nominal',
        'diskon',
        'nominal_akhir',
        'metode_bayar',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tgl_bayar' => 'datetime',
            'tgl_mulai' => 'date',
            'tgl_kadaluarsa' => 'date',
            'nominal' => 'float',
            'diskon' => 'float',
            'nominal_akhir' => 'float',
            'durasi_bulan' => 'integer',
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member', 'id_member');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }
}
