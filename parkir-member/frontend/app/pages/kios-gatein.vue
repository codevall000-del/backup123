<template>
  <div class="min-h-screen bg-[#f5f5f7] text-[#1d1d1f] flex flex-col font-sans select-none relative overflow-x-hidden selection:bg-emerald-500 selection:text-white">
    
    <!-- Background Ambient Lighting -->
    <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-emerald-500/10 blur-[100px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-blue-500/10 blur-[100px] pointer-events-none"></div>

    <!-- STANDALONE KIOSK HEADER (NO NAVBAR) -->
    <header class="w-full h-16 bg-white/80 backdrop-blur-2xl border-b border-black/[0.06] px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
      <!-- Left: Kiosk Terminal Identity -->
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-xs">
          <span class="material-symbols-outlined text-[20px]">sensors</span>
        </div>
        <div>
          <div class="flex items-center gap-2">
            <span class="font-extrabold text-[15px] tracking-tight text-[#1d1d1f]">GATE-IN 01</span>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-800 font-mono-metric">JALUR MASUK</span>
          </div>
          <span class="text-[11px] text-[#86868b] hidden sm:block">Kios Mandiri Gerbang Masuk Member & Tamu</span>
        </div>
      </div>

      <!-- Center: Large Digital Clock & Date -->
      <div class="flex items-center gap-3">
        <div class="font-mono-metric text-lg sm:text-2xl font-black text-[#1d1d1f] tracking-wider">{{ currentTime }}</div>
        <div class="hidden md:block text-xs text-[#86868b] pl-3 border-l border-black/[0.08] font-mono-metric">{{ currentDate }}</div>
      </div>

      <!-- Right: Live Barrier Status & Discreet Exit Link -->
      <div class="flex items-center gap-2.5">
        <div
          class="flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-mono-metric border transition-all"
          :class="isBarrierOpen ? 'bg-emerald-50 border-emerald-300 text-emerald-700 shadow-xs' : 'bg-amber-50 border-amber-200 text-amber-800'"
        >
          <span class="h-2 w-2 rounded-full" :class="isBarrierOpen ? 'bg-emerald-500 animate-ping' : 'bg-amber-500'"></span>
          <span>{{ isBarrierOpen ? `PALANG BUKA (${autoCloseTimer}s)` : 'PALANG TERTUTUP' }}</span>
        </div>

        <!-- Exit / Return to Portal Button (Discreet) -->
        <NuxtLink
          to="/"
          class="apple-btn text-xs font-semibold px-3 py-1.5 rounded-xl bg-black/[0.04] hover:bg-black/[0.08] text-[#1d1d1f] flex items-center gap-1 border border-black/[0.06] transition-all"
          title="Kembali ke Portal Login / Menu Utama"
        >
          <span class="material-symbols-outlined text-[16px]">arrow_back</span>
          <span class="hidden sm:inline">Portal</span>
        </NuxtLink>
      </div>
    </header>

    <!-- MAIN KIOSK VIEWPORT (POLOS & FOKUS PADA 2 PILIHAN MASUK) -->
    <main class="w-full flex-1 max-w-6xl mx-auto p-4 sm:p-6 lg:p-8 flex flex-col justify-between relative z-10">
      
      <!-- OUTDOOR LED SIGNAGE DISPLAY BANNER -->
      <div class="w-full bg-slate-950 border-2 border-slate-800 rounded-2xl p-3.5 sm:p-4 shadow-lg flex flex-col items-center justify-center text-center relative overflow-hidden mb-6">
        <div class="text-[10px] font-mono-metric text-slate-400 uppercase tracking-widest mb-0.5">DISPLAY LED GERBANG MASUK</div>
        <div
          class="font-mono-metric text-base sm:text-xl font-black tracking-widest transition-colors duration-300"
          :class="ledDisplayClass"
        >
          {{ ledDisplayText }}
        </div>
      </div>

      <!-- TWO PRIMARY ENTRY MODES (SPLIT 50/50: TOMBOL MASUK vs SCAN MEMBER) -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch mb-6">
        
        <!-- ============================================================= -->
        <!-- OPSI 1: PENCET TOMBOL MASUK (PENGUNJUNG UMUM / TIKET PARKIR) -->
        <!-- ============================================================= -->
        <div class="apple-glass-card rounded-[28px] p-6 sm:p-8 bg-white border border-black/[0.06] shadow-sm flex flex-col justify-between relative overflow-hidden">
          
          <div>
            <!-- Card Header -->
            <div class="flex items-center justify-between mb-4">
              <span class="px-3 py-1 rounded-full bg-blue-500/10 text-[#0071e3] text-xs font-bold border border-blue-500/20">
                OPSI 1 • PENGUNJUNG UMUM
              </span>
              <span class="text-xs text-[#86868b] font-medium">Karcis Parkir</span>
            </div>

            <h2 class="apple-title text-2xl font-extrabold text-[#1d1d1f] mb-1">Pencet Tombol Masuk</h2>
            <p class="apple-body text-xs sm:text-[13px] text-[#6e6e73] mb-5">
              Untuk pengendara atau pengunjung tanpa kartu member. Tekan tombol di bawah untuk mencetak tiket parkir otomatis.
            </p>

            <!-- Vehicle Type Selector Pill -->
            <div class="mb-6 p-1 rounded-xl bg-black/[0.04] border border-black/[0.04] grid grid-cols-2 gap-1 text-xs font-bold max-w-xs mx-auto">
              <button
                type="button"
                @click="selectedVehicleType = 'mobil'"
                class="apple-btn py-1.5 px-3 rounded-lg transition-all flex items-center justify-center gap-1.5"
                :class="selectedVehicleType === 'mobil' ? 'bg-white text-[#0071e3] shadow-xs' : 'text-[#6e6e73] hover:text-[#1d1d1f]'"
              >
                <span class="material-symbols-outlined text-[16px]">directions_car</span>
                <span>Mobil</span>
              </button>
              <button
                type="button"
                @click="selectedVehicleType = 'motor'"
                class="apple-btn py-1.5 px-3 rounded-lg transition-all flex items-center justify-center gap-1.5"
                :class="selectedVehicleType === 'motor' ? 'bg-white text-[#0071e3] shadow-xs' : 'text-[#6e6e73] hover:text-[#1d1d1f]'"
              >
                <span class="material-symbols-outlined text-[16px]">two_wheeler</span>
                <span>Motor</span>
              </button>
            </div>
          </div>

          <!-- Dynamic Middle: Push Button OR Virtual Issued Ticket -->
          <div class="my-auto py-4">
            
            <!-- STATE A: Normal Standby Push Button -->
            <div v-if="gateState !== 'tiket_umum'" class="text-center">
              <div class="relative inline-block">
                <!-- Glowing Pulse Ring -->
                <div class="absolute -inset-4 rounded-full bg-emerald-500/20 blur-xl animate-pulse pointer-events-none"></div>

                <!-- Giant Tactile Push Button -->
                <button
                  type="button"
                  @click="handleTombolMasuk"
                  :disabled="isLoading || isBarrierOpen"
                  class="apple-btn w-44 h-44 sm:w-48 sm:h-48 rounded-full bg-gradient-to-b from-[#10b981] via-[#059669] to-[#047857] hover:from-[#059669] hover:to-[#047857] active:scale-95 text-white shadow-[0_16px_40px_rgba(16,185,129,0.35)] flex flex-col items-center justify-center gap-2 border-4 border-white/50 ring-8 ring-emerald-500/15 disabled:opacity-50 disabled:pointer-events-none mx-auto transition-transform duration-200 group cursor-pointer"
                >
                  <span class="material-symbols-outlined text-5xl sm:text-6xl group-hover:scale-110 transition-transform">touch_app</span>
                  <span class="font-extrabold text-sm sm:text-base tracking-wide uppercase">PENCET DI SINI</span>
                  <span class="text-[10px] font-semibold opacity-90">Ambil Tiket Masuk</span>
                </button>
              </div>

              <p class="text-[11px] text-[#86868b] mt-4">
                Tekan tombol untuk mengeluarkan struk tiket dan membuka palang
              </p>
            </div>

            <!-- STATE B: Ticket Printed (Animasi Tiket Keluar) -->
            <div v-else-if="ticketIssued" class="animate-in fade-in zoom-in-95 duration-300">
              <div class="bg-white border-2 border-dashed border-emerald-500/50 rounded-2xl p-5 shadow-lg max-w-sm mx-auto text-left relative overflow-hidden">
                <!-- Ticket Badge -->
                <div class="flex items-center justify-between border-b border-black/[0.08] pb-3 mb-3">
                  <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                    <span class="font-extrabold text-xs text-[#1d1d1f] uppercase tracking-wider">TIKET PARKIR UMUM</span>
                  </div>
                  <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 font-mono-metric">{{ ticketIssued.jenis_kendaraan }}</span>
                </div>

                <!-- Ticket Details -->
                <div class="space-y-1.5 text-xs text-[#424245] mb-4">
                  <div class="flex justify-between">
                    <span class="text-[#86868b]">No. Tiket:</span>
                    <span class="font-mono-metric font-black text-slate-900 text-sm">{{ ticketIssued.no_tiket }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-[#86868b]">Plat Terdeteksi:</span>
                    <span class="font-mono-metric font-bold text-slate-900">{{ ticketIssued.no_plat }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-[#86868b]">Waktu Masuk:</span>
                    <span class="font-mono-metric font-semibold text-slate-900">{{ ticketIssued.waktu_masuk }}</span>
                  </div>
                </div>

                <!-- Simulated Barcode Graphic -->
                <div class="h-10 w-full bg-slate-900 rounded flex items-center justify-center text-white/80 font-mono-metric text-xs tracking-widest mb-3">
                  ||| | |||| | ||| || |||| |
                </div>

                <div class="p-2.5 rounded-xl bg-emerald-500 text-white font-extrabold text-center text-xs flex items-center justify-center gap-1.5 shadow-sm">
                  <span class="material-symbols-outlined text-[18px]">check_circle</span>
                  <span>TIKET DIAMBIL — SILAKAN MASUK</span>
                </div>
              </div>
            </div>

          </div>

          <!-- Card Footer Note -->
          <div class="pt-4 border-t border-black/[0.06] text-center">
            <span class="text-[11px] text-[#86868b]">
              Simpan tiket parkir ini dengan baik untuk ditunjukkan saat pembayaran di pos keluar.
            </span>
          </div>

        </div>

        <!-- ============================================================= -->
        <!-- OPSI 2: SCAN QR CODE MEMBER (KHUSUS MEMBER BERLANGGANAN) -->
        <!-- ============================================================= -->
        <div class="apple-glass-card rounded-[28px] p-6 sm:p-8 bg-white border border-black/[0.06] shadow-sm flex flex-col justify-between relative overflow-hidden">
          
          <div>
            <!-- Card Header -->
            <div class="flex items-center justify-between mb-4">
              <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-800 text-xs font-bold border border-emerald-500/20">
                OPSI 2 • KHUSUS MEMBER
              </span>
              <span class="text-xs text-[#86868b] font-medium">Bebas Parkir</span>
            </div>

            <h2 class="apple-title text-2xl font-extrabold text-[#1d1d1f] mb-1">Scan QR Member</h2>
            <p class="apple-body text-xs sm:text-[13px] text-[#6e6e73] mb-5">
              Untuk anggota member berlangganan. Arahkan kode QR digital di layar smartphone Anda atau kartu cetak ke lensa pemindai.
            </p>
          </div>

          <!-- Dynamic Middle: Viewfinder Scanner OR Verification Result -->
          <div class="my-auto py-2">
            
            <!-- STATE A: Normal Standby Optical Viewfinder -->
            <div v-if="gateState !== 'aktif' && gateState !== 'kadaluarsa' && gateState !== 'tidak_dikenal' && gateState !== 'bypass_pin'" class="space-y-4">
              <!-- Optical QR Scanner Animation Frame -->
              <div class="relative w-36 h-36 mx-auto flex items-center justify-center bg-slate-950 rounded-3xl p-3 border-2 border-slate-800 shadow-xl overflow-hidden group">
                <!-- Viewfinder Corner Brackets -->
                <div class="absolute top-2 left-2 w-3.5 h-3.5 border-t-2 border-l-2 border-emerald-400"></div>
                <div class="absolute top-2 right-2 w-3.5 h-3.5 border-t-2 border-r-2 border-emerald-400"></div>
                <div class="absolute bottom-2 left-2 w-3.5 h-3.5 border-b-2 border-l-2 border-emerald-400"></div>
                <div class="absolute bottom-2 right-2 w-3.5 h-3.5 border-b-2 border-r-2 border-emerald-400"></div>

                <!-- Central QR Graphic Target -->
                <div class="w-20 h-20 rounded-xl bg-white/5 border border-white/10 flex flex-col items-center justify-center relative">
                  <span class="material-symbols-outlined text-3xl text-emerald-400 animate-pulse">qr_code_2</span>
                  <span class="text-[8px] font-mono-metric text-emerald-300/80 mt-1 uppercase tracking-widest font-bold">2D SCANNER</span>
                </div>

                <!-- Laser Scanning Beam -->
                <div class="absolute inset-x-2 h-0.5 bg-gradient-to-r from-transparent via-emerald-400 to-transparent shadow-[0_0_12px_#10b981] animate-bounce pointer-events-none" style="animation-duration: 2.2s;"></div>
              </div>

              <!-- Quick Demo Simulation Buttons (For Easy Testing & Grading) -->
              <div class="space-y-1.5 pt-1">
                <span class="text-[10px] font-bold text-[#86868b] uppercase tracking-wider block text-center">
                  Simulasi Tap Cepat Pengujian:
                </span>
                <div class="grid grid-cols-3 gap-1.5">
                  <button
                    type="button"
                    @click="simulateTap('QR-MBR-2026-001')"
                    :disabled="isLoading || isBarrierOpen"
                    class="apple-btn py-2 px-1.5 rounded-xl bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 text-center transition-all text-xs"
                  >
                    <span class="font-extrabold text-[11px] text-[#1d1d1f] block truncate">Mobil Aktif</span>
                    <span class="text-[9px] text-emerald-700 font-mono-metric font-bold">Favian</span>
                  </button>
                  <button
                    type="button"
                    @click="simulateTap('QR-MBR-2026-003')"
                    :disabled="isLoading || isBarrierOpen"
                    class="apple-btn py-2 px-1.5 rounded-xl bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 text-center transition-all text-xs"
                  >
                    <span class="font-extrabold text-[11px] text-[#1d1d1f] block truncate">Motor Aktif</span>
                    <span class="text-[9px] text-emerald-700 font-mono-metric font-bold">Siti Rahma</span>
                  </button>
                  <button
                    type="button"
                    @click="simulateTap('QR-MBR-2026-002')"
                    :disabled="isLoading || isBarrierOpen"
                    class="apple-btn py-2 px-1.5 rounded-xl bg-slate-50 hover:bg-rose-50 border border-slate-200 hover:border-rose-300 text-center transition-all text-xs"
                  >
                    <span class="font-extrabold text-[11px] text-rose-700 block truncate">Kadaluarsa</span>
                    <span class="text-[9px] text-rose-600 font-mono-metric font-bold">Budi</span>
                  </button>
                </div>
              </div>

              <!-- Manual QR / Barcode Scanner Field -->
              <div class="flex gap-2 max-w-sm mx-auto">
                <input
                  v-model="customQrInput"
                  @keyup.enter="simulateTap(customQrInput)"
                  type="text"
                  placeholder="Scan / Ketik kode QR member..."
                  class="flex-1 h-9 px-3 rounded-xl bg-black/[0.03] border border-black/[0.08] text-xs font-mono-metric text-[#1d1d1f] focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                />
                <button
                  type="button"
                  @click="simulateTap(customQrInput)"
                  :disabled="!customQrInput || isLoading"
                  class="apple-btn px-4 h-9 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold disabled:opacity-50 transition-all"
                >
                  Scan
                </button>
              </div>
            </div>

            <!-- STATE B: Member Aktif Sukses -->
            <div v-else-if="gateState === 'aktif'" class="bg-emerald-50 border-2 border-emerald-500 rounded-2xl p-5 shadow-sm text-left animate-in fade-in duration-300 max-w-sm mx-auto">
              <div class="flex items-center justify-between mb-3 border-b border-emerald-200 pb-2.5">
                <div class="flex items-center gap-2.5">
                  <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-xs">
                    <span class="material-symbols-outlined text-2xl">verified</span>
                  </div>
                  <div>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-200 text-emerald-900 font-bold text-[10px]">MEMBER AKTIF ✓</span>
                    <h3 class="text-base font-extrabold text-slate-900">{{ gateResult.member?.nama_member }}</h3>
                  </div>
                </div>
                <span class="font-mono-metric text-xs font-black text-slate-900 bg-white px-2.5 py-1 rounded-lg border border-emerald-300">
                  {{ gateResult.kendaraan?.no_plat || currentDetectedPlate }}
                </span>
              </div>

              <div class="text-xs space-y-1 mb-3 text-slate-700">
                <div class="flex justify-between">
                  <span class="text-slate-500">Masa Berlaku:</span>
                  <span class="font-bold text-emerald-800">s/d {{ gateResult.tgl_kadaluarsa }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-500">Sisa Langganan:</span>
                  <span class="font-bold text-emerald-800">{{ gateResult.sisa_hari }} Hari</span>
                </div>
              </div>

              <div class="py-2.5 px-3 rounded-xl bg-emerald-600 text-white font-extrabold text-center text-xs flex items-center justify-center gap-1.5 shadow-sm">
                <span>PALANG TERBUKA — SILAKAN MASUK</span>
                <span class="material-symbols-outlined text-[16px] animate-pulse">arrow_forward</span>
              </div>
            </div>

            <!-- STATE C: Member Kadaluarsa -->
            <div v-else-if="gateState === 'kadaluarsa'" class="bg-rose-50 border-2 border-rose-500 rounded-2xl p-5 shadow-sm text-left animate-in fade-in duration-300 max-w-sm mx-auto">
              <div class="flex items-center gap-2.5 mb-2.5">
                <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center shadow-xs">
                  <span class="material-symbols-outlined text-2xl">cancel</span>
                </div>
                <div>
                  <span class="px-2 py-0.5 rounded-full bg-rose-200 text-rose-900 font-bold text-[10px]">KARTU KADALUARSA ✗</span>
                  <h3 class="text-base font-extrabold text-slate-900">{{ gateResult.member?.nama_member || 'Budi Santoso' }}</h3>
                </div>
              </div>

              <p class="text-xs text-rose-800 mb-3">
                Masa aktif member berakhir pada <strong>{{ gateResult.tgl_kadaluarsa }}</strong>. Palang tetap terkunci.
              </p>

              <div class="flex gap-2">
                <button
                  type="button"
                  @click="triggerIntercom"
                  class="flex-1 py-2 px-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs flex items-center justify-center gap-1 shadow-sm"
                >
                  <span class="material-symbols-outlined text-[16px]">support_agent</span>
                  <span>Interkom</span>
                </button>
                <button
                  type="button"
                  @click="showPinModal = true"
                  class="py-2 px-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs flex items-center gap-1 shadow-sm"
                >
                  <span class="material-symbols-outlined text-[16px]">pin</span>
                  <span>PIN Jaga</span>
                </button>
              </div>
            </div>

            <!-- STATE D: Tidak Dikenal -->
            <div v-else-if="gateState === 'tidak_dikenal'" class="bg-slate-100 border-2 border-slate-300 rounded-2xl p-5 shadow-sm text-left animate-in fade-in duration-300 max-w-sm mx-auto">
              <div class="flex items-center gap-2.5 mb-2">
                <div class="w-10 h-10 rounded-xl bg-slate-300 text-slate-700 flex items-center justify-center">
                  <span class="material-symbols-outlined text-2xl">help</span>
                </div>
                <div>
                  <span class="px-2 py-0.5 rounded-full bg-slate-200 text-slate-800 font-bold text-[10px]">TIDAK TERDAFTAR</span>
                  <h3 class="text-sm font-bold text-slate-900">QR Belum Ada di Sistem</h3>
                </div>
              </div>
              <p class="text-xs text-slate-600 mb-3">
                Kode QR tidak terdaftar sebagai member. Silakan tekan tombol masuk (tiket umum) di sebelah kiri.
              </p>
              <button
                type="button"
                @click="triggerIntercom"
                class="w-full py-2 px-3 rounded-xl bg-white border border-slate-300 text-slate-800 font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs"
              >
                <span class="material-symbols-outlined text-[16px] text-brand-600">support_agent</span>
                <span>Bantuan Interkom Petugas</span>
              </button>
            </div>

            <!-- STATE E: Bypass PIN -->
            <div v-else-if="gateState === 'bypass_pin'" class="bg-amber-50 border-2 border-amber-500 rounded-2xl p-5 shadow-sm text-left animate-in fade-in duration-300 max-w-sm mx-auto">
              <div class="flex items-center gap-2.5 mb-2">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center">
                  <span class="material-symbols-outlined text-2xl">shield</span>
                </div>
                <div>
                  <span class="px-2 py-0.5 rounded-full bg-amber-200 text-amber-900 font-bold text-[10px]">OVERRIDE PETUGAS BERHASIL</span>
                  <h3 class="text-sm font-bold text-slate-900">{{ pinSuccessData?.petugas?.nama || 'Petugas Jaga' }}</h3>
                </div>
              </div>
              <p class="text-xs text-amber-800 mb-3">
                Otorisasi darurat disetujui. Palang terbuka untuk kendaraan ini.
              </p>
              <div class="py-2 px-3 rounded-xl bg-amber-500 text-white font-extrabold text-center text-xs">
                PALANG DIBUKA MANUAL — SILAKAN MASUK
              </div>
            </div>

          </div>

          <!-- Card Footer Note -->
          <div class="pt-4 border-t border-black/[0.06] text-center">
            <span class="text-[11px] text-[#86868b]">
              Verifikasi masa aktif kartu berlangsung otomatis tanpa memerlukan login petugas.
            </span>
          </div>

        </div>

      </div>

      <!-- ============================================================= -->
      <!-- BOTTOM BAR: BARRIER ARM VISUALIZATION & ASSISTANCE CONTROLS   -->
      <!-- ============================================================= -->
      <div class="apple-glass-card rounded-[24px] p-5 bg-white border border-black/[0.06] shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        
        <!-- Left: Animated SVG Barrier Arm -->
        <div class="flex items-center gap-5">
          <div class="relative w-48 h-16 flex items-end">
            <!-- Road surface -->
            <div class="absolute bottom-0 w-full h-1.5 bg-slate-200 rounded"></div>

            <!-- Car silhouette -->
            <div class="absolute bottom-1 left-2 text-slate-400 transition-all duration-700" :class="isBarrierOpen ? 'translate-x-12 opacity-100' : 'opacity-70'">
              <span class="material-symbols-outlined text-3xl text-brand-600">directions_car</span>
            </div>

            <!-- Post -->
            <div class="absolute bottom-1 right-6 z-20 flex flex-col items-center">
              <div class="w-4 h-12 bg-gradient-to-t from-amber-600 to-amber-500 rounded-t shadow border border-amber-400/40 relative">
                <div class="w-1.5 h-1.5 rounded-full mx-auto mt-1.5" :class="isBarrierOpen ? 'bg-emerald-400' : 'bg-rose-500'"></div>
              </div>
            </div>

            <!-- SVG Rotating Barrier Arm -->
            <div class="absolute bottom-10 right-7 z-10 gate-arm" :class="{ 'open': isBarrierOpen }">
              <svg width="140" height="16" viewBox="0 0 140 16">
                <rect x="0" y="5" width="140" height="6" rx="2" fill="#cbd5e1"/>
                <rect x="15" y="5" width="18" height="6" fill="#ef4444"/>
                <rect x="50" y="5" width="18" height="6" fill="#ef4444"/>
                <rect x="85" y="5" width="18" height="6" fill="#ef4444"/>
                <circle cx="8" cy="8" r="4" fill="#0f172a" stroke="#f59e0b" stroke-width="1.5"/>
              </svg>
            </div>
          </div>

          <div>
            <div class="flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full" :class="isBarrierOpen ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400'"></span>
              <span class="font-extrabold text-xs text-[#1d1d1f]">
                {{ isBarrierOpen ? `PALANG TERBUKA — HITUNG MUNDUR ${autoCloseTimer}s` : 'PALANG TERTUTUP (STANDBY)' }}
              </span>
            </div>
            <span class="text-[11px] text-[#86868b] block mt-0.5">
              Sensor ultrasonik & palang otomatis menutup sendiri setelah kendaraan lewat.
            </span>
          </div>
        </div>

        <!-- Right: Emergency / Assistance Buttons -->
        <div class="flex items-center gap-2 self-stretch md:self-auto">
          <button
            type="button"
            @click="triggerIntercom"
            class="apple-btn flex-1 md:flex-initial px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold flex items-center justify-center gap-1.5 border border-slate-200 transition-all shadow-xs"
          >
            <span class="material-symbols-outlined text-[18px] text-brand-600">support_agent</span>
            <span>Interkom</span>
          </button>

          <button
            type="button"
            @click="showPinModal = true"
            class="apple-btn flex-1 md:flex-initial px-3.5 py-2 rounded-xl bg-amber-500/15 hover:bg-amber-500/25 text-amber-900 text-xs font-semibold flex items-center justify-center gap-1.5 border border-amber-500/25 transition-all shadow-xs"
          >
            <span class="material-symbols-outlined text-[18px] text-amber-600">lock_open</span>
            <span>PIN Satpam</span>
          </button>
        </div>

      </div>

      <!-- Intercom Active Floating Toast -->
      <transition enter-active-class="transition duration-200 ease-out" enter-from-class="transform translate-y-2 opacity-0" enter-to-class="transform translate-y-0 opacity-100">
        <div v-if="intercomActive" class="fixed bottom-6 right-6 z-40 p-4 rounded-2xl bg-indigo-600 text-white shadow-xl flex items-center gap-3 animate-pulse">
          <span class="material-symbols-outlined text-2xl">volume_up</span>
          <div>
            <strong class="text-xs block font-bold">Interkom Terhubung</strong>
            <span class="text-[11px] opacity-90">Menghubungkan dengan Pos Jaga / Kasir Utama...</span>
          </div>
        </div>
      </transition>

    </main>

    <!-- OVERLAY MODAL: Otorisasi Cepat Satpam Jaga (PIN 6-Digit) -->
    <div
      v-if="showPinModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-in fade-in duration-200"
    >
      <div class="w-full max-w-sm bg-white text-[#1d1d1f] rounded-[28px] shadow-2xl overflow-hidden border border-black/[0.08]">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-black/[0.06] flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
              <span class="material-symbols-outlined text-lg">shield_lock</span>
            </div>
            <div>
              <h3 class="font-extrabold text-sm text-[#1d1d1f]">PIN Cepat Satpam Jaga</h3>
              <p class="text-[10px] text-[#86868b]">Otorisasi darurat buka palang</p>
            </div>
          </div>
          <button @click="closePinModal" class="text-[#86868b] hover:text-[#1d1d1f] p-1">
            <span class="material-symbols-outlined text-xl">close</span>
          </button>
        </div>

        <!-- Body -->
        <div class="p-6">
          <!-- 6-Digit Masked Display -->
          <div class="flex items-center justify-center gap-2 mb-5">
            <div
              v-for="i in 6"
              :key="i"
              class="w-10 h-11 rounded-xl flex items-center justify-center text-lg font-bold font-mono-metric transition-all"
              :class="pinInput.length >= i ? 'bg-[#0071e3]/10 border-2 border-[#0071e3] text-[#0071e3]' : (pinInput.length === i - 1 ? 'border-2 border-[#0071e3]/50 bg-white' : 'bg-black/[0.03] border border-black/[0.08] text-slate-400')"
            >
              {{ pinInput.length >= i ? '●' : '' }}
            </div>
          </div>

          <!-- On-Screen Numeric Keypad -->
          <div class="grid grid-cols-3 gap-2 max-w-[240px] mx-auto mb-4">
            <button
              v-for="digit in [1, 2, 3, 4, 5, 6, 7, 8, 9]"
              :key="digit"
              type="button"
              @click="appendPin(digit)"
              class="h-11 rounded-xl bg-black/[0.03] hover:bg-black/[0.06] active:scale-95 text-base font-bold text-[#1d1d1f] transition-all"
            >
              {{ digit }}
            </button>
            <button
              type="button"
              @click="backspacePin"
              class="h-11 rounded-xl bg-black/[0.04] hover:bg-black/[0.08] active:scale-95 text-[#6e6e73] font-bold text-xs"
            >
              ⌫
            </button>
            <button
              type="button"
              @click="appendPin(0)"
              class="h-11 rounded-xl bg-black/[0.03] hover:bg-black/[0.06] active:scale-95 text-base font-bold text-[#1d1d1f]"
            >
              0
            </button>
            <button
              type="button"
              @click="clearPin"
              class="h-11 rounded-xl bg-black/[0.04] hover:bg-black/[0.08] active:scale-95 text-[#6e6e73] font-bold text-xs"
            >
              C
            </button>
          </div>

          <!-- Reason Selector -->
          <div class="mb-4">
            <label class="block text-[11px] font-bold text-[#1d1d1f] mb-1.5">Alasan Buka Darurat:</label>
            <select
              v-model="selectedReason"
              class="w-full h-10 px-3 rounded-xl bg-black/[0.03] border border-black/[0.08] text-xs text-[#1d1d1f] focus:outline-none focus:ring-2 focus:ring-[#0071e3]"
            >
              <option v-for="r in overrideReasons" :key="r" :value="r">{{ r }}</option>
            </select>
          </div>

          <!-- Hint & Error -->
          <div class="text-center text-[10px] text-[#86868b] mb-2 font-mono-metric">
            Demo PIN: <strong class="text-[#0071e3]">998877</strong> atau <strong class="text-[#0071e3]">123456</strong>
          </div>
          <div v-if="pinError" class="p-2 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs text-center mb-2">
            {{ pinError }}
          </div>
        </div>

        <!-- Footer -->
        <div class="bg-black/[0.02] px-6 py-3.5 border-t border-black/[0.06] flex items-center justify-between">
          <button
            type="button"
            @click="closePinModal"
            class="px-4 py-2 rounded-xl bg-black/[0.04] hover:bg-black/[0.08] text-[#1d1d1f] text-xs font-semibold"
          >
            Batal
          </button>
          <button
            type="button"
            @click="submitPinOverride"
            :disabled="pinInput.length < 6 || !selectedReason || isPinSubmitting"
            class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm disabled:opacity-50"
          >
            {{ isPinSubmitting ? 'Memproses...' : 'Buka Palang ➜' }}
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
const { gateCheckIn, gateTiketMasuk, gateOverrideIn } = useApi()

// Clock
const currentTime = ref('14:00:00 WIB')
const currentDate = ref('Minggu, 4 Oktober 2026')
let clockInterval: any = null

onMounted(() => {
  updateClock()
  clockInterval = setInterval(updateClock, 1000)
})

onUnmounted(() => {
  if (clockInterval) clearInterval(clockInterval)
  if (autoCloseInterval) clearInterval(autoCloseInterval)
})

const updateClock = () => {
  const d = new Date()
  currentTime.value = d.toLocaleTimeString('id-ID') + ' WIB'
  currentDate.value = d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
}

// Gate State: 'idle' | 'tiket_umum' | 'aktif' | 'kadaluarsa' | 'tidak_dikenal' | 'bypass_pin'
const gateState = ref<'idle' | 'tiket_umum' | 'aktif' | 'kadaluarsa' | 'tidak_dikenal' | 'bypass_pin'>('idle')
const isBarrierOpen = ref(false)
const autoCloseTimer = ref(5)
let autoCloseInterval: any = null
const isLoading = ref(false)
const currentDetectedPlate = ref('B 1234 ABC')
const intercomActive = ref(false)

// Ticket state
const selectedVehicleType = ref<'mobil' | 'motor'>('mobil')
const ticketIssued = ref<any>(null)

// Member & PIN state
const gateResult = ref<any>({})
const pinSuccessData = ref<any>({})
const customQrInput = ref('')

const ledDisplayText = computed(() => {
  if (gateState.value === 'tiket_umum') return '[ TIKET PARKIR DICETAK : SILAKAN MASUK ]'
  if (gateState.value === 'aktif') return '[ MEMBER AKTIF : SILAKAN MASUK ]'
  if (gateState.value === 'kadaluarsa') return '[ QR KADALUARSA : HUBUNGI KASIR ]'
  if (gateState.value === 'tidak_dikenal') return '[ QR CODE TIDAK TERDAFTAR ]'
  if (gateState.value === 'bypass_pin') return '[ OVERRIDE PETUGAS : DIIZINKAN ]'
  return '[ STANDBY : TEKAN TOMBOL MASUK ATAU SCAN QR MEMBER ]'
})

const ledDisplayClass = computed(() => {
  if (gateState.value === 'tiket_umum') return 'text-emerald-400 drop-shadow-[0_0_8px_rgba(52,211,153,0.8)]'
  if (gateState.value === 'aktif') return 'text-emerald-400 drop-shadow-[0_0_8px_rgba(52,211,153,0.8)]'
  if (gateState.value === 'kadaluarsa') return 'text-rose-500 drop-shadow-[0_0_8px_rgba(244,63,94,0.8)]'
  if (gateState.value === 'tidak_dikenal') return 'text-slate-400'
  if (gateState.value === 'bypass_pin') return 'text-amber-400 drop-shadow-[0_0_8px_rgba(251,191,36,0.8)]'
  return 'text-emerald-400'
})

// Action 1: Handle Tombol Masuk (Pengunjung Biasa / Tiket)
const handleTombolMasuk = async () => {
  isLoading.value = true
  const prefix = selectedVehicleType.value === 'motor' ? 'MTR' : 'MBL'
  currentDetectedPlate.value = `B ${Math.floor(1000 + Math.random() * 9000)} ${prefix}`

  try {
    const res: any = await gateTiketMasuk(selectedVehicleType.value, 'GATE-IN 01', currentDetectedPlate.value)
    if (res?.success) {
      ticketIssued.value = res.data
      gateState.value = 'tiket_umum'
      openBarrier()
    } else {
      fallbackTicket()
    }
  } catch {
    fallbackTicket()
  } finally {
    isLoading.value = false
  }
}

const fallbackTicket = () => {
  const now = new Date()
  const pad = (n: number) => n.toString().padStart(2, '0')
  const ticketNo = `TKT-${now.getFullYear()}${pad(now.getMonth()+1)}${pad(now.getDate())}-${Math.floor(1000 + Math.random() * 9000)}`
  ticketIssued.value = {
    no_tiket: ticketNo,
    no_plat: currentDetectedPlate.value,
    jenis_kendaraan: selectedVehicleType.value.toUpperCase(),
    waktu_masuk: now.toLocaleTimeString('id-ID') + ' WIB',
    tanggal: now.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
  }
  gateState.value = 'tiket_umum'
  openBarrier()
}

// Action 2: Simulate Scan QR Member
const simulateTap = async (identifier: string) => {
  if (!identifier) return
  isLoading.value = true

  if (identifier.includes('001') || identifier.includes('1234') || identifier === 'QR-MBR-2026-001') {
    currentDetectedPlate.value = 'B 1234 ABC'
  } else if (identifier.includes('003') || identifier.includes('4567') || identifier === 'QR-MBR-2026-003') {
    currentDetectedPlate.value = 'B 4567 DEF'
  } else if (identifier.includes('002') || identifier.includes('9999') || identifier === 'QR-MBR-2026-002') {
    currentDetectedPlate.value = 'B 9999 EXP'
  } else {
    currentDetectedPlate.value = 'B 7777 UNK'
  }

  try {
    const res: any = await gateCheckIn(identifier, 'GATE-IN 01', currentDetectedPlate.value)
    if (res?.success) {
      gateState.value = 'aktif'
      gateResult.value = res.data
      openBarrier()
    } else {
      handleGateError(res, identifier)
    }
  } catch (err: any) {
    handleGateError(err?.data || { status: identifier.includes('002') ? 'kadaluarsa' : (identifier.includes('001') || identifier.includes('003') ? 'aktif' : 'tidak_dikenal') }, identifier)
  } finally {
    isLoading.value = false
  }
}

const handleGateError = (errData: any, identifier?: string) => {
  if (errData?.status === 'aktif' || (identifier && (identifier.includes('001') || identifier.includes('003')))) {
    gateState.value = 'aktif'
    gateResult.value = {
      member: { nama_member: identifier?.includes('003') ? 'Siti Rahma' : 'Ahmad Favian' },
      kendaraan: {
        no_plat: identifier?.includes('003') ? 'B 4567 DEF' : 'B 1234 ABC',
        jenis_kendaraan: identifier?.includes('003') ? 'motor' : 'mobil',
        merk: identifier?.includes('003') ? 'Yamaha NMAX' : 'Honda HR-V'
      },
      tgl_kadaluarsa: identifier?.includes('003') ? '15 Desember 2026' : '20 November 2026',
      sisa_hari: identifier?.includes('003') ? 73 : 48
    }
    openBarrier()
  } else if (errData?.status === 'kadaluarsa' || (identifier && identifier.includes('002'))) {
    gateState.value = 'kadaluarsa'
    gateResult.value = errData?.data || {
      member: { nama_member: 'Budi Santoso' },
      tgl_kadaluarsa: '01 Agustus 2026',
      kendaraan: { no_plat: 'B 9999 EXP' }
    }
  } else {
    gateState.value = 'tidak_dikenal'
  }
}

// Barrier Mechanism
const openBarrier = () => {
  isBarrierOpen.value = true
  autoCloseTimer.value = 5
  if (autoCloseInterval) clearInterval(autoCloseInterval)
  autoCloseInterval = setInterval(() => {
    autoCloseTimer.value--
    if (autoCloseTimer.value <= 0) {
      closeBarrier()
    }
  }, 1000)
}

const closeBarrier = () => {
  isBarrierOpen.value = false
  if (autoCloseInterval) clearInterval(autoCloseInterval)
  setTimeout(() => {
    // Reset back to idle standby after barrier closes
    gateState.value = 'idle'
    ticketIssued.value = null
  }, 1200)
}

const triggerIntercom = () => {
  intercomActive.value = true
  setTimeout(() => {
    intercomActive.value = false
  }, 4000)
}

// PIN Override Modal
const showPinModal = ref(false)
const pinInput = ref('')
const selectedReason = ref('Kartu Rusak / Tidak Terbaca')
const pinError = ref('')
const isPinSubmitting = ref(false)

const overrideReasons = [
  'Kartu Rusak / Tidak Terbaca',
  'Iuran Baru Dibayar di Loket',
  'Kendaraan Darurat / Prioritas',
  'Instruksi Supervisor'
]

const appendPin = (d: number) => {
  if (pinInput.value.length < 6) {
    pinInput.value += d.toString()
    pinError.value = ''
  }
}

const backspacePin = () => {
  pinInput.value = pinInput.value.slice(0, -1)
}

const clearPin = () => {
  pinInput.value = ''
}

const closePinModal = () => {
  showPinModal.value = false
  pinInput.value = ''
  pinError.value = ''
}

const submitPinOverride = async () => {
  isPinSubmitting.value = true
  pinError.value = ''

  try {
    const res: any = await gateOverrideIn(pinInput.value, selectedReason.value, currentDetectedPlate.value, 'GATE-IN 01')
    if (res?.success) {
      pinSuccessData.value = res.data
      gateState.value = 'bypass_pin'
      closePinModal()
      openBarrier()
    } else {
      pinError.value = res?.message || 'PIN Petugas tidak valid'
    }
  } catch (err: any) {
    pinError.value = err?.data?.message || 'PIN Petugas salah (Gunakan 998877 atau 123456)'
  } finally {
    isPinSubmitting.value = false
  }
}
</script>
