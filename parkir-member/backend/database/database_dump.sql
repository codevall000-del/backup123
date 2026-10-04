-- Database Dump for SIP Parkir Member
-- Generated: 2026-10-04 16:06:00

PRAGMA foreign_keys = OFF;

-- Table structure for migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE "migrations" ("id" integer primary key autoincrement not null, "migration" varchar not null, "batch" integer not null);

-- Dumping data for migrations
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2026_10_04_000002_create_sip_parkir_member_tables', '2');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2026_10_04_025222_create_personal_access_tokens_table', '2');

-- Table structure for users
DROP TABLE IF EXISTS `users`;
CREATE TABLE "users" ("id" integer primary key autoincrement not null, "name" varchar not null, "email" varchar not null, "email_verified_at" datetime, "password" varchar not null, "remember_token" varchar, "created_at" datetime, "updated_at" datetime);

-- Table structure for password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE "password_reset_tokens" ("email" varchar not null, "token" varchar not null, "created_at" datetime, primary key ("email"));

-- Table structure for sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE "sessions" ("id" varchar not null, "user_id" integer, "ip_address" varchar, "user_agent" text, "payload" text not null, "last_activity" integer not null, primary key ("id"));

-- Table structure for cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE "cache" ("key" varchar not null, "value" text not null, "expiration" integer not null, primary key ("key"));

-- Table structure for cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE "cache_locks" ("key" varchar not null, "owner" varchar not null, "expiration" integer not null, primary key ("key"));

-- Table structure for jobs
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE "jobs" ("id" integer primary key autoincrement not null, "queue" varchar not null, "payload" text not null, "attempts" integer not null, "reserved_at" integer, "available_at" integer not null, "created_at" integer not null);

-- Table structure for job_batches
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE "job_batches" ("id" varchar not null, "name" varchar not null, "total_jobs" integer not null, "pending_jobs" integer not null, "failed_jobs" integer not null, "failed_job_ids" text not null, "options" text, "cancelled_at" integer, "created_at" integer not null, "finished_at" integer, primary key ("id"));

-- Table structure for failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE "failed_jobs" ("id" integer primary key autoincrement not null, "uuid" varchar not null, "connection" varchar not null, "queue" varchar not null, "payload" text not null, "exception" text not null, "failed_at" datetime not null default CURRENT_TIMESTAMP);

-- Table structure for petugas
DROP TABLE IF EXISTS `petugas`;
CREATE TABLE "petugas" ("id_petugas" varchar not null, "nama_petugas" varchar not null, "username" varchar not null, "password" varchar not null, "peran" varchar not null default 'operator', "pin_petugas" varchar not null default '123456', "pos_aktif" varchar, "remember_token" varchar, "created_at" datetime, "updated_at" datetime, primary key ("id_petugas"));

-- Dumping data for petugas
INSERT INTO `petugas` (`id_petugas`, `nama_petugas`, `username`, `password`, `peran`, `pin_petugas`, `pos_aktif`, `remember_token`, `created_at`, `updated_at`) VALUES ('ADM-01', 'Administrator', 'admin', '$2y$12$9bDzsw3DPqKxBGvCKaQWGOThCCTIjQ2j1tiM.NOCfNsKUq9Li5ayq', 'admin', '123456', 'Pusat Administrasi', NULL, '2026-10-04 02:53:30', '2026-10-04 03:36:25');
INSERT INTO `petugas` (`id_petugas`, `nama_petugas`, `username`, `password`, `peran`, `pin_petugas`, `pos_aktif`, `remember_token`, `created_at`, `updated_at`) VALUES ('KSR-01', 'Petugas Kasir', 'kasir', '$2y$12$3jmaPUXOxE0ZPxXoGZ70peE1.aocUS7Pcjmo5pATfx2kLpwiv4RsG', 'kasir', '123456', 'Loket Kasir', NULL, '2026-10-04 02:53:31', '2026-10-04 03:32:30');
INSERT INTO `petugas` (`id_petugas`, `nama_petugas`, `username`, `password`, `peran`, `pin_petugas`, `pos_aktif`, `remember_token`, `created_at`, `updated_at`) VALUES ('OPR-01', 'Petugas Gerbang', 'operator', '$2y$12$qXHzXy5GgbjGmAUgSHNo7.5hdZNpifRn.M.2V26ZlkUIRYzHGLuSC', 'operator', '123456', 'Pos Gerbang Keluar', NULL, '2026-10-04 02:53:31', '2026-10-04 03:32:30');
INSERT INTO `petugas` (`id_petugas`, `nama_petugas`, `username`, `password`, `peran`, `pin_petugas`, `pos_aktif`, `remember_token`, `created_at`, `updated_at`) VALUES ('SEC-01', 'Petugas Keamanan', 'satpam', '$2y$12$f42Cnw.aGLBDR1Ot5U8FrO/Pw8NyntAlOvIWI0BLTQGnDYU/bkahS', 'satpam', '998877', 'Pos Gerbang Masuk', NULL, '2026-10-04 02:53:31', '2026-10-04 03:32:30');
INSERT INTO `petugas` (`id_petugas`, `nama_petugas`, `username`, `password`, `peran`, `pin_petugas`, `pos_aktif`, `remember_token`, `created_at`, `updated_at`) VALUES ('PETUGAS-01', 'Petugas Operasional', 'petugas', '$2y$12$ZZoUQ6Kh4L/QZDlUcEmb5.POu8YJt8VPqhMYFwx45PPGrtA1rKlPe', 'operator', '123456', 'Pos Gerbang Keluar', NULL, '2026-10-04 03:24:24', '2026-10-04 03:32:30');

