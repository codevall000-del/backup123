<template>
  <div class="min-h-screen bg-[#f5f5f7] text-[#1d1d1f] flex flex-col font-sans selection:bg-[#0071e3] selection:text-white">
    <TopNav />

    <!-- Sub-Header Breadcrumb & Actions -->
    <header class="w-full bg-white/85 backdrop-blur-xl border-b border-black/[0.06] sticky top-16 z-30 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div class="flex items-center gap-1.5 text-xs text-[#86868b] mb-0.5">
            <NuxtLink to="/dashboard" class="hover:text-[#0071e3]">Dashboard</NuxtLink>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-purple-700 font-bold">Kelola Akun Sistem</span>
            <span class="text-[10px] font-bold px-2 py-0.2 rounded-full bg-purple-500/10 text-purple-700 border border-purple-500/20 font-mono-metric">ADMIN ONLY</span>
          </div>
          <h1 class="text-xl sm:text-2xl font-black text-[#1d1d1f] tracking-tight flex items-center gap-2.5">
            <span class="material-symbols-outlined text-purple-600 text-2xl">manage_accounts</span>
            <span>Kelola Akun Kasir, Admin & Petugas</span>
          </h1>
          <p class="text-xs text-[#6e6e73]">
            Manajemen terpusat hak akses login petugas loket kasir, petugas pos gerbang operasional/keamanan, dan akun administrator SIP-Member.
          </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-2">
          <!-- Button Ke Pengaturan Member -->
          <NuxtLink
            to="/member"
            class="apple-btn px-3.5 py-2 rounded-xl bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-900 text-xs font-bold flex items-center gap-1.5 shadow-xs transition-all"
            title="Buka master data member"
          >
            <span class="material-symbols-outlined text-[18px] text-purple-600">badge</span>
            <span>Pengaturan Member</span>
          </NuxtLink>

          <!-- Button Refresh Data -->
          <button
            type="button"
            @click="loadData"
            :disabled="isLoading"
            class="apple-btn px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 border border-black/[0.08] text-slate-700 text-xs font-bold flex items-center gap-1.5 shadow-xs transition-all"
            title="Muat ulang data akun"
          >
            <span class="material-symbols-outlined text-[18px] text-slate-500" :class="{ 'animate-spin': isLoading }">sync</span>
            <span>Segarkan</span>
          </button>

          <!-- Button Tambah Akun Baru -->
          <button
            type="button"
            @click="openAddModal"
            class="apple-btn px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 active:scale-95 text-white text-xs font-bold flex items-center gap-1.5 shadow-[0_4px_12px_rgba(147,51,234,0.3)] transition-all"
          >
            <span class="material-symbols-outlined text-[18px]">person_add</span>
            <span>+ Tambah Akun Baru</span>
          </button>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="w-full flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

      <!-- Toast Notification -->
      <transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="-translate-y-2 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="toastMessage"
          class="p-4 rounded-2xl flex items-center justify-between gap-3 shadow-md"
          :class="toastType === 'error' ? 'bg-rose-50 border border-rose-200 text-rose-950' : 'bg-emerald-50 border border-emerald-200 text-emerald-950'"
        >
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-xl" :class="toastType === 'error' ? 'text-rose-600' : 'text-emerald-600'">
              {{ toastType === 'error' ? 'error' : 'check_circle' }}
            </span>
            <span class="text-xs sm:text-sm font-semibold">{{ toastMessage }}</span>
          </div>
          <button @click="toastMessage = ''" class="apple-btn text-slate-400 hover:text-slate-700">
            <span class="material-symbols-outlined text-[18px]">close</span>
          </button>
        </div>
      </transition>

      <!-- Administrator Active Banner -->
      <div class="apple-glass-card rounded-2xl p-4 border border-purple-200/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-gradient-to-r from-purple-50/40 via-white to-indigo-50/30">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-purple-600/10 text-purple-700 flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-[22px]">admin_panel_settings</span>
          </div>
          <div>
            <div class="flex items-center gap-2">
              <span class="text-xs font-bold text-[#1d1d1f]">Otoritas Kelola Akun Administrator (Level ADM-01)</span>
              <span class="text-[10px] font-mono-metric font-bold px-2 py-0.2 rounded-full bg-purple-100 text-purple-800">KONTROL PENGGUNA RESMI</span>
            </div>
            <p class="text-xs text-[#6e6e73] mt-0.5">
              Anda sedang mengelola akun pengguna sistem atas nama:
              <strong class="text-purple-900 font-semibold">{{ currentUser?.nama_petugas || 'Administrator' }}</strong>.
              Akun yang didaftarkan dapat langsung digunakan untuk login di Loket Kasir, Gerbang Keluar, Gerbang Masuk, atau Pusat Kontrol.
            </p>
          </div>
        </div>
        <div class="flex items-center gap-2 text-[11px] font-mono-metric text-purple-800 bg-purple-100/60 px-3 py-1.5 rounded-xl border border-purple-200 shrink-0">
          <span class="w-2 h-2 rounded-full bg-purple-600 animate-pulse"></span>
          <span>ADMINISTRATOR SESSION</span>
        </div>
      </div>

      <!-- 4 Stat Summary Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Semua Akun -->
        <div class="apple-glass-card rounded-2xl p-4 sm:p-5 bg-white border border-black/[0.06] shadow-xs">
          <div class="flex items-center justify-between text-[#86868b] mb-2">
            <span class="text-xs font-bold uppercase tracking-wider">Total Semua Akun</span>
            <span class="material-symbols-outlined text-[20px] text-[#0071e3]">groups</span>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="text-2xl sm:text-3xl font-extrabold text-[#1d1d1f] font-mono-metric">{{ metaCounts.total_semua }}</span>
            <span class="text-xs text-[#86868b]">pengguna</span>
          </div>
          <span class="text-[11px] text-[#86868b] mt-1 block">Tersimpan di basis data</span>
        </div>

        <!-- 2. Akun Administrator -->
        <div class="apple-glass-card rounded-2xl p-4 sm:p-5 bg-white border border-purple-500/20 shadow-xs">
          <div class="flex items-center justify-between text-purple-700 mb-2">
            <span class="text-xs font-bold uppercase tracking-wider">Akun Admin</span>
            <span class="material-symbols-outlined text-[20px] text-purple-600">shield_person</span>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="text-2xl sm:text-3xl font-extrabold text-purple-700 font-mono-metric">{{ metaCounts.total_admin }}</span>
            <span class="text-xs text-purple-600 font-semibold font-mono-metric">Akun</span>
          </div>
          <span class="text-[11px] text-purple-600/80 mt-1 block">Pengawas & Pengaturan Penuh</span>
        </div>

        <!-- 3. Akun Kasir -->
        <div class="apple-glass-card rounded-2xl p-4 sm:p-5 bg-white border border-emerald-500/20 shadow-xs">
          <div class="flex items-center justify-between text-emerald-700 mb-2">
            <span class="text-xs font-bold uppercase tracking-wider">Akun Kasir</span>
            <span class="material-symbols-outlined text-[20px] text-emerald-600">point_of_sale</span>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="text-2xl sm:text-3xl font-extrabold text-emerald-700 font-mono-metric">{{ metaCounts.total_kasir }}</span>
            <span class="text-xs text-emerald-600 font-semibold font-mono-metric">Akun</span>
          </div>
          <span class="text-[11px] text-emerald-600/80 mt-1 block">Loket Iuran & Cetak Bukti</span>
        </div>

        <!-- 4. Akun Petugas Lapangan/Pos -->
        <div class="apple-glass-card rounded-2xl p-4 sm:p-5 bg-white border border-blue-500/20 shadow-xs">
          <div class="flex items-center justify-between text-blue-700 mb-2">
            <span class="text-xs font-bold uppercase tracking-wider">Akun Petugas</span>
            <span class="material-symbols-outlined text-[20px] text-blue-600">badge</span>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="text-2xl sm:text-3xl font-extrabold text-blue-700 font-mono-metric">{{ metaCounts.total_petugas }}</span>
            <span class="text-xs text-blue-600 font-semibold font-mono-metric">Akun</span>
          </div>
          <span class="text-[11px] text-blue-600/80 mt-1 block">Gerbang Keluar & Satpam</span>
        </div>
      </div>

      <!-- Filter Tabs & Search Bar Strip -->
      <div class="apple-glass-card rounded-2xl p-4 border border-black/[0.06] bg-white flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        
        <!-- Role Tabs -->
        <div class="flex flex-wrap items-center gap-1.5 p-1 rounded-xl bg-slate-100 border border-slate-200/80 text-xs">
          <button
            type="button"
            @click="activeRole = 'semua'"
            class="px-3 py-1.5 rounded-lg font-bold transition-all flex items-center gap-1.5"
            :class="activeRole === 'semua' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
          >
            <span>Semua Akun</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono-metric" :class="activeRole === 'semua' ? 'bg-slate-100 text-slate-800' : 'bg-slate-200/60 text-slate-600'">
              {{ metaCounts.total_semua }}
            </span>
          </button>

          <!-- Tab Admin -->
          <button
            type="button"
            @click="activeRole = 'admin'"
            class="px-3 py-1.5 rounded-lg font-bold transition-all flex items-center gap-1.5"
            :class="activeRole === 'admin' ? 'bg-purple-600 text-white shadow-xs' : 'text-slate-600 hover:text-purple-700'"
          >
            <span class="w-1.5 h-1.5 rounded-full" :class="activeRole === 'admin' ? 'bg-white' : 'bg-purple-500'"></span>
            <span>Akun Admin</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono-metric" :class="activeRole === 'admin' ? 'bg-purple-700 text-white' : 'bg-purple-100 text-purple-700'">
              {{ metaCounts.total_admin }}
            </span>
          </button>

          <!-- Tab Kasir -->
          <button
            type="button"
            @click="activeRole = 'kasir'"
            class="px-3 py-1.5 rounded-lg font-bold transition-all flex items-center gap-1.5"
            :class="activeRole === 'kasir' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-emerald-700'"
          >
            <span class="w-1.5 h-1.5 rounded-full" :class="activeRole === 'kasir' ? 'bg-white' : 'bg-emerald-500'"></span>
            <span>Akun Kasir</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono-metric" :class="activeRole === 'kasir' ? 'bg-emerald-700 text-white' : 'bg-emerald-100 text-emerald-700'">
              {{ metaCounts.total_kasir }}
            </span>
          </button>

          <!-- Tab Petugas -->
          <button
            type="button"
            @click="activeRole = 'petugas'"
            class="px-3 py-1.5 rounded-lg font-bold transition-all flex items-center gap-1.5"
            :class="activeRole === 'petugas' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-blue-700'"
          >
            <span class="w-1.5 h-1.5 rounded-full" :class="activeRole === 'petugas' ? 'bg-white' : 'bg-blue-500'"></span>
            <span>Akun Petugas (Pos & Satpam)</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono-metric" :class="activeRole === 'petugas' ? 'bg-blue-700 text-white' : 'bg-blue-100 text-blue-700'">
              {{ metaCounts.total_petugas }}
            </span>
          </button>
        </div>

        <!-- Search Input -->
        <div class="relative flex-1 max-w-md">
          <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[18px]">search</span>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari nama petugas, username, ID akun, pos tugas..."
            class="w-full pl-9 pr-8 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
          />
          <button
            v-if="searchQuery"
            @click="searchQuery = ''"
            class="absolute right-2.5 top-2.5 text-slate-400 hover:text-slate-600"
          >
            <span class="material-symbols-outlined text-[16px]">cancel</span>
          </button>
        </div>
      </div>

      <!-- Accounts Table Card -->
      <div class="apple-glass-card rounded-2xl bg-white border border-black/[0.06] shadow-xs overflow-hidden">
        
        <!-- Table Header Info Bar -->
        <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between text-xs text-slate-500">
          <div class="flex items-center gap-2">
            <span class="font-bold text-slate-700">Daftar Akun Pengguna Terverifikasi</span>
            <span class="text-slate-400">•</span>
            <span class="font-mono-metric">Menampilkan {{ filteredAccounts.length }} akun</span>
          </div>
          <span class="text-[11px] text-slate-400 hidden sm:inline">Kredensial tersinkronisasi dengan Database Petugas</span>
        </div>

        <!-- Empty State -->
        <div v-if="filteredAccounts.length === 0" class="py-16 text-center">
          <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
            <span class="material-symbols-outlined text-3xl">person_off</span>
          </div>
          <h3 class="text-sm font-bold text-slate-700">Tidak ada akun yang sesuai kriteria</h3>
          <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
            Coba ganti kata kunci pencarian atau ubah tab filter kategori akun di atas.
          </p>
          <button
            type="button"
            @click="resetFilter"
            class="apple-btn mt-4 px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold inline-flex items-center gap-1"
          >
            <span>Reset Pencarian</span>
          </button>
        </div>

        <!-- Table View -->
        <div v-else class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50/70 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                <th class="py-3 px-4">Petugas / ID Akun</th>
                <th class="py-3 px-4">Kredensial Login</th>
                <th class="py-3 px-4">Peran (Role)</th>
                <th class="py-3 px-4">Pos Penugasan</th>
                <th class="py-3 px-4">PIN Otorisasi</th>
                <th class="py-3 px-4 text-center">Aksi Manajemen</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
              <tr
                v-for="acc in filteredAccounts"
                :key="acc.id_petugas"
                class="hover:bg-slate-50/60 transition-colors"
              >
                <!-- 1. Petugas & ID -->
                <td class="py-3.5 px-4">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-white text-xs shadow-xs"
                      :class="getRoleColor(acc.peran).avatarBg"
                    >
                      {{ acc.nama_petugas ? acc.nama_petugas.charAt(0).toUpperCase() : 'U' }}
                    </div>
                    <div>
                      <div class="font-bold text-slate-900 flex items-center gap-1.5">
                        <span>{{ acc.nama_petugas }}</span>
                        <span
                          v-if="acc.id_petugas === 'ADM-01'"
                          class="text-[9px] font-mono-metric font-bold px-1.5 py-0.2 rounded-full bg-purple-100 text-purple-800 border border-purple-200"
                        >
                          UTAMA
                        </span>
                      </div>
                      <div class="flex items-center gap-1 text-[11px] font-mono-metric text-slate-400 mt-0.5">
                        <span class="material-symbols-outlined text-[13px]">tag</span>
                        <span>{{ acc.id_petugas }}</span>
                      </div>
                    </div>
                  </div>
                </td>

                <!-- 2. Kredensial Login -->
                <td class="py-3.5 px-4">
                  <div class="space-y-1">
                    <div class="flex items-center gap-1 text-slate-700">
                      <span class="text-slate-400 text-[11px]">User:</span>
                      <code class="px-2 py-0.5 rounded-md bg-slate-100 font-mono-metric text-[11px] font-semibold text-slate-800 border border-slate-200/60">
                        {{ acc.username }}
                      </code>
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] text-slate-400">
                      <span>Pass:</span>
                      <span class="font-mono-metric tracking-widest text-slate-500">••••••••</span>
                      <button
                        type="button"
                        @click="openResetPassModal(acc)"
                        class="text-[10px] text-purple-600 hover:text-purple-800 font-semibold underline"
                        title="Reset kata sandi akun ini"
                      >
                        Reset
                      </button>
                    </div>
                  </div>
                </td>

                <!-- 3. Peran (Role) -->
                <td class="py-3.5 px-4">
                  <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border" :class="getRoleColor(acc.peran).badge">
                    <span class="material-symbols-outlined text-[14px]">{{ getRoleColor(acc.peran).icon }}</span>
                    <span>{{ getRoleLabel(acc.peran) }}</span>
                  </div>
                  <div class="text-[10px] text-slate-400 mt-1">
                    {{ getRoleDesc(acc.peran) }}
                  </div>
                </td>

                <!-- 4. Pos Penugasan -->
                <td class="py-3.5 px-4">
                  <div class="flex items-center gap-1.5 text-slate-700">
                    <span class="material-symbols-outlined text-[16px] text-slate-400">location_on</span>
                    <span class="font-medium">{{ acc.pos_aktif || '-' }}</span>
                  </div>
                </td>

                <!-- 5. PIN Otorisasi -->
                <td class="py-3.5 px-4">
                  <div class="flex items-center gap-2">
                    <code class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-900 border border-amber-200 font-mono-metric text-xs font-bold">
                      {{ revealedPins[acc.id_petugas] ? (acc.pin_petugas || '123456') : '••••••' }}
                    </code>
                    <button
                      type="button"
                      @click="toggleRevealPin(acc.id_petugas)"
                      class="text-slate-400 hover:text-slate-700 apple-btn p-1"
                      :title="revealedPins[acc.id_petugas] ? 'Sembunyikan PIN' : 'Tampilkan PIN'"
                    >
                      <span class="material-symbols-outlined text-[16px]">
                        {{ revealedPins[acc.id_petugas] ? 'visibility_off' : 'visibility' }}
                      </span>
                    </button>
                  </div>
                  <span class="text-[10px] text-slate-400 mt-0.5 block">Bypass gerbang / otorisasi</span>
                </td>

                <!-- 6. Aksi -->
                <td class="py-3.5 px-4 text-center">
                  <div class="flex items-center justify-center gap-1.5">
                    <!-- Edit Button -->
                    <button
                      type="button"
                      @click="openEditModal(acc)"
                      class="apple-btn px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold flex items-center gap-1 shadow-2xs"
                      title="Ubah data akun"
                    >
                      <span class="material-symbols-outlined text-[15px] text-slate-600">edit</span>
                      <span>Edit</span>
                    </button>

                    <!-- Reset Pass Button -->
                    <button
                      type="button"
                      @click="openResetPassModal(acc)"
                      class="apple-btn p-1.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs shadow-2xs"
                      title="Ganti Password"
                    >
                      <span class="material-symbols-outlined text-[16px]">lock_reset</span>
                    </button>

                    <!-- Delete Button -->
                    <button
                      type="button"
                      @click="openDeleteModal(acc)"
                      :disabled="acc.id_petugas === 'ADM-01' || acc.username === 'admin'"
                      class="apple-btn p-1.5 rounded-xl text-xs shadow-2xs"
                      :class="(acc.id_petugas === 'ADM-01' || acc.username === 'admin') ? 'opacity-30 cursor-not-allowed bg-slate-100 text-slate-400' : 'bg-rose-50 hover:bg-rose-100 text-rose-600'"
                      :title="(acc.id_petugas === 'ADM-01' || acc.username === 'admin') ? 'Akun Administrator Utama dilindungi' : 'Hapus akun'"
                    >
                      <span class="material-symbols-outlined text-[16px]">delete</span>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Footer Table Note -->
        <div class="p-4 bg-slate-50 border-t border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-amber-600 text-[18px]">verified_user</span>
            <span>Kredensial demo bawaan: Kata sandi standar sama dengan nama pengguna ditambah <strong>123</strong> (contoh: <code>admin123</code>, <code>kasir123</code>, <code>petugas123</code>).</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-[11px] font-mono-metric font-semibold text-slate-400">SIP-Member Security Module v2.4</span>
          </div>
        </div>
      </div>
    </main>

    <!-- ================= MODAL TAMBAH AKUN BARU ================= -->
    <div
      v-if="showAddModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
    >
      <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-black/[0.08] space-y-5 animate-scale-up">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
          <div class="flex items-center gap-2.5">
            <div class="w-10 h-10 rounded-2xl bg-purple-600/10 text-purple-700 flex items-center justify-center">
              <span class="material-symbols-outlined text-[22px]">person_add</span>
            </div>
            <div>
              <h3 class="text-base font-extrabold text-slate-900">Tambah Akun Pengguna Baru</h3>
              <p class="text-xs text-slate-500">Daftarkan akun kasir, admin, atau petugas lapangan</p>
            </div>
          </div>
          <button @click="showAddModal = false" class="apple-btn p-1 text-slate-400 hover:text-slate-700 rounded-xl">
            <span class="material-symbols-outlined text-xl">close</span>
          </button>
        </div>

        <!-- Form Body -->
        <form @submit.prevent="submitAddAccount" class="space-y-4 text-xs">
          
          <!-- Pilihan Peran / Jenis Akun -->
          <div>
            <label class="block font-bold text-slate-700 mb-2">Pilih Jenis Akun (Peran / Role) *</label>
            <div class="grid grid-cols-2 gap-2">
              
              <!-- 1. Admin -->
              <button
                type="button"
                @click="selectAddRole('admin')"
                class="p-2.5 rounded-xl border text-left flex items-start gap-2 transition-all"
                :class="addForm.peran === 'admin' ? 'border-purple-500 bg-purple-50/70 ring-2 ring-purple-500/20 text-purple-950' : 'border-slate-200 bg-white hover:bg-slate-50 text-slate-700'"
              >
                <span class="material-symbols-outlined text-[20px] text-purple-600 shrink-0">admin_panel_settings</span>
                <div>
                  <strong class="block text-xs font-bold">Admin</strong>
                  <span class="text-[10px] text-slate-500 leading-tight">Pengawas & Master Member</span>
                </div>
              </button>

              <!-- 2. Kasir -->
              <button
                type="button"
                @click="selectAddRole('kasir')"
                class="p-2.5 rounded-xl border text-left flex items-start gap-2 transition-all"
                :class="addForm.peran === 'kasir' ? 'border-emerald-500 bg-emerald-50/70 ring-2 ring-emerald-500/20 text-emerald-950' : 'border-slate-200 bg-white hover:bg-slate-50 text-slate-700'"
              >
                <span class="material-symbols-outlined text-[20px] text-emerald-600 shrink-0">point_of_sale</span>
                <div>
                  <strong class="block text-xs font-bold">Kasir</strong>
                  <span class="text-[10px] text-slate-500 leading-tight">Loket Iuran & Pembayaran</span>
                </div>
              </button>

              <!-- 3. Petugas Pos / Operator -->
              <button
                type="button"
                @click="selectAddRole('operator')"
                class="p-2.5 rounded-xl border text-left flex items-start gap-2 transition-all"
                :class="addForm.peran === 'operator' ? 'border-blue-500 bg-blue-50/70 ring-2 ring-blue-500/20 text-blue-950' : 'border-slate-200 bg-white hover:bg-slate-50 text-slate-700'"
              >
                <span class="material-symbols-outlined text-[20px] text-blue-600 shrink-0">directions_car</span>
                <div>
                  <strong class="block text-xs font-bold">Petugas Pos Keluar</strong>
                  <span class="text-[10px] text-slate-500 leading-tight">Operator Gerbang Keluar</span>
                </div>
              </button>

              <!-- 4. Petugas Keamanan / Satpam -->
              <button
                type="button"
                @click="selectAddRole('satpam')"
                class="p-2.5 rounded-xl border text-left flex items-start gap-2 transition-all"
                :class="addForm.peran === 'satpam' ? 'border-amber-500 bg-amber-50/70 ring-2 ring-amber-500/20 text-amber-950' : 'border-slate-200 bg-white hover:bg-slate-50 text-slate-700'"
              >
                <span class="material-symbols-outlined text-[20px] text-amber-600 shrink-0">local_police</span>
                <div>
                  <strong class="block text-xs font-bold">Petugas Masuk / Satpam</strong>
                  <span class="text-[10px] text-slate-500 leading-tight">Pengawas Gerbang Masuk</span>
                </div>
              </button>
            </div>
          </div>

          <!-- ID Petugas & Nama Lengkap -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">ID Akun *</label>
              <input
                v-model="addForm.id_petugas"
                type="text"
                required
                placeholder="ADM-02"
                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 font-mono-metric font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 uppercase"
              />
            </div>
            <div class="sm:col-span-2">
              <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap Petugas *</label>
              <input
                v-model="addForm.nama_petugas"
                type="text"
                required
                placeholder="Contoh: Rian Pratama"
                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
              />
            </div>
          </div>

          <!-- Username & Password -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Username Login *</label>
              <input
                v-model="addForm.username"
                type="text"
                required
                placeholder="rian"
                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 font-mono-metric text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 lowercase"
              />
              <span class="text-[10px] text-slate-400 mt-0.5 block">Huruf kecil tanpa spasi</span>
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Kata Sandi (Password) *</label>
              <input
                v-model="addForm.password"
                type="password"
                required
                minlength="4"
                placeholder="Minimal 4 karakter"
                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 font-mono-metric text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
              />
            </div>
          </div>

          <!-- PIN Petugas & Pos Penugasan -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">PIN Otorisasi (6 Angka) *</label>
              <input
                v-model="addForm.pin_petugas"
                type="text"
                maxlength="6"
                placeholder="123456"
                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 font-mono-metric font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-center"
              />
              <span class="text-[10px] text-slate-400 mt-0.5 block">Untuk bypass gerbang</span>
            </div>
            <div class="sm:col-span-2">
              <label class="block font-semibold text-slate-700 mb-1">Pos Penugasan Aktif</label>
              <input
                v-model="addForm.pos_aktif"
                type="text"
                placeholder="Contoh: Loket Kasir 02"
                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
              />
            </div>
          </div>

          <!-- Modal Action Buttons -->
          <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
            <button
              type="button"
              @click="showAddModal = false"
              class="apple-btn px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="apple-btn px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold flex items-center gap-1.5 shadow-md shadow-purple-600/30"
            >
              <span v-if="isSubmitting" class="material-symbols-outlined text-[16px] animate-spin">sync</span>
              <span>Simpan Akun Baru</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ================= MODAL EDIT DATA AKUN ================= -->
    <div
      v-if="showEditModal && editingAccount"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
    >
      <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-black/[0.08] space-y-5 animate-scale-up">
        
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
          <div class="flex items-center gap-2.5">
            <div class="w-10 h-10 rounded-2xl bg-blue-600/10 text-blue-700 flex items-center justify-center">
              <span class="material-symbols-outlined text-[22px]">manage_accounts</span>
            </div>
            <div>
              <h3 class="text-base font-extrabold text-slate-900">Ubah Data Akun: {{ editingAccount.id_petugas }}</h3>
              <p class="text-xs text-slate-500">Perbarui identitas, peran, PIN otorisasi, atau pos tugas</p>
            </div>
          </div>
          <button @click="showEditModal = false" class="apple-btn p-1 text-slate-400 hover:text-slate-700 rounded-xl">
            <span class="material-symbols-outlined text-xl">close</span>
          </button>
        </div>

        <form @submit.prevent="submitEditAccount" class="space-y-4 text-xs">
          
          <!-- Role selector -->
          <div>
            <label class="block font-bold text-slate-700 mb-1.5">Peran Akun (Role) *</label>
            <select
              v-model="editForm.peran"
              class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
            >
              <option value="admin">Administrator (Pusat Kontrol & Pengaturan Member)</option>
              <option value="kasir">Kasir (Loket Iuran & Transaksi Pembayaran)</option>
              <option value="operator">Petugas Pos Keluar (Operator Gerbang)</option>
              <option value="satpam">Petugas Keamanan / Satpam (Gerbang Masuk)</option>
            </select>
          </div>

          <!-- Nama Lengkap & Username -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Nama Petugas *</label>
              <input
                v-model="editForm.nama_petugas"
                type="text"
                required
                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
              />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Username *</label>
              <input
                v-model="editForm.username"
                type="text"
                required
                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 font-mono-metric text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 lowercase"
              />
            </div>
          </div>

          <!-- PIN Petugas & Pos Penugasan -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">PIN (6 Digit) *</label>
              <input
                v-model="editForm.pin_petugas"
                type="text"
                maxlength="6"
                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 font-mono-metric font-bold text-slate-900 text-center focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
              />
            </div>
            <div class="sm:col-span-2">
              <label class="block font-semibold text-slate-700 mb-1">Pos Penugasan</label>
              <input
                v-model="editForm.pos_aktif"
                type="text"
                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
              />
            </div>
          </div>

          <!-- Optional New Password -->
          <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80">
            <label class="block font-semibold text-slate-700 mb-1">Ganti Kata Sandi (Opsional)</label>
            <input
              v-model="editForm.password"
              type="password"
              minlength="4"
              placeholder="Kosongkan jika tidak ingin mengubah kata sandi"
              class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 font-mono-metric text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
            />
            <span class="text-[10px] text-slate-400 mt-1 block">Biarkan kosong jika kata sandi lama tetap dipertahankan.</span>
          </div>

          <!-- Modal Action Buttons -->
          <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
            <button
              type="button"
              @click="showEditModal = false"
              class="apple-btn px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="apple-btn px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold flex items-center gap-1.5 shadow-md shadow-blue-600/30"
            >
              <span v-if="isSubmitting" class="material-symbols-outlined text-[16px] animate-spin">sync</span>
              <span>Perbarui Akun</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ================= MODAL RESET PASSWORD CEPAT ================= -->
    <div
      v-if="showResetModal && resettingAccount"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
    >
      <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-black/[0.08] space-y-4 animate-scale-up">
        <div class="flex items-center gap-2.5 pb-2 border-b border-slate-100">
          <div class="w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
            <span class="material-symbols-outlined text-[22px]">lock_reset</span>
          </div>
          <div>
            <h3 class="text-sm font-extrabold text-slate-900">Reset Kata Sandi Akun</h3>
            <span class="text-[11px] text-slate-500 font-mono-metric">{{ resettingAccount.id_petugas }} ({{ resettingAccount.username }})</span>
          </div>
        </div>

        <form @submit.prevent="submitResetPassword" class="space-y-3 text-xs">
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Kata Sandi Baru *</label>
            <input
              v-model="newPasswordInput"
              type="password"
              required
              minlength="4"
              placeholder="Masukkan password baru"
              class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 font-mono-metric text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
            />
          </div>

          <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
            <button
              type="button"
              @click="showResetModal = false"
              class="apple-btn px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="apple-btn px-4 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold"
            >
              Simpan Password
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ================= MODAL KONFIRMASI HAPUS ================= -->
    <div
      v-if="showDeleteModal && deletingAccount"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
    >
      <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-black/[0.08] space-y-4 animate-scale-up">
        <div class="flex items-center gap-3">
          <div class="w-11 h-11 rounded-2xl bg-rose-500/10 text-rose-600 flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-[24px]">warning</span>
          </div>
          <div>
            <h3 class="text-sm font-extrabold text-slate-900">Hapus Akun Pengguna?</h3>
            <p class="text-xs text-slate-500">Tindakan ini tidak dapat dibatalkan.</p>
          </div>
        </div>

        <div class="p-3 rounded-2xl bg-rose-50/60 border border-rose-200 text-xs text-rose-950 space-y-1">
          <p>Anda akan menghapus akun:</p>
          <p class="font-bold font-mono-metric text-rose-900">
            {{ deletingAccount.id_petugas }} — {{ deletingAccount.nama_petugas }}
          </p>
          <p class="text-[11px] text-rose-700">Username: <strong>{{ deletingAccount.username }}</strong> ({{ deletingAccount.peran }})</p>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 text-xs">
          <button
            type="button"
            @click="showDeleteModal = false"
            class="apple-btn px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold"
          >
            Batal
          </button>
          <button
            type="button"
            @click="confirmDeleteAccount"
            :disabled="isSubmitting"
            class="apple-btn px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold flex items-center gap-1 shadow-md shadow-rose-600/30"
          >
            <span v-if="isSubmitting" class="material-symbols-outlined text-[15px] animate-spin">sync</span>
            <span>Ya, Hapus Akun</span>
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
const { currentUser, getPetugasList, createPetugas, updatePetugas, deletePetugas, resetPetugasPassword } = useApi()

