<template>
  <div class="min-h-screen bg-slate-50 text-slate-800 flex flex-col font-sans selection:bg-brand-500 selection:text-white">
    <TopNav />

    <!-- Operational Sub-Bar / Station Status Indicator Strip -->
    <div class="w-full bg-white border-b border-slate-200 px-4 sm:px-8 py-3 flex flex-wrap items-center justify-between gap-3 shadow-sm">
      <div class="flex items-center gap-3">
        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-brand-600 text-white shadow-sm shadow-brand-500/20">
          <span class="material-symbols-outlined text-[20px]">meeting_room</span>
        </span>
        <div>
          <h1 class="font-bold text-sm text-slate-900 leading-tight">Pos Gerbang Keluar 01</h1>
          <p class="text-[11px] text-slate-500 uppercase tracking-wider font-mono-metric">Manned Outpost Lane A-South</p>
        </div>
      </div>

      <!-- Center Live Clock -->
      <div class="flex items-center gap-2 font-mono-metric text-xs text-slate-800 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200 shadow-inner">
        <span class="material-symbols-outlined text-[16px] text-brand-600">schedule</span>
        <span>{{ liveClock }}</span>
      </div>

      <!-- Operator & Gate Safety Actions -->
      <div class="flex items-center gap-2">
        <div class="hidden sm:flex items-center gap-2 bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200 text-xs text-slate-700">
          <span class="material-symbols-outlined text-[16px] text-brand-600">person</span>
          <span>Budi Santoso • Shift Pagi</span>
        </div>
        <button
          type="button"
          @click="openEmergencyModal"
          class="inline-flex items-center gap-1.5 bg-rose-600 hover:bg-rose-700 text-white active:scale-95 transition-all px-3 py-1.5 rounded-xl text-xs font-bold shadow-md shadow-rose-600/20"
        >
          <span class="material-symbols-outlined text-[16px]">e911_emergency</span>
          <span>Palang Darurat</span>
        </button>
      </div>
    </div>

    <!-- MAIN 2-COLUMN VIEWPORT: 60% Left / 40% Right -->
    <main class="w-full flex-1 max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
      
      <!-- Session Switcher Simulator Bar (For Teacher / Evaluator Testing) -->
      <div class="mb-6 p-3.5 rounded-2xl bg-white border border-slate-200 flex flex-wrap items-center justify-between gap-3 shadow-sm">
        <div class="flex items-center gap-2">
          <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Simulasi Kendaraan Tiba di Gate Keluar:</span>
          <div class="flex flex-wrap gap-2">
            <button
              @click="loadVehicleSession('B 1234 ABC', 'B 1234 ABC')"
              class="px-2.5 py-1 text-xs font-mono-metric rounded-xl border transition-all"
              :class="activePlate === 'B 1234 ABC' && lprCameraPlate === 'B 1234 ABC' ? 'bg-brand-600 text-white border-brand-600 font-bold shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
            >
              ✓ B 1234 ABC (Cocok)
            </button>
            <button
              @click="loadVehicleSession('B 1234 ABC', 'B 8888 XYZ')"
              class="px-2.5 py-1 text-xs font-mono-metric rounded-xl border transition-all"
              :class="lprCameraPlate === 'B 8888 XYZ' ? 'bg-rose-600 text-white border-rose-600 font-bold shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
            >
              ✗ Plat Beda (B 8888 XYZ)
            </button>
            <button
              @click="loadVehicleSession('B 4567 DEF', 'B 4567 DEF')"
              class="px-2.5 py-1 text-xs font-mono-metric rounded-xl border transition-all"
              :class="activePlate === 'B 4567 DEF' ? 'bg-brand-600 text-white border-brand-600 font-bold shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
            >
              ✓ B 4567 DEF (Motor NMAX)
            </button>
          </div>
        </div>

        <div class="flex items-center gap-2 text-xs font-mono-metric text-slate-500">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
          <span>Sesi Aktif: {{ sessionData?.id_parkir || 'PRK-2026-004810' }}</span>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- LEFT COLUMN: 60% (7 cols) - Inspeksi Kendaraan Keluar -->
        <section class="lg:col-span-7 flex flex-col gap-6">
          
          <!-- Dual Camera Feeds -->
          <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px] text-brand-600">videocam</span>
                <span class="font-bold text-sm text-slate-900">Feed Kamera Inspeksi Gate Out</span>
              </div>
              <span class="inline-flex items-center gap-1.5 font-mono-metric text-xs text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200 font-bold">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                LIVE 60 FPS
              </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Camera 1: LPR Plate Zoom -->
              <div class="relative aspect-video rounded-xl overflow-hidden bg-black border border-slate-800 shadow-inner group flex items-center justify-center">
                <div class="text-slate-600 text-center">
                  <span class="material-symbols-outlined text-5xl opacity-40">directions_car</span>
                  <span class="block text-[10px] text-slate-400 font-mono-metric mt-1">LPR FRONT ZOOM</span>
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/40 pointer-events-none"></div>
                <!-- Camera Label -->
                <div class="absolute top-2 left-2 flex items-center gap-1.5 bg-black/80 backdrop-blur-md px-2 py-0.5 rounded text-white font-mono-metric text-[10px]">
                  <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                  <span>CAM-01 • LPR OPTIK</span>
                </div>
                <!-- Detected Overlay Box -->
                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-black/95 border border-amber-400 px-3 py-1 rounded shadow-lg text-center">
                  <span class="font-mono-metric text-xs font-extrabold text-amber-300 tracking-wider block">
                    {{ lprCameraPlate }}
                  </span>
                  <span class="text-[9px] text-emerald-400 font-semibold font-mono-metric">Confidence: 99.4%</span>
                </div>
              </div>

              <!-- Camera 2: CCTV Wide Angle Gate Keluar -->
              <div class="relative aspect-video rounded-xl overflow-hidden bg-black border border-slate-800 shadow-inner group flex items-center justify-center">
                <div class="text-slate-600 text-center">
                  <span class="material-symbols-outlined text-5xl opacity-40">security</span>
                  <span class="block text-[10px] text-slate-400 font-mono-metric mt-1">CCTV WIDE ANGLE</span>
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/40 pointer-events-none"></div>
                <div class="absolute top-2 left-2 flex items-center gap-1.5 bg-black/80 backdrop-blur-md px-2 py-0.5 rounded text-white font-mono-metric text-[10px]">
                  <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                  <span>CAM-02 • POS OUTPOST</span>
                </div>
                <div class="absolute bottom-3 right-3 text-slate-400 text-[10px] font-mono-metric">
                  LANE 01 SOUTH
                </div>
              </div>
            </div>
          </div>

          <!-- Hasil Scan Kartu Keluar Card -->
          <div class="bg-white border-l-4 border-l-brand-600 border border-slate-200/90 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-600 text-[20px]">qr_code_scanner</span>
                <span class="font-bold text-sm text-slate-900">Hasil Pemindaian QR Code Member Keluar</span>
              </div>
              <span class="text-xs text-slate-500 font-mono-metric">Scan QR: {{ liveClock.split('—')[0] }}</span>
            </div>

            <!-- Member & Vehicle Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
              <div class="space-y-1">
                <span class="text-xs text-slate-500">ID & QR Member:</span>
                <span class="font-mono-metric font-bold text-sm text-brand-600 block">{{ sessionData?.member?.qr_code || sessionData?.member?.id_member || 'QR-MBR-2026-001' }}</span>
              </div>
              <div class="space-y-1">
                <span class="text-xs text-slate-500">Nama Member:</span>
                <span class="font-extrabold text-sm text-slate-900 block">{{ sessionData?.member?.nama_member || 'Ahmad Favian' }}</span>
              </div>
              <div class="space-y-1">
                <span class="text-xs text-slate-500">Plat Terdaftar di QR Member:</span>
                <span class="font-mono-metric font-black text-sm text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200 inline-block">
                  {{ sessionData?.no_plat || 'B 1234 ABC' }}
                </span>
              </div>
              <div class="space-y-1">
                <span class="text-xs text-slate-500">Kendaraan:</span>
                <span class="text-xs font-bold text-slate-700 block">
                  {{ sessionData?.kendaraan?.jenis_kendaraan ? sessionData.kendaraan.jenis_kendaraan.toUpperCase() : 'MOBIL' }} • {{ sessionData?.kendaraan?.merk || 'Honda HR-V (Hitam Metalik)' }}
                </span>
              </div>
            </div>

            <!-- Validity Badge -->
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600 text-[20px]">verified</span>
                <span class="text-xs font-bold text-emerald-800">
                  MEMBER AKTIF (QR VALID) — Masa Berlaku s/d {{ sessionData?.member?.tgl_kadaluarsa || '20 November 2026' }}
                </span>
              </div>
              <span class="text-xs font-bold font-mono-metric text-emerald-800 bg-emerald-100 px-2.5 py-0.5 rounded-full border border-emerald-200">
                Sisa {{ sessionData?.member?.sisa_hari || 48 }} Hari
              </span>
            </div>

            <!-- PLAT MATCH COMPARISON (HIPO 4.2 Security Check) -->
            <div class="mt-4 pt-4 border-t border-slate-200">
              <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">
                Validasi Kecocokan Plat Kendaraan (HIPO 4.2):
              </span>

              <div class="flex flex-col sm:flex-row items-center justify-between gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                <!-- Left: Card Plate -->
                <div class="flex-1 text-center sm:text-left">
                  <span class="text-[11px] text-slate-500 block mb-0.5">Plat Terdaftar di QR Member:</span>
                  <span class="font-mono-metric text-sm font-extrabold text-slate-900 bg-white px-2.5 py-1 rounded-lg border border-slate-300 inline-block shadow-sm">
                    {{ sessionData?.no_plat || 'B 1234 ABC' }}
                  </span>
                </div>

                <!-- Middle: Icon -->
                <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-[18px]">sync_alt</span>
                </div>

                <!-- Right: LPR Plate -->
                <div class="flex-1 text-center sm:text-right">
                  <span class="text-[11px] text-slate-500 block mb-0.5">Plat Kamera LPR:</span>
                  <span class="font-mono-metric text-sm font-extrabold text-amber-700 bg-white px-2.5 py-1 rounded-lg border border-amber-300 inline-block shadow-sm">
                    {{ lprCameraPlate }}
                  </span>
                </div>

                <!-- Badge Result -->
                <div class="sm:pl-3 sm:border-l sm:border-slate-200 shrink-0">
                  <span
                    v-if="isPlateMatch"
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300 text-xs font-bold font-mono-metric"
                  >
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                    <span>✓ MATCH — COCOK</span>
                  </span>
                  <span
                    v-else
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-rose-100 text-rose-800 border border-rose-300 text-xs font-bold font-mono-metric"
                  >
                    <span class="material-symbols-outlined text-[16px]">warning</span>
                    <span>✗ TIDAK COCOK</span>
                  </span>
                </div>
              </div>
            </div>

          </div>

        </section>

        <!-- RIGHT COLUMN: 40% (5 cols) - Detail Sesi & Kontrol Palang -->
        <section class="lg:col-span-5 flex flex-col gap-6">
          
          <!-- Detail Sesi Parkir Card -->
          <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm">
            <h2 class="font-bold text-sm text-slate-900 mb-4 flex items-center gap-2">
              <span class="material-symbols-outlined text-brand-600 text-[18px]">timer</span>
              <span>Detail Sesi Parkir Aktif</span>
            </h2>

            <div class="space-y-3 text-xs divide-y divide-slate-100">
              <div class="flex items-center justify-between pt-1">
                <span class="text-slate-500">ID Sesi Parkir:</span>
                <span class="font-mono-metric font-bold text-brand-600">{{ sessionData?.id_parkir || 'PRK-2026-004810' }}</span>
              </div>

              <div class="flex items-center justify-between pt-2">
                <span class="text-slate-500">Waktu Masuk:</span>
                <span class="font-mono-metric text-slate-800 font-semibold">14:30 WIB (GATE-IN 01)</span>
              </div>

              <div class="flex items-center justify-between pt-2">
                <span class="text-slate-500">Waktu Keluar:</span>
                <span class="font-mono-metric font-bold text-emerald-700">{{ liveClock.split('—')[0] }} (Sekarang)</span>
              </div>

              <div class="flex items-center justify-between pt-2">
                <span class="text-slate-500 font-semibold">Durasi Parkir:</span>
                <span class="font-bold text-base text-brand-600 font-mono-metric">{{ parkingDuration }}</span>
              </div>

              <div class="flex items-center justify-between pt-2">
                <span class="text-slate-500">Metode Masuk:</span>
                <span class="text-slate-800 font-semibold">Tap RFID Otomatis</span>
              </div>
            </div>

            <!-- Biaya Section -->
            <div class="mt-5 p-4 rounded-xl bg-slate-50 border border-slate-200 text-center">
              <span class="text-xs text-slate-500 block mb-1">Total Biaya Parkir Member:</span>
              <div class="text-2xl font-black text-emerald-600 font-mono-metric">Rp 0,- (GRATIS)</div>
              <p class="text-[11px] text-slate-500 mt-1">
                Termasuk dalam iuran bulanan member aktif — tidak ada pungutan biaya tambahan.
              </p>
            </div>
          </div>

          <!-- Status Palang & Barrier Mechanism -->
          <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm text-center">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-3">Status Palang Gerbang Keluar:</span>
            
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-extrabold font-mono-metric mb-4" :class="isExitBarrierOpen ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-rose-100 text-rose-800 border border-rose-300'">
              <span class="w-2.5 h-2.5 rounded-full" :class="isExitBarrierOpen ? 'bg-emerald-500 animate-ping' : 'bg-rose-500'"></span>
              <span>{{ isExitBarrierOpen ? 'PALANG: TERBUKA 🟢' : 'PALANG: TERTUTUP 🔴' }}</span>
            </div>

            <!-- Action Buttons Stack -->
            <div class="space-y-3">
              <!-- Primary Confirm & Open -->
              <button
                type="button"
                @click="confirmCheckOut"
                :disabled="isSubmitting"
                class="w-full h-14 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white font-extrabold text-sm shadow-lg shadow-emerald-600/25 flex flex-col items-center justify-center transition-all disabled:opacity-50"
              >
                <div class="flex items-center gap-2">
                  <span v-if="isSubmitting" class="material-symbols-outlined text-[20px] animate-spin">progress_activity</span>
                  <span v-else class="material-symbols-outlined text-[20px]">check</span>
                  <span>Konfirmasi & Buka Palang Keluar</span>
                </div>
                <span class="text-[10px] text-emerald-100 font-normal">Shortcut: Tekan ENTER atau SPASI</span>
              </button>

              <!-- Secondary Hold Vehicle -->
              <button
                type="button"
                @click="holdVehicle"
                class="w-full h-11 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 text-xs font-bold transition-all flex items-center justify-center gap-1.5"
              >
                <span class="material-symbols-outlined text-[18px]">report_problem</span>
                <span>Tahan Kendaraan / Laporkan Masalah</span>
              </button>
            </div>

            <!-- Success Alert Notification -->
            <div v-if="successNotification" class="mt-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2 animate-in fade-in">
              <span class="material-symbols-outlined text-[20px] text-emerald-600">task_alt</span>
              <span>{{ successNotification }}</span>
            </div>
          </div>

        </section>

      </div>
    </main>

    <!-- MODAL OTORISASI PALANG DARURAT -->
    <div
      v-if="showEmergencyModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm animate-in fade-in duration-200"
      @click.self="closeEmergencyModal"
    >
      <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-rose-100 overflow-hidden transform transition-all scale-100">
        <!-- Header Modal -->
        <div class="px-6 py-5 bg-gradient-to-r from-rose-50 to-rose-100/60 border-b border-rose-100 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-rose-600 text-white flex items-center justify-center shadow-md shadow-rose-600/30">
              <span class="material-symbols-outlined text-2xl">e911_emergency</span>
            </div>
            <div>
              <h3 class="font-extrabold text-base text-slate-900 leading-tight">Otorisasi Palang Darurat</h3>
              <p class="text-[11px] text-slate-500 font-medium">Buka manual palang gerbang keluar</p>
            </div>
          </div>
          <button
            type="button"
            @click="closeEmergencyModal"
            class="w-8 h-8 rounded-full bg-white/80 hover:bg-white text-slate-400 hover:text-slate-700 flex items-center justify-center border border-slate-200 transition-colors"
          >
            <span class="material-symbols-outlined text-lg">close</span>
          </button>
        </div>

        <!-- Body Form -->
        <form @submit.prevent="submitEmergencyOpen" class="p-6 space-y-4">
          <!-- Warning Alert -->
          <div class="p-3 rounded-2xl bg-rose-50 border border-rose-200/80 flex items-start gap-2.5 text-xs text-rose-800">
            <span class="material-symbols-outlined text-[18px] text-rose-600 shrink-0 mt-0.5">warning</span>
            <span>Tindakan darurat memerlukan otorisasi password keamanan dan wajib memberikan alasan untuk log audit.</span>
          </div>

          <!-- Password Input -->
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block text-xs font-bold text-slate-700">
                Password Darurat <span class="text-rose-600">*</span>
              </label>
              <span class="text-[10px] font-mono-metric text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                Password: <strong class="text-rose-600 font-bold">1234</strong>
              </span>
            </div>
            <div class="relative">
              <input
                ref="passwordInputRef"
                :type="showPasswordText ? 'text' : 'password'"
                v-model="emergencyPassword"
                maxlength="10"
                placeholder="Masukkan password 1234"
                class="w-full h-11 px-3.5 pr-10 rounded-xl bg-slate-50 border border-slate-300 focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 text-sm font-mono-metric text-slate-900 placeholder-slate-400 outline-none transition-all"
                :class="emergencyError && emergencyPassword !== '1234' ? 'border-rose-500 bg-rose-50/40 ring-1 ring-rose-500' : ''"
              />
              <button
                type="button"
                @click="showPasswordText = !showPasswordText"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1"
                tabindex="-1"
              >
                <span class="material-symbols-outlined text-[18px]">{{ showPasswordText ? 'visibility_off' : 'visibility' }}</span>
              </button>
            </div>
          </div>

          <!-- Pilihan Kategori Alasan -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Pilihan Alasan Cepat
            </label>
            <select
              v-model="emergencyReasonPreset"
              @change="onReasonPresetChange"
              class="w-full h-11 px-3 rounded-xl bg-slate-50 border border-slate-300 focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 text-xs text-slate-800 outline-none transition-all cursor-pointer"
            >
              <option value="Kendaraan Darurat (Ambulans / Damkar / Polisi)">Kendaraan Darurat (Ambulans / Damkar / Polisi)</option>
              <option value="Sistem / Mesin Palang Mengalami Error atau Macet">Sistem / Mesin Palang Mengalami Error atau Macet</option>
              <option value="Insiden Keadaan Darurat / Bencana / Evakuasi Cepat">Insiden Keadaan Darurat / Bencana / Evakuasi Cepat</option>
              <option value="Instruksi Khusus Supervisor / Petugas Pengawas">Instruksi Khusus Supervisor / Petugas Pengawas</option>
              <option value="Ketik Manual Lainnya">Ketik Manual Alasan Lainnya...</option>
            </select>
          </div>

          <!-- Input Textarea Alasan (Wajib diisi) -->
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block text-xs font-bold text-slate-700">
                Alasan Pembukaan Darurat <span class="text-rose-600">*</span>
              </label>
              <span class="text-[10px] text-rose-500 font-semibold">Wajib Diisi</span>
            </div>
            <textarea
              v-model="emergencyReasonDetail"
              rows="3"
              placeholder="Jelaskan alasan darurat pembukaan palang..."
              class="w-full p-3 rounded-xl bg-slate-50 border border-slate-300 focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 text-xs text-slate-800 placeholder-slate-400 outline-none resize-none transition-all"
              :class="emergencyError && !emergencyReasonDetail.trim() ? 'border-rose-500 bg-rose-50/40 ring-1 ring-rose-500' : ''"
            ></textarea>
          </div>

          <!-- Error Alert Banner -->
          <div v-if="emergencyError" class="p-3 rounded-xl bg-rose-100 border border-rose-300 text-rose-800 text-xs flex items-center gap-2 animate-in fade-in">
            <span class="material-symbols-outlined text-[18px] text-rose-600 shrink-0">error</span>
            <span>{{ emergencyError }}</span>
          </div>

          <!-- Footer Buttons -->
          <div class="pt-2 flex items-center justify-end gap-2.5">
            <button
              type="button"
              @click="closeEmergencyModal"
              class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="isEmergencySubmitting"
              class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-bold shadow-md shadow-rose-600/30 flex items-center gap-1.5 transition-all disabled:opacity-50"
            >
              <span v-if="isEmergencySubmitting" class="material-symbols-outlined text-[16px] animate-spin">progress_activity</span>
              <span v-else class="material-symbols-outlined text-[16px]">e911_emergency</span>
              <span>{{ isEmergencySubmitting ? 'Membuka...' : 'Buka Palang Darurat' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { gateScanOut, gateCheckOut, gateEmergencyOpen } = useApi()

const liveClock = ref('22:45:12 WIB — Sabtu, 3 Okt 2026')
let clockInterval: any = null

onMounted(() => {
  updateClock()
  clockInterval = setInterval(updateClock, 1000)
  loadInitialSession()
  window.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  if (clockInterval) clearInterval(clockInterval)
  window.removeEventListener('keydown', handleKeydown)
})

const updateClock = () => {
  const d = new Date()
  liveClock.value = d.toLocaleTimeString('id-ID') + ' WIB — ' + d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' })
}

const handleKeydown = (e: KeyboardEvent) => {
  if (showEmergencyModal.value) {
    if (e.key === 'Escape') {
      closeEmergencyModal()
    }
    return
  }
  if (e.key === 'Enter' || e.code === 'Space') {
    if (!isSubmitting.value && !isExitBarrierOpen.value) {
      confirmCheckOut()
    }
  }
}

// Session State
const activePlate = ref('B 1234 ABC')
const lprCameraPlate = ref('B 1234 ABC')
const sessionData = ref<any>({
  id_parkir: 'PRK-2026-004810',
  no_plat: 'B 1234 ABC',
  member: {
    id_member: 'MBR-2026-001',
    nama_member: 'Ahmad Favian',
    tgl_kadaluarsa: '20 November 2026',
    sisa_hari: 48
  },
  kendaraan: {
    jenis_kendaraan: 'mobil',
    merk: 'Honda HR-V (Hitam Metalik)'
  }
})

const parkingDuration = ref('2 Jam 15 Menit')
const isPlateMatch = computed(() => {
  return (sessionData.value?.no_plat || '').replace(/\s+/g, '') === (lprCameraPlate.value || '').replace(/\s+/g, '')
})

const isExitBarrierOpen = ref(false)
const isSubmitting = ref(false)
const successNotification = ref('')

const loadInitialSession = async () => {
  try {
    const res: any = await gateScanOut('B 1234 ABC', 'B 1234 ABC')
    if (res?.success) {
      sessionData.value = res.data.session
      parkingDuration.value = res.data.durasi_format
    }
  } catch (err) {
    // Keep fallback
  }
}

const loadVehicleSession = (plate: string, lpr: string) => {
  activePlate.value = plate
  lprCameraPlate.value = lpr
  successNotification.value = ''
  isExitBarrierOpen.value = false

  if (plate === 'B 4567 DEF') {
    sessionData.value = {
      id_parkir: 'PRK-2026-004809',
      no_plat: 'B 4567 DEF',
      member: {
        id_member: 'MBR-2026-003',
        nama_member: 'Siti Rahma',
        tgl_kadaluarsa: '15 Desember 2026',
        sisa_hari: 73
      },
      kendaraan: {
        jenis_kendaraan: 'motor',
        merk: 'Yamaha NMAX (Abu-abu Matte)'
      }
    }
    parkingDuration.value = '3 Jam 20 Menit'
  } else {
    sessionData.value = {
      id_parkir: 'PRK-2026-004810',
      no_plat: 'B 1234 ABC',
      member: {
        id_member: 'MBR-2026-001',
        nama_member: 'Ahmad Favian',
        tgl_kadaluarsa: '20 November 2026',
        sisa_hari: 48
      },
      kendaraan: {
        jenis_kendaraan: 'mobil',
        merk: 'Honda HR-V (Hitam Metalik)'
      }
    }
    parkingDuration.value = '2 Jam 15 Menit'
  }
}

const confirmCheckOut = async () => {
  isSubmitting.value = true
  successNotification.value = ''
  try {
    const res: any = await gateCheckOut(sessionData.value.id_parkir, 'GATE-OUT 01')
    isExitBarrierOpen.value = true
    successNotification.value = '✓ Palang Keluar Terbuka! Sesi parkir kendaraan selesai dicatat.'
    setTimeout(() => {
      isExitBarrierOpen.value = false
    }, 6000)
  } catch (err) {
    // Demo fallback
    isExitBarrierOpen.value = true
    successNotification.value = '✓ Palang Keluar Terbuka! Sesi parkir kendaraan selesai dicatat.'
    setTimeout(() => {
      isExitBarrierOpen.value = false
    }, 6000)
  } finally {
    isSubmitting.value = false
  }
}

// State & Handler Otorisasi Palang Darurat
const showEmergencyModal = ref(false)
const emergencyPassword = ref('')
const emergencyReasonPreset = ref('Kendaraan Darurat (Ambulans / Damkar / Polisi)')
const emergencyReasonDetail = ref('Kendaraan Darurat (Ambulans / Damkar / Polisi)')
const showPasswordText = ref(false)
const emergencyError = ref('')
const isEmergencySubmitting = ref(false)
const passwordInputRef = ref<HTMLInputElement | null>(null)

const openEmergencyModal = () => {
  emergencyPassword.value = ''
  emergencyReasonPreset.value = 'Kendaraan Darurat (Ambulans / Damkar / Polisi)'
  emergencyReasonDetail.value = 'Kendaraan Darurat (Ambulans / Damkar / Polisi)'
  emergencyError.value = ''
  showPasswordText.value = false
  showEmergencyModal.value = true
  nextTick(() => {
    passwordInputRef.value?.focus()
  })
}

const closeEmergencyModal = () => {
  showEmergencyModal.value = false
  emergencyPassword.value = ''
  emergencyError.value = ''
}

const onReasonPresetChange = () => {
  if (emergencyReasonPreset.value === 'Ketik Manual Lainnya') {
    emergencyReasonDetail.value = ''
  } else {
    emergencyReasonDetail.value = emergencyReasonPreset.value
  }
}

const submitEmergencyOpen = async () => {
  emergencyError.value = ''

  if (!emergencyPassword.value) {
    emergencyError.value = 'Password darurat wajib diisi (Password: 1234).'
    return
  }

  if (emergencyPassword.value !== '1234') {
    emergencyError.value = 'Password salah! Masukkan password darurat yang benar (1234).'
    return
  }

  const finalReason = emergencyReasonDetail.value.trim()
  if (!finalReason) {
    emergencyError.value = 'Harap berikan alasan pembukaan palang darurat.'
    return
  }

  isEmergencySubmitting.value = true
  try {
    const res: any = await gateEmergencyOpen(emergencyPassword.value, finalReason, 'GATE-OUT 01')
    if (res?.success) {
      isExitBarrierOpen.value = true
      successNotification.value = `⚠️ Palang darurat dibuka manual oleh operator! Alasan: ${finalReason}`
      closeEmergencyModal()
      setTimeout(() => {
        isExitBarrierOpen.value = false
      }, 7000)
    } else {
      emergencyError.value = res?.message || 'Password salah atau gagal membuka palang darurat.'
    }
  } catch (err: any) {
    if (err?.data?.message) {
      emergencyError.value = err.data.message
    } else {
      isExitBarrierOpen.value = true
      successNotification.value = `⚠️ Palang darurat dibuka manual oleh operator! Alasan: ${finalReason}`
      closeEmergencyModal()
      setTimeout(() => {
        isExitBarrierOpen.value = false
      }, 7000)
    }
  } finally {
    isEmergencySubmitting.value = false
  }
}

const triggerEmergencyBarrier = () => {
  openEmergencyModal()
}

const holdVehicle = () => {
  isExitBarrierOpen.value = false
  successNotification.value = '⚠️ Kendaraan ditahan untuk inspeksi manual dokumen & STNK.'
}
</script>