-- Table structure for members
DROP TABLE IF EXISTS `members`;
CREATE TABLE "members" ("id_member" varchar not null, "nama_member" varchar not null, "nik" varchar, "no_telp" varchar not null, "alamat" text, "rfid_tag" varchar, "qr_code" varchar, "tgl_daftar" date not null, "tgl_kadaluarsa" date not null, "status_member" varchar not null default 'aktif', "created_at" datetime, "updated_at" datetime, primary key ("id_member"));

-- Dumping data for members
INSERT INTO `members` (`id_member`, `nama_member`, `nik`, `no_telp`, `alamat`, `rfid_tag`, `qr_code`, `tgl_daftar`, `tgl_kadaluarsa`, `status_member`, `created_at`, `updated_at`) VALUES ('MBR-2026-001', 'Ahmad Favian', '3276012408080001', '0812-9988-7766', 'Jl. Merdeka No. 10, Jakarta Selatan', 'RFID-8829-X', 'QR-MBR-2026-001', '2026-01-12', '2026-11-20', 'aktif', '2026-10-04 02:53:31', '2026-10-04 02:53:31');
INSERT INTO `members` (`id_member`, `nama_member`, `nik`, `no_telp`, `alamat`, `rfid_tag`, `qr_code`, `tgl_daftar`, `tgl_kadaluarsa`, `status_member`, `created_at`, `updated_at`) VALUES ('MBR-2026-002', 'Budi Santoso', '3276011505020002', '0813-8877-6655', 'Jl. Sudirman No. 45, Jakarta Pusat', 'RFID-9911-E', 'QR-MBR-2026-002', '2026-02-01', '2026-08-01', 'kadaluarsa', '2026-10-04 02:53:31', '2026-10-04 02:53:31');
INSERT INTO `members` (`id_member`, `nama_member`, `nik`, `no_telp`, `alamat`, `rfid_tag`, `qr_code`, `tgl_daftar`, `tgl_kadaluarsa`, `status_member`, `created_at`, `updated_at`) VALUES ('MBR-2026-003', 'Siti Rahma', '3276014407050003', '0857-1122-3344', 'Jl. Melati Blok C2 No. 8, Depok', 'RFID-4432-M', 'QR-MBR-2026-003', '2026-03-15', '2026-12-15', 'aktif', '2026-10-04 02:53:31', '2026-10-04 02:53:31');
INSERT INTO `members` (`id_member`, `nama_member`, `nik`, `no_telp`, `alamat`, `rfid_tag`, `qr_code`, `tgl_daftar`, `tgl_kadaluarsa`, `status_member`, `created_at`, `updated_at`) VALUES ('MBR-2026-004', 'Dian Kusuma', '3276016609040004', '0818-4455-6677', 'Jl. Anggrek Raya No. 12, Bekasi', 'RFID-2210-V', 'QR-MBR-2026-004', '2026-04-10', '2026-10-09', 'aktif', '2026-10-04 02:53:31', '2026-10-04 02:53:31');
INSERT INTO `members` (`id_member`, `nama_member`, `nik`, `no_telp`, `alamat`, `rfid_tag`, `qr_code`, `tgl_daftar`, `tgl_kadaluarsa`, `status_member`, `created_at`, `updated_at`) VALUES ('MBR-2026-005', 'Rizky Pratama', '3276012803010005', '0812-7788-9900', 'Jl. Gatot Subroto Kav. 5, Jakarta Selatan', 'RFID-7734-X', 'QR-MBR-2026-005', '2026-01-05', '2027-01-30', 'aktif', '2026-10-04 02:53:31', '2026-10-04 02:53:31');
INSERT INTO `members` (`id_member`, `nama_member`, `nik`, `no_telp`, `alamat`, `rfid_tag`, `qr_code`, `tgl_daftar`, `tgl_kadaluarsa`, `status_member`, `created_at`, `updated_at`) VALUES ('MBR-2026-006', 'Hendra Wijaya', '3276011112000006', '0856-3344-5566', 'Jl. Raya Bogor KM 28, Cimanggis', 'RFID-6612-H', 'QR-MBR-2026-006', '2025-10-01', '2026-04-01', 'nonaktif', '2026-10-04 02:53:31', '2026-10-04 02:53:31');