// State
const accountsList = ref<any[]>([])
const isLoading = ref(false)
const isSubmitting = ref(false)
const searchQuery = ref('')
const activeRole = ref('semua') // 'semua', 'admin', 'kasir', 'petugas'

const metaCounts = ref({
  total_semua: 0,
  total_admin: 0,
  total_kasir: 0,
  total_petugas: 0,
})

const toastMessage = ref('')
const toastType = ref<'success' | 'error'>('success')
let toastTimer: any = null

const showToast = (msg: string, type: 'success' | 'error' = 'success') => {
  toastMessage.value = msg
  toastType.value = type
  if (toastTimer) clearTimeout(toastTimer)
  toastTimer = setTimeout(() => {
    toastMessage.value = ''
  }, 4000)
}

// PIN visibility toggles per account
const revealedPins = ref<Record<string, boolean>>({})

const toggleRevealPin = (id: string) => {
  revealedPins.value[id] = !revealedPins.value[id]
}

// Load Accounts
const loadData = async () => {
  isLoading.value = true
  try {
    const res: any = await getPetugasList('', 'semua')
    if (res?.data) {
      accountsList.value = res.data
    }
    if (res?.meta) {
      metaCounts.value = res.meta
    } else {
      // Recalculate
      metaCounts.value = {
        total_semua: accountsList.value.length,
        total_admin: accountsList.value.filter(a => a.peran === 'admin').length,
        total_kasir: accountsList.value.filter(a => a.peran === 'kasir').length,
        total_petugas: accountsList.value.filter(a => ['operator', 'petugas', 'satpam'].includes(a.peran)).length,
      }
    }
  } catch (err: any) {
    showToast('Gagal memuat daftar akun: ' + (err?.message || 'Error'), 'error')
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  loadData()
})

