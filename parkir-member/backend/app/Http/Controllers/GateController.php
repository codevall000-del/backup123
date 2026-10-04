<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Kendaraan;
use App\Models\TransaksiParkir;
use App\Models\Petugas;
use Carbon\Carbon;

class GateController extends Controller
{
    /**
     * HIPO 3.1 - 3.4: Gate-In Check (RFID / QR / Plate)
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string', // RFID, QR, or No Plat
            'gerbang_masuk' => 'nullable|string',
            'lpr_plate' => 'nullable|string',
        ]);

        $id = trim($request->identifier);
        $gerbang = $request->input('gerbang_masuk', 'GATE-IN 01');

        // Look up member by QR Code, ID Member, Plate, or RFID
        $member = Member::with('kendaraans')
            ->where('qr_code', $id)
            ->orWhere('id_member', $id)
            ->orWhere('rfid_tag', $id)
            ->orWhereHas('kendaraans', function ($q) use ($id) {
                $q->where('no_plat', strtoupper($id));
            })
            ->first();

        // 1. Not Found
        if (!$member) {
            // Log rejected attempt
            $transaksi = TransaksiParkir::create([
                'id_parkir' => 'PRK-' . date('Y') . '-' . str_pad(TransaksiParkir::count() + 1, 6, '0', STR_PAD_LEFT),
                'id_member' => null,
                'no_plat' => strtoupper($request->input('lpr_plate', $id)),
                'waktu_masuk' => Carbon::now(),
                'gerbang_masuk' => $gerbang,
                'status_parkir' => 'ditolak',
                'metode_masuk' => 'scan_qr',
                'catatan_override' => 'QR Code Member tidak terdaftar dalam sistem',
            ]);

            return response()->json([
                'success' => false,
                'status' => 'tidak_dikenal',
                'palang' => 'tertutup',
                'message' => 'QR Code Tidak Terdaftar. Silakan registrasi terlebih dahulu di loket kasir / menu kelola member.',
                'data' => [
                    'identifier' => $id,
                    'timestamp' => Carbon::now()->format('H:i:s WIB'),
                ],
            ], 404);
        }

        $vehicle = $member->kendaraans->first();
        $noPlat = $vehicle ? $vehicle->no_plat : ($request->input('lpr_plate', 'TANPA-PLAT'));

        // 2. Expired / Inactive check (HIPO 3.2)
        if (!$member->is_active) {
            $transaksi = TransaksiParkir::create([
                'id_parkir' => 'PRK-' . date('Y') . '-' . str_pad(TransaksiParkir::count() + 1, 6, '0', STR_PAD_LEFT),
                'id_member' => $member->id_member,
                'no_plat' => $noPlat,
                'waktu_masuk' => Carbon::now(),
                'gerbang_masuk' => $gerbang,
                'status_parkir' => 'ditolak',
                'metode_masuk' => 'scan_qr',
                'catatan_override' => 'Masa aktif QR Code member telah kadaluarsa (' . Carbon::parse($member->tgl_kadaluarsa)->format('d M Y') . ')',
            ]);

            return response()->json([
                'success' => false,
                'status' => 'kadaluarsa',
                'palang' => 'tertutup',
                'message' => 'QR Code Member Kadaluarsa! Masa aktif berakhir pada ' . Carbon::parse($member->tgl_kadaluarsa)->format('d M Y'),
                'data' => [
                    'member' => $member,
                    'kendaraan' => $vehicle,
                    'tgl_kadaluarsa' => $member->tgl_kadaluarsa,
                    'sisa_hari' => $member->sisa_hari,
                ],
            ], 403);
        }

        // 3. Member Aktif -> Success! (HIPO 3.3 Catat Waktu Masuk & 3.4 Buka Palang)
        $idParkir = 'PRK-' . date('Y') . '-' . str_pad(TransaksiParkir::count() + 1, 6, '0', STR_PAD_LEFT);
        $transaksi = TransaksiParkir::create([
            'id_parkir' => $idParkir,
            'id_member' => $member->id_member,
            'no_plat' => $noPlat,
            'waktu_masuk' => Carbon::now(),
            'gerbang_masuk' => $gerbang,
            'status_parkir' => 'masuk',
            'metode_masuk' => 'scan_qr',
            'biaya' => 0,
        ]);

        return response()->json([
            'success' => true,
            'status' => 'aktif',
            'palang' => 'terbuka',
            'message' => 'QR Code Terverifikasi! Palang terbuka, silakan masuk.',
            'data' => [
                'transaksi' => $transaksi,
                'member' => $member,
                'kendaraan' => $vehicle,
                'sisa_hari' => $member->sisa_hari,
                'tgl_kadaluarsa' => Carbon::parse($member->tgl_kadaluarsa)->format('d F Y'),
            ],
        ]);
    }

    /**
     * Tombol Masuk / Cetak Tiket Parkir Tamu (Non-Member)
     */
    public function tiketMasuk(Request $request)
    {
        $gerbang = $request->input('gerbang_masuk', 'GATE-IN 01');
        $jenis = strtolower($request->input('jenis_kendaraan', 'mobil'));
        $suffix = $jenis === 'motor' ? 'MTR' : 'MBL';
        $noPlat = strtoupper(trim($request->input('lpr_plate', 'B ' . rand(1000, 9999) . ' ' . $suffix)));
        
        $idParkir = 'TKT-' . date('Ymd') . '-' . str_pad(TransaksiParkir::count() + 1, 4, '0', STR_PAD_LEFT);

        $transaksi = TransaksiParkir::create([
            'id_parkir' => $idParkir,
            'id_member' => null,
            'no_plat' => $noPlat,
            'waktu_masuk' => Carbon::now(),
            'gerbang_masuk' => $gerbang,
            'status_parkir' => 'masuk',
            'metode_masuk' => 'tombol_tiket',
            'biaya' => 0,
        ]);

        return response()->json([
            'success' => true,
            'status' => 'tiket_umum',
            'palang' => 'terbuka',
            'message' => 'Tiket parkir diterbitkan! Palang terbuka, silakan masuk.',
            'data' => [
                'transaksi' => $transaksi,
                'no_tiket' => $idParkir,
                'no_plat' => $noPlat,
                'jenis_kendaraan' => strtoupper($jenis),
                'waktu_masuk' => Carbon::now()->format('H:i:s \W\I\B'),
                'tanggal' => Carbon::now()->format('d F Y'),
            ],
        ]);
    }

