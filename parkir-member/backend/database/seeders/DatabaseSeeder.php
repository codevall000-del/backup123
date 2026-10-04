<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Petugas;
use App\Models\Member;
use App\Models\Kendaraan;
use App\Models\Pembayaran;
use App\Models\TransaksiParkir;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Petugas
        $petugasList = [
            [
                'id_petugas' => 'ADM-01',
                'nama_petugas' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'peran' => 'admin',
                'pin_petugas' => '123456',
                'pos_aktif' => 'Pusat Administrasi & Pengawas',
            ],
            [
                'id_petugas' => 'PETUGAS-01',
                'nama_petugas' => 'Petugas Operasional',
                'username' => 'petugas',
                'password' => Hash::make('petugas123'),
                'peran' => 'operator',
                'pin_petugas' => '123456',
                'pos_aktif' => 'Pos Gerbang Keluar',
            ],
            [
                'id_petugas' => 'KSR-01',
                'nama_petugas' => 'Petugas Kasir',
                'username' => 'kasir',
                'password' => Hash::make('kasir123'),
                'peran' => 'kasir',
                'pin_petugas' => '123456',
                'pos_aktif' => 'Loket Kasir',
            ],
            [
                'id_petugas' => 'OPR-01',
                'nama_petugas' => 'Petugas Gerbang',
                'username' => 'operator',
                'password' => Hash::make('operator123'),
                'peran' => 'operator',
                'pin_petugas' => '123456',
                'pos_aktif' => 'Pos Gerbang Keluar',
            ],
            [
                'id_petugas' => 'SEC-01',
                'nama_petugas' => 'Petugas Keamanan',
                'username' => 'satpam',
                'password' => Hash::make('satpam123'),
                'peran' => 'satpam',
                'pin_petugas' => '998877',
                'pos_aktif' => 'Pos Gerbang Masuk',
            ],
        ];

        foreach ($petugasList as $p) {
            Petugas::updateOrCreate(['id_petugas' => $p['id_petugas']], $p);
        }

        // 2. Seed Members
        $members = [
            [
                'id_member' => 'MBR-2026-001',
                'nama_member' => 'Ahmad Favian',
                'nik' => '3276012408080001',
                'no_telp' => '0812-9988-7766',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta Selatan',
                'rfid_tag' => 'RFID-8829-X',
                'qr_code' => 'QR-MBR-2026-001',
                'tgl_daftar' => '2026-01-12',
                'tgl_kadaluarsa' => '2026-11-20',
                'status_member' => 'aktif',
            ],
            [
                'id_member' => 'MBR-2026-002',
                'nama_member' => 'Budi Santoso',
                'nik' => '3276011505020002',
                'no_telp' => '0813-8877-6655',
                'alamat' => 'Jl. Sudirman No. 45, Jakarta Pusat',
                'rfid_tag' => 'RFID-9911-E',
                'qr_code' => 'QR-MBR-2026-002',
                'tgl_daftar' => '2026-02-01',
                'tgl_kadaluarsa' => '2026-08-01',
                'status_member' => 'kadaluarsa',
            ],
            [
                'id_member' => 'MBR-2026-003',
                'nama_member' => 'Siti Rahma',
                'nik' => '3276014407050003',
                'no_telp' => '0857-1122-3344',
                'alamat' => 'Jl. Melati Blok C2 No. 8, Depok',
                'rfid_tag' => 'RFID-4432-M',
                'qr_code' => 'QR-MBR-2026-003',
                'tgl_daftar' => '2026-03-15',
                'tgl_kadaluarsa' => '2026-12-15',
                'status_member' => 'aktif',
            ],
            [
                'id_member' => 'MBR-2026-004',
                'nama_member' => 'Dian Kusuma',
                'nik' => '3276016609040004',
                'no_telp' => '0818-4455-6677',
                'alamat' => 'Jl. Anggrek Raya No. 12, Bekasi',
                'rfid_tag' => 'RFID-2210-V',
                'qr_code' => 'QR-MBR-2026-004',
                'tgl_daftar' => '2026-04-10',
                'tgl_kadaluarsa' => Carbon::now()->addDays(5)->toDateString(),
                'status_member' => 'aktif',
            ],
            [
                'id_member' => 'MBR-2026-005',
                'nama_member' => 'Rizky Pratama',
                'nik' => '3276012803010005',
                'no_telp' => '0812-7788-9900',
                'alamat' => 'Jl. Gatot Subroto Kav. 5, Jakarta Selatan',
                'rfid_tag' => 'RFID-7734-X',
                'qr_code' => 'QR-MBR-2026-005',
                'tgl_daftar' => '2026-01-05',
                'tgl_kadaluarsa' => '2027-01-30',
                'status_member' => 'aktif',
            ],
            [
                'id_member' => 'MBR-2026-006',
                'nama_member' => 'Hendra Wijaya',
                'nik' => '3276011112000006',
                'no_telp' => '0856-3344-5566',
                'alamat' => 'Jl. Raya Bogor KM 28, Cimanggis',
                'rfid_tag' => 'RFID-6612-H',
                'qr_code' => 'QR-MBR-2026-006',
                'tgl_daftar' => '2025-10-01',
                'tgl_kadaluarsa' => '2026-04-01',
                'status_member' => 'nonaktif',
            ],
        ];

        foreach ($members as $m) {
            Member::updateOrCreate(['id_member' => $m['id_member']], $m);
        }

        // 3. Seed Kendaraans
        $kendaraans = [
            [
                'no_plat' => 'B 1234 ABC',
                'id_member' => 'MBR-2026-001',
                'jenis_kendaraan' => 'mobil',
                'merk' => 'Honda HR-V',
                'warna' => 'Hitam Metalik',
            ],
            [
                'no_plat' => 'B 9999 EXP',
                'id_member' => 'MBR-2026-002',
                'jenis_kendaraan' => 'mobil',
                'merk' => 'Toyota Avanza',
                'warna' => 'Putih',
            ],
            [
                'no_plat' => 'B 4567 DEF',
                'id_member' => 'MBR-2026-003',
                'jenis_kendaraan' => 'motor',
                'merk' => 'Yamaha NMAX',
                'warna' => 'Abu-abu Matte',
            ],
            [
                'no_plat' => 'B 3321 JKL',
                'id_member' => 'MBR-2026-004',
                'jenis_kendaraan' => 'motor',
                'merk' => 'Honda Vario 160',
                'warna' => 'Merah',
            ],
            [
                'no_plat' => 'B 8890 GHI',
                'id_member' => 'MBR-2026-005',
                'jenis_kendaraan' => 'mobil',
                'merk' => 'Mitsubishi Xpander',
                'warna' => 'Silver',
            ],
            [
                'no_plat' => 'B 6672 KLM',
                'id_member' => 'MBR-2026-006',
                'jenis_kendaraan' => 'motor',
                'merk' => 'Honda Beat',
                'warna' => 'Biru',
            ],
        ];

        foreach ($kendaraans as $k) {
            Kendaraan::updateOrCreate(['no_plat' => $k['no_plat']], $k);
        }

        // 4. Seed Pembayarans
        $pembayarans = [
            [
                'id_pembayaran' => 'BYR-2026-0001',
                'id_member' => 'MBR-2026-001',
                'id_petugas' => 'KSR-01',
                'tgl_bayar' => '2026-08-20 10:15:00',
                'durasi_bulan' => 3,
                'tgl_mulai' => '2026-08-20',
                'tgl_kadaluarsa' => '2026-11-20',
                'nominal' => 450000,
                'diskon' => 22500,
                'nominal_akhir' => 427500,
                'metode_bayar' => 'qris',
                'catatan' => 'Perpanjangan iuran mobil 3 bulan diskon 5%',
            ],
            [
                'id_pembayaran' => 'BYR-2026-0002',
                'id_member' => 'MBR-2026-003',
                'id_petugas' => 'KSR-01',
                'tgl_bayar' => '2026-09-15 14:30:00',
                'durasi_bulan' => 3,
                'tgl_mulai' => '2026-09-15',
                'tgl_kadaluarsa' => '2026-12-15',
                'nominal' => 150000,
                'diskon' => 7500,
                'nominal_akhir' => 142500,
                'metode_bayar' => 'tunai',
                'catatan' => 'Iuran motor 3 bulan paket hemat',
            ],
            [
                'id_pembayaran' => 'BYR-2026-0003',
                'id_member' => 'MBR-2026-005',
                'id_petugas' => 'KSR-01',
                'tgl_bayar' => '2026-01-30 09:00:00',
                'durasi_bulan' => 12,
                'tgl_mulai' => '2026-01-30',
                'tgl_kadaluarsa' => '2027-01-30',
                'nominal' => 1800000,
                'diskon' => 216000,
                'nominal_akhir' => 1584000,
                'metode_bayar' => 'transfer',
                'catatan' => 'Paket tahunan mobil diskon 12%',
            ],
        ];

        foreach ($pembayarans as $p) {
            Pembayaran::updateOrCreate(['id_pembayaran' => $p['id_pembayaran']], $p);
        }

        // 5. Seed TransaksiParkir
        $now = Carbon::now();
        $transaksis = [
            [
                'id_parkir' => 'PRK-2026-004810',
                'id_member' => 'MBR-2026-001',
                'no_plat' => 'B 1234 ABC',
                'waktu_masuk' => $now->copy()->subHours(2)->subMinutes(15),
                'waktu_keluar' => null, // Sedang di dalam (aktif)
                'durasi_menit' => null,
                'gerbang_masuk' => 'GATE-IN 01',
                'gerbang_keluar' => null,
                'status_parkir' => 'masuk',
                'metode_masuk' => 'scan_qr',
                'catatan_override' => null,
                'id_petugas_override' => null,
                'biaya' => 0,
            ],
            [
                'id_parkir' => 'PRK-2026-004809',
                'id_member' => 'MBR-2026-003',
                'no_plat' => 'B 4567 DEF',
                'waktu_masuk' => $now->copy()->subHours(4)->subMinutes(30),
                'waktu_keluar' => $now->copy()->subHours(1)->subMinutes(10),
                'durasi_menit' => 200,
                'gerbang_masuk' => 'GATE-IN 01',
                'gerbang_keluar' => 'GATE-OUT 01',
                'status_parkir' => 'keluar',
                'metode_masuk' => 'scan_qr',
                'catatan_override' => null,
                'id_petugas_override' => null,
                'biaya' => 0,
            ],
            [
                'id_parkir' => 'PRK-2026-004808',
                'id_member' => 'MBR-2026-002',
                'no_plat' => 'B 9999 EXP',
                'waktu_masuk' => $now->copy()->subHours(5),
                'waktu_keluar' => null,
                'durasi_menit' => null,
                'gerbang_masuk' => 'GATE-IN 01',
                'gerbang_keluar' => null,
                'status_parkir' => 'ditolak',
                'metode_masuk' => 'scan_qr',
                'catatan_override' => 'Masa aktif kartu telah kadaluarsa',
                'id_petugas_override' => null,
                'biaya' => 0,
            ],
            [
                'id_parkir' => 'PRK-2026-004807',
                'id_member' => 'MBR-2026-004',
                'no_plat' => 'B 3321 JKL',
                'waktu_masuk' => $now->copy()->subHours(6),
                'waktu_keluar' => null,
                'durasi_menit' => null,
                'gerbang_masuk' => 'GATE-IN 01',
                'gerbang_keluar' => null,
                'status_parkir' => 'bypass_pin',
                'metode_masuk' => 'bypass_pin',
                'catatan_override' => 'Kartu Rusak / Tidak Terbaca',
                'id_petugas_override' => 'SEC-01',
                'biaya' => 0,
            ],
        ];

        foreach ($transaksis as $t) {
            TransaksiParkir::updateOrCreate(['id_parkir' => $t['id_parkir']], $t);
        }
    }
}
