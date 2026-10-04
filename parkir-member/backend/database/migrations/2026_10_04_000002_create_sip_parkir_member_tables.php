<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Table Petugas (Admin, Kasir, Operator, Satpam)
        Schema::create('petugas', function (Blueprint $table) {
            $table->string('id_petugas', 20)->primary();
            $table->string('nama_petugas', 100);
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->string('peran', 30)->default('operator'); // admin, kasir, operator, satpam
            $table->string('pin_petugas', 10)->default('123456');
            $table->string('pos_aktif', 100)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Table Member
        Schema::create('members', function (Blueprint $table) {
            $table->string('id_member', 30)->primary();
            $table->string('nama_member', 100);
            $table->string('nik', 30)->nullable();
            $table->string('no_telp', 25);
            $table->text('alamat')->nullable();
            $table->string('rfid_tag', 50)->nullable()->unique();
            $table->string('qr_code', 100)->nullable();
            $table->date('tgl_daftar');
            $table->date('tgl_kadaluarsa');
            $table->string('status_member', 20)->default('aktif'); // aktif, kadaluarsa, nonaktif
            $table->timestamps();
        });

        // 3. Table Kendaraan
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->string('no_plat', 20)->primary();
            $table->string('id_member', 30);
            $table->string('jenis_kendaraan', 20)->default('mobil'); // mobil, motor
            $table->string('merk', 50)->nullable();
            $table->string('warna', 50)->nullable();
            $table->string('foto_stnk', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_member')->references('id_member')->on('members')->onDelete('cascade');
        });

        // 4. Table Pembayaran (Iuran Bulanan)
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->string('id_pembayaran', 30)->primary();
            $table->string('id_member', 30);
            $table->string('id_petugas', 20)->nullable();
            $table->dateTime('tgl_bayar');
            $table->integer('durasi_bulan')->default(1);
            $table->date('tgl_mulai');
            $table->date('tgl_kadaluarsa');
            $table->decimal('nominal', 12, 2);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->decimal('nominal_akhir', 12, 2);
            $table->string('metode_bayar', 30)->default('tunai'); // tunai, qris, transfer, debit
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_member')->references('id_member')->on('members')->onDelete('cascade');
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas')->nullOnDelete();
        });

        // 5. Table Transaksi Parkir
        Schema::create('transaksi_parkirs', function (Blueprint $table) {
            $table->string('id_parkir', 30)->primary();
            $table->string('id_member', 30)->nullable();
            $table->string('no_plat', 20);
            $table->dateTime('waktu_masuk');
            $table->dateTime('waktu_keluar')->nullable();
            $table->integer('durasi_menit')->nullable();
            $table->string('gerbang_masuk', 50)->default('GATE-IN 01');
            $table->string('gerbang_keluar', 50)->nullable();
            $table->string('status_parkir', 30)->default('masuk'); // masuk, keluar, ditolak, bypass_pin
            $table->string('metode_masuk', 50)->default('tap_rfid'); // tap_rfid, scan_qr, bypass_pin, manual
            $table->string('catatan_override', 255)->nullable();
            $table->string('id_petugas_override', 20)->nullable();
            $table->decimal('biaya', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_member')->references('id_member')->on('members')->nullOnDelete();
            $table->foreign('id_petugas_override')->references('id_petugas')->on('petugas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_parkirs');
        Schema::dropIfExists('pembayarans');
        Schema::dropIfExists('kendaraans');
        Schema::dropIfExists('members');
        Schema::dropIfExists('petugas');
    }
};