    /**
     * Rapid PIN Override for Gate-In (Satpam / Petugas Jaga Bypass)
     */
    public function overrideIn(Request $request)
    {
        $request->validate([
            'pin' => 'required|string',
            'alasan' => 'required|string',
            'no_plat' => 'nullable|string',
            'gerbang_masuk' => 'nullable|string',
        ]);

        $petugas = Petugas::where('pin_petugas', $request->pin)->first();
        if (!$petugas) {
            return response()->json([
                'success' => false,
                'message' => 'PIN Petugas tidak valid.',
            ], 401);
        }

        $noPlat = strtoupper(trim($request->input('no_plat', 'B 7777 DEF')));
        $member = Member::whereHas('kendaraans', function ($q) use ($noPlat) {
            $q->where('no_plat', $noPlat);
        })->first();

        $idParkir = 'PRK-' . date('Y') . '-' . str_pad(TransaksiParkir::count() + 1, 6, '0', STR_PAD_LEFT);
        $transaksi = TransaksiParkir::create([
            'id_parkir' => $idParkir,
            'id_member' => $member ? $member->id_member : null,
            'no_plat' => $noPlat,
            'waktu_masuk' => Carbon::now(),
            'gerbang_masuk' => $request->input('gerbang_masuk', 'GATE-IN 01'),
            'status_parkir' => 'bypass_pin',
            'metode_masuk' => 'bypass_pin',
            'catatan_override' => $request->alasan,
            'id_petugas_override' => $petugas->id_petugas,
            'biaya' => 0,
        ]);

        return response()->json([
            'success' => true,
            'status' => 'bypass_pin',
            'palang' => 'terbuka',
            'message' => 'Override PIN Petugas berhasil! Palang terbuka.',
            'data' => [
                'transaksi' => $transaksi,
                'petugas' => [
                    'id' => $petugas->id_petugas,
                    'nama' => $petugas->nama_petugas,
                    'peran' => $petugas->peran,
                ],
                'alasan' => $request->alasan,
            ],
        ]);
    }

