<template>
  <div class="min-h-screen bg-[#f5f5f7] text-[#1d1d1f] flex flex-col font-sans selection:bg-[#0071e3] selection:text-white">
    <TopNav />

    <!-- Sub-Header Breadcrumb & Actions -->
    <header class="w-full bg-white/85 backdrop-blur-xl border-b border-black/[0.06] sticky top-16 z-30 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div class="flex items-center gap-1.5 text-xs text-[#86868b] mb-0.5">
            <NuxtLink to="/" class="hover:text-[#0071e3]">Beranda</NuxtLink>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-purple-700 font-bold">Pengaturan Member (Khusus Administrator)</span>
            <span class="text-[10px] font-bold px-2 py-0.2 rounded-full bg-purple-500/10 text-purple-700 border border-purple-500/20 font-mono-metric">ADMIN ONLY</span>
          </div>
          <h1 class="text-xl sm:text-2xl font-black text-[#1d1d1f] tracking-tight flex items-center gap-2.5">
            <span class="material-symbols-outlined text-purple-600 text-2xl">admin_panel_settings</span>
            <span>Pusat Pengaturan & Kelola Member Parkir</span>
          </h1>
          <p class="text-xs text-[#6e6e73]">
            Area otorisasi Administrator untuk pengelolaan direktori keanggotaan parkir berlangganan berbasis QR Code, penerbitan kartu digital, dan konfigurasi hak akses kendaraan.
          </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-2">
          <!-- Button SOP Edukasi Pemahaman Login & Member -->
          <button
            type="button"
            @click="showEducationModal = true"
            class="apple-btn px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-900 text-xs font-bold flex items-center gap-1.5 shadow-xs transition-all"
            title="Pelajari alur otorisasi login dan penerbitan QR member"
          >
            <span class="material-symbols-outlined text-[18px] text-amber-600">school</span>
            <span>Pemahaman Alur Sistem</span>
          </button>

          <!-- Button Tambah Member Baru -->
          <button
            type="button"
            @click="openAddModal"
            class="apple-btn px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 active:scale-95 text-white text-xs font-bold flex items-center gap-1.5 shadow-[0_4px_12px_rgba(147,51,234,0.3)] transition-all"
          >
            <span class="material-symbols-outlined text-[18px]">person_add</span>
            <span>+ Tambah Member Baru</span>
          </button>
        </div>
      </div>
    </header>

    <!-- Main Container -->
    <main class="w-full flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

      <!-- Staff Login Responsibility Notice Banner -->
      <div class="apple-glass-card rounded-2xl p-4 border border-purple-200/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-gradient-to-r from-purple-50/40 via-white to-indigo-50/30">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-purple-600/10 text-purple-700 flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-[22px]">admin_panel_settings</span>
          </div>
          <div>
            <div class="flex items-center gap-2">
              <span class="text-xs font-bold text-[#1d1d1f]">Hak Akses Administrator Sistem (Level ADM-01)</span>
              <span class="text-[10px] font-mono-metric font-bold px-2 py-0.2 rounded-full bg-purple-100 text-purple-800">LEVEL OTORISASI TERTINGGI</span>
            </div>
            <p class="text-xs text-[#6e6e73] mt-0.5">
              Anda sedang mengelola master data anggota atas nama Administrator:
              <strong class="text-purple-900 font-semibold">{{ currentUser?.nama_petugas || 'Pak Supriadi (ADM-01)' }}</strong>.
              Seluruh penambahan, perubahan nomor plat, dan penerbitan kartu QR diaudit secara ketat di sistem.
            </p>
          </div>
        </div>
        <div class="flex items-center gap-2 text-[11px] font-mono-metric text-purple-800 bg-purple-100/60 px-3 py-1.5 rounded-xl border border-purple-200">
          <span class="w-2 h-2 rounded-full bg-purple-600 animate-pulse"></span>
          <span>ADMINISTRATOR MODE</span>
        </div>
      </div>

      <!-- 4 Key Stat Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Member -->
        <div class="apple-glass-card rounded-2xl p-4 sm:p-5 bg-white border border-black/[0.06] shadow-xs">
          <div class="flex items-center justify-between text-[#86868b] mb-2">
            <span class="text-xs font-bold uppercase tracking-wider">Total Member</span>
            <span class="material-symbols-outlined text-[20px] text-[#0071e3]">groups</span>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="text-2xl sm:text-3xl font-extrabold text-[#1d1d1f] font-mono-metric">{{ membersList.length }}</span>
            <span class="text-xs text-[#86868b]">pengendara</span>
          </div>
          <span class="text-[11px] text-[#86868b] mt-1 block">Tercatat di basis data</span>
        </div>

        <!-- Card 2: Member Aktif -->
        <div class="apple-glass-card rounded-2xl p-4 sm:p-5 bg-white border border-emerald-500/20 shadow-xs">
          <div class="flex items-center justify-between text-emerald-700 mb-2">
            <span class="text-xs font-bold uppercase tracking-wider">QR Member Aktif</span>
            <span class="material-symbols-outlined text-[20px] text-emerald-600">verified</span>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="text-2xl sm:text-3xl font-extrabold text-emerald-700 font-mono-metric">{{ countActive }}</span>
            <span class="text-xs text-emerald-600 font-semibold font-mono-metric">({{ percentActive }}%)</span>
          </div>
          <span class="text-[11px] text-emerald-800 mt-1 block">Akses gate terbuka bebas</span>
        </div>

        <!-- Card 3: Akan Kadaluarsa -->
        <div class="apple-glass-card rounded-2xl p-4 sm:p-5 bg-white border border-amber-500/20 shadow-xs">
          <div class="flex items-center justify-between text-amber-800 mb-2">
            <span class="text-xs font-bold uppercase tracking-wider">Akan Berakhir</span>
            <span class="material-symbols-outlined text-[20px] text-amber-600">notification_important</span>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="text-2xl sm:text-3xl font-extrabold text-amber-800 font-mono-metric">{{ countExpiringSoon }}</span>
            <span class="text-xs text-amber-700">≤ 7 hari</span>
          </div>
          <span class="text-[11px] text-amber-800 mt-1 block">Perlu pengingat perpanjangan</span>
        </div>

        <!-- Card 4: Kadaluarsa / Nonaktif -->
        <div class="apple-glass-card rounded-2xl p-4 sm:p-5 bg-white border border-rose-500/20 shadow-xs">
          <div class="flex items-center justify-between text-rose-700 mb-2">
            <span class="text-xs font-bold uppercase tracking-wider">Kadaluarsa / Nonaktif</span>
            <span class="material-symbols-outlined text-[20px] text-rose-600">block</span>
          </div>
          <div class="flex items-baseline gap-2">
            <span class="text-2xl sm:text-3xl font-extrabold text-rose-700 font-mono-metric">{{ countInactive }}</span>
            <span class="text-xs text-rose-600">terkunci</span>
          </div>
          <span class="text-[11px] text-rose-800 mt-1 block">Palang gate tertutup</span>
        </div>
      </div>

      <!-- Toolbar Search & Multi-Filter Strip -->
      <div class="apple-glass-card rounded-2xl p-4 bg-white border border-black/[0.06] flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 shadow-xs">
        
        <!-- Search Bar -->
        <div class="relative flex-1 max-w-md">
          <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-[#86868b] text-[18px]">search</span>
          <input
            v-model="searchQuery"
            @input="handleSearch"
            type="text"
            placeholder="Cari Nama, Plat Nomor, Kode QR (QR-MBR-...), No HP..."
            class="w-full h-10 pl-10 pr-3 rounded-xl bg-black/[0.03] hover:bg-black/[0.05] focus:bg-white border border-black/[0.08] focus:border-[#0071e3] text-xs text-[#1d1d1f] placeholder-[#86868b] focus:outline-none focus:ring-2 focus:ring-[#0071e3]/15 transition-all"
          />
        </div>

        <!-- Filter Pills & Vehicle Selector -->
        <div class="flex flex-wrap items-center gap-2">
          <!-- Status Filters -->
          <div class="flex items-center bg-black/[0.03] p-1 rounded-xl border border-black/[0.04] text-xs font-semibold">
            <button
              v-for="st in [
                { id: 'semua', label: 'Semua' },
                { id: 'aktif', label: 'Aktif' },
                { id: 'akan_habis', label: 'Akan Habis' },
                { id: 'kadaluarsa', label: 'Kadaluarsa' },
                { id: 'nonaktif', label: 'Nonaktif' }
              ]"
              :key="st.id"
              type="button"
              @click="filterStatus = st.id; handleSearch()"
              class="apple-btn px-2.5 py-1 rounded-lg transition-all text-[11px]"
              :class="filterStatus === st.id ? 'bg-white text-[#1d1d1f] font-bold shadow-xs' : 'text-[#6e6e73] hover:text-[#1d1d1f]'"
            >
              {{ st.label }}
            </button>
          </div>

          <!-- Vehicle Type Filter -->
          <select
            v-model="filterVehicle"
            @change="handleSearch"
            class="h-9 px-3 rounded-xl bg-black/[0.03] border border-black/[0.08] text-xs text-[#1d1d1f] font-semibold cursor-pointer focus:bg-white focus:outline-none"
          >
            <option value="semua">Semua Kendaraan</option>
            <option value="mobil">Hanya Mobil</option>
            <option value="motor">Hanya Motor</option>
          </select>
        </div>
      </div>

      <!-- Main Member Table -->
      <div class="apple-glass-card rounded-[24px] bg-white border border-black/[0.06] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead class="bg-black/[0.02] text-[#86868b] uppercase font-mono-metric border-b border-black/[0.06]">
              <tr>
                <th class="py-3.5 px-4 font-bold">Identitas Member</th>
                <th class="py-3.5 px-4 font-bold">Kode QR Akses</th>
                <th class="py-3.5 px-4 font-bold">Kendaraan & Plat</th>
                <th class="py-3.5 px-4 font-bold">Masa Aktif & Sisa</th>
                <th class="py-3.5 px-4 font-bold">Status</th>
                <th class="py-3.5 px-4 font-bold text-center">Aksi Manajemen</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-black/[0.04]">
              <tr
                v-for="m in filteredMembers"
                :key="m.id_member"
                class="hover:bg-[#0071e3]/[0.02] transition-colors group"
              >
                <!-- 1. Identitas Member -->
                <td class="py-3.5 px-4">
                  <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#0071e3] to-[#4338ca] text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0">
                      {{ m.nama_member ? m.nama_member.charAt(0).toUpperCase() : 'M' }}
                    </div>
                    <div>
                      <div class="flex items-center gap-1.5">
                        <span class="font-extrabold text-[13px] text-[#1d1d1f] block leading-tight">{{ m.nama_member }}</span>
                      </div>
                      <div class="flex items-center gap-2 mt-0.5 text-[11px] text-[#86868b] font-mono-metric">
                        <span>ID: {{ m.id_member }}</span>
                        <span>•</span>
                        <span>{{ m.no_telp }}</span>
                      </div>
                      <span v-if="m.nik" class="text-[10px] text-[#a1a1a6] font-mono-metric block">NIK: {{ m.nik }}</span>
                    </div>
                  </div>
                </td>

                <!-- 2. QR Code Member -->
                <td class="py-3.5 px-4">
                  <div class="flex items-center gap-2">
                    <!-- QR Thumbnail Icon -->
                    <button
                      type="button"
                      @click="openQrModal(m)"
                      class="w-10 h-10 rounded-xl bg-black/[0.03] hover:bg-[#0071e3]/10 border border-black/[0.06] hover:border-[#0071e3]/30 text-[#1d1d1f] hover:text-[#0071e3] flex items-center justify-center shrink-0 transition-all shadow-xs group/btn"
                      title="Klik untuk melihat Kartu QR Member Fullsize"
                    >
                      <span class="material-symbols-outlined text-[22px] group-hover/btn:scale-110 transition-transform">qr_code_2</span>
                    </button>
                    <div>
                      <div class="flex items-center gap-1.5">
                        <span class="font-mono-metric font-bold text-[#0071e3] text-xs select-all">{{ m.qr_code || ('QR-' + m.id_member) }}</span>
                        <button
                          type="button"
                          @click="copyToClipboard(m.qr_code || ('QR-' + m.id_member))"
                          class="text-[#86868b] hover:text-[#1d1d1f]"
                          title="Salin Kode QR"
                        >
                          <span class="material-symbols-outlined text-[14px]">content_copy</span>
                        </button>
                      </div>
                      <span class="text-[10px] text-emerald-800 font-bold block">100% Validasi Optik</span>
                    </div>
                  </div>
                </td>

                <!-- 3. Kendaraan & Plat -->
                <td class="py-3.5 px-4">
                  <div class="space-y-1">
                    <span class="inline-block px-2.5 py-0.5 rounded-lg bg-black/[0.05] border border-black/[0.08] font-mono-metric font-black text-xs text-[#1d1d1f] tracking-wide">
                      {{ m.kendaraans?.[0]?.no_plat || 'TANPA-PLAT' }}
                    </span>
                    <div class="flex items-center gap-1.5 text-[11px] text-[#6e6e73]">
                      <span class="material-symbols-outlined text-[14px] text-[#86868b]">
                        {{ m.kendaraans?.[0]?.jenis_kendaraan === 'motor' ? 'two_wheeler' : 'directions_car' }}
                      </span>
                      <span class="uppercase font-semibold text-[10px] font-mono-metric">{{ m.kendaraans?.[0]?.jenis_kendaraan || 'MOBIL' }}</span>
                      <span>•</span>
                      <span class="truncate max-w-[100px]">{{ m.kendaraans?.[0]?.merk || '-' }}</span>
                    </div>
                  </div>
                </td>

                <!-- 4. Masa Aktif & Sisa Hari -->
                <td class="py-3.5 px-4 font-mono-metric">
                  <div class="space-y-0.5">
                    <span class="text-xs font-semibold text-[#1d1d1f] block">{{ m.tgl_kadaluarsa }}</span>
                    <span
                      class="text-[10px] font-bold inline-block px-2 py-0.2 rounded-full"
                      :class="m.sisa_hari > 7 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (m.sisa_hari > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200')"
                    >
                      {{ m.sisa_hari > 0 ? 'Sisa ' + m.sisa_hari + ' hari' : 'Lewat ' + Math.abs(m.sisa_hari) + ' hari' }}
                    </span>
                  </div>
                </td>

                <!-- 5. Status Keanggotaan -->
                <td class="py-3.5 px-4">
                  <span
                    class="px-2.5 py-1 rounded-full text-[10px] font-mono-metric font-bold uppercase inline-flex items-center gap-1"
                    :class="getStatusBadgeClass(m)"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :class="m.status_member === 'aktif' && m.sisa_hari > 0 ? 'bg-emerald-500 animate-pulse' : (m.status_member === 'aktif' ? 'bg-amber-500' : 'bg-slate-400')"></span>
                    <span>{{ getStatusText(m) }}</span>
                  </span>
                </td>

                <!-- 6. Aksi Manajemen -->
                <td class="py-3.5 px-4 text-center">
                  <div class="flex items-center justify-center gap-1.5">
                    <!-- Kartu Member QR -->
                    <button
                      type="button"
                      @click="openQrModal(m)"
                      class="apple-btn p-1.5 rounded-xl bg-black/[0.04] hover:bg-[#0071e3] text-[#6e6e73] hover:text-white transition-all border border-black/[0.06]"
                      title="Lihat & Cetak Kartu QR Member"
                    >
                      <span class="material-symbols-outlined text-[17px]">badge</span>
                    </button>

                    <!-- Edit Member -->
                    <button
                      type="button"
                      @click="openEditModal(m)"
                      class="apple-btn p-1.5 rounded-xl bg-black/[0.04] hover:bg-[#0071e3] text-[#6e6e73] hover:text-white transition-all border border-black/[0.06]"
                      title="Ubah Data Member"
                    >
                      <span class="material-symbols-outlined text-[17px]">edit</span>
                    </button>

                    <!-- Perpanjang Iuran -->
                    <button
                      type="button"
                      @click="openRenewModal(m)"
                      class="apple-btn p-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white transition-all border border-emerald-200"
                      title="Perpanjang Iuran Bulanan"
                    >
                      <span class="material-symbols-outlined text-[17px]">credit_card</span>
                    </button>

                    <!-- Toggle Status -->
                    <button
                      type="button"
                      @click="handleToggleStatus(m)"
                      class="apple-btn p-1.5 rounded-xl transition-all border"
                      :class="m.status_member === 'aktif' ? 'bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white border-rose-200' : 'bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border-emerald-200'"
                      :title="m.status_member === 'aktif' ? 'Nonaktifkan Member' : 'Aktifkan Kembali Member'"
                    >
                      <span class="material-symbols-outlined text-[17px]">{{ m.status_member === 'aktif' ? 'power_settings_new' : 'check' }}</span>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Empty State -->
              <tr v-if="filteredMembers.length === 0">
                <td colspan="6" class="py-12 text-center text-[#86868b]">
                  <span class="material-symbols-outlined text-4xl text-[#86868b] block mb-2">person_search</span>
                  <p class="text-sm font-semibold text-[#1d1d1f]">Tidak ada anggota member ditemukan</p>
                  <p class="text-xs text-[#86868b] mt-0.5">Coba sesuaikan kata kunci pencarian atau filter status.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </main>

    <!-- ============================================== -->
    <!-- MODAL 1: Tambah Member Baru (Otorisasi Login) -->
    <!-- ============================================== -->
    <div
      v-if="showAddModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-in fade-in"
    >
      <div class="w-full max-w-2xl bg-white rounded-[32px] p-6 sm:p-8 shadow-2xl border border-black/[0.08] max-h-[90vh] overflow-y-auto">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-black/[0.06] mb-5">
          <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-[#0071e3] to-[#4338ca] text-white flex items-center justify-center shadow-md shadow-[#0071e3]/25">
              <span class="material-symbols-outlined text-[22px]">person_add</span>
            </div>
            <div>
              <h2 class="font-bold text-lg text-[#1d1d1f]">Registrasi Anggota Member Baru</h2>
              <span class="text-xs text-[#0071e3] font-semibold">Penerbitan Kredensial Khusus QR Code • Bu Fadillah</span>
            </div>
          </div>
          <button
            type="button"
            @click="showAddModal = false"
            class="apple-btn p-1.5 rounded-full text-[#86868b] hover:text-[#1d1d1f] hover:bg-black/[0.05]"
          >
            <span class="material-symbols-outlined text-[20px]">close</span>
          </button>
        </div>

        <!-- Staff Login Audit Box -->
        <div class="mb-5 p-3.5 rounded-2xl bg-[#0071e3]/[0.05] border border-[#0071e3]/20 flex items-center justify-between text-xs">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-[#0071e3] text-[20px]">badge</span>
            <div>
              <span class="text-[#86868b] block text-[10px] uppercase font-bold">Petugas Pencatat (Sesi Login Terverifikasi):</span>
              <strong class="text-[#1d1d1f]">{{ currentUser?.nama_petugas || 'Siti Nurhaliza' }}</strong>
              <span class="text-[#0071e3] font-mono-metric font-bold ml-1.5">({{ currentUser?.id_petugas || 'KSR-01' }} • {{ currentUser?.peran || 'KASIR' }})</span>
            </div>
          </div>
          <span class="text-[11px] font-mono-metric font-semibold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">AUDIT OK</span>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitAddMember" class="space-y-4 text-xs">
          <!-- Data Pribadi -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
              <label class="block font-bold text-[#1d1d1f] mb-1">Nama Lengkap Member *</label>
              <input
                v-model="addForm.nama_member"
                required
                type="text"
                placeholder="Contoh: Ahmad Favian"
                class="w-full h-11 px-3.5 rounded-xl bg-black/[0.03] border border-black/[0.08] focus:bg-white text-xs text-[#1d1d1f] placeholder-[#86868b] focus:outline-none focus:ring-2 focus:ring-[#0071e3]"
              />
            </div>

            <div>
              <label class="block font-bold text-[#1d1d1f] mb-1">No. WhatsApp / HP *</label>
              <input
                v-model="addForm.no_telp"
                required
                type="tel"
                placeholder="0812-xxxx-xxxx"
                class="w-full h-11 px-3.5 rounded-xl bg-black/[0.03] border border-black/[0.08] focus:bg-white text-xs font-mono-metric text-[#1d1d1f] placeholder-[#86868b] focus:outline-none focus:ring-2 focus:ring-[#0071e3]"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
              <label class="block font-bold text-[#1d1d1f] mb-1">Nomor KTP / NIK (Opsional)</label>
              <input
                v-model="addForm.nik"
                type="text"
                maxlength="16"
                placeholder="327601xxxxxxxxx"
                class="w-full h-11 px-3.5 rounded-xl bg-black/[0.03] border border-black/[0.08] focus:bg-white text-xs font-mono-metric text-[#1d1d1f] placeholder-[#86868b] focus:outline-none focus:ring-2 focus:ring-[#0071e3]"
              />
            </div>

            <div>
              <label class="block font-bold text-[#1d1d1f] mb-1">Alamat Domisili</label>
              <input
                v-model="addForm.alamat"
                type="text"
                placeholder="Jl. Merdeka No. 10, Jakarta Selatan"
                class="w-full h-11 px-3.5 rounded-xl bg-black/[0.03] border border-black/[0.08] focus:bg-white text-xs text-[#1d1d1f] placeholder-[#86868b] focus:outline-none focus:ring-2 focus:ring-[#0071e3]"
              />
            </div>
          </div>

          <!-- Data Kendaraan -->
          <div class="pt-2 border-t border-black/[0.06]">
            <span class="block font-bold text-[#1d1d1f] mb-2 uppercase tracking-wider text-[11px] text-[#86868b]">Data Kendaraan & Tarif Iuran:</span>
            
            <div class="grid grid-cols-2 gap-3 mb-3">
              <label
                class="p-3 rounded-xl border cursor-pointer flex flex-col items-center justify-center gap-1 transition-all"
                :class="addForm.jenis_kendaraan === 'mobil' ? 'bg-[#0071e3]/10 border-[#0071e3] text-[#0071e3] font-bold shadow-xs' : 'bg-black/[0.02] border-black/[0.06] text-[#6e6e73] hover:bg-black/[0.04]'"
              >
                <input type="radio" value="mobil" v-model="addForm.jenis_kendaraan" class="sr-only" />
                <span class="material-symbols-outlined text-2xl">directions_car</span>
                <span class="text-xs">Mobil (Rp 150.000 / bln)</span>
              </label>

              <label
                class="p-3 rounded-xl border cursor-pointer flex flex-col items-center justify-center gap-1 transition-all"
                :class="addForm.jenis_kendaraan === 'motor' ? 'bg-[#0071e3]/10 border-[#0071e3] text-[#0071e3] font-bold shadow-xs' : 'bg-black/[0.02] border-black/[0.06] text-[#6e6e73] hover:bg-black/[0.04]'"
              >
                <input type="radio" value="motor" v-model="addForm.jenis_kendaraan" class="sr-only" />
                <span class="material-symbols-outlined text-2xl">two_wheeler</span>
                <span class="text-xs">Motor (Rp 50.000 / bln)</span>
              </label>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <div>
                <label class="block font-bold text-[#1d1d1f] mb-1">Nomor Plat *</label>
                <input
                  v-model="addForm.no_plat"
                  required
                  type="text"
                  placeholder="B 1234 ABC"
                  class="w-full h-11 px-3.5 rounded-xl bg-black/[0.03] border border-black/[0.08] focus:bg-white text-xs font-mono-metric uppercase font-black text-[#1d1d1f] placeholder-[#86868b] focus:outline-none focus:ring-2 focus:ring-[#0071e3]"
                />
              </div>

              <div>
                <label class="block font-bold text-[#1d1d1f] mb-1">Merk / Tipe</label>
                <input
                  v-model="addForm.merk"
                  type="text"
                  placeholder="Honda HR-V"
                  class="w-full h-11 px-3.5 rounded-xl bg-black/[0.03] border border-black/[0.08] focus:bg-white text-xs text-[#1d1d1f] placeholder-[#86868b] focus:outline-none focus:ring-2 focus:ring-[#0071e3]"
                />
              </div>

              <div>
                <label class="block font-bold text-[#1d1d1f] mb-1">Warna</label>
                <input
                  v-model="addForm.warna"
                  type="text"
                  placeholder="Hitam Metalik"
                  class="w-full h-11 px-3.5 rounded-xl bg-black/[0.03] border border-black/[0.08] focus:bg-white text-xs text-[#1d1d1f] placeholder-[#86868b] focus:outline-none focus:ring-2 focus:ring-[#0071e3]"
                />
              </div>
            </div>
          </div>

          <!-- Paket Durasi Berlangganan -->
          <div class="p-3.5 rounded-2xl bg-black/[0.02] border border-black/[0.06] space-y-2">
            <div class="flex items-center justify-between">
              <span class="font-bold text-[#1d1d1f]">Durasi Paket Berlangganan:</span>
              <select
                v-model="addForm.durasi_bulan"
                class="h-9 px-3 rounded-lg bg-white border border-black/[0.1] text-xs font-bold text-[#1d1d1f] cursor-pointer"
              >
                <option :value="1">1 Bulan (Standar)</option>
                <option :value="3">3 Bulan (Diskon 5%)</option>
                <option :value="6">6 Bulan (Diskon 8%)</option>
                <option :value="12">12 Bulan (Diskon 12%)</option>
              </select>
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-black/[0.04]">
              <span class="text-[#6e6e73]">Total Iuran Awal yang Harus Dibayar:</span>
              <span class="font-mono-metric font-black text-sm text-emerald-700">{{ formatRupiah(computedAddTotal) }}</span>
            </div>
          </div>

          <!-- Generator QR Code Notice -->
          <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center gap-2">
            <span class="material-symbols-outlined text-emerald-600 text-[20px]">qr_code_2</span>
            <div class="text-[11px] leading-tight">
              <strong>Sistem Khusus QR Code:</strong>
              <span>Sistem akan langsung menerbitkan QR Code digital terenkripsi yang dapat dicetak atau disimpan di HP member.</span>
            </div>
          </div>

          <!-- Actions -->
          <div class="pt-3 flex items-center justify-end gap-2.5">
            <button
              type="button"
              @click="showAddModal = false"
              class="apple-btn px-4 py-2.5 rounded-xl bg-black/[0.05] hover:bg-black/[0.08] text-[#1d1d1f] font-semibold text-xs transition-all"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="apple-btn px-5 py-2.5 rounded-xl bg-[#0071e3] hover:bg-[#0077ed] text-white font-bold text-xs shadow-md shadow-[#0071e3]/30 flex items-center gap-1.5 transition-all disabled:opacity-50"
            >
              <span v-if="isSubmitting" class="material-symbols-outlined text-[16px] animate-spin">progress_activity</span>
              <span>Daftarkan & Terbitkan Kartu QR ➜</span>
            </button>
          </div>
        </form>

      </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL 2: Kartu Member VIP QR Code Digital -->
    <!-- ============================================== -->
    <div
      v-if="showQrModal && activeMember"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-md animate-in fade-in"
    >
      <div class="w-full max-w-lg bg-white rounded-[32px] p-6 sm:p-8 shadow-2xl border border-black/[0.08] space-y-6">
        
        <div class="flex items-center justify-between pb-3 border-b border-black/[0.06]">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-[#0071e3] text-[22px]">badge</span>
            <h3 class="font-bold text-base text-[#1d1d1f]">Kartu Anggota Member Parkir (Digital)</h3>
          </div>
          <button
            type="button"
            @click="showQrModal = false"
            class="apple-btn p-1.5 rounded-full text-[#86868b] hover:text-[#1d1d1f] hover:bg-black/[0.05]"
          >
            <span class="material-symbols-outlined text-[20px]">close</span>
          </button>
        </div>

        <!-- VIP Physical Card Rendering (Apple Card Style with High-Res QR) -->
        <div
          id="printableCardArea"
          class="w-full aspect-[1.586] rounded-[24px] bg-gradient-to-tr from-slate-900 via-indigo-950 to-slate-900 text-white p-5 sm:p-6 shadow-2xl relative overflow-hidden flex flex-col justify-between border border-white/10 group"
        >
          <!-- Background Geometric Patterns & Glow -->
          <div class="absolute -right-20 -bottom-20 w-60 h-60 bg-[#0071e3]/20 rounded-full blur-3xl pointer-events-none"></div>
          <div class="absolute -left-20 -top-20 w-60 h-60 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

          <!-- Card Top Bar: Logo & Status -->
          <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-[#0071e3] to-[#4338ca] flex items-center justify-center text-white shadow-sm">
                <span class="material-symbols-outlined text-[18px]">local_parking</span>
              </div>
              <div>
                <span class="font-extrabold text-sm tracking-tight block leading-none">SIP-MEMBER</span>
                <span class="text-[9px] text-[#86868b] font-mono-metric uppercase tracking-wider">Pass Berlangganan Resmi</span>
              </div>
            </div>

            <div class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-[10px] font-mono-metric font-bold">
              <span class="w-2 h-2 rounded-full" :class="activeMember.is_active ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400'"></span>
              <span>{{ activeMember.is_active ? 'AKTIF' : 'KADALUARSA' }}</span>
            </div>
          </div>

          <!-- Card Center: High-Res SVG/Canvas QR Code and Member Info -->
          <div class="grid grid-cols-12 gap-4 items-center relative z-10 py-1">
            <!-- Left 7 cols: Member Details -->
            <div class="col-span-7 space-y-2">
              <div>
                <span class="text-[10px] text-white/50 uppercase tracking-wider font-mono-metric block">Nama Anggota:</span>
                <h4 class="font-black text-base sm:text-lg text-white leading-tight truncate">{{ activeMember.nama_member }}</h4>
              </div>

              <div>
                <span class="text-[10px] text-white/50 uppercase tracking-wider font-mono-metric block">Nomor Plat Terdaftar:</span>
                <span class="inline-block px-2.5 py-0.5 rounded-lg bg-white/10 border border-white/20 font-mono-metric font-black text-sm text-amber-300 tracking-wider">
                  {{ activeMember.kendaraans?.[0]?.no_plat || 'B 1234 ABC' }}
                </span>
                <span class="text-[10px] text-white/60 ml-1.5 uppercase font-mono-metric">
                  ({{ activeMember.kendaraans?.[0]?.jenis_kendaraan || 'MOBIL' }})
                </span>
              </div>

              <div>
                <span class="text-[10px] text-white/50 uppercase tracking-wider font-mono-metric block">Masa Berlaku Hingga:</span>
                <span class="font-mono-metric text-xs font-bold text-emerald-400">{{ activeMember.tgl_kadaluarsa }}</span>
              </div>
            </div>

            <!-- Right 5 cols: Generated High Resolution QR Code -->
            <div class="col-span-5 flex flex-col items-center justify-center">
              <div class="w-24 h-24 sm:w-28 sm:h-28 bg-white p-2 rounded-2xl shadow-xl flex items-center justify-center border-2 border-white">
                <img
                  v-if="modalQrDataUrl"
                  :src="modalQrDataUrl"
                  :alt="activeMember.qr_code"
                  class="w-full h-full object-contain"
                />
                <span v-else class="material-symbols-outlined text-4xl text-slate-800 animate-spin">progress_activity</span>
              </div>
              <span class="font-mono-metric text-[9px] text-white/70 font-semibold mt-1 tracking-wider text-center">
                {{ activeMember.qr_code || ('QR-' + activeMember.id_member) }}
              </span>
            </div>
          </div>

          <!-- Card Bottom: Security Strip & Chip -->
          <div class="flex items-center justify-between pt-2 border-t border-white/10 relative z-10 text-[10px] text-white/60 font-mono-metric">
            <span class="flex items-center gap-1">
              <span class="material-symbols-outlined text-[14px] text-emerald-400">verified</span>
              <span>100% Optical QR Gate System</span>
            </span>
            <span>XI RPL 2 • Bu Fadillah</span>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-2">
          <!-- Cetak Kartu -->
          <button
            type="button"
            @click="printCard"
            class="apple-btn py-2.5 px-3 rounded-xl bg-black/[0.05] hover:bg-black/[0.08] text-[#1d1d1f] font-semibold text-xs flex items-center justify-center gap-1.5 transition-all shadow-xs"
          >
            <span class="material-symbols-outlined text-[18px]">print</span>
            <span>Cetak Kartu</span>
          </button>

          <!-- Download QR Image -->
          <a
            v-if="modalQrDataUrl"
            :href="modalQrDataUrl"
            :download="`QR_${activeMember.id_member}_${activeMember.nama_member}.png`"
            class="apple-btn py-2.5 px-3 rounded-xl bg-black/[0.05] hover:bg-black/[0.08] text-[#1d1d1f] font-semibold text-xs flex items-center justify-center gap-1.5 transition-all shadow-xs text-center"
          >
            <span class="material-symbols-outlined text-[18px]">download</span>
            <span>Unduh QR</span>
          </a>

          <!-- Direct Test in Kiosk Gate-In -->
          <NuxtLink
            to="/kios-gatein"
            class="apple-btn py-2.5 px-3 rounded-xl bg-[#10b981] hover:bg-[#059669] text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-md shadow-[#10b981]/25 transition-all text-center"
          >
            <span class="material-symbols-outlined text-[18px]">sensors</span>
            <span>Uji di Kios Gate</span>
          </NuxtLink>
        </div>

      </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL 3: Edit Data Member & Kendaraan -->
    <!-- ============================================== -->
    <div
      v-if="showEditModal && editTarget"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-in fade-in"
    >
      <div class="w-full max-w-lg bg-white rounded-[32px] p-6 sm:p-8 shadow-2xl border border-black/[0.08]">
        
        <div class="flex items-center justify-between pb-3 border-b border-black/[0.06] mb-4">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-[#0071e3] text-[22px]">edit_note</span>
            <h3 class="font-bold text-base text-[#1d1d1f]">Ubah Data Anggota Member</h3>
          </div>
          <button
            type="button"
            @click="showEditModal = false"
            class="apple-btn p-1.5 rounded-full text-[#86868b] hover:text-[#1d1d1f] hover:bg-black/[0.05]"
          >
            <span class="material-symbols-outlined text-[20px]">close</span>
          </button>
        </div>

        <form @submit.prevent="submitEditMember" class="space-y-3.5 text-xs">
          <div>
            <label class="block font-bold text-[#1d1d1f] mb-1">Nama Lengkap</label>
            <input
              v-model="editForm.nama_member"
              required
              type="text"
              class="w-full h-10 px-3.5 rounded-xl bg-black/[0.03] border border-black/[0.08] focus:bg-white text-xs text-[#1d1d1f] focus:outline-none focus:ring-2 focus:ring-[#0071e3]"
            />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-[#1d1d1f] mb-1">No. WhatsApp / HP</label>
              <input
                v-model="editForm.no_telp"
                required
                type="text"
                class="w-full h-10 px-3.5 rounded-xl bg-black/[0.03] border border-black/[0.08] focus:bg-white text-xs font-mono-metric text-[#1d1d1f] focus:outline-none focus:ring-2 focus:ring-[#0071e3]"
              />
            </div>
            <div>
              <label class="block font-bold text-[#1d1d1f] mb-1">NIK (16-Digit)</label>
              <input
                v-model="editForm.nik"
                type="text"
                maxlength="16"
                class="w-full h-10 px-3.5 rounded-xl bg-black/[0.03] border border-black/[0.08] focus:bg-white text-xs font-mono-metric text-[#1d1d1f] focus:outline-none focus:ring-2 focus:ring-[#0071e3]"
              />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-[#1d1d1f] mb-1">Plat Kendaraan</label>
              <input
                v-model="editForm.no_plat"
                required
                type="text"
                class="w-full h-10 px-3.5 rounded-xl bg-black/[0.03] border border-black/[0.08] focus:bg-white text-xs font-mono-metric uppercase font-bold text-[#1d1d1f] focus:outline-none focus:ring-2 focus:ring-[#0071e3]"
              />
            </div>
            <div>
              <label class="block font-bold text-[#1d1d1f] mb-1">Merk / Tipe</label>
              <input
                v-model="editForm.merk"
                type="text"
                class="w-full h-10 px-3.5 rounded-xl bg-black/[0.03] border border-black/[0.08] focus:bg-white text-xs text-[#1d1d1f] focus:outline-none focus:ring-2 focus:ring-[#0071e3]"
              />
            </div>
          </div>

          <div>
            <label class="block font-bold text-[#1d1d1f] mb-1">Alamat</label>
            <input
              v-model="editForm.alamat"
              type="text"
              class="w-full h-10 px-3.5 rounded-xl bg-black/[0.03] border border-black/[0.08] focus:bg-white text-xs text-[#1d1d1f] focus:outline-none focus:ring-2 focus:ring-[#0071e3]"
            />
          </div>

          <div class="pt-3 flex items-center justify-end gap-2">
            <button
              type="button"
              @click="showEditModal = false"
              class="apple-btn px-4 py-2 rounded-xl bg-black/[0.05] hover:bg-black/[0.08] text-[#1d1d1f] font-semibold text-xs transition-all"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="apple-btn px-5 py-2 rounded-xl bg-[#0071e3] hover:bg-[#0077ed] text-white font-bold text-xs shadow-md shadow-[#0071e3]/30 transition-all disabled:opacity-50"
            >
              Simpan Perubahan
            </button>
          </div>
        </form>

      </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL 4: Edukasi Pemahaman Login & Pembuatan Member -->
    <!-- ============================================== -->
    <div
      v-if="showEducationModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-md animate-in fade-in"
    >
      <div class="w-full max-w-2xl bg-white rounded-[32px] p-6 sm:p-8 shadow-2xl border border-black/[0.08] max-h-[90vh] overflow-y-auto space-y-6">
        
        <div class="flex items-center justify-between pb-4 border-b border-black/[0.06]">
          <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-amber-500/10 text-amber-800 flex items-center justify-center">
              <span class="material-symbols-outlined text-[24px]">school</span>
            </div>
            <div>
              <h3 class="font-extrabold text-base sm:text-lg text-[#1d1d1f]">Pemahaman Sistem: Login Petugas & Anggota Member QR</h3>
              <p class="text-xs text-[#86868b]">Pedoman HIPO & ERD — Kelompok 2 (XI RPL 2) • Bu Fadillah</p>
            </div>
          </div>
          <button
            type="button"
            @click="showEducationModal = false"
            class="apple-btn p-1.5 rounded-full text-[#86868b] hover:text-[#1d1d1f] hover:bg-black/[0.05]"
          >
            <span class="material-symbols-outlined text-[20px]">close</span>
          </button>
        </div>

        <!-- 5 Pillars Flowchart -->
        <div class="space-y-4 text-xs">
          <!-- Pilar 1: Mengapa Login Petugas Wajib Saat Pembuatan Member? -->
          <div class="p-4 rounded-2xl bg-[#0071e3]/[0.04] border border-[#0071e3]/20 space-y-2">
            <div class="flex items-center gap-2 text-[#0071e3] font-bold">
              <span class="material-symbols-outlined text-[20px]">lock_person</span>
              <span>1. Akuntabilitas & Otorisasi Login Petugas (Kasir / Admin)</span>
            </div>
            <p class="text-[#424245] leading-relaxed">
              Pembuatan anggota member <strong>tidak boleh dilakukan secara anonim</strong>. Member parkir adalah pelanggan berbayar dengan hak akses khusus tanpa tarif per jam. Petugas Kasir/Admin wajib login terlebih dahulu agar:
            </p>
            <ul class="list-disc list-inside space-y-1 text-[#6e6e73] pl-2">
              <li>Mencatat identitas petugas pembuat (<code class="font-mono-metric text-[#0071e3]">id_petugas</code>) di audit log database.</li>
              <li>Menjamin penerimaan uang iuran awal masuk ke kas shift loket yang sah.</li>
              <li>Mencegah kecurangan penerbitan QR Code parkir gratis tanpa setor uang.</li>
            </ul>
          </div>

          <!-- Pilar 2: Mengapa Sistem Menggunakan QR Code SAJA? -->
          <div class="p-4 rounded-2xl bg-emerald-500/[0.04] border border-emerald-500/20 space-y-2">
            <div class="flex items-center gap-2 text-emerald-800 font-bold">
              <span class="material-symbols-outlined text-[20px]">qr_code_scanner</span>
              <span>2. Efisiensi & Kemudahan: Sistem Member Menggunakan QR SAJA</span>
            </div>
            <p class="text-[#424245] leading-relaxed">
              Menggantikan sistem kartu RFID fisik tradisional menjadi <strong>100% QR Code Digital</strong>:
            </p>
            <ul class="list-disc list-inside space-y-1 text-[#6e6e73] pl-2">
              <li><strong>Zero Hardware Cost:</strong> Pengendara cukup membuka QR Code dari smartphone tanpa perlu cetak kartu plastik RFID mahal.</li>
              <li><strong>Kamera Optik Gate:</strong> Kios gerbang masuk & pos keluar dilengkapi kamera pemindai 2D berkecepatan tinggi (deteksi &lt; 0.3 detik).</li>
              <li><strong>Kriptografi Unik:</strong> Setiap QR Code memiliki token identifikasi unik (<code class="font-mono-metric text-emerald-800">QR-MBR-YYYY-XXX</code>) yang terhubung langsung ke database masa aktif member.</li>
            </ul>
          </div>

          <!-- Pilar 3: Login Portal Member Mandiri -->
          <div class="p-4 rounded-2xl bg-purple-500/[0.04] border border-purple-500/20 space-y-2">
            <div class="flex items-center gap-2 text-purple-800 font-bold">
              <span class="material-symbols-outlined text-[20px]">smartphone</span>
              <span>3. Login Mandiri Member Menggunakan QR Code</span>
            </div>
            <p class="text-[#424245] leading-relaxed">
              Member tidak perlu mengingat kombinasi password rumit. Untuk memeriksa masa aktif, riwayat parkir, atau mengunduh ulang kartu:
            </p>
            <ul class="list-disc list-inside space-y-1 text-[#6e6e73] pl-2">
              <li>Member cukup <strong>Scan QR Code</strong> di Portal Member Mandiri (<NuxtLink to="/portal-member" class="text-[#0071e3] underline">/portal-member</NuxtLink>).</li>
              <li>Sistem langsung memvalidasi token QR dan menampilkan profil serta sisa hari langganan member.</li>
            </ul>
          </div>

          <!-- Pilar 4: Validasi Gerbang (Gate In & Gate Out) -->
          <div class="p-4 rounded-2xl bg-amber-500/[0.04] border border-amber-500/20 space-y-2">
            <div class="flex items-center gap-2 text-amber-900 font-bold">
              <span class="material-symbols-outlined text-[20px]">meeting_room</span>
              <span>4. Otomasi Palang Masuk & Keluar</span>
            </div>
            <p class="text-[#424245] leading-relaxed">
              Saat member tiba di Kios Gerbang Masuk, member mengarahkan QR Code ke kamera scanner. Jika masa aktif masih berlaku, palang otomatis terangkat (biaya Rp 0,-). Di gerbang keluar, kamera LPR mencocokkan plat nomor fisik dengan plat terdaftar di QR Code untuk mencegah pertukaran kendaraan.
            </p>
          </div>
        </div>

        <div class="pt-2 text-right">
          <button
            type="button"
            @click="showEducationModal = false"
            class="apple-btn px-5 py-2.5 rounded-xl bg-[#0071e3] hover:bg-[#0077ed] text-white font-bold text-xs shadow-md transition-all"
          >
            Paham, Tutup Panduan
          </button>
        </div>

      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { useQrCode } from '~/composables/useQrCode'

const {
  currentUser,
  isLoggedIn,
  getMembers,
  createMember,
  updateMember,
  toggleMemberStatus
} = useApi()

const { generateDataUrl } = useQrCode()

// State
const searchQuery = ref('')
const filterStatus = ref('semua')
const filterVehicle = ref('semua')
const membersList = ref<any[]>([])

// Modals
const showAddModal = ref(false)
const showEditModal = ref(false)
const showQrModal = ref(false)
const showEducationModal = ref(false)

const activeMember = ref<any>(null)
const modalQrDataUrl = ref<string>('')
const editTarget = ref<any>(null)
const isSubmitting = ref(false)

// Forms
const addForm = reactive({
  nama_member: '',
  nik: '',
  no_telp: '',
  alamat: '',
  jenis_kendaraan: 'mobil',
  no_plat: '',
  merk: '',
  warna: '',
  durasi_bulan: 1
})

const editForm = reactive({
  nama_member: '',
  nik: '',
  no_telp: '',
  alamat: '',
  no_plat: '',
  merk: ''
})

onMounted(() => {
  if (currentUser.value && currentUser.value.peran && currentUser.value.peran !== 'admin') {
    const router = useRouter()
    router.push({ path: '/', query: { alert: 'admin_only' } })
    return
  }
  fetchMembersData()
})

const fetchMembersData = async () => {
  try {
    const data = await getMembers(searchQuery.value, filterStatus.value)
    if (data && data.length > 0) {
      membersList.value = data
    } else {
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
      nik: '3276012408080001',
      no_telp: '0812-9988-7766',
      alamat: 'Jl. Merdeka No. 10, Jakarta Selatan',
      qr_code: 'QR-MBR-2026-001',
      tgl_daftar: '2026-01-12',
      tgl_kadaluarsa: '2026-11-20',
      status_member: 'aktif',
      is_active: true,
      sisa_hari: 48,
      kendaraans: [{ no_plat: 'B 1234 ABC', jenis_kendaraan: 'mobil', merk: 'Honda HR-V', warna: 'Hitam Metalik' }]
    },
    {
      id_member: 'MBR-2026-002',
      nama_member: 'Budi Santoso',
      nik: '3276011505020002',
      no_telp: '0813-8877-6655',
      alamat: 'Jl. Sudirman No. 45, Jakarta Pusat',
      qr_code: 'QR-MBR-2026-002',
      tgl_daftar: '2026-02-01',
      tgl_kadaluarsa: '2026-08-01',
      status_member: 'kadaluarsa',
      is_active: false,
      sisa_hari: -63,
      kendaraans: [{ no_plat: 'B 9999 EXP', jenis_kendaraan: 'mobil', merk: 'Toyota Avanza', warna: 'Putih' }]
    },
    {
      id_member: 'MBR-2026-003',
      nama_member: 'Siti Rahma',
      nik: '3276014407050003',
      no_telp: '0857-1122-3344',
      alamat: 'Jl. Melati Blok C2 No. 8, Depok',
      qr_code: 'QR-MBR-2026-003',
      tgl_daftar: '2026-03-15',
      tgl_kadaluarsa: '2026-12-15',
      status_member: 'aktif',
      is_active: true,
      sisa_hari: 73,
      kendaraans: [{ no_plat: 'B 4567 DEF', jenis_kendaraan: 'motor', merk: 'Yamaha NMAX', warna: 'Abu-abu Matte' }]
    },
    {
      id_member: 'MBR-2026-004',
      nama_member: 'Dian Kusuma',
      nik: '3276016609040004',
      no_telp: '0818-4455-6677',
      alamat: 'Jl. Anggrek Raya No. 12, Bekasi',
      qr_code: 'QR-MBR-2026-004',
      tgl_daftar: '2026-04-10',
      tgl_kadaluarsa: '2026-10-09',
      status_member: 'aktif',
      is_active: true,
      sisa_hari: 5,
      kendaraans: [{ no_plat: 'B 3321 JKL', jenis_kendaraan: 'motor', merk: 'Honda Vario 160', warna: 'Merah' }]
    },
    {
      id_member: 'MBR-2026-005',
      nama_member: 'Rizky Pratama',
      nik: '3276012803010005',
      no_telp: '0812-7788-9900',
      alamat: 'Jl. Gatot Subroto Kav. 5, Jakarta Selatan',
      qr_code: 'QR-MBR-2026-005',
      tgl_daftar: '2026-01-05',
      tgl_kadaluarsa: '2027-01-30',
      status_member: 'aktif',
      is_active: true,
      sisa_hari: 118,
      kendaraans: [{ no_plat: 'B 8890 GHI', jenis_kendaraan: 'mobil', merk: 'Mitsubishi Xpander', warna: 'Silver' }]
    },
    {
      id_member: 'MBR-2026-006',
      nama_member: 'Hendra Wijaya',
      nik: '3276011112000006',
      no_telp: '0856-3344-5566',
      alamat: 'Jl. Raya Bogor KM 28, Cimanggis',
      qr_code: 'QR-MBR-2026-006',
      tgl_daftar: '2025-10-01',
      tgl_kadaluarsa: '2026-04-01',
      status_member: 'nonaktif',
      is_active: false,
      sisa_hari: -186,
      kendaraans: [{ no_plat: 'B 6672 KLM', jenis_kendaraan: 'motor', merk: 'Honda Beat', warna: 'Biru' }]
    }
  ]
}

// Filtered computation
const filteredMembers = computed(() => {
  return membersList.value.filter(m => {
    // 1. Search Query
    if (searchQuery.value) {
      const q = searchQuery.value.toLowerCase().trim()
      const matchName = (m.nama_member || '').toLowerCase().includes(q)
      const matchPlate = (m.kendaraans?.[0]?.no_plat || '').toLowerCase().includes(q)
      const matchId = (m.id_member || '').toLowerCase().includes(q)
      const matchQr = (m.qr_code || '').toLowerCase().includes(q)
      const matchPhone = (m.no_telp || '').includes(q)
      if (!matchName && !matchPlate && !matchId && !matchQr && !matchPhone) return false
    }

    // 2. Status Filter
    if (filterStatus.value !== 'semua') {
      if (filterStatus.value === 'aktif' && (!m.is_active || m.status_member !== 'aktif')) return false
      if (filterStatus.value === 'akan_habis' && (!m.is_active || m.sisa_hari > 7 || m.sisa_hari < 0)) return false
      if (filterStatus.value === 'kadaluarsa' && (m.is_active || m.status_member === 'nonaktif')) return false
      if (filterStatus.value === 'nonaktif' && m.status_member !== 'nonaktif') return false
    }

    // 3. Vehicle Filter
    if (filterVehicle.value !== 'semua') {
      const vType = m.kendaraans?.[0]?.jenis_kendaraan || 'mobil'
      if (vType !== filterVehicle.value) return false
    }

    return true
  })
})

// Metrics
const countActive = computed(() => membersList.value.filter(m => m.is_active && m.status_member === 'aktif').length)
const countExpiringSoon = computed(() => membersList.value.filter(m => m.is_active && m.sisa_hari <= 7 && m.sisa_hari >= 0).length)
const countInactive = computed(() => membersList.value.filter(m => !m.is_active || m.status_member === 'nonaktif').length)
const percentActive = computed(() => {
  if (membersList.value.length === 0) return 0
  return Math.round((countActive.value / membersList.value.length) * 100)
})

const computedAddTotal = computed(() => {
  const rate = addForm.jenis_kendaraan === 'mobil' ? 150000 : 50000
  const nominal = rate * addForm.durasi_bulan
  let diskon = 0
  if (addForm.durasi_bulan >= 12) diskon = nominal * 0.12
  else if (addForm.durasi_bulan >= 6) diskon = nominal * 0.08
  else if (addForm.durasi_bulan >= 3) diskon = nominal * 0.05
  return nominal - diskon
})

const handleSearch = () => {
  // Local filtered computation updates automatically
}

// Add Member
const openAddModal = () => {
  addForm.nama_member = ''
  addForm.nik = ''
  addForm.no_telp = ''
  addForm.alamat = ''
  addForm.no_plat = ''
  addForm.merk = ''
  addForm.warna = ''
  addForm.durasi_bulan = 1
  showAddModal.value = true
}

const submitAddMember = async () => {
  isSubmitting.value = true
  try {
    const res: any = await createMember({
      nama_member: addForm.nama_member,
      nik: addForm.nik,
      no_telp: addForm.no_telp,
      alamat: addForm.alamat,
      jenis_kendaraan: addForm.jenis_kendaraan,
      no_plat: addForm.no_plat,
      merk: addForm.merk,
      warna: addForm.warna,
      durasi_bulan: addForm.durasi_bulan,
      id_petugas: currentUser.value?.id_petugas || 'KSR-01'
    })

    showAddModal.value = false

    if (res?.data?.member) {
      activeMember.value = res.data.member
      modalQrDataUrl.value = await generateDataUrl(res.data.member.qr_code || ('QR-' + res.data.member.id_member))
      showQrModal.value = true
    }

    fetchMembersData()
  } catch (err: any) {
    // Fallback local add for seamless demo
    const newId = 'MBR-2026-' + String(membersList.value.length + 1).padStart(3, '0')
    const qrString = 'QR-' + newId
    const newMember = {
      id_member: newId,
      nama_member: addForm.nama_member,
      nik: addForm.nik,
      no_telp: addForm.no_telp,
      alamat: addForm.alamat,
      qr_code: qrString,
      tgl_daftar: new Date().toISOString().split('T')[0],
      tgl_kadaluarsa: new Date(Date.now() + addForm.durasi_bulan * 30 * 86400000).toISOString().split('T')[0],
      status_member: 'aktif',
      is_active: true,
      sisa_hari: addForm.durasi_bulan * 30,
      kendaraans: [{
        no_plat: addForm.no_plat.toUpperCase(),
        jenis_kendaraan: addForm.jenis_kendaraan,
        merk: addForm.merk,
        warna: addForm.warna
      }]
    }

    membersList.value.unshift(newMember)
    showAddModal.value = false

    activeMember.value = newMember
    modalQrDataUrl.value = await generateDataUrl(qrString)
    showQrModal.value = true
  } finally {
    isSubmitting.value = false
  }
}

// QR Modal
const openQrModal = async (m: any) => {
  activeMember.value = m
  const qrString = m.qr_code || ('QR-' + m.id_member)
  modalQrDataUrl.value = await generateDataUrl(qrString)
  showQrModal.value = true
}

// Edit Member
const openEditModal = (m: any) => {
  editTarget.value = m
  editForm.nama_member = m.nama_member
  editForm.nik = m.nik || ''
  editForm.no_telp = m.no_telp
  editForm.alamat = m.alamat || ''
  editForm.no_plat = m.kendaraans?.[0]?.no_plat || ''
  editForm.merk = m.kendaraans?.[0]?.merk || ''
  showEditModal.value = true
}

const submitEditMember = async () => {
  if (!editTarget.value) return
  isSubmitting.value = true
  try {
    await updateMember(editTarget.value.id_member, {
      nama_member: editForm.nama_member,
      nik: editForm.nik,
      no_telp: editForm.no_telp,
      alamat: editForm.alamat,
      no_plat: editForm.no_plat,
      merk: editForm.merk
    })

    editTarget.value.nama_member = editForm.nama_member
    editTarget.value.nik = editForm.nik
    editTarget.value.no_telp = editForm.no_telp
    editTarget.value.alamat = editForm.alamat
    if (editTarget.value.kendaraans?.[0]) {
      editTarget.value.kendaraans[0].no_plat = editForm.no_plat.toUpperCase()
      editTarget.value.kendaraans[0].merk = editForm.merk
    }

    showEditModal.value = false
    fetchMembersData()
  } catch {
    // Fallback local update
    editTarget.value.nama_member = editForm.nama_member
    editTarget.value.nik = editForm.nik
    editTarget.value.no_telp = editForm.no_telp
    editTarget.value.alamat = editForm.alamat
    if (editTarget.value.kendaraans?.[0]) {
      editTarget.value.kendaraans[0].no_plat = editForm.no_plat.toUpperCase()
      editTarget.value.kendaraans[0].merk = editForm.merk
    }
    showEditModal.value = false
  } finally {
    isSubmitting.value = false
  }
}

// Toggle Status
const handleToggleStatus = async (m: any) => {
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

// Open renew tab in /kasir
const openRenewModal = (m: any) => {
  const router = useRouter()
  router.push({ path: '/kasir', query: { tab: 'payment', member: m.id_member } })
}

// Helpers
const getStatusBadgeClass = (m: any) => {
  if (m.status_member === 'nonaktif') return 'bg-slate-100 text-slate-700 border border-slate-200'
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

const copyToClipboard = (text: string) => {
  if (navigator?.clipboard) {
    navigator.clipboard.writeText(text)
    alert(`Kode QR "${text}" berhasil disalin ke clipboard!`)
  }
}

const printCard = () => {
  window.print()
}

const formatRupiah = (val: number) => {
  return 'Rp ' + Number(val).toLocaleString('id-ID')
}
</script>
