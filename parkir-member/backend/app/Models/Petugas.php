<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Petugas extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_petugas',
        'nama_petugas',
        'username',
        'password',
        'peran',
        'pin_petugas',
        'pos_aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'id_petugas', 'id_petugas');
    }

    public function overrideParkirs()
    {
        return $this->hasMany(TransaksiParkir::class, 'id_petugas_override', 'id_petugas');
    }
}
