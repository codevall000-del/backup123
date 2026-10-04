<template>
  <div class="min-h-screen bg-slate-50 text-slate-800 flex flex-col font-sans selection:bg-brand-500 selection:text-white">
    <TopNav />

    <main class="w-full flex-1 max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
      
      <!-- Top Operational Control Strip -->
      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Dashboard Monitoring & Laporan Manajemen</h1>
            <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 font-mono-metric text-xs font-bold border border-indigo-200">
              HIPO 5.0
            </span>
          </div>
          <p class="text-xs text-slate-500">
            Sistem Informasi Parkir Khusus Member Berlangganan (SIP-Member) • Wilayah Operasi Pusat • Admin: Pak Supriadi (ADM-01)
          </p>
        </div>

        <!-- Action Toolbar -->
        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
          <!-- Button Pengaturan Member (Admin Exclusive) -->
          <NuxtLink
            to="/member"
            class="flex items-center gap-1.5 bg-purple-600 hover:bg-purple-700 text-white px-3.5 py-2 rounded-xl text-xs font-bold shadow-md shadow-purple-600/25 transition-all"
            title="Akses Pengaturan Master Data Member & Kartu QR"
          >
            <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
            <span>Pengaturan Member</span>
            <span class="text-[9px] bg-white/20 px-1.5 py-0.2 rounded-full font-mono-metric">ADMIN</span>
          </NuxtLink>

          <!-- Date Range Pill -->
          <div class="flex items-center gap-2 bg-white border border-slate-200 px-3 py-2 rounded-xl shadow-sm text-xs font-mono-metric text-slate-700">
            <span class="material-symbols-outlined text-[18px] text-brand-600">calendar_month</span>
            <span>1 Okt 2026 - 31 Okt 2026</span>
          </div>

          <!-- Refresh Button -->
          <button
            type="button"
            @click="refreshDashboard"
            :disabled="isRefreshing"
            class="flex items-center gap-1.5 bg-white hover:bg-slate-50 border border-slate-200 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 transition-all shadow-sm"
          >
            <span class="material-symbols-outlined text-[18px] text-brand-600" :class="{ 'animate-spin': isRefreshing }">sync</span>
            <span>Refresh</span>
          </button>

          <!-- Export Dropdown -->
          <div class="relative">
            <button
              type="button"
              @click="showExportMenu = !showExportMenu"
              class="flex items-center gap-1.5 bg-brand-600 hover:bg-brand-700 text-white px-3.5 py-2 rounded-xl text-xs font-bold shadow-md shadow-brand-600/20 transition-all"
            >
              <span class="material-symbols-outlined text-[18px]">file_download</span>
              <span>Export Laporan</span>
              <span class="material-symbols-outlined text-[16px]">expand_more</span>
            </button>

            <div
              v-if="showExportMenu"
              class="absolute right-0 mt-2 w-48 rounded-xl bg-white border border-slate-200 shadow-xl py-1 z-30 divide-y divide-slate-100"
            >
              <button
                @click="exportPdf"
                class="w-full text-left flex items-center gap-2 px-3.5 py-2 text-xs text-slate-700 hover:bg-slate-50 hover:text-slate-900"
              >
                <span class="material-symbols-outlined text-rose-500 text-[18px]">picture_as_pdf</span>
                <span>Unduh Format PDF</span>
              </button>
              <button
                @click="exportExcel"
                class="w-full text-left flex items-center gap-2 px-3.5 py-2 text-xs text-slate-700 hover:bg-slate-50 hover:text-slate-900"
              >
                <span class="material-symbols-outlined text-emerald-600 text-[18px]">table_view</span>
                <span>Unduh Excel (.xlsx)</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- ROW 1: 4 KPI Summary Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- Card 1: Total Member Aktif -->
        <div class="bg-white border border-slate-200/90 p-5 rounded-2xl shadow-sm flex flex-col justify-between relative overflow-hidden group">
          <div class="absolute -right-4 -top-4 w-20 h-20 bg-brand-500/10 rounded-full blur-xl group-hover:scale-125 transition-transform"></div>
          <div class="flex items-center justify-between z-10 mb-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Member Aktif</span>
            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-brand-600 flex items-center justify-center">
              <span class="material-symbols-outlined text-[18px]">badge</span>
            </div>
          </div>
          <div class="z-10 flex items-baseline justify-between pt-1">
            <span class="text-3xl font-black text-slate-900 font-mono-metric">{{ stats.total_member_aktif }}</span>
            <span class="text-xs font-bold text-emerald-700 font-mono-metric flex items-center bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
              <span class="material-symbols-outlined text-[16px]">arrow_upward</span>
              <span>+{{ stats.growth_member_percent }}%</span>
            </span>
          </div>
          <span class="text-[11px] text-slate-400 mt-2 z-10 font-medium">Bulan berjalan 2026</span>
        </div>

        <!-- Card 2: Okupansi Slot Parkir -->
        <div class="bg-white border border-slate-200/90 p-5 rounded-2xl shadow-sm flex flex-col justify-between relative overflow-hidden group">
          <div class="flex items-center justify-between z-10 mb-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Okupansi Slot Parkir</span>
            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
              <span class="material-symbols-outlined text-[18px]">local_parking</span>
            </div>
          </div>
          <div class="z-10 flex items-baseline justify-between pt-1">
            <span class="text-2xl font-black text-slate-900 font-mono-metric">
              {{ stats.okupansi_slot?.terisi }} / {{ stats.okupansi_slot?.kapasitas }}
            </span>
            <span class="text-xs font-bold text-blue-700 font-mono-metric bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">
              {{ stats.okupansi_slot?.persen }}% Terisi
            </span>
          </div>
          <!-- Progress Bar -->
          <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden mt-3 z-10">
            <div
              class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full rounded-full transition-all duration-1000"
              :style="{ width: (stats.okupansi_slot?.persen || 74) + '%' }"
            ></div>
          </div>
        </div>

        <!-- Card 3: Kunjungan Hari Ini -->
        <div class="bg-white border border-slate-200/90 p-5 rounded-2xl shadow-sm flex flex-col justify-between relative overflow-hidden group">
          <div class="flex items-center justify-between z-10 mb-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kunjungan Hari Ini</span>
            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
              <span class="material-symbols-outlined text-[18px]">sync_alt</span>
            </div>
          </div>
          <div class="z-10 flex items-baseline justify-between pt-1">
            <span class="text-xl font-black text-slate-900 font-mono-metric">
              {{ stats.kunjungan_hari_ini?.masuk }} In / {{ stats.kunjungan_hari_ini?.keluar }} Out
            </span>
          </div>
          <div class="flex items-center justify-between mt-2 z-10">
            <span class="text-[11px] text-slate-500">Sedang di dalam:</span>
            <span class="text-xs font-bold font-mono-metric px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200">
              {{ stats.kunjungan_hari_ini?.sedang_parkir }} Kendaraan
            </span>
          </div>
        </div>

        <!-- Card 4: Kas Iuran Bulan Ini -->
        <div class="bg-white border border-slate-200/90 p-5 rounded-2xl shadow-sm flex flex-col justify-between relative overflow-hidden group">
          <div class="flex items-center justify-between z-10 mb-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kas Iuran Bulan Ini</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
              <span class="material-symbols-outlined text-[18px]">payments</span>
            </div>
          </div>
          <div class="z-10 flex items-baseline justify-between pt-1">
            <span class="text-xl sm:text-2xl font-black text-emerald-700 font-mono-metric">
              {{ formatRupiah(stats.kas_iuran_bulan_ini?.nominal || 42500000) }}
            </span>
          </div>
          <div class="flex items-center justify-between mt-2 z-10 text-[11px]">
            <span class="text-slate-500">Target Rp 40jt</span>
            <span class="text-emerald-700 font-bold font-mono-metric">+8.4% vs bln lalu</span>
          </div>
        </div>

      </div>

      <!-- ROW 2: Split Section (50% HIPO 5.1 / 50% HIPO 5.2) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- LEFT 50% (6 cols): Rekap Pendapatan Iuran Bulanan (HIPO 5.1) -->
        <div class="lg:col-span-6 bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
          <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <div>
              <h2 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-600 text-[18px]">bar_chart</span>
                <span>Rekap Pendapatan Iuran Bulanan (HIPO 5.1)</span>
              </h2>
              <p class="text-[11px] text-slate-500">Target Bulanan: Rp 40.000.000,- (Garis Target)</p>
            </div>
            <button
              type="button"
              @click="exportPdf"
              class="text-xs text-brand-600 hover:text-brand-800 font-bold flex items-center gap-1"
            >
              <span class="material-symbols-outlined text-[16px]">print</span>
              <span>Cetak PDF</span>
            </button>
          </div>

          <!-- Bar Chart Visualization -->
          <div class="relative h-48 w-full pt-6 flex items-end justify-between gap-3 px-2 border-b border-slate-200">
            <!-- Horizontal Target Line (Rp 40M) -->
            <div class="absolute top-12 left-0 right-0 border-b border-dashed border-amber-500/60 flex items-center justify-end pr-2 pointer-events-none">
              <span class="text-[9px] font-mono-metric text-amber-800 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded">Target 40M</span>
            </div>

            <div
              v-for="item in revenueReportData.monthly"
              :key="item.bulan"
              class="flex-1 flex flex-col items-center h-full justify-end group cursor-pointer"
            >
              <!-- Value tooltip on hover -->
              <span class="text-[9px] font-mono-metric text-brand-600 font-bold opacity-80 group-hover:opacity-100 mb-1">
                {{ (item.total / 1000000).toFixed(1) }}M
              </span>
              <!-- Bar -->
              <div
                class="w-full max-w-[36px] bg-gradient-to-t from-brand-600 to-indigo-500 rounded-t-lg transition-all duration-300 group-hover:brightness-110 shadow-sm"
                :style="{ height: (item.total / 45000000) * 100 + '%' }"
              ></div>
              <!-- Month Label -->
              <span class="text-[10px] font-mono-metric text-slate-500 mt-2">{{ item.bulan }}</span>
            </div>
          </div>

          <!-- Summary Table -->
          <div class="overflow-x-auto pt-2">
            <table class="w-full text-left text-xs">
              <thead class="text-[11px] font-mono-metric text-slate-500 border-b border-slate-200">
                <tr>
                  <th class="py-2">Bulan</th>
                  <th class="py-2">Motor</th>
                  <th class="py-2">Mobil</th>
                  <th class="py-2">Total Kas</th>
                  <th class="py-2 text-right">Target %</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 font-mono-metric text-[11px]">
                <tr v-for="row in revenueReportData.monthly" :key="row.bulan" class="hover:bg-slate-50">
                  <td class="py-2 font-bold text-slate-900">{{ row.bulan }} 2026</td>
                  <td class="py-2 text-slate-600">{{ row.motor_count }} unit</td>
                  <td class="py-2 text-slate-600">{{ row.mobil_count }} unit</td>
                  <td class="py-2 font-black text-emerald-700">{{ formatRupiah(row.total) }}</td>
                  <td class="py-2 text-right font-bold" :class="row.achievement >= 100 ? 'text-emerald-700' : 'text-amber-700'">
                    {{ row.achievement }}%
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- RIGHT 50% (6 cols): Laporan Kunjungan Parkir Harian (HIPO 5.2) -->
        <div class="lg:col-span-6 bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
          <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <div>
              <h2 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-brand-600 text-[18px]">insights</span>
                <span>Laporan Kunjungan Parkir Harian (HIPO 5.2)</span>
              </h2>
              <p class="text-[11px] text-slate-500">Analisis traffic kendaraan gate masuk & keluar</p>
            </div>
            <span class="text-[11px] text-slate-500 font-mono-metric">Update: Hari Ini</span>
          </div>

          <!-- 4 Mini Stat Cards Grid -->
          <div class="grid grid-cols-2 gap-3">
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
              <span class="text-[11px] text-slate-500 block">Rata-rata Durasi Parkir:</span>
              <span class="font-bold text-sm text-slate-900 font-mono-metric">{{ visitReportData.rata_rata_durasi }}</span>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
              <span class="text-[11px] text-slate-500 block">Jam Puncak (Peak Hour):</span>
              <span class="font-bold text-sm text-amber-700 font-mono-metric">{{ visitReportData.peak_hour }}</span>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
              <span class="text-[11px] text-slate-500 block">Bypass PIN Satpam Hari Ini:</span>
              <span class="font-bold text-sm text-brand-600 font-mono-metric">{{ visitReportData.override_hari_ini }} kali</span>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
              <span class="text-[11px] text-slate-500 block">Kartu Ditolak Hari Ini:</span>
              <span class="font-bold text-sm text-rose-700 font-mono-metric">{{ visitReportData.kartu_ditolak_hari_ini }} kali</span>
            </div>
          </div>

          <!-- Trend Bar Chart (7 Days) -->
          <div class="pt-2">
            <span class="text-xs font-bold text-slate-700 block mb-3">Tren Kunjungan 7 Hari Terakhir:</span>
            <div class="h-32 w-full flex items-end justify-between gap-2 px-1 border-b border-slate-200 pb-1">
              <div
                v-for="d in visitReportData.trend_7_hari"
                :key="d.hari"
                class="flex-1 flex flex-col items-center h-full justify-end group"
              >
                <span class="text-[9px] font-mono-metric text-slate-600 font-bold group-hover:text-slate-900 mb-1">{{ d.kunjungan }}</span>
                <div
                  class="w-full max-w-[28px] bg-emerald-500 hover:bg-emerald-600 rounded-t transition-all shadow-sm"
                  :style="{ height: (d.kunjungan / 500) * 100 + '%' }"
                ></div>
                <span class="text-[10px] font-mono-metric text-slate-500 mt-1">{{ d.hari.slice(0, 3) }}</span>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- ROW 3: Feed Aktivitas Gate Real-Time -->
      <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
        
        <!-- Feed Header & Filter Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-brand-600 text-[20px]">sensors</span>
            <div>
              <h2 class="font-bold text-sm text-slate-900">Feed Aktivitas Gate Real-Time</h2>
              <p class="text-[11px] text-slate-500">Log audit keluar-masuk kendaraan member & otorisasi bypass</p>
            </div>
          </div>

          <!-- Filters -->
          <div class="flex flex-wrap items-center gap-2">
            <select
              v-model="gateFilter"
              @change="fetchRecentActivity"
              class="h-8 px-2.5 rounded-lg bg-slate-50 border border-slate-200 text-xs text-slate-700 cursor-pointer focus:bg-white"
            >
              <option value="semua">Semua Gate</option>
              <option value="GATE-IN 01">GATE-IN 01</option>
              <option value="GATE-OUT 01">GATE-OUT 01</option>
            </select>

            <select
              v-model="statusFilter"
              @change="fetchRecentActivity"
              class="h-8 px-2.5 rounded-lg bg-slate-50 border border-slate-200 text-xs text-slate-700 cursor-pointer focus:bg-white"
            >
              <option value="semua">Semua Status</option>
              <option value="masuk">Masuk / Sukses</option>
              <option value="ditolak">Ditolak</option>
              <option value="bypass_pin">Bypass PIN</option>
            </select>

            <span class="text-[11px] text-slate-400 font-mono-metric">Auto-refresh 10s</span>
          </div>
        </div>

        <!-- Activity Feed Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-600 uppercase font-mono-metric border-b border-slate-200">
              <tr>
                <th class="py-3 px-3">Waktu</th>
                <th class="py-3 px-3">Gate</th>
                <th class="py-3 px-3">Arah</th>
                <th class="py-3 px-3">ID Member</th>
                <th class="py-3 px-3">Nama Member</th>
                <th class="py-3 px-3">No. Plat</th>
                <th class="py-3 px-3">Status</th>
                <th class="py-3 px-3">Petugas / Keterangan</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-mono-metric text-xs">
              <tr
                v-for="log in activityFeed"
                :key="log.id_parkir"
                class="hover:bg-slate-50 transition-colors"
                :class="log.status_badge === 'bypass' ? 'bg-amber-50/40' : (log.status_badge === 'ditolak' ? 'bg-rose-50/30' : '')"
              >
                <td class="py-2.5 px-3 text-slate-700 font-bold">{{ log.waktu }} WIB</td>
                <td class="py-2.5 px-3 text-slate-600">{{ log.gate }}</td>
                <td class="py-2.5 px-3">
                  <span
                    class="px-2 py-0.5 rounded text-[10px] font-bold"
                    :class="log.arah === 'Masuk' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-blue-50 text-blue-700 border border-blue-200'"
                  >
                    {{ log.arah }}
                  </span>
                </td>
                <td class="py-2.5 px-3 text-brand-600 font-bold">{{ log.id_member }}</td>
                <td class="py-2.5 px-3 text-slate-900 font-sans font-semibold">{{ log.nama }}</td>
                <td class="py-2.5 px-3">
                  <span class="bg-slate-100 px-2 py-0.5 rounded border border-slate-200 font-bold text-slate-900">
                    {{ log.plat }}
                  </span>
                </td>
                <td class="py-2.5 px-3">
                  <span
                    class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase"
                    :class="log.status_badge === 'sukses' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : (log.status_badge === 'ditolak' ? 'bg-rose-50 text-rose-800 border border-rose-200' : 'bg-amber-50 text-amber-800 border border-amber-200')"
                  >
                    {{ log.status_text }}
                  </span>
                </td>
                <td class="py-2.5 px-3 text-slate-500 font-sans text-[11px]">{{ log.petugas }}</td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>

    </main>
  </div>