// Filtered Accounts
const filteredAccounts = computed(() => {
  let list = accountsList.value

  // Role filter
  if (activeRole.value !== 'semua') {
    if (activeRole.value === 'admin') {
      list = list.filter(a => a.peran === 'admin')
    } else if (activeRole.value === 'kasir') {
      list = list.filter(a => a.peran === 'kasir')
    } else if (activeRole.value === 'petugas') {
      list = list.filter(a => ['operator', 'petugas', 'satpam'].includes(a.peran))
    }
  }

  // Search query
  if (searchQuery.value.trim()) {
    const s = searchQuery.value.toLowerCase().trim()
    list = list.filter(a =>
      (a.nama_petugas || '').toLowerCase().includes(s) ||
      (a.username || '').toLowerCase().includes(s) ||
      (a.id_petugas || '').toLowerCase().includes(s) ||
      (a.pos_aktif || '').toLowerCase().includes(s)
    )
  }

  return list
})

const resetFilter = () => {
  searchQuery.value = ''
  activeRole.value = 'semua'
}

// Helpers
const getRoleLabel = (role: string) => {
  switch (role) {
    case 'admin': return 'Administrator'
    case 'kasir': return 'Petugas Kasir'
    case 'satpam': return 'Petugas Masuk / Satpam'
    default: return 'Petugas Pos Keluar'
  }
}

