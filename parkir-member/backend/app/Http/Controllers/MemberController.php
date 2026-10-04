<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Kendaraan;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    /**
     * List and search members
     */
    public function index(Request $request)
    {
        $query = Member::with(['kendaraans', 'pembayarans']);

        if ($request->has('search') && !empty($request->search)) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_member', 'like', "%{$s}%")
                  ->orWhere('id_member', 'like', "%{$s}%")
                  ->orWhere('no_telp', 'like', "%{$s}%")
                  ->orWhere('qr_code', 'like', "%{$s}%")
                  ->orWhere('rfid_tag', 'like', "%{$s}%")
                  ->orWhereHas('kendaraans', function ($kq) use ($s) {
                      $kq->where('no_plat', 'like', "%{$s}%");
                  });
            });
        }

        if ($request->has('status') && !empty($request->status) && $request->status !== 'semua') {
            $now = Carbon::now()->toDateString();
            if ($request->status === 'aktif') {
                $query->where('status_member', 'aktif')
                      ->where('tgl_kadaluarsa', '>=', $now);
            } elseif ($request->status === 'akan_habis') {
                $query->where('status_member', 'aktif')
                      ->whereBetween('tgl_kadaluarsa', [$now, Carbon::now()->addDays(7)->toDateString()]);
            } elseif ($request->status === 'kadaluarsa') {
                $query->where(function ($q) use ($now) {
                    $q->where('status_member', 'kadaluarsa')
                      ->orWhere('tgl_kadaluarsa', '<', $now);
                });
            } elseif ($request->status === 'nonaktif') {
                $query->where('status_member', 'nonaktif');
            }
        }

        $members = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $members,
        ]);
    }

    /**
     * HIPO 1.1 + 1.2: Register new member and vehicle
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_member' => 'required|string|max:100',
            'no_telp' => 'required|string|max:25',
            'nik' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
            'qr_code' => 'nullable|string|unique:members,qr_code',
            'rfid_tag' => 'nullable|string|unique:members,rfid_tag',
            // Vehicle fields
            'no_plat' => 'required|string|max:20|unique:kendaraans,no_plat',
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'merk' => 'nullable|string|max:50',
            'warna' => 'nullable|string|max:50',
            'durasi_bulan' => 'nullable|integer|min:1',
            'metode_bayar' => 'nullable|string',
            'id_petugas' => 'nullable|string',
        ]);

        // Generate ID Member: MBR-YYYY-XXXX
        $count = Member::count() + 1;
        $idMember = 'MBR-' . date('Y') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        $durasi = $request->input('durasi_bulan', 1);
        $tglDaftar = Carbon::now()->toDateString();
        $tglKadaluarsa = Carbon::now()->addMonths($durasi)->toDateString();

        $member = Member::create([
            'id_member' => $idMember,
            'nama_member' => $request->nama_member,
            'nik' => $request->nik,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'rfid_tag' => $request->rfid_tag ?: ('RFID-' . strtoupper(Str::random(4)) . '-' . rand(10, 99)),
            'qr_code' => $request->qr_code ?: ('QR-' . $idMember),
            'tgl_daftar' => $tglDaftar,
            'tgl_kadaluarsa' => $tglKadaluarsa,
            'status_member' => 'aktif',
        ]);

        // HIPO 1.2: Register Vehicle
        $kendaraan = Kendaraan::create([
            'no_plat' => strtoupper(trim($request->no_plat)),
            'id_member' => $member->id_member,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'merk' => $request->merk,
            'warna' => $request->warna,
        ]);

        // Create initial payment record if requested
        $tarifPerBulan = $request->jenis_kendaraan === 'mobil' ? 150000 : 50000;
        $nominal = $tarifPerBulan * $durasi;
        $diskon = 0;
        if ($durasi >= 12) {
            $diskon = $nominal * 0.12;
        } elseif ($durasi >= 6) {
            $diskon = $nominal * 0.08;
        } elseif ($durasi >= 3) {
            $diskon = $nominal * 0.05;
        }
        $nominalAkhir = $nominal - $diskon;

        $idPembayaran = 'BYR-' . date('Y') . '-' . str_pad(Pembayaran::count() + 1, 4, '0', STR_PAD_LEFT);
        $pembayaran = Pembayaran::create([
            'id_pembayaran' => $idPembayaran,
            'id_member' => $member->id_member,
            'id_petugas' => $request->input('id_petugas', 'KSR-01'),
            'tgl_bayar' => Carbon::now(),
            'durasi_bulan' => $durasi,
            'tgl_mulai' => $tglDaftar,
            'tgl_kadaluarsa' => $tglKadaluarsa,
            'nominal' => $nominal,
            'diskon' => $diskon,
            'nominal_akhir' => $nominalAkhir,
            'metode_bayar' => $request->input('metode_bayar', 'tunai'),
            'catatan' => "Pendaftaran member baru paket {$durasi} bulan ({$request->jenis_kendaraan})",
        ]);

        $member->load(['kendaraans', 'pembayarans']);

        return response()->json([
            'success' => true,
            'message' => 'Member dan kendaraan berhasil didaftarkan.',
            'data' => [
                'member' => $member,
                'pembayaran' => $pembayaran,
            ],
        ], 201);
    }

    /**
     * Get single member details
     */
    public function show($id)
    {
        $member = Member::with(['kendaraans', 'pembayarans.petugas', 'transaksiParkirs'])
            ->where('id_member', $id)
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

        return response()->json([
            'success' => true,
            'data' => $member,
        ]);
    }

    /**
     * HIPO 1.3: Update member data & vehicles
     */
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'nama_member' => 'sometimes|string|max:100',
            'no_telp' => 'sometimes|string|max:25',
            'nik' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
            'rfid_tag' => 'nullable|string|unique:members,rfid_tag,' . $member->id_member . ',id_member',
            'status_member' => 'sometimes|in:aktif,kadaluarsa,nonaktif',
            'no_plat' => 'nullable|string|max:20',
            'jenis_kendaraan' => 'nullable|in:motor,mobil',
            'merk' => 'nullable|string|max:50',
            'warna' => 'nullable|string|max:50',
        ]);

        $member->update($request->only([
            'nama_member', 'nik', 'no_telp', 'alamat', 'rfid_tag', 'status_member'
        ]));

        if ($request->has('no_plat') && !empty($request->no_plat)) {
            $vehicle = $member->kendaraans->first();
            if ($vehicle) {
                $vehicle->update([
                    'no_plat' => strtoupper(trim($request->no_plat)),
                    'jenis_kendaraan' => $request->jenis_kendaraan ?: $vehicle->jenis_kendaraan,
                    'merk' => $request->merk !== null ? $request->merk : $vehicle->merk,
                    'warna' => $request->warna !== null ? $request->warna : $vehicle->warna,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data member berhasil diperbarui.',
            'data' => $member->fresh(['kendaraans', 'pembayarans']),
        ]);
    }

    /**
     * HIPO 1.4: Deactivate/Toggle member status
     */
    public function toggleStatus(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $newStatus = $request->input('status', $member->status_member === 'aktif' ? 'nonaktif' : 'aktif');
        $member->status_member = $newStatus;
        $member->save();

        return response()->json([
            'success' => true,
            'message' => "Status member berhasil diubah menjadi {$newStatus}.",
            'data' => $member,
        ]);
    }

    /**
     * QR Login: Member authentication & profile lookup via QR code
     */
    public function qrLogin(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $code = trim($request->qr_code);

        $member = Member::with(['kendaraans', 'pembayarans.petugas', 'transaksiParkirs'])
            ->where('qr_code', $code)
            ->orWhere('id_member', $code)
            ->orWhere('rfid_tag', $code)
            ->orWhereHas('kendaraans', function ($q) use ($code) {
                $q->where('no_plat', strtoupper($code));
            })
            ->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code Member tidak terdaftar atau tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login Member berhasil via QR Code.',
            'data' => $member,
        ]);
    }

    /**
     * Delete / Archive member
     */
    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data member berhasil dihapus.',
        ]);
    }
}
