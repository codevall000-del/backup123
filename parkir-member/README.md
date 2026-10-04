# SIP-Member (Sistem Informasi Parkir Khusus Member Berlangganan)
> **Tugas Proyek Perancangan HIPO & ERD — Kelompok 2 (Fase F • XI RPL 2)**  
> **Guru Pembimbing**: Bu Fadillah  
> **Tech Stack**: Fullstack Laravel 11 (Backend API) + Nuxt 4 (Frontend UI) + Tailwind CSS + Stitch Design System

---

## 📁 Struktur Direktori

```text
parkir-member/
├── backend/                   # Laravel 11 Application (RESTful API)
│   ├── app/
│   │   ├── Http/Controllers/  # Auth, Member, Pembayaran, Gate, Dashboard
│   │   └── Models/            # Petugas, Member, Kendaraan, Pembayaran, TransaksiParkir
│   ├── database/
│   │   ├── migrations/        # ERD database schema
│   │   └── seeders/           # Realistic demo dataset
│   ├── routes/api.php         # 23 RESTful API Endpoints
│   └── database/database.sqlite
│
├── frontend/                  # Nuxt 4 Application (Stitch UI Design)
│   ├── app/
│   │   ├── assets/css/        # Tailwind directives, animations & print styles
│   │   ├── components/        # TopNav screen navigator
│   │   ├── composables/       # useApi (API integration + offline simulation)
│   │   ├── pages/
│   │   │   ├── index.vue      # Screen 01: Portal Login & Dual Gateway
│   │   │   ├── kios-gatein.vue# Screen 02: Kios Mandiri Gerbang Masuk
│   │   │   ├── pos-gateout.vue# Screen 03: Pos Operator Gerbang Keluar
│   │   │   ├── kasir.vue      # Screen 04: Loket Kasir & Administrasi
│   │   │   └── dashboard.vue  # Screen 05: Dashboard Admin & Laporan
│   │   └── app.vue            # Root App Component
│   ├── nuxt.config.ts         # Google Fonts, Material Symbols, Tailwind Config
│   └── tailwind.config.js     # Custom design system tokens
│
└── README.md                  # Dokumentasi Proyek
```

---

## 🚀 Panduan Menjalankan Proyek

### 1. Menjalankan Backend (Laravel)
Pastikan PHP 8.2+ dan Composer sudah terpasang di komputer Anda.

```bash
# Masuk ke direktori backend
cd parkir-member/backend

# Jalankan migrasi dan seeder database (SQLite otomatis siap pakai)
php artisan migrate --seed

# Jalankan server API backend
php artisan serve
```
Backend API akan berjalan di: **`http://127.0.0.1:8000`**

### 2. Menjalankan Frontend (Nuxt)
Pastikan Node.js 18+ dan npm sudah terpasang.

```bash
# Masuk ke direktori frontend
cd parkir-member/frontend

# Jalankan server frontend Nuxt development
npm run dev
```
Buka browser Anda dan akses: **`http://localhost:3000`**

---

## 🔑 Akun Demo Petugas untuk Evaluasi & Pengujian

| Peran | Username / ID | Password | PIN Cepat | Pos Penugasan |
|---|---|---|---|---|
| **Admin** | `ADM-01` atau `admin` | `admin123` | `123456` | Pusat Administrasi & Monitoring |
| **Kasir** | `KSR-01` atau `kasir` | `kasir123` | `123456` | Loket Kasir Utama |
| **Operator** | `OPR-01` atau `operator` | `operator123` | `123456` | Pos Gerbang Keluar 01 |
| **Satpam Jaga** | `SEC-01` atau `satpam` | `satpam123` | `998877` | Kios Gerbang Masuk (Bypass PIN) |

---

## 🎯 Pemetaan Modul HIPO & ERD ke Layar Aplikasi

