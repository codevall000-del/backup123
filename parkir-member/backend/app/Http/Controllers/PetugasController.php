<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petugas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PetugasController extends Controller
{
    /**
     * Display a listing of petugas/accounts with optional role & search filtering.
     */
    public function index(Request $request)
    {
        $query = Petugas::query();

        // Search by nama, username, id_petugas, or pos_aktif
        if ($request->has('search') && !empty($request->search)) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('nama_petugas', 'like', "%{$s}%")
                  ->orWhere('username', 'like', "%{$s}%")
                  ->orWhere('id_petugas', 'like', "%{$s}%")
                  ->orWhere('pos_aktif', 'like', "%{$s}%");
            });
        }

        // Filter by role: 'admin', 'kasir', 'petugas' (operator or satpam), 'operator', 'satpam'
        if ($request->has('role') && !empty($request->role) && $request->role !== 'semua' && $request->role !== 'all') {
            $role = strtolower($request->role);
            if ($role === 'petugas') {
                $query->whereIn('peran', ['operator', 'petugas', 'satpam']);
            } elseif ($role === 'admin') {
                $query->where('peran', 'admin');
            } elseif ($role === 'kasir') {
                $query->where('peran', 'kasir');
            } else {
                $query->where('peran', $role);
            }
        }

        $petugas = $query->orderBy('id_petugas', 'asc')->get();

        // Statistics
        $totalAll = Petugas::count();
        $totalAdmin = Petugas::where('peran', 'admin')->count();
        $totalKasir = Petugas::where('peran', 'kasir')->count();
        $totalPetugas = Petugas::whereIn('peran', ['operator', 'petugas', 'satpam'])->count();

        return response()->json([
            'success' => true,
            'data' => $petugas,
            'meta' => [
                'total_semua' => $totalAll,
                'total_admin' => $totalAdmin,
                'total_kasir' => $totalKasir,
                'total_petugas' => $totalPetugas,
            ]
        ]);
    }

    /**
     * Store a newly created petugas/account.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:petugas,username',
            'password' => 'required|string|min:4',
            'peran' => 'required|in:admin,kasir,operator,satpam',
            'pin_petugas' => 'nullable|string|max:10',
            'pos_aktif' => 'nullable|string|max:100',
            'id_petugas' => 'nullable|string|max:20|unique:petugas,id_petugas',
        ]);

        $peran = strtolower($request->peran);

        // Auto-generate id_petugas if not provided
        $idPetugas = $request->id_petugas;
        if (empty($idPetugas)) {
            $prefix = match($peran) {
                'admin' => 'ADM',
                'kasir' => 'KSR',
                'satpam' => 'SEC',
                default => 'PTG',
            };

            // Find max numeric suffix
            $existing = Petugas::where('id_petugas', 'like', "{$prefix}-%")->get();
            $maxNum = 0;
            foreach ($existing as $p) {
                if (preg_match('/-(\d+)$/', $p->id_petugas, $m)) {
                    $num = (int)$m[1];
                    if ($num > $maxNum) $maxNum = $num;
                }
            }
            $idPetugas = sprintf('%s-%02d', $prefix, $maxNum + 1);
        }

        $defaultPos = match($peran) {
            'admin' => 'Pusat Administrasi & Pengawas',
            'kasir' => 'Loket Kasir',
            'satpam' => 'Pos Gerbang Masuk',
            default => 'Pos Gerbang Keluar',
        };

        $petugas = Petugas::create([
            'id_petugas' => strtoupper(trim($idPetugas)),
            'nama_petugas' => trim($request->nama_petugas),
            'username' => strtolower(trim($request->username)),
            'password' => Hash::make($request->password),
            'peran' => $peran,
            'pin_petugas' => $request->pin_petugas ?: '123456',
            'pos_aktif' => $request->pos_aktif ?: $defaultPos,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Akun {$peran} ({$petugas->id_petugas}) berhasil ditambahkan.",
            'data' => $petugas,
        ], 201);
    }

    /**
     * Display single petugas detail.
     */
    public function show($id)
    {
        $petugas = Petugas::with(['pembayarans', 'overrideParkirs'])->find($id);

        if (!$petugas) {
            return response()->json([
                'success' => false,
                'message' => 'Akun petugas tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $petugas,
        ]);
    }

    /**
     * Update petugas data.
     */
    public function update(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);

        $request->validate([
            'nama_petugas' => 'sometimes|string|max:100',
            'username' => 'sometimes|string|max:50|unique:petugas,username,' . $petugas->id_petugas . ',id_petugas',
            'password' => 'nullable|string|min:4',
            'peran' => 'sometimes|in:admin,kasir,operator,satpam',
            'pin_petugas' => 'nullable|string|max:10',
            'pos_aktif' => 'nullable|string|max:100',
        ]);

        $updateData = $request->only(['nama_petugas', 'peran', 'pin_petugas', 'pos_aktif']);

        if ($request->has('username') && !empty($request->username)) {
            $updateData['username'] = strtolower(trim($request->username));
        }

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $petugas->update($updateData);

        return response()->json([
            'success' => true,
            'message' => "Data akun {$petugas->id_petugas} berhasil diperbarui.",
            'data' => $petugas->fresh(),
        ]);
    }

    /**
     * Remove petugas/account.
     */
    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);

        // Security check: cannot delete the primary admin account
        if (strtoupper($petugas->id_petugas) === 'ADM-01' || $petugas->username === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Akun Administrator Utama (ADM-01) dilindungi dan tidak dapat dihapus.',
            ], 403);
        }

        // Security check: cannot delete if it is the only admin
        if ($petugas->peran === 'admin') {
            $adminCount = Petugas::where('peran', 'admin')->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun administrator terakhir dalam sistem.',
                ], 403);
            }
        }

        $idName = "{$petugas->id_petugas} ({$petugas->nama_petugas})";
        $petugas->delete();

        return response()->json([
            'success' => true,
            'message' => "Akun {$idName} berhasil dihapus dari sistem.",
        ]);
    }

    /**
     * Quick reset password
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:4',
        ]);

        $petugas = Petugas::findOrFail($id);
        $petugas->password = Hash::make($request->password);
        $petugas->save();

        return response()->json([
            'success' => true,
            'message' => "Kata sandi akun {$petugas->id_petugas} berhasil direset.",
        ]);
    }
}