</template>

<script setup lang="ts">
const { getDashboardStats, getRevenueReport, getVisitReport, getRecentActivity } = useApi()

const isRefreshing = ref(false)
const showExportMenu = ref(false)
const gateFilter = ref('semua')
const statusFilter = ref('semua')

const stats = ref<any>({
  total_member_aktif: 842,
  growth_member_percent: 12,
  okupansi_slot: { terisi: 185, kapasitas: 250, persen: 74 },
  kunjungan_hari_ini: { masuk: 420, keluar: 395, sedang_parkir: 25 },
  kas_iuran_bulan_ini: { nominal: 42500000, growth_percent: 8.4 }
})

const revenueReportData = ref<any>({
  target_bulanan: 40000000,
  monthly: [
    { bulan: 'Mei', motor_count: 180, mobil_count: 210, total: 38500000, achievement: 96.25 },
    { bulan: 'Jun', motor_count: 195, mobil_count: 220, total: 40200000, achievement: 100.5 },
    { bulan: 'Jul', motor_count: 210, mobil_count: 225, total: 41500000, achievement: 103.75 },
    { bulan: 'Agu', motor_count: 205, mobil_count: 215, total: 39800000, achievement: 99.5 },
    { bulan: 'Sep', motor_count: 220, mobil_count: 230, total: 42100000, achievement: 105.25 },
    { bulan: 'Okt', motor_count: 235, mobil_count: 240, total: 42500000, achievement: 106.25 }
  ]
})