const getRoleDesc = (role: string) => {
  switch (role) {
    case 'admin': return 'Akses penuh sistem & pengaturan'
    case 'kasir': return 'Loket iuran perpanjangan member'
    case 'satpam': return 'Pemantauan jalur masuk & bypass'
    default: return 'Validasi keluar & override gerbang'
  }
}

const getRoleColor = (role: string) => {
  switch (role) {
    case 'admin':
      return {
        badge: 'bg-purple-50 text-purple-700 border-purple-200',
        avatarBg: 'bg-gradient-to-tr from-purple-600 to-indigo-600',
        icon: 'admin_panel_settings'
      }
    case 'kasir':
      return {
        badge: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        avatarBg: 'bg-gradient-to-tr from-emerald-600 to-teal-600',
        icon: 'point_of_sale'
      }
    case 'satpam':
      return {
        badge: 'bg-amber-50 text-amber-800 border-amber-200',
        avatarBg: 'bg-gradient-to-tr from-amber-600 to-orange-500',
        icon: 'local_police'
      }
    default:
      return {
        badge: 'bg-blue-50 text-blue-700 border-blue-200',
        avatarBg: 'bg-gradient-to-tr from-blue-600 to-indigo-600',
        icon: 'directions_car'
      }
  }
}

// ================= MODAL TAMBAH =================
const showAddModal = ref(false)
const addForm = reactive({
  id_petugas: '',
  nama_petugas: '',
  username: '',
  password: '',
  peran: 'operator',
  pin_petugas: '123456',
  pos_aktif: 'Pos Gerbang Keluar'
})

