# SIP-Member (Sistem Informasi Parkir Khusus Member Berlangganan)
> **Tugas Proyek Perancangan HIPO & ERD — Kelompok 2 (Fase F • XI RPL 2)**  
> **Guru Pembimbing**: Bu Fadillah  
> **Tech Stack**: Fullstack Laravel 11 (Backend RESTful API) + Nuxt 4 (Frontend UI) + Tailwind CSS + SQLite Database

---

## 📂 Struktur Repositori

```text
mkkfadillah/
├── Desain Figma/              # File rancangan antarmuka & prompt desain Figma
├── ERD/                       # Diagram Entity-Relationship Diagram & keterangannya
├── HIPO/                      # Diagram Hierarchy plus Input-Process-Output
├── LKPD/                      # Lembar Kerja Peserta Didik
├── PPT/                       # Slide Presentasi Proyek Kelompok 2
├── LKPD HIPO, ERD.pdf         # Dokumen PDF LKPD & Analisis ERD/HIPO
├── stitch_sip_member_parking_system_ui.zip # Arsip UI Stitch
└── parkir-member/             # Source Code Aplikasi Fullstack
    ├── backend/               # Laravel 11 API (dilengkapi Database SQLite & SQL Dump)
    │   ├── database/
    │   │   ├── database.sqlite       # Database aktif berisi data siap pakai
    │   │   ├── database_dump.sql     # Backup export query SQL
    │   │   ├── migrations/           # Skema tabel database
    │   │   └── seeders/              # Dataset awal demo
    │   └── ...
    └── frontend/              # Nuxt 4 Web Application (Stitch UI)
        ├── app/
        │   ├── pages/         # 5 Layar Aplikasi (Login, Kios In, Pos Out, Kasir, Dashboard)
        │   └── ...
        └── ...
```

---

## 💻 Panduan Menjalankan di Komputer Lain (Step-by-Step)

Ketika Anda melakukan `git clone` di komputer baru/komputer lain, ikuti langkah berikut:

### 1. Clone Repositori
```bash
git clone https://github.com/codevall000-del/mkkfadillah.git
cd mkkfadillah
```

---

### 2. Menjalankan Backend (Laravel 11)

**Prasyarat**: PHP 8.2 atau lebih baru, Composer, dan ekstensi SQLite (pdo_sqlite).

1. Masuk ke direktori backend:
   ```bash
   cd parkir-member/backend
   ```

2. Pasang dependensi PHP via Composer:
   ```bash
   composer install
   ```

3. Siapkan file environment:
   ```bash
   # Windows PowerShell / CMD:
   copy .env.example .env

   # Linux / macOS:
   cp .env.example .env
   ```
   *(File `.env.example` sudah disetting dengan `APP_KEY` dan koneksi `DB_CONNECTION=sqlite` bawaan).*

4. Database SQLite (`database/database.sqlite`) **sudah langsung tersedia dan berisi data**.
   - Jika ingin me-reset data ke kondisi awal seeder:
     ```bash
     php artisan migrate:fresh --seed
     ```

5. Jalankan server Laravel:
   ```bash
   php artisan serve
   ```
   Backend API akan aktif di: **`http://127.0.0.1:8000`**

---

### 3. Menjalankan Frontend (Nuxt 4)

**Prasyarat**: Node.js 18+ dan npm.

1. Buka terminal baru, masuk ke direktori frontend:
   ```bash
   cd parkir-member/frontend
   ```

2. Pasang dependensi npm:
   ```bash
   npm install
   ```

3. Jalankan server development Nuxt:
   ```bash
   npm run dev
   ```

4. Buka browser dan akses:
   **`http://localhost:3000`**

---

## 🔑 Akun Demo Petugas untuk Pengujian

| Peran | Username / ID | Password | PIN Cepat | Pos Penugasan |
|---|---|---|---|---|
| **Admin** | `ADM-01` atau `admin` | `admin123` | `123456` | Pusat Administrasi & Monitoring |
| **Kasir** | `KSR-01` atau `kasir` | `kasir123` | `123456` | Loket Kasir Utama |
| **Operator** | `OPR-01` atau `operator` | `operator123` | `123456` | Pos Gerbang Keluar 01 |
| **Satpam Jaga** | `SEC-01` atau `satpam` | `satpam123` | `998877` | Kios Gerbang Masuk (Bypass PIN) |

---

## 🎯 Ringkasan Layar Utama Aplikasi
1. **Screen 01: Portal Login & Dual Gateway** (`/`) - Autentikasi petugas dengan kredensial atau PIN cepat.
2. **Screen 02: Kios Mandiri Gerbang Masuk** (`/kios-gatein`) - Tap kartu RFID member mandiri dan bypass PIN petugas satpam jaga.
3. **Screen 03: Pos Operator Gerbang Keluar** (`/pos-gateout`) - Verifikasi kamera LPR, pencocokan plat kendaraan, dan pembukaan palang otomatis (biaya Rp 0,- untuk member).
4. **Screen 04: Loket Kasir & Administrasi** (`/kasir`) - Pendaftaran member baru, registrasi plat & RFID, perpanjangan masa aktif bulanan (1, 3, 6, 12 bulan), serta cetak kuitansi.
5. **Screen 05: Dashboard Admin & Laporan** (`/dashboard`) - Grafik pendapatan iuran bulanan, statistik kunjungan, rasio armada, dan feed aktivitas gerbang real-time.
6. **Screen 06: Pengaturan Member** (`/member`) - Master data keanggotaan dan penerbitan kartu digital QR.
7. **Screen 07: Kelola Akun (Admin, Kasir, Petugas)** (`/kelola-akun`) - Manajemen akun login, peran (Admin, Kasir, Petugas Gerbang, Petugas Keamanan), PIN otorisasi bypass gerbang, dan lokasi pos penugasan.