### **1.0 Kelola Data Member** (`/kasir`)
- **1.1 Input Data Member**: Formulir identitas lengkap (Nama, NIK, No. HP, Alamat).
- **1.2 Registrasi Plat Kendaraan**: Integrasi jenis kendaraan (Motor Rp 50.000 / Mobil Rp 150.000) dan penugasan RFID serial reader.
- **1.3 Ubah Data Member**: Update kontak, alamat, dan nomor plat.
- **1.4 Nonaktifkan Member**: Toggle status aktif/nonaktif member.

### **2.0 Kelola Pembayaran Iuran** (`/kasir`)
- **2.1 Cek Tagihan Member**: Pencarian instan member berdasarkan nama, plat, ID, atau tap RFID.
- **2.2 Input Bayar Bulanan**: Pilihan paket 1 bulan, 3 bulan (diskon 5%), 6 bulan (diskon 8%), atau 12 bulan (diskon 12%).
- **2.3 Perpanjang Masa Aktif**: Perpanjangan otomatis masa berlaku kartu ke tanggal baru.
- **2.4 Cetak Kuitansi Bayar**: Modal kuitansi resmi bukti pembayaran iuran bulanan yang siap dicetak ke printer (`window.print()`).

### **3.0 Kelola Parkir Masuk** (`/kios-gatein`)
- **3.1 Scan Kartu di Gerbang**: Kios mandiri dengan animasi gelombang RFID contactless.
- **3.2 Cek Status Aktif Member**: Validasi real-time status kartu (Member Aktif, Kadaluarsa, Tidak Dikenal).
- **3.3 Catat Waktu Masuk**: Penyimpanan timestamp kedatangan kendaraan di database.
- **3.4 Buka Palang Masuk**: Animasi mekanik palang SVG terbuka dengan countdown auto-close.
- **Otorisasi Cepat Petugas (Bypass PIN)**: Keypad numerik virtual 6-digit untuk satpam jaga dengan pilihan alasan override.

### **4.0 Kelola Parkir Keluar** (`/pos-gateout`)
- **4.1 Scan Kartu di Gerbang**: Manned booth dengan dual camera feed (LPR Zoom + CCTV Lane).
- **4.2 Validasi Sesi Masuk (Security Check)**: Perbandingan otomatis antara plat terdaftar di kartu vs plat terbaca kamera LPR (`MATCH - COCOK` / `TIDAK COCOK`).
- **4.3 Catat Waktu Keluar**: Perhitungan otomatis durasi parkir (contoh: 2 Jam 15 Menit) dan biaya Rp 0,- (GRATIS bagi member).
- **4.4 Buka Palang Keluar**: Tombol konfirmasi rilis palang keluar (didukung tombol shortcut `ENTER` / `SPASI`).

### **5.0 Kelola Laporan** (`/dashboard`)
- **5.1 Laporan Iuran Bulanan**: Grafik batang perbandingan pendapatan iuran 6 bulan terakhir terhadap garis target (Rp 40.000.000,-) beserta tabel rekapitulasi.
- **5.2 Laporan Kunjungan Parkir**: Statistik rata-rata durasi parkir, peak hour (07:00-09:00 WIB), jumlah kartu ditolak, jumlah bypass PIN, dan tren kunjungan 7 hari.
- **Feed Aktivitas Gate Real-Time**: Log audit aktivitas keluar-masuk kendaraan auto-refresh per 10 detik dengan filter gate dan status.

---

## 🎨 Design System & Visual Stitch Integration
- **Tipografi**: Plus Jakarta Sans (Header & UI Label) + JetBrains Mono (Plat Kendaraan, ID, Waktu, Nilai Nominal).
- **Palette**: Indigo (#4F46E5 Brand), Emerald (#10B981 Active), Amber (#F59E0B Warning/Bypass), Rose (#EF4444 Expired/Emergency), Slate (#0F172A Dark Kiosk).
- **Interaktivitas**: Top Navigator memungkinkan penguji berpindah antar 5 layar secara instan dengan state yang tersinkronisasi.
- **Offline Fallback**: Apabila server backend belum dinyalakan, frontend secara otomatis beralih ke simulasi offline terpadu sehingga seluruh demonstrasi tetap dapat dijalankan tanpa hambatan.
