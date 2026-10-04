<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiParkir extends Model
{
    use HasFactory;

    protected $table = 'transaksi_parkirs';
    protected $primaryKey = 'id_parkir';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_parkir',
        'id_member',
        'no_plat',
        'waktu_masuk',
        'waktu_keluar',
        'durasi_menit',
        'gerbang_masuk',
        'gerbang_keluar',
        'status_parkir',
        'metode_masuk',
        'catatan_override',
        'id_petugas_override',
        'biaya',
    ];

    protected function casts(): array
    {
        return [
            'waktu_masuk' => 'datetime',
            'waktu_keluar' => 'datetime',
            'durasi_menit' => 'integer',
            'biaya' => 'float',
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member', 'id_member');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'no_plat', 'no_plat');
    }

    public function petugasOverride()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas_override', 'id_petugas');
    }
}