const generateSuggestedId = (role: string) => {
  const prefix = role === 'admin' ? 'ADM' : (role === 'kasir' ? 'KSR' : (role === 'satpam' ? 'SEC' : 'PTG'))
  const existingWithPrefix = accountsList.value.filter(a => (a.id_petugas || '').startsWith(prefix))
  let maxNum = 0
  for (const acc of existingWithPrefix) {
    const parts = acc.id_petugas.split('-')
    if (parts.length === 2) {
      const num = parseInt(parts[1], 10)
      if (!isNaN(num) && num > maxNum) maxNum = num
    }
  }
  return `${prefix}-${String(maxNum + 1).padStart(2, '0')}`
}

const selectAddRole = (role: string) => {
  addForm.peran = role
  addForm.id_petugas = generateSuggestedId(role)
  switch (role) {
    case 'admin':
      addForm.pos_aktif = 'Pusat Administrasi & Pengawas'
      break
    case 'kasir':
      addForm.pos_aktif = 'Loket Kasir'
      break
    case 'satpam':
      addForm.pos_aktif = 'Pos Gerbang Masuk'
      break
    default:
      addForm.pos_aktif = 'Pos Gerbang Keluar'
      break
  }
}

const openAddModal = () => {
  selectAddRole('operator')
  addForm.nama_petugas = ''
  addForm.username = ''
  addForm.password = ''
  addForm.pin_petugas = '123456'
  showAddModal.value = true
}

