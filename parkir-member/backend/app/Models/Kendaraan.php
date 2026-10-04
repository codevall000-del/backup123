<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraans';
    protected $primaryKey = 'no_plat';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_plat',
        'id_member',
        'jenis_kendaraan',
        'merk',
        'warna',
        'foto_stnk',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member', 'id_member');
    }

    public function transaksiParkirs()
    {
        return $this->hasMany(TransaksiParkir::class, 'no_plat', 'no_plat');
    }
}