-- Table structure for kendaraans
DROP TABLE IF EXISTS `kendaraans`;
CREATE TABLE "kendaraans" ("no_plat" varchar not null, "id_member" varchar not null, "jenis_kendaraan" varchar not null default 'mobil', "merk" varchar, "warna" varchar, "foto_stnk" varchar, "created_at" datetime, "updated_at" datetime, foreign key("id_member") references "members"("id_member") on delete cascade, primary key ("no_plat"));

-- Dumping data for kendaraans
INSERT INTO `kendaraans` (`no_plat`, `id_member`, `jenis_kendaraan`, `merk`, `warna`, `foto_stnk`, `created_at`, `updated_at`) VALUES ('B 1234 ABC', 'MBR-2026-001', 'mobil', 'Honda HR-V', 'Hitam Metalik', NULL, '2026-10-04 02:53:31', '2026-10-04 02:53:31');
INSERT INTO `kendaraans` (`no_plat`, `id_member`, `jenis_kendaraan`, `merk`, `warna`, `foto_stnk`, `created_at`, `updated_at`) VALUES ('B 9999 EXP', 'MBR-2026-002', 'mobil', 'Toyota Avanza', 'Putih', NULL, '2026-10-04 02:53:31', '2026-10-04 02:53:31');
INSERT INTO `kendaraans` (`no_plat`, `id_member`, `jenis_kendaraan`, `merk`, `warna`, `foto_stnk`, `created_at`, `updated_at`) VALUES ('B 4567 DEF', 'MBR-2026-003', 'motor', 'Yamaha NMAX', 'Abu-abu Matte', NULL, '2026-10-04 02:53:31', '2026-10-04 02:53:31');
INSERT INTO `kendaraans` (`no_plat`, `id_member`, `jenis_kendaraan`, `merk`, `warna`, `foto_stnk`, `created_at`, `updated_at`) VALUES ('B 3321 JKL', 'MBR-2026-004', 'motor', 'Honda Vario 160', 'Merah', NULL, '2026-10-04 02:53:31', '2026-10-04 02:53:31');
INSERT INTO `kendaraans` (`no_plat`, `id_member`, `jenis_kendaraan`, `merk`, `warna`, `foto_stnk`, `created_at`, `updated_at`) VALUES ('B 8890 GHI', 'MBR-2026-005', 'mobil', 'Mitsubishi Xpander', 'Silver', NULL, '2026-10-04 02:53:31', '2026-10-04 02:53:31');
INSERT INTO `kendaraans` (`no_plat`, `id_member`, `jenis_kendaraan`, `merk`, `warna`, `foto_stnk`, `created_at`, `updated_at`) VALUES ('B 6672 KLM', 'MBR-2026-006', 'motor', 'Honda Beat', 'Biru', NULL, '2026-10-04 02:53:31', '2026-10-04 02:53:31');

-- Table structure for pembayarans
DROP TABLE IF EXISTS `pembayarans`;
CREATE TABLE "pembayarans" ("id_pembayaran" varchar not null, "id_member" varchar not null, "id_petugas" varchar, "tgl_bayar" datetime not null, "durasi_bulan" integer not null default '1', "tgl_mulai" date not null, "tgl_kadaluarsa" date not null, "nominal" numeric not null, "diskon" numeric not null default '0', "nominal_akhir" numeric not null, "metode_bayar" varchar not null default 'tunai', "catatan" text, "created_at" datetime, "updated_at" datetime, foreign key("id_member") references "members"("id_member") on delete cascade, foreign key("id_petugas") references "petugas"("id_petugas") on delete set null, primary key ("id_pembayaran"));