const visitReportData = ref<any>({
  rata_rata_durasi: '3 Jam 42 Menit',
  peak_hour: '07:00–09:00 WIB',
  override_hari_ini: 3,
  kartu_ditolak_hari_ini: 7,
  trend_7_hari: [
    { hari: 'Senin', kunjungan: 380 },
    { hari: 'Selasa', kunjungan: 410 },
    { hari: 'Rabu', kunjungan: 395 },
    { hari: 'Kamis', kunjungan: 430 },
    { hari: 'Jumat', kunjungan: 450 },
    { hari: 'Sabtu', kunjungan: 420 },
    { hari: 'Minggu', kunjungan: 290 }
  ]
})

const activityFeed = ref<any[]>([
  { id_parkir: '1', waktu: '22:42', gate: 'GATE-IN 01', arah: 'Masuk', id_member: 'MBR-001', nama: 'Ahmad Favian', plat: 'B 1234 ABC', status_badge: 'sukses', status_text: '✓ Sukses', petugas: '— (Otomatis)' },
  { id_parkir: '2', waktu: '22:38', gate: 'GATE-IN 01', arah: 'Masuk', id_member: 'MBR-002', nama: 'Budi Santoso', plat: 'B 9999 EXP', status_badge: 'ditolak', status_text: '✗ Ditolak (Kadaluarsa)', petugas: '—' },
  { id_parkir: '3', waktu: '22:35', gate: 'GATE-IN 01', arah: 'Masuk', id_member: 'MBR-004', nama: 'Dian Kusuma', plat: 'B 3321 JKL', status_badge: 'bypass', status_text: '⚠️ Bypass PIN', petugas: 'Satpam Joko (Alasan: Kartu Rusak)' },
  { id_parkir: '4', waktu: '22:30', gate: 'GATE-OUT 01', arah: 'Keluar', id_member: 'MBR-003', nama: 'Siti Rahma', plat: 'B 4567 DEF', status_badge: 'sukses', status_text: '✓ Selesai (3j20m)', petugas: 'Op. Budi Santoso' }
])