const submitAddAccount = async () => {
  if (!addForm.nama_petugas || !addForm.username || !addForm.password) {
    showToast('Lengkapi nama petugas, username, dan password!', 'error')
    return
  }

  isSubmitting.value = true
  try {
    const res: any = await createPetugas({
      id_petugas: addForm.id_petugas.trim().toUpperCase(),
      nama_petugas: addForm.nama_petugas.trim(),
      username: addForm.username.trim().toLowerCase(),
      password: addForm.password.trim(),
      peran: addForm.peran,
      pin_petugas: addForm.pin_petugas || '123456',
      pos_aktif: addForm.pos_aktif.trim()
    })

    showToast(res?.message || 'Akun baru berhasil ditambahkan!')
    showAddModal.value = false
    await loadData()
  } catch (err: any) {
    showToast('Gagal menambah akun: ' + (err?.data?.message || err?.message || 'Kesalahan data'), 'error')
  } finally {
    isSubmitting.value = false
  }
}

// ================= MODAL EDIT =================
const showEditModal = ref(false)
const editingAccount = ref<any>(null)
const editForm = reactive({
  nama_petugas: '',
  username: '',
  peran: 'operator',
  pin_petugas: '123456',
  pos_aktif: '',
  password: ''
})

const openEditModal = (acc: any) => {
  editingAccount.value = acc
  editForm.nama_petugas = acc.nama_petugas || ''
  editForm.username = acc.username || ''
  editForm.peran = acc.peran || 'operator'
  editForm.pin_petugas = acc.pin_petugas || '123456'
  editForm.pos_aktif = acc.pos_aktif || ''
  editForm.password = ''
  showEditModal.value = true
}

