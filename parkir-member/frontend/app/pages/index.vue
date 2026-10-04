<template>
  <div class="min-h-screen bg-[#f5f5f7] text-[#1d1d1f] flex flex-col justify-center items-center p-4 sm:p-6 lg:p-8 relative overflow-hidden selection:bg-[#0071e3] selection:text-white">
    
    <!-- Background Ambient Lighting (Apple Soft Diffused Orbs) -->
    <div class="absolute -top-40 -left-40 w-[550px] h-[550px] rounded-full bg-gradient-to-br from-indigo-400/15 to-purple-400/10 blur-[130px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-[550px] h-[550px] rounded-full bg-gradient-to-tl from-emerald-400/15 to-teal-400/10 blur-[130px] pointer-events-none"></div>

    <main class="w-full flex items-center justify-center relative z-10 max-w-5xl my-auto">
      
      <!-- Main Glassmorphism Container Card -->
      <div class="w-full apple-glass-card rounded-[32px] p-7 sm:p-10 lg:p-12 transition-all">
        
        <!-- Flash Alert If Redirected from Protected Route -->
        <transition
          enter-active-class="transform ease-out duration-300 transition"
          enter-from-class="-translate-y-2 opacity-0"
          enter-to-class="translate-y-0 opacity-100"
        >
          <div v-if="route.query.alert === 'admin_only'" class="mb-6 p-4 rounded-2xl bg-rose-50/90 backdrop-blur-md border border-rose-200/90 text-rose-950 flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-rose-600 text-2xl shrink-0 mt-0.5">gpp_bad</span>
            <div class="text-xs sm:text-sm">
              <strong class="font-bold block text-rose-950">Akses Ditolak — Khusus Administrator</strong>
              <span class="text-rose-800">Pengaturan dan Kelola Member hanya dapat diakses melalui akun <strong>Administrator</strong>. Silakan login sebagai Admin (Pak Supriadi / ADM-01).</span>
            </div>
          </div>
          <div v-else-if="route.query.alert === 'need_login'" class="mb-6 p-4 rounded-2xl bg-amber-50/80 backdrop-blur-md border border-amber-200/80 text-amber-950 flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-amber-600 text-2xl shrink-0 mt-0.5">lock_clock</span>
            <div class="text-xs sm:text-sm">
              <strong class="font-semibold block text-amber-950">Akses Dibatasi — Silakan Login Petugas / Admin</strong>
              <span class="text-amber-800">Halaman ini memerlukan otorisasi petugas atau administrator. Silakan masuk terlebih dahulu dengan akun yang berwenang.</span>
            </div>
          </div>
        </transition>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-stretch">
          
          <!-- LEFT PANEL: 58% (7 cols) - Login Petugas Operasional -->
          <div class="lg:col-span-7 flex flex-col justify-between">
            <div>
              <!-- Header Brand & Badge -->
              <div class="flex items-center justify-between gap-3 mb-6">
                <div class="flex items-center gap-3">
                  <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-[#0071e3] to-[#4338ca] flex items-center justify-center text-white shadow-[0_6px_16px_rgba(0,113,227,0.3)]">
                    <span class="material-symbols-outlined text-[24px]">local_parking</span>
                  </div>
                  <div>
                    <h2 class="font-extrabold text-[17px] tracking-[-0.02em] text-[#1d1d1f] leading-none">SIP-Member</h2>
                    <span class="font-mono-metric text-xs text-[#0071e3] font-semibold">v2.4 Core Gate</span>
                  </div>
                </div>
                <span class="font-mono-metric text-[11px] font-semibold px-3 py-1 rounded-full bg-black/[0.04] text-[#1d1d1f] border border-black/[0.06] tracking-wide">
                  FASE F • XI RPL 2
                </span>
              </div>

              <!-- Title & Subtitle -->
              <div class="space-y-1.5 mb-5">
                <div class="flex items-center gap-2">
                  <span class="material-symbols-outlined text-[#0071e3] text-[20px]">admin_panel_settings</span>
                  <span class="text-xs font-bold text-[#0071e3] uppercase tracking-wider font-mono-metric">Sistem Operasional & Kontrol Pusat</span>
                </div>
                <h1 class="apple-display text-2xl sm:text-[30px] font-extrabold text-[#1d1d1f]">Portal Login Petugas & Administrator</h1>
                <p class="apple-body text-[13px] text-[#86868b]">Akses sistem perparkiran khusus petugas operasional gerbang, loket kasir, dan administrator</p>
              </div>

              <!-- Admin Role & Member Management Security Note -->
              <div class="mb-5 p-3.5 rounded-2xl bg-indigo-50/70 border border-indigo-200/70 text-xs text-indigo-950 flex items-start gap-2.5 shadow-2xs">
                <span class="material-symbols-outlined text-brand-600 text-[20px] shrink-0 mt-0.5">verified_user</span>
                <div class="space-y-0.5">
                  <strong class="block font-bold text-slate-900">Hak Akses & Pengaturan Member Terpusat (Khusus Admin)</strong>
                  <p class="text-[11px] text-slate-600 leading-relaxed">
                    Pengaturan master data anggota member, penetapan tarif paket, dan penerbitan kartu QR diaudit secara ketat dan <strong>hanya dapat dikelola melalui sesi Administrator</strong>.
                  </p>
                </div>
              </div>

              <!-- Active Session Card (If User is Already Logged In) -->
              <div v-if="isLoggedIn && currentUser" class="mb-6 p-4 rounded-2xl bg-[#0071e3]/[0.06] border border-[#0071e3]/20">
                <div class="flex items-center justify-between mb-2.5">
                  <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-[#0071e3] uppercase">Sesi Petugas Aktif</span>
                  </div>
                  <span class="text-[11px] font-mono-metric font-bold bg-white text-[#0071e3] px-2 py-0.5 rounded-full border border-[#0071e3]/20 shadow-xs">{{ currentUser.id_petugas }}</span>
                </div>
                <p class="text-xs text-[#424245] mb-3">
                  Anda saat ini login sebagai <strong>{{ currentUser.nama_petugas }}</strong> (<span class="uppercase font-semibold text-[#0071e3]">{{ currentUser.peran }}</span>) di pos: <em>{{ currentUser.pos_aktif }}</em>.
                </p>
                <div class="flex flex-wrap gap-2">
                  <button
                    type="button"
                    @click="resumeSession"
                    class="apple-btn px-4 py-2 rounded-xl bg-[#0071e3] hover:bg-[#0077ed] text-white font-semibold text-xs shadow-sm flex items-center gap-1.5"
                  >
                    <span>Lanjut ke Pos Tugas ➜</span>
                  </button>
                  <button
                    type="button"
                    @click="logout"
                    class="apple-btn px-4 py-2 rounded-xl bg-white hover:bg-rose-50 text-rose-600 border border-black/[0.08] font-semibold text-xs shadow-xs flex items-center gap-1.5"
                  >
                    <span class="material-symbols-outlined text-[15px]">logout</span>
                    <span>Keluar / Ganti Akun</span>
                  </button>
                </div>
              </div>

              <!-- Fast Fill Demo Pills: Admin, Kasir, Petugas -->
              <div class="mb-5 p-3.5 rounded-2xl bg-black/[0.03] border border-black/[0.04]">
                <div class="flex items-center justify-between mb-2 px-1">
                  <span class="text-[11px] font-bold text-[#86868b] uppercase tracking-wider">Pilih Akun Otorisasi (Klik untuk Mengisi):</span>
                  <span class="text-[10px] text-[#0071e3] font-semibold">1-Klik Otomatis</span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                  <!-- 1. ADMIN (PRIMARY) -->
                  <button
                    type="button"
                    @click="fillCredentials('admin', 'admin123', 'Pusat Administrasi')"
                    class="apple-btn py-2.5 px-2 text-xs rounded-xl transition-all flex flex-col items-center justify-center gap-1 border text-center"
                    :class="form.username === 'admin' ? 'bg-white shadow-[0_2px_8px_rgba(0,0,0,0.08)] border-purple-300 text-purple-900 font-bold ring-2 ring-purple-500/20' : 'bg-transparent border-transparent text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white/60'"
                  >
                    <div class="flex items-center gap-1">
                      <span class="w-2 h-2 rounded-full bg-purple-500 shrink-0"></span>
                      <span class="font-bold">Admin</span>
                    </div>
                    <span class="text-[9px] text-[#86868b] leading-tight">Pengaturan & Member</span>
                  </button>

                  <!-- 2. KASIR -->
                  <button
                    type="button"
                    @click="fillCredentials('kasir', 'kasir123', 'Loket Kasir')"
                    class="apple-btn py-2.5 px-2 text-xs rounded-xl transition-all flex flex-col items-center justify-center gap-1 border text-center"
                    :class="form.username === 'kasir' ? 'bg-white shadow-[0_2px_8px_rgba(0,0,0,0.08)] border-blue-300 text-blue-900 font-bold ring-2 ring-blue-500/20' : 'bg-transparent border-transparent text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white/60'"
                  >
                    <div class="flex items-center gap-1">
                      <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                      <span class="font-bold">Kasir</span>
                    </div>
                    <span class="text-[9px] text-[#86868b] leading-tight">Loket Iuran Kasir</span>
                  </button>

                  <!-- 3. PETUGAS POS KELUAR -->
                  <button
                    type="button"
                    @click="fillCredentials('petugas', 'petugas123', 'Pos Gerbang Keluar')"
                    class="apple-btn py-2.5 px-2 text-xs rounded-xl transition-all flex flex-col items-center justify-center gap-1 border text-center"
                    :class="form.username === 'petugas' ? 'bg-white shadow-[0_2px_8px_rgba(0,0,0,0.08)] border-emerald-300 text-emerald-900 font-bold ring-2 ring-emerald-500/20' : 'bg-transparent border-transparent text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white/60'"
                  >
                    <div class="flex items-center gap-1">
                      <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                      <span class="font-bold">Petugas</span>
                    </div>
                    <span class="text-[9px] text-[#86868b] leading-tight">Pos Gate-Out</span>
                  </button>
                </div>
              </div>

                <!-- Form -->
                <form @submit.prevent="handleLogin" class="space-y-4">
                  <!-- Username / ID Petugas -->
                  <div>
                    <label class="block text-xs font-semibold text-[#1d1d1f] tracking-tight mb-1.5">
                      Username / ID Petugas
                    </label>
                    <div class="relative flex items-center">
                      <span class="material-symbols-outlined absolute left-3.5 text-[#86868b] pointer-events-none text-[20px]">badge</span>
                      <input
                        v-model="form.username"
                        type="text"
                        required
                        placeholder="Contoh: petugas, admin, atau kasir"
                        class="w-full h-12 pl-11 pr-3 rounded-2xl bg-black/[0.03] hover:bg-black/[0.05] focus:bg-white border border-black/[0.08] focus:border-[#0071e3] focus:ring-4 focus:ring-[#0071e3]/15 text-sm text-[#1d1d1f] placeholder-[#86868b] font-mono-metric focus:outline-none transition-all duration-200"
                      />
                    </div>
                  </div>

                  <!-- Password -->
                  <div>
                    <label class="block text-xs font-semibold text-[#1d1d1f] tracking-tight mb-1.5">
                      Kata Sandi
                    </label>
                    <div class="relative flex items-center">
                      <span class="material-symbols-outlined absolute left-3.5 text-[#86868b] pointer-events-none text-[20px]">lock</span>
                      <input
                        v-model="form.password"
                        :type="showPassword ? 'text' : 'password'"
                        required
                        placeholder="••••••••"
                        class="w-full h-12 pl-11 pr-11 rounded-2xl bg-black/[0.03] hover:bg-black/[0.05] focus:bg-white border border-black/[0.08] focus:border-[#0071e3] focus:ring-4 focus:ring-[#0071e3]/15 text-sm text-[#1d1d1f] placeholder-[#86868b] font-mono-metric focus:outline-none transition-all duration-200"
                      />
                      <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="apple-btn absolute right-3 text-[#86868b] hover:text-[#1d1d1f] p-1.5 rounded-lg"
                      >
                        <span class="material-symbols-outlined text-[20px]">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
                      </button>
                    </div>
                  </div>

                  <!-- Pos Tugas Aktif -->
                  <div>
                    <label class="block text-xs font-semibold text-[#1d1d1f] tracking-tight mb-1.5">
                      Pos Tugas Aktif
                    </label>
                    <div class="relative flex items-center">
                      <span class="material-symbols-outlined absolute left-3.5 text-[#86868b] pointer-events-none text-[20px]">meeting_room</span>
                      <select
                        v-model="form.pos_aktif"
                        class="w-full h-12 pl-11 pr-9 rounded-2xl bg-black/[0.03] hover:bg-black/[0.05] focus:bg-white border border-black/[0.08] focus:border-[#0071e3] focus:ring-4 focus:ring-[#0071e3]/15 text-sm text-[#1d1d1f] focus:outline-none transition-all duration-200 appearance-none cursor-pointer"
                      >
                        <option value="Pos Gerbang Keluar">Pos Gerbang Keluar</option>
                        <option value="Loket Kasir">Loket Kasir</option>
                        <option value="Pusat Administrasi">Pusat Administrasi (Admin)</option>
                      </select>
                      <span class="material-symbols-outlined absolute right-3 text-[#86868b] pointer-events-none text-[20px]">expand_more</span>
                    </div>
                  </div>

                  <!-- Forgot Password Link -->
                  <div class="flex justify-end text-xs text-[#6e6e73] pt-1">
                    <span class="text-xs text-[#0071e3] hover:underline cursor-pointer font-medium">Lupa PIN/Sandi?</span>
                  </div>

                  <!-- Error Message Banner -->
                  <div v-if="errorMessage" class="p-3.5 rounded-2xl bg-rose-50/90 border border-rose-200 text-rose-800 text-xs flex items-center gap-2 shadow-xs">
                    <span class="material-symbols-outlined text-rose-600 text-[18px]">error</span>
                    <span>{{ errorMessage }}</span>
                  </div>

                  <!-- CTA Submit Button (Apple-style Blue Button) -->
                  <button
                    type="submit"
                    :disabled="isLoading"
                    class="apple-btn w-full h-12 rounded-2xl bg-[#0071e3] hover:bg-[#0077ed] text-white font-semibold text-[15px] shadow-[0_4px_16px_rgba(0,113,227,0.35)] flex items-center justify-center gap-2 mt-3 disabled:opacity-50 border-t border-white/25"
                  >
                    <span v-if="isLoading" class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span>
                    <span>{{ isLoading ? 'Memverifikasi...' : 'Masuk ke Sistem ➜' }}</span>
                  </button>
                </form>
              </div>

            <!-- Footer note -->
            <p class="text-[11px] text-[#86868b] mt-6 pt-4 border-t border-black/[0.06]">
              Pertanyaan atau reset akses? Hubungi Administrator / Pengelola Parkir
            </p>
          </div>

          <!-- RIGHT PANEL: 42% (5 cols) - APPLE-STYLE HIGHLIGHTED KIOSK CARD -->
          <div class="lg:col-span-5 bg-gradient-to-br from-emerald-500/[0.08] via-teal-500/[0.04] to-emerald-500/[0.02] backdrop-blur-2xl border border-emerald-500/30 rounded-[28px] p-7 sm:p-8 flex flex-col justify-between relative overflow-hidden shadow-[0_20px_50px_rgba(16,185,129,0.08)] ring-1 ring-emerald-500/20 group">
            
            <!-- Subtle Radial Glow Behind Kiosk Icon -->
            <div class="absolute -right-16 -top-16 w-56 h-56 bg-emerald-400/20 rounded-full blur-3xl pointer-events-none group-hover:scale-125 transition-transform duration-700"></div>

            <div>
              <!-- Kiosk Icon & Highlighted Standby Badge -->
              <div class="flex items-center justify-between mb-5">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-[0_8px_20px_rgba(16,185,129,0.3)]">
                  <span class="material-symbols-outlined text-3xl">sensors</span>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/25 text-[#065f46] text-[11px] font-semibold shadow-xs">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                  STANDBY JALUR MASUK
                </div>
              </div>

              <!-- Title & Desc -->
              <h2 class="apple-title text-2xl font-bold text-[#1d1d1f] mb-1">Terminal Gerbang Masuk</h2>
              <span class="inline-block text-xs font-semibold text-emerald-700 uppercase tracking-wider mb-3">
                Kios Mandiri Jalur Masuk Pengendara
              </span>
              <p class="apple-body text-[14px] text-[#424245] leading-relaxed mb-6">
                Buka antarmuka kios mandiri untuk jalur masuk kendaraan member. Beroperasi secara otomatis 24 jam tanpa memerlukan login operator petugas.
              </p>

              <!-- Feature Bullets (Frosted Apple Check Chips) -->
              <div class="space-y-3.5 mb-6">
                <div class="flex items-start gap-3">
                  <div class="w-6 h-6 rounded-full bg-emerald-500/15 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                    <span class="material-symbols-outlined text-[15px]">check</span>
                  </div>
                  <div>
                    <span class="text-xs font-bold text-[#1d1d1f] block">100% Scan QR Code Optik 2D</span>
                    <span class="text-[11px] text-[#6e6e73]">Deteksi instan kartu digital QR member di kamera pemindai</span>
                  </div>
                </div>

                <div class="flex items-start gap-3">
                  <div class="w-6 h-6 rounded-full bg-emerald-500/15 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                    <span class="material-symbols-outlined text-[15px]">check</span>
                  </div>
                  <div>
                    <span class="text-xs font-bold text-[#1d1d1f] block">Palang Buka Instan & LED Status</span>
                    <span class="text-[11px] text-[#6e6e73]">Verifikasi masa aktif kartu secara real-time otomatis</span>
                  </div>
                </div>

                <div class="flex items-start gap-3">
                  <div class="w-6 h-6 rounded-full bg-emerald-500/15 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                    <span class="material-symbols-outlined text-[15px]">check</span>
                  </div>
                  <div>
                    <span class="text-xs font-bold text-[#1d1d1f] block">PIN Cepat 6-Digit Satpam Jaga</span>
                    <span class="text-[11px] text-[#6e6e73]">Otorisasi darurat bila kartu tertinggal/rusak</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- KIOSK GATE-IN ACTION BUTTON (TANPA KETERANGAN PUBLIK) -->
            <div class="mt-6 pt-4 border-t border-emerald-500/20">
              <NuxtLink
                to="/kios-gatein"
                class="apple-btn w-full h-12 py-3 px-5 rounded-2xl bg-gradient-to-b from-[#10b981] to-[#059669] hover:from-[#059669] hover:to-[#047857] text-white font-semibold text-[15px] shadow-[0_10px_25px_rgba(16,185,129,0.3)] flex items-center justify-center gap-2.5 border-t border-white/25 group"
              >
                <span class="material-symbols-outlined text-[20px]">sensors</span>
                <span>Buka Kios Gate-In</span>
                <span class="material-symbols-outlined text-[20px] transition-transform duration-200 group-hover:translate-x-1">arrow_forward</span>
              </NuxtLink>
            </div>
          </div>

        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const router = useRouter()
const { currentUser, isLoggedIn, login, logout } = useApi()

const form = reactive({
  username: 'admin',
  password: 'admin123',
  pos_aktif: 'Pusat Administrasi',
  remember: true
})

const showPassword = ref(false)
const isLoading = ref(false)
const errorMessage = ref('')

const fillCredentials = (username: string, pass: string, pos: string) => {
  form.username = username
  form.password = pass
  form.pos_aktif = pos
  errorMessage.value = ''
}

const resumeSession = () => {
  if (!currentUser.value) return
  const pos = (currentUser.value.pos_aktif || '').toLowerCase()
  const u = (currentUser.value.username || currentUser.value.id_petugas || '').toLowerCase()
  const role = (currentUser.value.peran || '').toLowerCase()

  if (role === 'admin' || pos.includes('administrasi') || u.includes('adm')) {
    router.push('/member')
  } else if (pos.includes('kasir') || u.includes('kasir') || u.includes('ksr') || role === 'kasir') {
    router.push('/kasir')
  } else {
    router.push('/pos-gateout')
  }
}

const handleLogin = async () => {
  isLoading.value = true
  errorMessage.value = ''
  try {
    const res: any = await login({
      username: form.username,
      password: form.password,
      pos_aktif: form.pos_aktif
    })

    if (res?.success) {
      if (route.query.target && typeof route.query.target === 'string') {
        router.push(route.query.target)
        return
      }

      const pos = form.pos_aktif.toLowerCase()
      const u = form.username.toLowerCase()
      if (u.includes('admin') || pos.includes('administrasi')) {
        router.push('/member')
      } else if (pos.includes('kasir') || u.includes('kasir') || u.includes('ksr')) {
        router.push('/kasir')
      } else {
        router.push('/pos-gateout')
      }
    } else {
      errorMessage.value = res?.message || 'Login gagal. Periksa data kembali.'
    }
  } catch (err: any) {
    errorMessage.value = err?.data?.message || err?.message || 'Terjadi kesalahan saat login.'
  } finally {
    isLoading.value = false
  }
}
</script>
