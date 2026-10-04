<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';
    protected $primaryKey = 'id_member';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_member',
        'nama_member',
        'nik',
        'no_telp',
        'alamat',
        'rfid_tag',
        'qr_code',
        'tgl_daftar',
        'tgl_kadaluarsa',
        'status_member',
    ];

    protected $appends = ['is_active', 'sisa_hari'];

    public function kendaraans()
    {
        return $this->hasMany(Kendaraan::class, 'id_member', 'id_member');
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'id_member', 'id_member')->orderBy('tgl_bayar', 'desc');
    }

    public function transaksiParkirs()
    {
        return $this->hasMany(TransaksiParkir::class, 'id_member', 'id_member')->orderBy('waktu_masuk', 'desc');
    }

    public function getIsActiveAttribute(): bool
    {
        if ($this->status_member === 'nonaktif') {
            return false;
        }

        if (!$this->tgl_kadaluarsa) {
            return false;
        }

        return Carbon::parse($this->tgl_kadaluarsa)->endOfDay()->isFuture();
    }

    public function getSisaHariAttribute(): int
    {
        if (!$this->tgl_kadaluarsa) {
            return 0;
        }

        $now = Carbon::now()->startOfDay();
        $expiry = Carbon::parse($this->tgl_kadaluarsa)->startOfDay();

        return (int) $now->diffInDays($expiry, false);
    }
}