const submitEditAccount = async () => {
  if (!editingAccount.value) return
  isSubmitting.value = true
  try {
    const payload: any = {
      nama_petugas: editForm.nama_petugas.trim(),
      username: editForm.username.trim().toLowerCase(),
      peran: editForm.peran,
      pin_petugas: editForm.pin_petugas,
      pos_aktif: editForm.pos_aktif.trim()
    }
    if (editForm.password.trim()) {
      payload.password = editForm.password.trim()
    }

    const res: any = await updatePetugas(editingAccount.value.id_petugas, payload)
    showToast(res?.message || 'Data akun berhasil diperbarui!')
    showEditModal.value = false
    await loadData()
  } catch (err: any) {
    showToast('Gagal mengubah akun: ' + (err?.data?.message || err?.message || 'Error'), 'error')
  } finally {
    isSubmitting.value = false
  }
}

// ================= MODAL RESET PASSWORD =================
const showResetModal = ref(false)
const resettingAccount = ref<any>(null)
const newPasswordInput = ref('')

const openResetPassModal = (acc: any) => {
  resettingAccount.value = acc
  newPasswordInput.value = ''
  showResetModal.value = true
}

const submitResetPassword = async () => {
  if (!resettingAccount.value || !newPasswordInput.value) return
  isSubmitting.value = true
  try {
    const res: any = await resetPetugasPassword(resettingAccount.value.id_petugas, newPasswordInput.value.trim())
    showToast(res?.message || 'Password akun berhasil direset!')
    showResetModal.value = false
  } catch (err: any) {
    showToast('Gagal mereset password: ' + (err?.data?.message || err?.message || 'Error'), 'error')
  } finally {
    isSubmitting.value = false
  }
}

// ================= MODAL HAPUS =================
const showDeleteModal = ref(false)
const deletingAccount = ref<any>(null)

const openDeleteModal = (acc: any) => {
  if (acc.id_petugas === 'ADM-01' || acc.username === 'admin') {
    showToast('Akun Administrator Utama (ADM-01) tidak dapat dihapus demi keamanan.', 'error')
    return
  }
  deletingAccount.value = acc
  showDeleteModal.value = true
}

const confirmDeleteAccount = async () => {
  if (!deletingAccount.value) return
  isSubmitting.value = true
  try {
    const res: any = await deletePetugas(deletingAccount.value.id_petugas)
    showToast(res?.message || 'Akun berhasil dihapus.')
    showDeleteModal.value = false
    await loadData()
  } catch (err: any) {
    showToast('Gagal menghapus akun: ' + (err?.data?.message || err?.message || 'Error'), 'error')
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
@keyframes scaleUp {
  0% {
    transform: scale(0.95);
    opacity: 0;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}
.animate-scale-up {
  animation: scaleUp 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
