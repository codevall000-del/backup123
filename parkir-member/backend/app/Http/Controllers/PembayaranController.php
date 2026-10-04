<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Member;
use App\Models\Kendaraan;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    /**
     * List payment history
     */
    public function index(Request $request)
    {
        $query = Pembayaran::with(['member.kendaraans', 'petugas']);

        if ($request->has('search') && !empty($request->search)) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('id_pembayaran', 'like', "%{$s}%")
                  ->orWhere('id_member', 'like', "%{$s}%")
                  ->orWhereHas('member', function ($mq) use ($s) {
                      $mq->where('nama_member', 'like', "%{$s}%");
                  });
            });
        }

        $payments = $query->orderBy('tgl_bayar', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $payments,
        ]);
    }

    /**
     * HIPO 2.1: Check member subscription status & calculate options
     */
    public function checkMember(Request $request, $id)
    {
        $member = Member::with(['kendaraans', 'pembayarans'])->where('id_member', $id)
            ->orWhere('rfid_tag', $id)
            ->orWhereHas('kendaraans', function ($q) use ($id) {
                $q->where('no_plat', $id);
            })
            ->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member tidak ditemukan.',
            ], 404);
        }

        $vehicle = $member->kendaraans->first();
        $jenis = $vehicle ? $vehicle->jenis_kendaraan : 'mobil';
        $rate = $jenis === 'mobil' ? 150000 : 50000;

        // Packages options
        $packages = [
            [
                'durasi' => 1,
                'label' => '1 Bulan',
                'diskon_persen' => 0,
                'harga_asli' => $rate * 1,
                'diskon' => 0,
                'total' => $rate * 1,
                'badge' => 'Standar',
            ],
            [
                'durasi' => 3,
                'label' => '3 Bulan',
                'diskon_persen' => 5,
                'harga_asli' => $rate * 3,
                'diskon' => ($rate * 3) * 0.05,
                'total' => ($rate * 3) * 0.95,
                'badge' => 'Hemat 5%',
            ],
            [
                'durasi' => 6,
                'label' => '6 Bulan',
                'diskon_persen' => 8,
                'harga_asli' => $rate * 6,
                'diskon' => ($rate * 6) * 0.08,
                'total' => ($rate * 6) * 0.92,
                'badge' => 'Hemat 8%',
            ],
            [
                'durasi' => 12,
                'label' => '12 Bulan (1 Tahun)',
                'diskon_persen' => 12,
                'harga_asli' => $rate * 12,
                'diskon' => ($rate * 12) * 0.12,
                'total' => ($rate * 12) * 0.88,
                'badge' => 'Paling Hemat 12%',
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'member' => $member,
                'kendaraan' => $vehicle,
                'packages' => $packages,
            ],
        ]);
    }

    /**
     * HIPO 2.2 + 2.3: Process monthly subscription renewal and extend expiry
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_member' => 'required|exists:members,id_member',
            'durasi_bulan' => 'required|integer|min:1',
            'metode_bayar' => 'required|string',
            'id_petugas' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $member = Member::with('kendaraans')->findOrFail($request->id_member);
        $durasi = (int) $request->durasi_bulan;

        $vehicle = $member->kendaraans->first();
        $tarifBulanan = ($vehicle && $vehicle->jenis_kendaraan === 'motor') ? 50000 : 150000;
        $nominal = $tarifBulanan * $durasi;

        // Diskon calculation
        $diskon = 0;
        if ($durasi >= 12) {
            $diskon = $nominal * 0.12;
        } elseif ($durasi >= 6) {
            $diskon = $nominal * 0.08;
        } elseif ($durasi >= 3) {
            $diskon = $nominal * 0.05;
        }
        $nominalAkhir = $nominal - $diskon;

        // Calculate dates
        // If current expiry is in the future, add months from that date; otherwise from today
        $now = Carbon::now();
        $startDate = $now->toDateString();
        if ($member->tgl_kadaluarsa && Carbon::parse($member->tgl_kadaluarsa)->isFuture()) {
            $startDate = $member->tgl_kadaluarsa;
            $newExpiry = Carbon::parse($member->tgl_kadaluarsa)->addMonths($durasi)->toDateString();
        } else {
            $newExpiry = $now->copy()->addMonths($durasi)->toDateString();
        }

        // Generate ID: BYR-YYYY-XXXX
        $count = Pembayaran::count() + 1;
        $idPembayaran = 'BYR-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $pembayaran = Pembayaran::create([
            'id_pembayaran' => $idPembayaran,
            'id_member' => $member->id_member,
            'id_petugas' => $request->input('id_petugas', 'KSR-01'),
            'tgl_bayar' => Carbon::now(),
            'durasi_bulan' => $durasi,
            'tgl_mulai' => $startDate,
            'tgl_kadaluarsa' => $newExpiry,
            'nominal' => $nominal,
            'diskon' => $diskon,
            'nominal_akhir' => $nominalAkhir,
            'metode_bayar' => $request->metode_bayar,
            'catatan' => $request->catatan ?: "Perpanjangan iuran {$durasi} bulan",
        ]);

        // HIPO 2.3: Perpanjang Masa Aktif
        $member->tgl_kadaluarsa = $newExpiry;
        $member->status_member = 'aktif';
        $member->save();

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diproses dan masa aktif diperpanjang.',
            'data' => [
                'pembayaran' => $pembayaran->load(['member.kendaraans', 'petugas']),
                'member' => $member,
            ],
        ], 201);
    }

    /**
     * HIPO 2.4: Cetak Kuitansi Bayar
     */
    public function receipt($id)
    {
        $pembayaran = Pembayaran::with(['member.kendaraans', 'petugas'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'receipt' => [
                    'no_transaksi' => $pembayaran->id_pembayaran,
                    'tgl_bayar' => $pembayaran->tgl_bayar->format('d/m/Y H:i:s'),
                    'member' => [
                        'id' => $pembayaran->member->id_member,
                        'nama' => $pembayaran->member->nama_member,
                        'no_telp' => $pembayaran->member->no_telp,
                        'kendaraan' => $pembayaran->member->kendaraans->first() ? [
                            'no_plat' => $pembayaran->member->kendaraans->first()->no_plat,
                            'jenis' => $pembayaran->member->kendaraans->first()->jenis_kendaraan,
                            'merk' => $pembayaran->member->kendaraans->first()->merk,
                        ] : null,
                    ],
                    'durasi_bulan' => $pembayaran->durasi_bulan,
                    'tgl_mulai' => Carbon::parse($pembayaran->tgl_mulai)->format('d M Y'),
                    'tgl_kadaluarsa_baru' => Carbon::parse($pembayaran->tgl_kadaluarsa)->format('d M Y'),
                    'nominal' => $pembayaran->nominal,
                    'diskon' => $pembayaran->diskon,
                    'nominal_akhir' => $pembayaran->nominal_akhir,
                    'metode_bayar' => strtoupper($pembayaran->metode_bayar),
                    'petugas' => $pembayaran->petugas ? $pembayaran->petugas->nama_petugas : 'Kasir Utama',
                ],
            ],
        ]);
    }
}