    /**
     * HIPO 4.1 - 4.2: Gate-Out Scan & Validation
     */
    public function scanOut(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string', // RFID, plate, or session ID
            'lpr_plate' => 'nullable|string',
        ]);

        $id = trim($request->identifier);
        $lprPlate = strtoupper(trim($request->input('lpr_plate', '')));

        // Find active session
        $session = TransaksiParkir::with(['member.kendaraans', 'kendaraan'])
            ->where(function ($q) use ($id) {
                $q->where('id_parkir', $id)
                  ->orWhere('no_plat', strtoupper($id))
                  ->orWhereHas('member', function ($mq) use ($id) {
                      $mq->where('qr_code', $id)->orWhere('id_member', $id)->orWhere('rfid_tag', $id);
                  });
            })
            ->whereNull('waktu_keluar')
            ->orderBy('waktu_masuk', 'desc')
            ->first();

        if (!$session) {
            // Check if there was any member matching this plate/QR/RFID
            $member = Member::with('kendaraans')->where('qr_code', $id)
                ->orWhere('id_member', $id)
                ->orWhere('rfid_tag', $id)
                ->orWhereHas('kendaraans', function ($q) use ($id) {
                    $q->where('no_plat', strtoupper($id));
                })->first();

            return response()->json([
                'success' => false,
                'message' => 'Tidak ditemukan sesi parkir aktif untuk kendaraan ini.',
                'data' => [
                    'member' => $member,
                    'identifier' => $id,
                ],
            ], 404);
        }

        $now = Carbon::now();
        $durasiMenit = $session->waktu_masuk->diffInMinutes($now);
        $jam = floor($durasiMenit / 60);
        $menit = $durasiMenit % 60;
        $durasiStr = ($jam > 0 ? "{$jam} Jam " : "") . "{$menit} Menit";

        $registeredPlate = $session->no_plat;
        $isPlateMatch = empty($lprPlate) || ($registeredPlate === $lprPlate);

        return response()->json([
            'success' => true,
            'data' => [
                'session' => $session,
                'member' => $session->member,
                'kendaraan' => $session->kendaraan ?: ($session->member ? $session->member->kendaraans->first() : null),
                'durasi_menit' => $durasiMenit,
                'durasi_format' => $durasiStr,
                'waktu_masuk_format' => $session->waktu_masuk->format('H:i:s \W\I\B'),
                'waktu_keluar_sekarang' => $now->format('H:i:s \W\I\B'),
                'biaya' => 0,
                'biaya_text' => 'Rp 0,- (GRATIS - Member Berlangganan)',
                'registered_plate' => $registeredPlate,
                'lpr_plate' => $lprPlate ?: $registeredPlate,
                'is_plate_match' => $isPlateMatch,
            ],
        ]);
    }

    /**
     * HIPO 4.3 - 4.4: Confirm Check-Out & Release Barrier
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'id_parkir' => 'required|exists:transaksi_parkirs,id_parkir',
            'gerbang_keluar' => 'nullable|string',
            'id_petugas' => 'nullable|string',
        ]);

        $session = TransaksiParkir::findOrFail($request->id_parkir);

        if ($session->waktu_keluar) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi parkir ini sudah diselesaikan sebelumnya.',
            ], 400);
        }

        $now = Carbon::now();
        $durasiMenit = $session->waktu_masuk->diffInMinutes($now);

        $session->waktu_keluar = $now;
        $session->durasi_menit = $durasiMenit;
        $session->gerbang_keluar = $request->input('gerbang_keluar', 'GATE-OUT 01');
        $session->status_parkir = 'keluar';
        $session->save();

        return response()->json([
            'success' => true,
            'palang' => 'terbuka',
            'message' => 'Palang Keluar Terbuka! Sesi parkir selesai.',
            'data' => [
                'session' => $session->load('member'),
                'durasi_menit' => $durasiMenit,
            ],
        ]);
    }

    /**
     * Emergency Open Barrier
     */
    public function emergencyOpen(Request $request)
    {
        return response()->json([
            'success' => true,
            'palang' => 'terbuka_darurat',
            'message' => 'Palang darurat berhasil diaktifkan secara manual.',
            'timestamp' => Carbon::now()->format('H:i:s WIB'),
        ]);
    }

    /**
     * Active vehicles currently inside
     */
    public function activeSessions()
    {
        $sessions = TransaksiParkir::with(['member.kendaraans'])
            ->whereNull('waktu_keluar')
            ->whereIn('status_parkir', ['masuk', 'bypass_pin'])
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $sessions->count(),
            'data' => $sessions,
        ]);
    }
}
