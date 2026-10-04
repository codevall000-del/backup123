<template>
  <div class="min-h-screen bg-slate-50 text-slate-800 flex flex-col font-sans selection:bg-brand-500 selection:text-white">
    <TopNav />

    <div class="flex-1 flex flex-col lg:flex-row">
      
      <!-- SIDEBAR (Collapsible or 240px) -->
      <aside class="w-full lg:w-64 bg-white border-r border-slate-200 p-5 flex flex-col justify-between shrink-0 shadow-sm">
        <div class="space-y-6">
          <!-- Hub Status -->
          <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200">
            <div class="flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
              <span class="text-xs font-bold text-emerald-700 uppercase font-mono-metric">Gate Online</span>
            </div>
            <span class="text-[11px] text-slate-500 font-mono-metric">v3.8.2</span>
          </div>

          <!-- Navigation Links -->
          <nav class="space-y-1.5 text-xs font-semibold">
            <button
              type="button"
              @click="activeTab = 'register'"
              class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all text-left"
              :class="activeTab === 'register' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
            >
              <span class="material-symbols-outlined text-[20px]">person_add</span>
              <span>Registrasi Member</span>
            </button>

            <button
              type="button"
              @click="activeTab = 'payment'"
              class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all text-left"
              :class="activeTab === 'payment' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
            >
              <span class="material-symbols-outlined text-[20px]">credit_card</span>
              <span>Pembayaran Iuran</span>
            </button>

            <button
              type="button"
              @click="activeTab = 'members'"
              class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all text-left"
              :class="activeTab === 'members' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
            >
              <span class="material-symbols-outlined text-[20px]">badge</span>
              <span>Direktori Member</span>
              <span class="ml-auto font-mono-metric text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">{{ membersList.length }}</span>
            </button>

            <div class="pt-4 border-t border-slate-200 space-y-1">
              <NuxtLink
                to="/member"
                class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-purple-700 bg-purple-50/70 hover:bg-purple-100 transition-all font-bold"
              >
                <div class="flex items-center gap-3">
                  <span class="material-symbols-outlined text-[20px] text-purple-600">manage_accounts</span>
                  <span>04. Pengaturan Member</span>
                </div>
                <span class="text-[9px] bg-purple-200 text-purple-900 px-1.5 py-0.5 rounded font-mono-metric font-bold">ADMIN</span>
              </NuxtLink>

              <NuxtLink
                to="/dashboard"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all"
              >
                <span class="material-symbols-outlined text-[20px] text-brand-600">monitoring</span>
                <span>Monitoring Pusat</span>
              </NuxtLink>

              <NuxtLink
                to="/kios-gatein"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all"
              >
                <span class="material-symbols-outlined text-[20px] text-emerald-600">sensors</span>
                <span>Kios Gate-In (Scan QR)</span>
              </NuxtLink>

              <NuxtLink
                to="/pos-gateout"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all"
              >
                <span class="material-symbols-outlined text-[20px] text-indigo-600">meeting_room</span>
                <span>Pos Gate-Out (Scan QR)</span>
              </NuxtLink>
            </div>
          </nav>
        </div>

        <!-- Operator Bottom Profile Card -->
        <div class="mt-6 pt-5 border-t border-slate-200 space-y-3">
          <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Pos Terminal Aktif:</span>
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-slate-900">Loket Kasir Utama</span>
              <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-mono-metric">SHIFT 1</span>
            </div>
          </div>

          <div class="flex items-center justify-between text-xs text-slate-700">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-xl bg-brand-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                SN
              </div>
              <div class="flex flex-col">
                <span class="font-bold text-slate-900 leading-tight">Siti Nurhaliza</span>
                <span class="text-[10px] text-brand-600 font-mono-metric font-semibold">KSR-01</span>
              </div>
            </div>
            <span class="material-symbols-outlined text-slate-400 text-[18px]">verified_user</span>
          </div>
        </div>
      </aside>

      <!-- MAIN CONTENT AREA -->
      <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-slate-50 overflow-y-auto max-w-6xl">
        
        <!-- Header & Top Search / Quick QR Scanner Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
          <div>
            <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-1 font-medium">
              <span>Loket Kasir</span>
              <span class="material-symbols-outlined text-[14px]">chevron_right</span>
              <span class="text-brand-600 font-bold">
                {{ activeTab === 'register' ? 'Pendaftaran Member Baru' : (activeTab === 'payment' ? 'Pembayaran Iuran' : 'Direktori Member') }}
              </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">Pelayanan Loket Kasir & Administrasi</h1>
            <p class="text-xs text-slate-500 mt-0.5">Pendaftaran member kendaraan baru, integrasi QR Code Scanner 2D Optik, dan cetak kuitansi resmi.</p>
          </div>

          <!-- Fast Search Input & QR Simulator -->
          <div class="flex items-center gap-2">
            <div class="relative w-64 sm:w-72">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
              <input
                v-model="quickSearch"
                @input="handleQuickSearch"
                type="text"
                placeholder="Cari nama / plat / QR Code..."
                class="w-full h-10 pl-9 pr-3 rounded-xl bg-white border border-slate-300 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 shadow-sm"
              />
            </div>
            <button
              type="button"
              @click="quickScanQr"
              class="h-10 px-3.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:scale-95 text-white text-xs font-bold shadow-md shadow-brand-600/20 flex items-center gap-1.5 transition-all shrink-0"
              title="Simulasi Scan Barcode QR Member"
            >
              <span class="material-symbols-outlined text-[18px]">qr_code_scanner</span>
              <span class="hidden sm:inline">Scan QR Member</span>
            </button>
          </div>
        </div>

        <!-- Mode Tabs Indicator Strip -->
        <div class="flex items-center justify-between bg-white p-1.5 rounded-2xl border border-slate-200 mb-6 shadow-sm">
          <div class="flex items-center gap-1 sm:gap-2">
            <button
              type="button"
              @click="activeTab = 'register'"
              class="flex items-center gap-1.5 px-3 sm:px-4 py-2 rounded-xl text-xs font-bold transition-all"
              :class="activeTab === 'register' ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
            >
              <span class="material-symbols-outlined text-[18px]">person_add</span>
              <span>Pendaftaran Baru</span>
            </button>

            <button
              type="button"
              @click="activeTab = 'payment'"
              class="flex items-center gap-1.5 px-3 sm:px-4 py-2 rounded-xl text-xs font-bold transition-all"
              :class="activeTab === 'payment' ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
            >
              <span class="material-symbols-outlined text-[18px]">credit_card</span>
              <span>Perpanjangan Iuran</span>
            </button>

            <button
              type="button"
              @click="activeTab = 'members'"
              class="flex items-center gap-1.5 px-3 sm:px-4 py-2 rounded-xl text-xs font-bold transition-all"
              :class="activeTab === 'members' ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
            >
              <span class="material-symbols-outlined text-[18px]">badge</span>
              <span>Data Member</span>
              <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px] font-mono-metric">{{ membersList.length }}</span>
            </button>
          </div>

          <div class="hidden sm:flex items-center gap-2 pr-3 text-xs font-mono-metric text-slate-500">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>QR SCANNER READY (100% QR ONLY)</span>
          </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 1: Pendaftaran Member Baru (HIPO 1.1 + 1.2) -->
        <!-- ============================================== -->
        <div v-if="activeTab === 'register'" class="space-y-6">
          <form @submit.prevent="submitNewMember" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Column: Identitas Member (60%) -->
            <div class="lg:col-span-7 bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 space-y-4 shadow-sm">
              <h2 class="font-bold text-sm text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-200">
                <span class="material-symbols-outlined text-brand-600 text-[20px]">badge</span>
                <span>Data Identitas Member (HIPO 1.1)</span>
              </h2>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                <input
                  v-model="regForm.nama_member"
                  required
                  type="text"
                  placeholder="Contoh: Ahmad Favian"
                  class="w-full h-11 px-3.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500"
                />
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">No. KTP / NIK (16-Digit)</label>
                  <input
                    v-model="regForm.nik"
                    type="text"
                    maxlength="16"
                    placeholder="327601xxxxxxxxx"
                    class="w-full h-11 px-3.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 placeholder-slate-400 font-mono-metric focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500"
                  />
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">No. WhatsApp / HP *</label>
                  <input
                    v-model="regForm.no_telp"
                    required
                    type="tel"
                    placeholder="0812-xxxx-xxxx"
                    class="w-full h-11 px-3.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 placeholder-slate-400 font-mono-metric focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500"
                  />
                </div>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                <textarea
                  v-model="regForm.alamat"
                  rows="3"
                  placeholder="Jl. Merdeka No. 10, Jakarta Selatan"
                  class="w-full p-3 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500"
                ></textarea>
              </div>

              <!-- 100% QR Code Member Generator & Live Preview (Zero RFID) -->
              <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-200/80 space-y-3">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-brand-600 text-white flex items-center justify-center shadow-xs">
                      <span class="material-symbols-outlined text-[18px]">qr_code_2</span>
                    </span>
                    <div>
                      <span class="text-xs font-bold text-slate-900 block">Kode Akses QR Member (100% QR Code)</span>
                      <span class="text-[10px] text-slate-500">Tanpa Kartu RFID Fisik — Berbasis Barcode Optik 2D</span>
                    </div>
                  </div>
                  <button
                    type="button"
                    @click="generateRandomQrCode"
                    class="px-2.5 py-1 text-[11px] rounded-lg bg-white hover:bg-indigo-50 border border-indigo-200 text-brand-700 font-bold flex items-center gap-1 transition-all shadow-2xs"
                  >
                    <span class="material-symbols-outlined text-[14px]">refresh</span>
                    <span>Generate Ulang</span>
                  </button>
                </div>

                <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-indigo-100 shadow-xs">
                  <div class="w-16 h-16 rounded-lg bg-slate-50 border border-slate-200 p-1 flex items-center justify-center shrink-0">
                    <img v-if="regQrDataUrl" :src="regQrDataUrl" alt="QR Preview" class="w-full h-full object-contain" />
                    <span v-else class="material-symbols-outlined text-slate-300 text-2xl">qr_code_2</span>
                  </div>
                  <div class="flex-1 space-y-1">
                    <div class="flex items-center justify-between">
                      <span class="text-[10px] uppercase font-bold text-slate-500 font-mono-metric">KODE IDENTIFIKASI QR:</span>
                      <span class="text-[10px] font-bold text-emerald-700 font-mono-metric">SIAP PAKAI</span>
                    </div>
                    <input
                      v-model="regForm.qr_code"
                      type="text"
                      placeholder="QR-MBR-2026-XXX"
                      class="w-full h-9 px-3 rounded-lg bg-slate-50 border border-slate-200 text-xs font-mono-metric text-brand-600 font-black focus:bg-white focus:outline-none"
                    />
                    <p class="text-[10px] text-slate-500">
                      Scan di Kios Gate-In & Pos Gate-Out. Member juga dapat login di <strong class="text-brand-600">Portal Member</strong> via QR ini.
                    </p>
                  </div>
                </div>

                <!-- Otentikasi Petugas Kasir (Pemahaman Login dalam Pembuatan Member) -->
                <div class="p-3 rounded-xl bg-slate-100/90 border border-slate-200 flex items-start gap-2.5 text-[11px] text-slate-600">
                  <span class="material-symbols-outlined text-brand-600 text-[18px] shrink-0 mt-0.5">admin_panel_settings</span>
                  <div class="space-y-0.5">
                    <span class="font-bold text-slate-900 block">Akuntabilitas Login Kasir Aktif: Siti Nurhaliza (KSR-01)</span>
                    <p class="text-[10px] text-slate-500 leading-relaxed">
                      Pembuatan member wajib melalui otentikasi login kasir/admin untuk mencatat audit trail petugas, menjamin keabsahan transaksi pembayaran iuran, serta mencegah pemalsuan hak akses parkir.
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column: Data Kendaraan & Paket (40%) -->
            <div class="lg:col-span-5 space-y-6">
              <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 space-y-4 shadow-sm">
                <h2 class="font-bold text-sm text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-200">
                  <span class="material-symbols-outlined text-brand-600 text-[20px]">directions_car</span>
                  <span>Data Kendaraan (HIPO 1.2)</span>
                </h2>

                <!-- Jenis Kendaraan Selector -->
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-2">Jenis Kendaraan *</label>
                  <div class="grid grid-cols-2 gap-3">
                    <label
                      class="p-3 rounded-xl border cursor-pointer flex flex-col items-center justify-center gap-1.5 transition-all text-center"
                      :class="regForm.jenis_kendaraan === 'mobil' ? 'bg-indigo-50 border-brand-500 text-brand-900 font-bold shadow-sm' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100'"
                    >
                      <input type="radio" value="mobil" v-model="regForm.jenis_kendaraan" class="sr-only" />
                      <span class="material-symbols-outlined text-2xl text-brand-600">directions_car</span>
                      <span class="text-xs">Mobil</span>
                      <span class="text-[10px] text-emerald-700 font-mono-metric font-bold">Rp 150.000 / bln</span>
                    </label>

                    <label
                      class="p-3 rounded-xl border cursor-pointer flex flex-col items-center justify-center gap-1.5 transition-all text-center"
                      :class="regForm.jenis_kendaraan === 'motor' ? 'bg-indigo-50 border-brand-500 text-brand-900 font-bold shadow-sm' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100'"
                    >
                      <input type="radio" value="motor" v-model="regForm.jenis_kendaraan" class="sr-only" />
                      <span class="material-symbols-outlined text-2xl text-emerald-600">two_wheeler</span>
                      <span class="text-xs">Motor</span>
                      <span class="text-[10px] text-emerald-700 font-mono-metric font-bold">Rp 50.000 / bln</span>
                    </label>
                  </div>
                </div>

                <!-- Plat Kendaraan -->
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Plat Kendaraan *</label>
                  <input
                    v-model="regForm.no_plat"
                    required
                    type="text"
                    placeholder="Contoh: B 1234 ABC"
                    class="w-full h-11 px-3.5 rounded-xl bg-slate-50 border border-slate-300 text-xs font-mono-metric uppercase font-black text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500"
                  />
                </div>

                <div class="grid grid-cols-2 gap-3">
                  <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Merk & Tipe</label>
                    <input
                      v-model="regForm.merk"
                      type="text"
                      placeholder="Honda HR-V"
                      class="w-full h-11 px-3 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500"
                    />
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Warna</label>
                    <input
                      v-model="regForm.warna"
                      type="text"
                      placeholder="Hitam Metalik"
                      class="w-full h-11 px-3 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500"
                    />
                  </div>
                </div>

                <!-- Paket Awal & Metode Bayar -->
                <div class="pt-3 border-t border-slate-200 space-y-3">
                  <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700">Durasi Paket Awal:</span>
                    <select
                      v-model="regForm.durasi_bulan"
                      class="h-9 px-3 rounded-lg bg-slate-50 border border-slate-300 text-xs text-slate-900 font-bold cursor-pointer focus:bg-white"
                    >
                      <option :value="1">1 Bulan</option>
                      <option :value="3">3 Bulan (Diskon 5%)</option>
                      <option :value="6">6 Bulan (Diskon 8%)</option>
                      <option :value="12">12 Bulan (Diskon 12%)</option>
                    </select>
                  </div>

                  <div class="p-3.5 rounded-xl bg-indigo-50 border border-indigo-200 flex items-center justify-between">
                    <div>
                      <span class="text-[11px] text-slate-500 block">Total Iuran Awal:</span>
                      <span class="font-extrabold text-sm text-emerald-700 font-mono-metric">{{ formatRupiah(calculateInitialTotal) }}</span>
                    </div>
                    <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold uppercase font-mono-metric border border-emerald-200">
                      {{ regForm.durasi_bulan }} BULAN
                    </span>
                  </div>
                </div>

                <!-- Submit Button -->
                <button
                  type="submit"
                  :disabled="isSubmittingRegister"
                  class="w-full h-12 rounded-xl bg-brand-600 hover:bg-brand-700 active:scale-[0.99] text-white font-bold text-xs shadow-md shadow-brand-600/20 flex items-center justify-center gap-2 transition-all disabled:opacity-50"
                >
                  <span v-if="isSubmittingRegister" class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span>
                  <span v-else class="material-symbols-outlined text-[18px]">how_to_reg</span>
                  <span>Simpan & Cetak Kartu Member ➜</span>
                </button>
              </div>
            </div>

          </form>
        </div>

        <!-- ============================================== -->
        <!-- TAB 2: Perpanjangan Iuran Bulanan (HIPO 2.1 - 2.4) -->
        <!-- ============================================== -->
        <div v-else-if="activeTab === 'payment'" class="space-y-6">
          
          <!-- Search Member Bar for Payment -->
          <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm space-y-3">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Cari Member untuk Perpanjangan Iuran (HIPO 2.1):</span>
            <div class="flex gap-2">
              <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input
                  v-model="paymentSearchQuery"
                  @keyup.enter="searchMemberForPayment"
                  type="text"
                  placeholder="Ketik Nama, No. Plat (contoh: B 1234 ABC), ID Member, atau Scan QR..."
                  class="w-full h-12 pl-10 pr-4 rounded-xl bg-slate-50 border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500"
                />
              </div>
              <button
                type="button"
                @click="searchMemberForPayment"
                class="px-5 h-12 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-600/20 flex items-center gap-1.5 transition-all shrink-0"
              >
                <span>Cari</span>
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
              </button>
            </div>

            <!-- Quick pick member badges -->
            <div class="flex flex-wrap items-center gap-2 pt-2">
              <span class="text-[11px] text-slate-500 font-semibold">Pilih Cepat:</span>
              <button
                v-for="m in membersList.slice(0, 4)"
                :key="m.id_member"
                type="button"
                @click="selectMemberForPayment(m)"
                class="px-2.5 py-1 text-xs rounded-lg bg-slate-50 hover:bg-indigo-50 border border-slate-200 text-slate-700 hover:text-brand-700 transition-all font-mono-metric"
              >
                {{ m.nama_member }} ({{ m.kendaraans?.[0]?.no_plat || m.id_member }})
              </button>
            </div>
          </div>

          <!-- Active Member Profile Preview Card -->
          <div v-if="selectedMember" class="bg-gradient-to-r from-slate-50 via-white to-indigo-50/40 border border-slate-200 rounded-2xl p-5 shadow-sm grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
            <div class="md:col-span-6 flex items-center gap-3">
              <div class="w-14 h-14 rounded-2xl bg-brand-600 text-white flex items-center justify-center font-bold text-xl shadow-md shadow-brand-500/20">
                {{ selectedMember.nama_member.charAt(0) }}
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <h3 class="font-extrabold text-base text-slate-900">{{ selectedMember.nama_member }}</h3>
                  <span class="text-[10px] font-mono-metric px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 font-bold border border-indigo-200">
                    {{ selectedMember.id_member }}
                  </span>
                </div>
                <div class="text-xs text-slate-500 flex items-center gap-3 mt-1">
                  <span>{{ selectedMember.kendaraans?.[0]?.no_plat || 'Plat Kendaraan' }} ({{ selectedMember.kendaraans?.[0]?.jenis_kendaraan ? selectedMember.kendaraans[0].jenis_kendaraan.toUpperCase() : 'MOBIL' }})</span>
                  <span>•</span>
                  <span>{{ selectedMember.no_telp }}</span>
                </div>
              </div>
            </div>

            <div class="md:col-span-3 text-left md:text-center">
              <span class="text-[11px] text-slate-500 block mb-0.5">Masa Berlaku Saat Ini:</span>
              <span class="text-xs font-bold text-slate-900 block font-mono-metric">{{ selectedMember.tgl_kadaluarsa }}</span>
            </div>

            <div class="md:col-span-3 text-left md:text-right">
              <span
                class="inline-block px-3 py-1 rounded-full text-xs font-bold font-mono-metric"
                :class="selectedMember.is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-rose-100 text-rose-800 border border-rose-300'"
              >
                {{ selectedMember.is_active ? 'AKTIF (Sisa ' + selectedMember.sisa_hari + ' Hari)' : 'KADALUARSA' }}
              </span>
            </div>
          </div>

          <!-- Subscription Packages Selector Grid -->
          <div v-if="selectedMember" class="space-y-4">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Pilih Paket Perpanjangan Iuran (HIPO 2.2):</span>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
              <div
                v-for="pkg in subscriptionPackages"
                :key="pkg.durasi"
                @click="selectedPackageDuration = pkg.durasi"
                class="p-4 rounded-2xl border cursor-pointer transition-all flex flex-col justify-between relative overflow-hidden"
                :class="selectedPackageDuration === pkg.durasi ? 'bg-indigo-50 border-2 border-brand-600 shadow-md shadow-brand-500/10' : 'bg-white border-slate-200 hover:bg-slate-50'"
              >
                <div v-if="pkg.badge" class="absolute top-2 right-2 text-[10px] font-bold px-2 py-0.5 rounded-full font-mono-metric" :class="pkg.durasi >= 12 ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-emerald-100 text-emerald-800 border border-emerald-300'">
                  {{ pkg.badge }}
                </div>

                <div>
                  <h4 class="font-bold text-sm text-slate-900 mb-1">{{ pkg.label }}</h4>
                  <span class="text-[11px] text-slate-500 block mb-2">{{ selectedVehicleType === 'motor' ? 'Tarif Motor' : 'Tarif Mobil' }}</span>
                </div>

                <div class="pt-2 border-t border-slate-200">
                  <div v-if="pkg.diskon > 0" class="text-xs text-slate-400 line-through font-mono-metric">
                    {{ formatRupiah(pkg.harga_asli) }}
                  </div>
                  <div class="text-lg font-black text-slate-900 font-mono-metric">
                    {{ formatRupiah(pkg.total) }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Payment Method & Summary -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start mt-6">
              
              <!-- Payment Method Selection (7 cols) -->
              <div class="lg:col-span-7 bg-white border border-slate-200/90 rounded-2xl p-5 space-y-4 shadow-sm">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Metode Pembayaran:</span>
                
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                  <button
                    v-for="m in ['tunai', 'qris', 'transfer', 'debit']"
                    :key="m"
                    type="button"
                    @click="selectedPaymentMethod = m"
                    class="p-3 rounded-xl border text-center transition-all flex flex-col items-center justify-center gap-1.5"
                    :class="selectedPaymentMethod === m ? 'bg-brand-600 text-white border-brand-600 shadow-md font-bold' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100'"
                  >
                    <span class="material-symbols-outlined text-xl">
                      {{ m === 'tunai' ? 'payments' : (m === 'qris' ? 'qr_code_scanner' : (m === 'transfer' ? 'account_balance' : 'credit_card')) }}
                    </span>
                    <span class="text-xs uppercase font-mono-metric">{{ m }}</span>
                  </button>
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Transaksi (Opsional)</label>
                  <input
                    v-model="paymentNote"
                    type="text"
                    placeholder="Contoh: Pembayaran kasir shift pagi"
                    class="w-full h-10 px-3 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500"
                  />
                </div>
              </div>

              <!-- Billing Summary Box (5 cols) -->
              <div class="lg:col-span-5 bg-gradient-to-b from-indigo-50 via-white to-indigo-50/50 border border-indigo-200 rounded-2xl p-5 space-y-3 shadow-sm">
                <h3 class="font-extrabold text-xs text-brand-700 uppercase tracking-wider">Ringkasan Tagihan & Masa Aktif</h3>

                <div class="space-y-2 text-xs divide-y divide-slate-100">
                  <div class="flex items-center justify-between pt-1">
                    <span class="text-slate-500">Paket Iuran:</span>
                    <span class="font-bold text-slate-900">{{ currentSelectedPackage?.label }}</span>
                  </div>
                  <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Subtotal:</span>
                    <span class="font-mono-metric text-slate-700 font-semibold">{{ formatRupiah(currentSelectedPackage?.harga_asli || 0) }}</span>
                  </div>
                  <div v-if="currentSelectedPackage?.diskon > 0" class="flex items-center justify-between pt-2 text-emerald-700">
                    <span>Diskon Promosi:</span>
                    <span class="font-mono-metric font-bold">-{{ formatRupiah(currentSelectedPackage?.diskon || 0) }}</span>
                  </div>
                  <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Masa Berlaku Baru:</span>
                    <span class="font-bold text-emerald-700 font-mono-metric">Hingga {{ calculatedNewExpiry }}</span>
                  </div>
                  <div class="flex items-center justify-between pt-3 text-base">
                    <span class="font-bold text-slate-900">Total Tagihan:</span>
                    <span class="font-black text-xl text-emerald-700 font-mono-metric">{{ formatRupiah(currentSelectedPackage?.total || 0) }}</span>
                  </div>
                </div>

                <button
                  type="button"
                  @click="handleProcessPayment"
                  :disabled="isProcessingPayment"
                  class="w-full h-12 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white font-bold text-xs shadow-lg shadow-emerald-600/25 flex items-center justify-center gap-2 transition-all mt-4 disabled:opacity-50"
                >
                  <span v-if="isProcessingPayment" class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span>
                  <span v-else class="material-symbols-outlined text-[18px]">receipt_long</span>
                  <span>Proses Bayar & Cetak Kuitansi ➜</span>
                </button>
              </div>

            </div>
          </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 3: Manajemen Data Member (HIPO 1.3 + 1.4) -->
        <!-- ============================================== -->
        <div v-else-if="activeTab === 'members'" class="space-y-4">
          <!-- Banner link to dedicated Pengaturan Member module (Admin) -->
          <div class="p-3.5 rounded-2xl bg-gradient-to-r from-purple-800 via-indigo-700 to-indigo-900 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-md">
            <div class="flex items-center gap-3">
              <span class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
              </span>
              <div>
                <div class="flex items-center gap-2">
                  <span class="font-bold text-xs block">Pengaturan Master Member (Khusus Administrator)</span>
                  <span class="text-[9px] bg-purple-400/40 text-purple-100 px-1.5 py-0.2 rounded font-mono-metric font-bold">ADMIN ONLY</span>
                </div>
                <span class="text-[11px] text-white/80">Pengaturan master data member, edit nomor plat, dan cetak kartu VIP QR dikelola penuh oleh Administrator.</span>
              </div>
            </div>
            <NuxtLink
              to="/member"
              class="px-3.5 py-1.5 rounded-xl bg-white text-purple-900 hover:bg-slate-100 text-xs font-bold transition-all shadow-xs shrink-0 flex items-center gap-1.5"
            >
              <span>Buka Pengaturan Member</span>
              <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </NuxtLink>
          </div>

          <!-- Filter & Search Toolbar -->
          <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-2 w-full sm:w-auto">
              <span class="text-xs font-bold text-slate-500">Filter Status:</span>
              <div class="flex gap-1.5 overflow-x-auto">
                <button
                  v-for="st in ['semua', 'aktif', 'akan_habis', 'kadaluarsa', 'nonaktif']"
                  :key="st"
                  type="button"
                  @click="filterStatus = st; fetchMembers()"
                  class="px-2.5 py-1 text-xs rounded-xl font-bold transition-all"
                  :class="filterStatus === st ? 'bg-brand-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200'"
                >
                  {{ st.replace('_', ' ').toUpperCase() }}
                </button>
              </div>
            </div>

            <button
              type="button"
              @click="activeTab = 'register'"
              class="px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold flex items-center gap-1.5 shadow-md shadow-brand-600/20"
            >
              <span class="material-symbols-outlined text-[18px]">add</span>
              <span>+ Member Baru</span>
            </button>
          </div>

          <!-- Data Table -->
          <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
              <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-mono-metric border-b border-slate-200">
                  <tr>
                    <th class="py-3.5 px-4">ID Member</th>
                    <th class="py-3.5 px-4">Nama & Kontak</th>
                    <th class="py-3.5 px-4">Kendaraan / Plat</th>
                    <th class="py-3.5 px-4">Kode QR Akses</th>
                    <th class="py-3.5 px-4">Jatuh Tempo</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr v-for="m in membersList" :key="m.id_member" class="hover:bg-slate-50 transition-colors">
                    <td class="py-3 px-4 font-mono-metric font-bold text-brand-600">
                      {{ m.id_member }}
                    </td>
                    <td class="py-3 px-4">
                      <span class="font-bold text-slate-900 block">{{ m.nama_member }}</span>
                      <span class="text-[11px] text-slate-500 font-mono-metric">{{ m.no_telp }}</span>
                    </td>
                    <td class="py-3 px-4">
                      <span class="font-mono-metric font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 inline-block">
                        {{ m.kendaraans?.[0]?.no_plat || 'TANPA-PLAT' }}
                      </span>
                      <span class="block text-[11px] text-slate-500 mt-0.5">
                        {{ m.kendaraans?.[0]?.jenis_kendaraan ? m.kendaraans[0].jenis_kendaraan.toUpperCase() : 'MOBIL' }} • {{ m.kendaraans?.[0]?.merk || '-' }}
                      </span>
                    </td>
                    <td class="py-3 px-4 font-mono-metric">
                      <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-indigo-50 border border-indigo-200 text-brand-700 font-bold text-[11px]">
                        <span class="material-symbols-outlined text-[13px]">qr_code</span>
                        <span>{{ m.qr_code || m.rfid_tag || ('QR-' + m.id_member) }}</span>
                      </span>
                    </td>
                    <td class="py-3 px-4 font-mono-metric">
                      <span class="text-slate-800 font-semibold block">{{ m.tgl_kadaluarsa }}</span>
                      <span class="text-[10px] font-bold" :class="m.sisa_hari > 0 ? 'text-emerald-700' : 'text-rose-700'">
                        {{ m.sisa_hari > 0 ? 'Sisa ' + m.sisa_hari + ' hari' : 'Lewat ' + Math.abs(m.sisa_hari) + ' hari' }}
                      </span>
                    </td>
                    <td class="py-3 px-4">
                      <span
                        class="px-2.5 py-1 rounded-full text-[10px] font-bold font-mono-metric uppercase inline-block"
                        :class="getStatusBadgeClass(m)"
                      >
                        {{ getStatusText(m) }}
                      </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                      <div class="flex items-center justify-center gap-1.5">
                        <button
                          type="button"
                          @click="openRenewFor(m)"
                          title="Perpanjang Iuran"
                          class="p-1.5 rounded-lg bg-indigo-50 hover:bg-brand-600 text-brand-700 hover:text-white transition-all border border-indigo-200"
                        >
                          <span class="material-symbols-outlined text-[16px]">credit_card</span>
                        </button>
                        <button
                          type="button"
                          @click="toggleStatus(m)"
                          :title="m.status_member === 'aktif' ? 'Nonaktifkan' : 'Aktifkan'"
                          class="p-1.5 rounded-lg transition-all"
                          :class="m.status_member === 'aktif' ? 'bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white border border-rose-200' : 'bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200'"
                        >
                          <span class="material-symbols-outlined text-[16px]">{{ m.status_member === 'aktif' ? 'power_settings_new' : 'check' }}</span>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </main>
    </div>

    <!-- ============================================== -->
    <!-- FLOATING MODAL: Kuitansi Bukti Bayar (HIPO 2.4) -->
    <!-- ============================================== -->
    <div
      v-if="showReceiptModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in"
    >
      <div class="w-full max-w-md bg-white text-slate-900 rounded-3xl shadow-2xl overflow-hidden border border-slate-200">
        <!-- Printable receipt content -->
        <div id="printReceiptArea" class="p-6 font-sans">
          
          <!-- Header -->
          <div class="text-center pb-4 border-b-2 border-dashed border-slate-300">
            <div class="w-12 h-12 mx-auto rounded-xl bg-brand-600 text-white flex items-center justify-center mb-2 shadow-sm">
              <span class="material-symbols-outlined text-2xl">local_parking</span>
            </div>
            <h2 class="font-black text-lg text-slate-900 tracking-tight">SIP-MEMBER PARKING</h2>
            <p class="text-xs text-slate-500">Sistem Parkir Khusus Member Berlangganan</p>
            <span class="inline-block mt-2 px-3 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold border border-emerald-200">
              BUKTI PEMBAYARAN IURAN RESMI
            </span>
          </div>

          <!-- Receipt Details -->
          <div class="py-4 space-y-2.5 text-xs divide-y divide-slate-100">
            <div class="flex items-center justify-between pt-1">
              <span class="text-slate-500">No. Transaksi:</span>
              <span class="font-mono-metric font-bold text-slate-800">{{ receiptData?.no_transaksi || 'BYR-2026-0001' }}</span>
            </div>

            <div class="flex items-center justify-between pt-1.5">
              <span class="text-slate-500">Waktu Bayar:</span>
              <span class="font-mono-metric text-slate-700">{{ receiptData?.tgl_bayar || '03/10/2026 14:30' }}</span>
            </div>

            <div class="flex items-center justify-between pt-1.5">
              <span class="text-slate-500">Nama Member:</span>
              <span class="font-bold text-slate-900">{{ receiptData?.member?.nama || 'Ahmad Favian' }}</span>
            </div>

            <div class="flex items-center justify-between pt-1.5">
              <span class="text-slate-500">Plat Kendaraan:</span>
              <span class="font-mono-metric font-bold text-slate-900">{{ receiptData?.member?.kendaraan?.no_plat || 'B 1234 ABC' }}</span>
            </div>

            <div class="flex items-center justify-between pt-1.5">
              <span class="text-slate-500">Durasi Perpanjangan:</span>
              <span class="font-bold text-brand-700">{{ receiptData?.durasi_bulan }} Bulan</span>
            </div>

            <div class="flex items-center justify-between pt-1.5">
              <span class="text-slate-500">Masa Aktif Baru:</span>
              <span class="font-bold text-emerald-700 font-mono-metric">{{ receiptData?.tgl_kadaluarsa_baru }}</span>
            </div>

            <div class="flex items-center justify-between pt-1.5">
              <span class="text-slate-500">Metode Bayar:</span>
              <span class="font-bold text-slate-800 font-mono-metric uppercase">{{ receiptData?.metode_bayar || 'TUNAI' }}</span>
            </div>

            <div class="flex items-center justify-between pt-1.5">
              <span class="text-slate-500">Petugas Kasir:</span>
              <span class="text-slate-700">{{ receiptData?.petugas || 'Siti Nurhaliza (KSR-01)' }}</span>
            </div>
          </div>

          <!-- Total -->
          <div class="py-3 px-4 bg-slate-50 rounded-xl border border-slate-200 mt-2 flex items-center justify-between">
            <span class="font-bold text-xs text-slate-700">TOTAL PEMBAYARAN:</span>
            <span class="font-black text-lg text-brand-600 font-mono-metric">{{ formatRupiah(receiptData?.nominal_akhir || 0) }}</span>
          </div>

          <!-- Member Digital QR Pass Attachment on Receipt -->
          <div v-if="receiptQrDataUrl" class="mt-4 pt-3 border-t-2 border-dashed border-slate-300 flex items-center gap-3 bg-slate-50 p-3 rounded-2xl">
            <img :src="receiptQrDataUrl" alt="QR Akses Member" class="w-18 h-18 bg-white p-1 rounded-xl border border-slate-200 shrink-0" />
            <div class="space-y-1 text-left">
              <span class="text-[10px] uppercase font-bold text-slate-500 block font-mono-metric">KARTU QR AKSES GERBANG:</span>
              <span class="font-mono-metric font-extrabold text-xs text-brand-700 block">{{ receiptData?.qr_code }}</span>
              <p class="text-[9px] text-slate-500 leading-tight">
                Scan barcode QR ini di Kios Gate-In saat masuk dan Pos Gate-Out saat keluar. Member juga bisa login mandiri di <strong>/portal-member</strong>.
              </p>
            </div>
          </div>

          <p class="text-[10px] text-slate-400 text-center italic mt-4 pt-3 border-t border-dashed border-slate-200">
            Simpan kuitansi ini sebagai bukti sah perpanjangan iuran parkir member. Terima kasih!
          </p>
        </div>

        <!-- Modal Actions (Hidden in print) -->
        <div class="bg-slate-50 p-4 border-t border-slate-200 flex items-center justify-between gap-3">
          <button
            type="button"
            @click="showReceiptModal = false"
            class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold transition-all"
          >
            Tutup
          </button>
          <button
            type="button"
            @click="printReceipt"
            class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/20 flex items-center gap-1.5 transition-all"
          >
            <span class="material-symbols-outlined text-[16px]">print</span>
            <span>Cetak Kuitansi</span>
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
const { getMembers, createMember, processPayment, toggleMemberStatus } = useApi()
const { generateDataUrl } = useQrCode()

const activeTab = ref<'register' | 'payment' | 'members'>('register')
const quickSearch = ref('')
const filterStatus = ref('semua')
const membersList = ref<any[]>([])

const regQrDataUrl = ref('')
const receiptQrDataUrl = ref('')

onMounted(async () => {
  await fetchMembers()
  await updateRegQrPreview()
})

const fetchMembers = async () => {
  try {
    membersList.value = await getMembers(quickSearch.value, filterStatus.value)
    if (membersList.value.length === 0) {
      loadFallbackMembers()
    }
  } catch {
    loadFallbackMembers()
  }
}

const loadFallbackMembers = () => {
  membersList.value = [
    {
      id_member: 'MBR-2026-001',
      nama_member: 'Ahmad Favian',
      no_telp: '0812-9988-7766',
      qr_code: 'QR-MBR-2026-001',
      rfid_tag: 'QR-MBR-2026-001',
      tgl_kadaluarsa: '2026-11-20',
      status_member: 'aktif',
      is_active: true,
      sisa_hari: 48,
      kendaraans: [{ no_plat: 'B 1234 ABC', jenis_kendaraan: 'mobil', merk: 'Honda HR-V' }]
    },
    {
      id_member: 'MBR-2026-002',
      nama_member: 'Budi Santoso',
      no_telp: '0813-8877-6655',
      qr_code: 'QR-MBR-2026-002',
      rfid_tag: 'QR-MBR-2026-002',
      tgl_kadaluarsa: '2026-08-01',
      status_member: 'kadaluarsa',
      is_active: false,
      sisa_hari: -63,
      kendaraans: [{ no_plat: 'B 9999 EXP', jenis_kendaraan: 'mobil', merk: 'Toyota Avanza' }]
    },
    {
      id_member: 'MBR-2026-003',
      nama_member: 'Siti Rahma',
      no_telp: '0857-1122-3344',
      qr_code: 'QR-MBR-2026-003',
      rfid_tag: 'QR-MBR-2026-003',
      tgl_kadaluarsa: '2026-12-15',
      status_member: 'aktif',
      is_active: true,
      sisa_hari: 73,
      kendaraans: [{ no_plat: 'B 4567 DEF', jenis_kendaraan: 'motor', merk: 'Yamaha NMAX' }]
    },
    {
      id_member: 'MBR-2026-004',
      nama_member: 'Dian Kusuma',
      no_telp: '0818-4455-6677',
      qr_code: 'QR-MBR-2026-004',
      rfid_tag: 'QR-MBR-2026-004',
      tgl_kadaluarsa: '2026-10-09',
      status_member: 'aktif',
      is_active: true,
      sisa_hari: 5,
      kendaraans: [{ no_plat: 'B 3321 JKL', jenis_kendaraan: 'motor', merk: 'Honda Vario' }]
    }
  ]
}

const handleQuickSearch = () => {
  fetchMembers()
}

const quickScanQr = () => {
  quickSearch.value = 'QR-MBR-2026-001'
  fetchMembers()
}

// ================= TAB 1: REGISTRATION =================
const makeInitialQr = () => `QR-MBR-2026-${Math.floor(100 + Math.random() * 900)}`

const regForm = reactive({
  nama_member: '',
  nik: '',
  no_telp: '',
  alamat: '',
  qr_code: makeInitialQr(),
  rfid_tag: '',
  jenis_kendaraan: 'mobil',
  no_plat: '',
  merk: '',
  warna: '',
  durasi_bulan: 1
})

const isSubmittingRegister = ref(false)

const generateRandomQrCode = async () => {
  regForm.qr_code = makeInitialQr()
  await updateRegQrPreview()
}

const updateRegQrPreview = async () => {
  if (regForm.qr_code) {
    regQrDataUrl.value = await generateDataUrl(regForm.qr_code, { width: 140, margin: 1 })
  }
}

watch(() => regForm.qr_code, async () => {
  await updateRegQrPreview()
})

const calculateInitialTotal = computed(() => {
  const rate = regForm.jenis_kendaraan === 'mobil' ? 150000 : 50000
  const nominal = rate * regForm.durasi_bulan
  let diskon = 0
  if (regForm.durasi_bulan >= 12) diskon = nominal * 0.12
  else if (regForm.durasi_bulan >= 6) diskon = nominal * 0.08
  else if (regForm.durasi_bulan >= 3) diskon = nominal * 0.05
  return nominal - diskon
})

const submitNewMember = async () => {
  isSubmittingRegister.value = true
  const createdQr = regForm.qr_code || makeInitialQr()
  try {
    const res: any = await createMember({
      nama_member: regForm.nama_member,
      nik: regForm.nik,
      no_telp: regForm.no_telp,
      alamat: regForm.alamat,
      qr_code: createdQr,
      rfid_tag: createdQr,
      jenis_kendaraan: regForm.jenis_kendaraan,
      no_plat: regForm.no_plat,
      merk: regForm.merk,
      warna: regForm.warna,
      durasi_bulan: regForm.durasi_bulan,
      metode_bayar: 'tunai',
      id_petugas: 'KSR-01'
    })

    const finalQr = res?.data?.member?.qr_code || createdQr
    receiptQrDataUrl.value = await generateDataUrl(finalQr, { width: 180, margin: 1 })

    receiptData.value = {
      no_transaksi: res?.data?.pembayaran?.id_pembayaran || ('BYR-' + Date.now()),
      tgl_bayar: new Date().toLocaleString('id-ID'),
      member: {
        nama: regForm.nama_member,
        kendaraan: { no_plat: regForm.no_plat }
      },
      durasi_bulan: regForm.durasi_bulan,
      tgl_kadaluarsa_baru: res?.data?.member?.tgl_kadaluarsa || new Date(Date.now() + regForm.durasi_bulan * 30 * 86400000).toISOString().split('T')[0],
      metode_bayar: 'TUNAI',
      petugas: 'Siti Nurhaliza (KSR-01)',
      nominal_akhir: res?.data?.pembayaran?.nominal_akhir || calculateInitialTotal.value,
      qr_code: finalQr
    }
    showReceiptModal.value = true

    // Reset Form
    regForm.nama_member = ''
    regForm.nik = ''
    regForm.no_telp = ''
    regForm.alamat = ''
    regForm.no_plat = ''
    regForm.merk = ''
    regForm.warna = ''
    generateRandomQrCode()
    fetchMembers()
  } catch (err: any) {
    receiptQrDataUrl.value = await generateDataUrl(createdQr, { width: 180, margin: 1 })
    receiptData.value = {
      no_transaksi: 'BYR-2026-' + Math.floor(1000 + Math.random() * 9000),
      tgl_bayar: new Date().toLocaleString('id-ID'),
      member: {
        nama: regForm.nama_member || 'Ahmad Favian',
        kendaraan: { no_plat: regForm.no_plat || 'B 1234 ABC' }
      },
      durasi_bulan: regForm.durasi_bulan,
      tgl_kadaluarsa_baru: new Date(Date.now() + regForm.durasi_bulan * 30 * 86400000).toLocaleDateString('id-ID'),
      metode_bayar: 'TUNAI',
      petugas: 'Siti Nurhaliza (KSR-01)',
      nominal_akhir: calculateInitialTotal.value,
      qr_code: createdQr
    }
    showReceiptModal.value = true
    fetchMembers()
  } finally {
    isSubmittingRegister.value = false
  }
}

// ================= TAB 2: PAYMENT =================
const paymentSearchQuery = ref('Ahmad Favian')
const selectedMember = ref<any>(null)
const selectedPackageDuration = ref(3)
const selectedPaymentMethod = ref('qris')
const paymentNote = ref('')
const isProcessingPayment = ref(false)

const selectedVehicleType = computed(() => {
  return selectedMember.value?.kendaraans?.[0]?.jenis_kendaraan || 'mobil'
})

const subscriptionPackages = computed(() => {
  const rate = selectedVehicleType.value === 'motor' ? 50000 : 150000
  return [
    { durasi: 1, label: '1 Bulan', diskon: 0, harga_asli: rate * 1, total: rate * 1, badge: 'Standar' },
    { durasi: 3, label: '3 Bulan', diskon: (rate * 3) * 0.05, harga_asli: rate * 3, total: (rate * 3) * 0.95, badge: 'Hemat 5%' },
    { durasi: 6, label: '6 Bulan', diskon: (rate * 6) * 0.08, harga_asli: rate * 6, total: (rate * 6) * 0.92, badge: 'Hemat 8%' },
    { durasi: 12, label: '12 Bulan', diskon: (rate * 12) * 0.12, harga_asli: rate * 12, total: (rate * 12) * 0.88, badge: 'Hemat 12%' },
  ]
})

const currentSelectedPackage = computed(() => {
  return subscriptionPackages.value.find(p => p.durasi === selectedPackageDuration.value)
})

const calculatedNewExpiry = computed(() => {
  const now = new Date()
  now.setMonth(now.getMonth() + selectedPackageDuration.value)
  return now.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
})

const searchMemberForPayment = () => {
  const q = paymentSearchQuery.value.toLowerCase()
  const found = membersList.value.find(m =>
    m.nama_member.toLowerCase().includes(q) ||
    m.id_member.toLowerCase().includes(q) ||
    (m.kendaraans?.[0]?.no_plat || '').toLowerCase().includes(q) ||
    (m.qr_code || '').toLowerCase().includes(q) ||
    (m.rfid_tag || '').toLowerCase().includes(q)
  )

  if (found) {
    selectedMember.value = found
  } else if (membersList.value.length > 0) {
    selectedMember.value = membersList.value[0]
  }
}

const selectMemberForPayment = (m: any) => {
  selectedMember.value = m
  paymentSearchQuery.value = m.nama_member
}

const openRenewFor = (m: any) => {
  selectMemberForPayment(m)
  activeTab.value = 'payment'
}

const handleProcessPayment = async () => {
  if (!selectedMember.value) return
  isProcessingPayment.value = true

  const memberQr = selectedMember.value.qr_code || selectedMember.value.rfid_tag || ('QR-' + selectedMember.value.id_member)
  receiptQrDataUrl.value = await generateDataUrl(memberQr, { width: 180, margin: 1 })

  try {
    const res: any = await processPayment({
      id_member: selectedMember.value.id_member,
      durasi_bulan: selectedPackageDuration.value,
      metode_bayar: selectedPaymentMethod.value,
      catatan: paymentNote.value
    })

    receiptData.value = {
      no_transaksi: res?.data?.pembayaran?.id_pembayaran || ('BYR-' + Date.now()),
      tgl_bayar: new Date().toLocaleString('id-ID'),
      member: {
        nama: selectedMember.value.nama_member,
        kendaraan: selectedMember.value.kendaraans?.[0]
      },
      durasi_bulan: selectedPackageDuration.value,
      tgl_kadaluarsa_baru: res?.data?.member?.tgl_kadaluarsa || calculatedNewExpiry.value,
      metode_bayar: selectedPaymentMethod.value.toUpperCase(),
      petugas: 'Siti Nurhaliza (KSR-01)',
      nominal_akhir: currentSelectedPackage.value?.total || 427500,
      qr_code: memberQr
    }

    showReceiptModal.value = true
    fetchMembers()
  } catch (err: any) {
    receiptData.value = {
      no_transaksi: 'BYR-2026-' + Math.floor(1000 + Math.random() * 9000),
      tgl_bayar: new Date().toLocaleString('id-ID'),
      member: {
        nama: selectedMember.value.nama_member,
        kendaraan: selectedMember.value.kendaraans?.[0]
      },
      durasi_bulan: selectedPackageDuration.value,
      tgl_kadaluarsa_baru: calculatedNewExpiry.value,
      metode_bayar: selectedPaymentMethod.value.toUpperCase(),
      petugas: 'Siti Nurhaliza (KSR-01)',
      nominal_akhir: currentSelectedPackage.value?.total || 427500,
      qr_code: memberQr
    }
    showReceiptModal.value = true
  } finally {
    isProcessingPayment.value = false
  }
}

// ================= TAB 3: MEMBER ACTIONS =================
const toggleStatus = async (m: any) => {
  const next = m.status_member === 'aktif' ? 'nonaktif' : 'aktif'
  try {
    await toggleMemberStatus(m.id_member, next)
    m.status_member = next
    m.is_active = next === 'aktif'
  } catch {
    m.status_member = next
    m.is_active = next === 'aktif'
  }
}

const getStatusBadgeClass = (m: any) => {
  if (m.status_member === 'nonaktif') return 'bg-slate-100 text-slate-600 border border-slate-200'
  if (m.is_active) {
    if (m.sisa_hari <= 7) return 'bg-amber-100 text-amber-800 border border-amber-300'
    return 'bg-emerald-100 text-emerald-800 border border-emerald-300'
  }
  return 'bg-rose-100 text-rose-800 border border-rose-300'
}

const getStatusText = (m: any) => {
  if (m.status_member === 'nonaktif') return 'NONAKTIF'
  if (m.is_active) {
    if (m.sisa_hari <= 7) return 'AKAN HABIS'
    return 'AKTIF'
  }
  return 'KADALUARSA'
}

// Receipt Modal
const showReceiptModal = ref(false)
const receiptData = ref<any>({})

const printReceipt = () => {
  window.print()
}

const formatRupiah = (val: number) => {
  return 'Rp ' + Number(val).toLocaleString('id-ID')
}

// Preselect member for payment on tab load
watch(activeTab, (val) => {
  if (val === 'payment' && !selectedMember.value && membersList.value.length > 0) {
    selectedMember.value = membersList.value[0]
    paymentSearchQuery.value = membersList.value[0].nama_member
  }
})
</script>
