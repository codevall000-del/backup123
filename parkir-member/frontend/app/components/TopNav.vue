<template>
  <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-2xl border-b border-black/[0.06] text-[#1d1d1f] shadow-[0_1px_3px_rgba(0,0,0,0.02)] transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <!-- Logo & School Project Badge -->
        <NuxtLink to="/" class="flex items-center gap-3 group apple-btn">
          <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-[#0071e3] to-[#4338ca] flex items-center justify-center text-white shadow-[0_4px_12px_rgba(0,113,227,0.25)] group-hover:scale-105 transition-transform duration-200">
            <span class="material-symbols-outlined text-[24px]">local_parking</span>
          </div>
          <div>
            <div class="flex items-center gap-2">
              <span class="font-extrabold text-[17px] tracking-[-0.02em] text-[#1d1d1f] group-hover:text-[#0071e3] transition-colors">SIP-Member</span>
              <span class="text-[10px] font-semibold bg-black/[0.04] text-[#1d1d1f] border border-black/[0.06] px-2 py-0.5 rounded-full font-mono-metric">Kelompok 2 • XI RPL 2</span>
            </div>
            <p class="text-xs text-[#86868b] hidden sm:block">Sistem Informasi Parkir Khusus Member • Bu Fadillah</p>
          </div>
        </NuxtLink>

        <!-- Screen Switcher Tabs (Apple Segmented Control Style) -->
        <nav class="flex items-center gap-1 sm:gap-1.5 bg-black/[0.04] p-1 rounded-2xl border border-black/[0.04] text-xs font-semibold overflow-x-auto">
          <!-- 01. Login Portal (Always accessible) -->
          <NuxtLink
            to="/"
            class="apple-btn px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 whitespace-nowrap"
            :class="route.path === '/' ? 'text-white bg-[#0071e3] shadow-sm' : 'text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white/60'"
          >
            <span class="material-symbols-outlined text-[16px]">login</span>
            <span>01. Login Portal</span>
          </NuxtLink>

          <!-- 02. Kios Gate-In -->
          <NuxtLink
            to="/kios-gatein"
            class="apple-btn px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 whitespace-nowrap relative"
            :class="route.path === '/kios-gatein' ? 'text-white bg-[#10b981] shadow-sm' : 'text-[#6e6e73] hover:text-[#065f46] hover:bg-white/60'"
          >
            <span class="material-symbols-outlined text-[16px]">meeting_room</span>
            <span>02. Kios Gate-In</span>
          </NuxtLink>

          <!-- 03. Pos Gate-Out (Locked before login) -->
          <template v-if="isLoggedIn">
            <NuxtLink
              to="/pos-gateout"
              class="apple-btn px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 whitespace-nowrap"
              :class="route.path === '/pos-gateout' ? 'text-white bg-[#0071e3] shadow-sm' : 'text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white/60'"
            >
              <span class="material-symbols-outlined text-[16px]">sensors</span>
              <span>03. Pos Gate-Out</span>
            </NuxtLink>
          </template>
          <template v-else>
            <button
              type="button"
              @click="notifyNeedLogin('Pos Gerbang Keluar')"
              class="apple-btn px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 whitespace-nowrap text-[#a1a1a6] hover:text-[#6e6e73] hover:bg-black/[0.02] cursor-pointer"
              title="Akses dikunci: Silakan login petugas terlebih dahulu"
            >
              <span class="material-symbols-outlined text-[16px] text-amber-500">lock</span>
              <span>03. Pos Gate-Out</span>
            </button>
          </template>

          <!-- 04. Pengaturan Member (KHUSUS HAK AKSES ADMIN) -->
          <template v-if="isLoggedIn && isAdminUser">
            <NuxtLink
              to="/member"
              class="apple-btn px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 whitespace-nowrap"
              :class="route.path.startsWith('/member') ? 'text-white bg-[#0071e3] shadow-sm' : 'text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white/60'"
            >
              <span class="material-symbols-outlined text-[16px]">manage_accounts</span>
              <span>04. Pengaturan Member</span>
              <span class="text-[9px] font-bold px-1.5 py-0.2 rounded-full bg-purple-500/20 text-purple-700 font-mono-metric">ADMIN</span>
            </NuxtLink>
          </template>
          <template v-else-if="isLoggedIn">
            <!-- Non-admin user logged in (Kasir / Petugas) -->
            <button
              type="button"
              @click="notifyAdminOnly"
              class="apple-btn px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 whitespace-nowrap text-[#a1a1a6] hover:text-amber-800 hover:bg-amber-50/50 cursor-pointer"
              title="Akses Terkunci: Halaman Pengaturan Member hanya berhak diakses oleh Administrator"
            >
              <span class="material-symbols-outlined text-[16px] text-amber-500">lock</span>
              <span>04. Pengaturan Member</span>
              <span class="text-[9px] font-bold px-1.5 py-0.2 rounded-full bg-amber-500/20 text-amber-800 font-mono-metric">ADMIN ONLY</span>
            </button>
          </template>
          <template v-else>
            <!-- Guest -->
            <button
              type="button"
              @click="notifyNeedLogin('Pengaturan Member (Khusus Administrator)')"
              class="apple-btn px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 whitespace-nowrap text-[#a1a1a6] hover:text-[#6e6e73] hover:bg-black/[0.02] cursor-pointer"
              title="Akses dikunci: Silakan login sebagai Administrator terlebih dahulu"
            >
              <span class="material-symbols-outlined text-[16px] text-amber-500">lock</span>
              <span>04. Pengaturan Member</span>
            </button>
          </template>

          <!-- 05. Loket Kasir (Locked before login) -->
          <template v-if="isLoggedIn">
            <NuxtLink
              to="/kasir"
              class="apple-btn px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 whitespace-nowrap"
              :class="route.path.startsWith('/kasir') ? 'text-white bg-[#0071e3] shadow-sm' : 'text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white/60'"
            >
              <span class="material-symbols-outlined text-[16px]">point_of_sale</span>
              <span>05. Loket Kasir</span>
            </NuxtLink>
          </template>
          <template v-else>
            <button
              type="button"
              @click="notifyNeedLogin('Loket Kasir & Iuran')"
              class="apple-btn px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 whitespace-nowrap text-[#a1a1a6] hover:text-[#6e6e73] hover:bg-black/[0.02] cursor-pointer"
              title="Akses dikunci: Silakan login petugas terlebih dahulu"
            >
              <span class="material-symbols-outlined text-[16px] text-amber-500">lock</span>
              <span>05. Loket Kasir</span>
            </button>
          </template>

          <!-- 06. Dashboard (Locked before login) -->
          <template v-if="isLoggedIn">
            <NuxtLink
              to="/dashboard"
              class="apple-btn px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 whitespace-nowrap"
              :class="route.path === '/dashboard' ? 'text-white bg-[#0071e3] shadow-sm' : 'text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white/60'"
            >
              <span class="material-symbols-outlined text-[16px]">monitoring</span>
              <span>06. Dashboard</span>
            </NuxtLink>
          </template>
          <template v-else>
            <button
              type="button"
              @click="notifyNeedLogin('Dashboard Monitoring')"
              class="apple-btn px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 whitespace-nowrap text-[#a1a1a6] hover:text-[#6e6e73] hover:bg-black/[0.02] cursor-pointer"
              title="Akses dikunci: Silakan login petugas terlebih dahulu"
            >
              <span class="material-symbols-outlined text-[16px] text-amber-500">lock</span>
              <span>06. Dashboard</span>
            </button>
          </template>
        </nav>

        <!-- Right Side: API Status & User Profile -->
        <div class="hidden xl:flex items-center gap-3">
          <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/[0.04] border border-black/[0.04] text-[11px] font-mono-metric">
            <span class="w-2 h-2 rounded-full" :class="isBackendOnline ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500'"></span>
            <span class="text-[#6e6e73]">API: Laravel 11</span>
          </div>

          <!-- When Logged In -->
          <div v-if="isLoggedIn && currentUser" class="flex items-center gap-2 pl-2 border-l border-black/[0.08]">
            <div class="w-8 h-8 rounded-full bg-[#0071e3] text-white flex items-center justify-center text-xs font-bold shadow-xs">
              {{ currentUser.nama_petugas ? currentUser.nama_petugas.charAt(0) : 'P' }}
            </div>
            <div class="flex flex-col text-left">
              <span class="text-xs font-semibold text-[#1d1d1f] leading-tight max-w-[130px] truncate">{{ currentUser.nama_petugas || 'Petugas' }}</span>
              <span class="text-[10px] text-[#0071e3] font-bold uppercase font-mono-metric">{{ currentUser.peran || 'OPERATOR' }}</span>
            </div>

            <!-- Logout Button -->
            <button
              type="button"
              @click="handleLogout"
              class="apple-btn ml-1 px-3 py-1 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-xs font-semibold flex items-center gap-1 shadow-xs"
              title="Keluar dari sesi petugas"
            >
              <span class="material-symbols-outlined text-[15px]">logout</span>
              <span class="hidden 2xl:inline">Keluar</span>
            </button>
          </div>

          <!-- When NOT Logged In -->
          <div v-else class="flex items-center gap-2 pl-2 border-l border-black/[0.08]">
            <div class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-[11px] font-semibold">
              <span class="material-symbols-outlined text-[14px]">lock</span>
              <span>Mode Tamu</span>
            </div>
            <NuxtLink
              to="/"
              class="apple-btn px-3 py-1 rounded-xl bg-[#0071e3] hover:bg-[#0077ed] text-white text-xs font-semibold shadow-xs flex items-center gap-1"
            >
              <span class="material-symbols-outlined text-[15px]">login</span>
              <span>Masuk Petugas</span>
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>

    <!-- Alert Toast (When user clicks locked menu) -->
    <transition
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="-translate-y-2 opacity-0"
      enter-to-class="translate-y-0 opacity-100"
      leave-active-class="transition ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="lockedAlertMessage"
        class="bg-amber-50/90 backdrop-blur-md border-y border-amber-200 px-4 py-2.5 text-xs text-amber-950 flex items-center justify-between"
      >
        <div class="max-w-7xl mx-auto w-full flex items-center justify-between gap-3">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-amber-600 text-[18px]">lock</span>
            <span class="font-medium">{{ lockedAlertMessage }}</span>
          </div>
          <div class="flex items-center gap-2">
            <NuxtLink
              to="/"
              class="font-semibold underline text-amber-800 hover:text-amber-950"
            >
              Login Sekarang ➜
            </NuxtLink>
            <button
              @click="lockedAlertMessage = ''"
              class="apple-btn p-1 text-amber-600 hover:text-amber-950 ml-2"
            >
              <span class="material-symbols-outlined text-[16px]">close</span>
            </button>
          </div>
        </div>
      </div>
    </transition>
  </header>
</template>

<script setup lang="ts">
const route = useRoute()
const { currentUser, isLoggedIn, isBackendOnline, logout } = useApi()

const lockedAlertMessage = ref('')
let alertTimer: any = null

const isAdminUser = computed(() => {
  if (!currentUser.value) return false
  const role = (currentUser.value.peran || '').toLowerCase()
  const u = (currentUser.value.username || currentUser.value.id_petugas || '').toLowerCase()
  return role === 'admin' || u.includes('adm')
})

const notifyNeedLogin = (menuName: string) => {
  lockedAlertMessage.value = `Akses Menu Dibatasi: Silakan login sebagai petugas/admin terlebih dahulu untuk membuka ${menuName}.`
  if (alertTimer) clearTimeout(alertTimer)
  alertTimer = setTimeout(() => {
    lockedAlertMessage.value = ''
  }, 4500)
}

const notifyAdminOnly = () => {
  lockedAlertMessage.value = `Akses Dibatasi: Pengaturan & Kelola Member hanya dapat diakses melalui akun Administrator Sistem (ADM-01). Sesi Anda saat ini tidak memiliki kewenangan ini.`
  if (alertTimer) clearTimeout(alertTimer)
  alertTimer = setTimeout(() => {
    lockedAlertMessage.value = ''
  }, 5000)
}

const handleLogout = () => {
  logout()
}
</script>