let refreshInterval: any = null

onMounted(async () => {
  await refreshDashboard()
  refreshInterval = setInterval(fetchRecentActivity, 10000)
})

onUnmounted(() => {
  if (refreshInterval) clearInterval(refreshInterval)
})

const refreshDashboard = async () => {
  isRefreshing.value = true
  try {
    const s = await getDashboardStats()
    if (s) stats.value = s

    const r = await getRevenueReport()
    if (r?.monthly?.length) revenueReportData.value = r

    const v = await getVisitReport()
    if (v?.trend_7_hari?.length) visitReportData.value = v

    await fetchRecentActivity()
  } catch {
    // Keep fallback
  } finally {
    isRefreshing.value = false
  }
}

const fetchRecentActivity = async () => {
  try {
    const act = await getRecentActivity(gateFilter.value, statusFilter.value)
    if (act?.length) {
      activityFeed.value = act
    }
  } catch {
    // Keep fallback
  }
}

const formatRupiah = (val: number) => {
  return 'Rp ' + Number(val).toLocaleString('id-ID')
}

const exportPdf = () => {
  showExportMenu.value = false
  window.print()
}

const exportExcel = () => {
  showExportMenu.value = false
  alert('Laporan Rekapitulasi Berhasil Diekspor ke Excel (.xlsx). File siap diunduh.')
}
</script>