-- Dumping data for pembayarans
INSERT INTO `pembayarans` (`id_pembayaran`, `id_member`, `id_petugas`, `tgl_bayar`, `durasi_bulan`, `tgl_mulai`, `tgl_kadaluarsa`, `nominal`, `diskon`, `nominal_akhir`, `metode_bayar`, `catatan`, `created_at`, `updated_at`) VALUES ('BYR-2026-0001', 'MBR-2026-001', 'KSR-01', '2026-08-20 10:15:00', '3', '2026-08-20 00:00:00', '2026-11-20 00:00:00', '450000', '22500', '427500', 'qris', 'Perpanjangan iuran mobil 3 bulan diskon 5%', '2026-10-04 02:53:31', '2026-10-04 02:53:31');
INSERT INTO `pembayarans` (`id_pembayaran`, `id_member`, `id_petugas`, `tgl_bayar`, `durasi_bulan`, `tgl_mulai`, `tgl_kadaluarsa`, `nominal`, `diskon`, `nominal_akhir`, `metode_bayar`, `catatan`, `created_at`, `updated_at`) VALUES ('BYR-2026-0002', 'MBR-2026-003', 'KSR-01', '2026-09-15 14:30:00', '3', '2026-09-15 00:00:00', '2026-12-15 00:00:00', '150000', '7500', '142500', 'tunai', 'Iuran motor 3 bulan paket hemat', '2026-10-04 02:53:31', '2026-10-04 02:53:31');
INSERT INTO `pembayarans` (`id_pembayaran`, `id_member`, `id_petugas`, `tgl_bayar`, `durasi_bulan`, `tgl_mulai`, `tgl_kadaluarsa`, `nominal`, `diskon`, `nominal_akhir`, `metode_bayar`, `catatan`, `created_at`, `updated_at`) VALUES ('BYR-2026-0003', 'MBR-2026-005', 'KSR-01', '2026-01-30 09:00:00', '12', '2026-01-30 00:00:00', '2027-01-30 00:00:00', '1800000', '216000', '1584000', 'transfer', 'Paket tahunan mobil diskon 12%', '2026-10-04 02:53:31', '2026-10-04 02:53:31');

-- Table structure for transaksi_parkirs
DROP TABLE IF EXISTS `transaksi_parkirs`;
CREATE TABLE "transaksi_parkirs" ("id_parkir" varchar not null, "id_member" varchar, "no_plat" varchar not null, "waktu_masuk" datetime not null, "waktu_keluar" datetime, "durasi_menit" integer, "gerbang_masuk" varchar not null default 'GATE-IN 01', "gerbang_keluar" varchar, "status_parkir" varchar not null default 'masuk', "metode_masuk" varchar not null default 'tap_rfid', "catatan_override" varchar, "id_petugas_override" varchar, "biaya" numeric not null default '0', "created_at" datetime, "updated_at" datetime, foreign key("id_member") references "members"("id_member") on delete set null, foreign key("id_petugas_override") references "petugas"("id_petugas") on delete set null, primary key ("id_parkir"));

-- Dumping data for transaksi_parkirs
INSERT INTO `transaksi_parkirs` (`id_parkir`, `id_member`, `no_plat`, `waktu_masuk`, `waktu_keluar`, `durasi_menit`, `gerbang_masuk`, `gerbang_keluar`, `status_parkir`, `metode_masuk`, `catatan_override`, `id_petugas_override`, `biaya`, `created_at`, `updated_at`) VALUES ('PRK-2026-004810', 'MBR-2026-001', 'B 1234 ABC', '2026-10-04 01:17:30', NULL, NULL, 'GATE-IN 01', NULL, 'masuk', 'scan_qr', NULL, NULL, '0', '2026-10-04 02:53:31', '2026-10-04 03:32:30');
INSERT INTO `transaksi_parkirs` (`id_parkir`, `id_member`, `no_plat`, `waktu_masuk`, `waktu_keluar`, `durasi_menit`, `gerbang_masuk`, `gerbang_keluar`, `status_parkir`, `metode_masuk`, `catatan_override`, `id_petugas_override`, `biaya`, `created_at`, `updated_at`) VALUES ('PRK-2026-004809', 'MBR-2026-003', 'B 4567 DEF', '2026-10-03 23:02:30', '2026-10-04 02:22:30', '200', 'GATE-IN 01', 'GATE-OUT 01', 'keluar', 'scan_qr', NULL, NULL, '0', '2026-10-04 02:53:31', '2026-10-04 03:32:30');
INSERT INTO `transaksi_parkirs` (`id_parkir`, `id_member`, `no_plat`, `waktu_masuk`, `waktu_keluar`, `durasi_menit`, `gerbang_masuk`, `gerbang_keluar`, `status_parkir`, `metode_masuk`, `catatan_override`, `id_petugas_override`, `biaya`, `created_at`, `updated_at`) VALUES ('PRK-2026-004808', 'MBR-2026-002', 'B 9999 EXP', '2026-10-03 22:32:30', NULL, NULL, 'GATE-IN 01', NULL, 'ditolak', 'scan_qr', 'Masa aktif kartu telah kadaluarsa', NULL, '0', '2026-10-04 02:53:31', '2026-10-04 03:32:30');
INSERT INTO `transaksi_parkirs` (`id_parkir`, `id_member`, `no_plat`, `waktu_masuk`, `waktu_keluar`, `durasi_menit`, `gerbang_masuk`, `gerbang_keluar`, `status_parkir`, `metode_masuk`, `catatan_override`, `id_petugas_override`, `biaya`, `created_at`, `updated_at`) VALUES ('PRK-2026-004807', 'MBR-2026-004', 'B 3321 JKL', '2026-10-03 21:32:30', NULL, NULL, 'GATE-IN 01', NULL, 'bypass_pin', 'bypass_pin', 'Kartu Rusak / Tidak Terbaca', 'SEC-01', '0', '2026-10-04 02:53:31', '2026-10-04 03:32:30');
INSERT INTO `transaksi_parkirs` (`id_parkir`, `id_member`, `no_plat`, `waktu_masuk`, `waktu_keluar`, `durasi_menit`, `gerbang_masuk`, `gerbang_keluar`, `status_parkir`, `metode_masuk`, `catatan_override`, `id_petugas_override`, `biaya`, `created_at`, `updated_at`) VALUES ('TKT-20261004-0005', NULL, 'B 9467 MBL', '2026-10-04 07:13:45', NULL, NULL, 'GATE-IN 01', NULL, 'masuk', 'tombol_tiket', NULL, NULL, '0', '2026-10-04 07:13:45', '2026-10-04 07:13:45');
INSERT INTO `transaksi_parkirs` (`id_parkir`, `id_member`, `no_plat`, `waktu_masuk`, `waktu_keluar`, `durasi_menit`, `gerbang_masuk`, `gerbang_keluar`, `status_parkir`, `metode_masuk`, `catatan_override`, `id_petugas_override`, `biaya`, `created_at`, `updated_at`) VALUES ('PRK-2026-000006', 'MBR-2026-001', 'B 1234 ABC', '2026-10-04 07:32:49', NULL, NULL, 'GATE-IN 01', NULL, 'masuk', 'scan_qr', NULL, NULL, '0', '2026-10-04 07:32:49', '2026-10-04 07:32:49');

-- Table structure for personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE "personal_access_tokens" ("id" integer primary key autoincrement not null, "tokenable_type" varchar not null, "tokenable_id" integer not null, "name" text not null, "token" varchar not null, "abilities" text, "last_used_at" datetime, "expires_at" datetime, "created_at" datetime, "updated_at" datetime);

-- Dumping data for personal_access_tokens
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES ('1', 'App\Models\Petugas', 'PETUGAS-01', 'petugas_token', '4311ed9f7ac3f1242825d2fe26ad83464791d10872c3a351b76e25aa0dade904', '["*"]', NULL, NULL, '2026-10-04 03:30:37', '2026-10-04 03:30:37');
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES ('2', 'App\Models\Petugas', 'ADM-01', 'petugas_token', 'd93d9782e8ca1391b07b1d007a029ca3be20bcd2bfdf1e4aa9bca857ebd4a602', '["*"]', NULL, NULL, '2026-10-04 03:30:56', '2026-10-04 03:30:56');
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES ('3', 'App\Models\Petugas', 'KSR-01', 'petugas_token', 'be5a2c400f37b3343007cd1e8022da530e4e3c9f77d01c1228f4df4a3099a2a3', '["*"]', NULL, NULL, '2026-10-04 03:31:04', '2026-10-04 03:31:04');
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES ('4', 'App\Models\Petugas', 'PETUGAS-01', 'petugas_token', '7e73416d9b6e7ce53a9da31e200c6c12e222f597886afebcbe5e978f8ff4363e', '["*"]', NULL, NULL, '2026-10-04 03:35:52', '2026-10-04 03:35:52');
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES ('5', 'App\Models\Petugas', 'KSR-01', 'petugas_token', '04231b4c2095ad4a0f3869493341c6a2dc2f37a30f9a796f1c25efadb4aea6d0', '["*"]', NULL, NULL, '2026-10-04 03:36:07', '2026-10-04 03:36:07');
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES ('6', 'App\Models\Petugas', 'ADM-01', 'petugas_token', '4debe2d93b04eae2d260bc19eb2a0192e9f20946a38bb81a24c4d5ae0f926210', '["*"]', NULL, NULL, '2026-10-04 03:36:25', '2026-10-04 03:36:25');

PRAGMA foreign_keys = ON;
