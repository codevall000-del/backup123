<template>
  <div class="min-h-screen bg-[#f5f5f7] text-[#1d1d1f] flex flex-col font-sans selection:bg-[#0071e3] selection:text-white">
    <TopNav />

    <!-- Ambient Lighting Background -->
    <div class="fixed -top-40 -left-40 w-[550px] h-[550px] rounded-full bg-gradient-to-br from-indigo-400/10 to-purple-400/10 blur-[130px] pointer-events-none"></div>
    <div class="fixed -bottom-40 -right-40 w-[550px] h-[550px] rounded-full bg-gradient-to-tl from-emerald-400/10 to-teal-400/10 blur-[130px] pointer-events-none"></div>

    <!-- MAIN PORTAL CONTAINER -->
    <main class="w-full flex-1 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10 flex flex-col justify-center">

      <!-- ============================================== -->
      <!-- VIEW 1: QR LOGIN SCREEN FOR MEMBER (If Not Logged In) -->
      <!-- ============================================== -->
      <div v-if="!activeMember" class="w-full apple-glass-card rounded-[32px] p-6 sm:p-10 bg-white border border-black/[0.08] shadow-xl text-center space-y-6">
        
        <div class="max-w-md mx-auto space-y-3">
          <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-[#0071e3] to-[#4338ca] text-white flex items-center justify-center mx-auto shadow-lg shadow-[#0071e3]/25">
            <span class="material-symbols-outlined text-3xl">qr_code_scanner</span>
          </div>
          <h1 class="apple-display text-2xl sm:text-3xl font-extrabold text-[#1d1d1f]">Portal Mandiri Anggota Member</h1>
          <p class="text-xs sm:text-sm text-[#86868b]">
            Akses kartu digital dan pantau masa aktif langganan parkir Anda menggunakan kode QR. Tanpa kartu fisik RFID!
          </p>
        </div>

        <!-- Fast Demo Select Pills -->
        <div class="p-3.5 rounded-2xl bg-black/[0.02] border border-black/[0.06] max-w-lg mx-auto text-left">
          <span class="text-[11px] font-bold text-[#86868b] uppercase tracking-wider block mb-2 px-1">
            Pilih Akun Member Demo untuk Pengujian:
          </span>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
            <button
              type="button"
              @click="loginWithCode('QR-MBR-2026-001')"
              class="apple-btn p-2.5 rounded-xl border text-left transition-all"
              :class="qrInput === 'QR-MBR-2026-001' ? 'bg-[#0071e3]/10 border-[#0071e3] text-[#0071e3] font-bold' : 'bg-white border-black/[0.06] text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-black/[0.02]'"
            >
              <span class="font-extrabold text-xs block text-[#1d1d1f]">Ahmad Favian</span>
              <span class="text-[10px] font-mono-metric block text-emerald-700 font-bold">B 1234 ABC • AKTIF</span>
            </button>

            <button
              type="button"
              @click="loginWithCode('QR-MBR-2026-003')"
              class="apple-btn p-2.5 rounded-xl border text-left transition-all"
              :class="qrInput === 'QR-MBR-2026-003' ? 'bg-[#0071e3]/10 border-[#0071e3] text-[#0071e3] font-bold' : 'bg-white border-black/[0.06] text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-black/[0.02]'"
            >
              <span class="font-extrabold text-xs block text-[#1d1d1f]">Siti Rahma</span>
              <span class="text-[10px] font-mono-metric block text-emerald-700 font-bold">B 4567 DEF • AKTIF</span>
            </button>

            <button
              type="button"
              @click="loginWithCode('QR-MBR-2026-002')"
              class="apple-btn p-2.5 rounded-xl border text-left transition-all"
              :class="qrInput === 'QR-MBR-2026-002' ? 'bg-[#0071e3]/10 border-[#0071e3] text-[#0071e3] font-bold' : 'bg-white border-black/[0.06] text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-black/[0.02]'"
            >
              <span class="font-extrabold text-xs block text-[#1d1d1f]">Budi Santoso</span>
              <span class="text-[10px] font-mono-metric block text-rose-700 font-bold">B 9999 EXP • KADALUARSA</span>
            </button>
          </div>
        </div>

        <!-- QR Input Form -->
        <form @submit.prevent="handleQrLogin" class="max-w-md mx-auto space-y-3.5">
          <div class="text-left">
            <label class="block text-xs font-bold text-[#1d1d1f] mb-1.5">
              Scan atau Masukkan Kode QR Member / Plat Nomor:
            </label>
            <div class="relative flex items-center">
              <span class="material-symbols-outlined absolute left-3.5 text-[#86868b] pointer-events-none text-[20px]">qr_code_scanner</span>
              <input
                v-model="qrInput"
                type="text"
                required
                placeholder="Contoh: QR-MBR-2026-001 atau B 1234 ABC"
                class="w-full h-12 pl-11 pr-3 rounded-2xl bg-black/[0.03] hover:bg-black/[0.05] focus:bg-white border border-black/[0.08] focus:border-[#0071e3] focus:ring-4 focus:ring-[#0071e3]/15 text-sm font-mono-metric text-[#1d1d1f] placeholder-[#86868b] focus:outline-none transition-all"
              />
            </div>
          </div>

          <div v-if="loginError" class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs text-left flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-rose-600">error</span>
            <span>{{ loginError }}</span>
          </div>

          <button
            type="submit"
            :disabled="isLoggingIn"
            class="apple-btn w-full h-12 rounded-2xl bg-[#0071e3] hover:bg-[#0077ed] text-white font-bold text-sm shadow-[0_4px_16px_rgba(0,113,227,0.35)] flex items-center justify-center gap-2 transition-all disabled:opacity-50"
          >
            <span v-if="isLoggingIn" class="material-symbols-outlined text-[20px] animate-spin">progress_activity</span>
            <span>{{ isLoggingIn ? 'Memverifikasi QR...' : 'Masuk ke Portal Member ➜' }}</span>
          </button>
        </form>

        <p class="text-xs text-[#86868b] pt-4 border-t border-black/[0.06] max-w-md mx-auto">
          Belum menjadi anggota member? Kunjungi Loket Kasir Utama untuk mendaftar dan mendapatkan kartu QR resmi.
        </p>

      </div>

      <!-- ============================================== -->
      <!-- VIEW 2: MEMBER DASHBOARD (Logged In via QR) -->
      <!-- ============================================== -->
      <div v-else class="space-y-6 animate-in fade-in">
        
        <!-- Header User & Logout -->
        <div class="apple-glass-card rounded-[28px] p-5 bg-white border border-black/[0.06] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#0071e3] to-[#4338ca] text-white flex items-center justify-center text-lg font-bold shadow-md">
              {{ activeMember.nama_member.charAt(0) }}
            </div>
            <div>
              <div class="flex items-center gap-2">
                <h2 class="text-lg font-extrabold text-[#1d1d1f]">{{ activeMember.nama_member }}</h2>
                <span class="text-[10px] font-mono-metric font-bold px-2 py-0.5 rounded-full bg-blue-500/10 text-[#0071e3] border border-blue-500/20">
                  {{ activeMember.id_member }}
                </span>
              </div>
              <p class="text-xs text-[#6e6e73] mt-0.5">
                Anggota Resmi Parkir Khusus Member Berlangganan
              </p>
            </div>
          </div>

          <div class="flex items-center gap-2">
            <button
              type="button"
              @click="printCard"
              class="apple-btn px-3.5 py-2 rounded-xl bg-black/[0.04] hover:bg-black/[0.08] text-[#1d1d1f] font-semibold text-xs flex items-center gap-1.5 transition-all shadow-xs"
            >
              <span class="material-symbols-outlined text-[16px]">print</span>
              <span>Cetak Kartu</span>
            </button>
            <button
              type="button"
              @click="logoutMember"
              class="apple-btn px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 font-semibold text-xs flex items-center gap-1.5 transition-all shadow-xs"
            >
              <span class="material-symbols-outlined text-[16px]">logout</span>
              <span>Keluar</span>
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
          
          <!-- LEFT: 58% (7 cols) - DIGITAL MEMBER PASS -->
          <div class="lg:col-span-7 space-y-4">
            
            <!-- VIP Pass Card Container -->
            <div
              id="printableCardArea"
              class="w-full aspect-[1.586] rounded-[28px] bg-gradient-to-tr from-slate-900 via-indigo-950 to-slate-900 text-white p-6 shadow-2xl relative overflow-hidden flex flex-col justify-between border border-white/10 group"
            >
              <!-- Background Orbs -->
              <div class="absolute -right-20 -bottom-20 w-60 h-60 bg-[#0071e3]/20 rounded-full blur-3xl pointer-events-none"></div>
              <div class="absolute -left-20 -top-20 w-60 h-60 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

              <!-- Top Bar -->
              <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center gap-2.5">
                  <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#0071e3] to-[#4338ca] flex items-center justify-center text-white shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">local_parking</span>
                  </div>
                  <div>
                    <span class="font-extrabold text-sm tracking-tight block leading-none">SIP-MEMBER PASS</span>
                    <span class="text-[9px] text-[#86868b] font-mono-metric uppercase tracking-wider">Akses Parkir Bebas Hambatan</span>
                  </div>
                </div>

                <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-[11px] font-mono-metric font-bold">
                  <span class="w-2 h-2 rounded-full" :class="activeMember.is_active ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400'"></span>
                  <span>{{ activeMember.is_active ? 'MEMBER AKTIF' : 'KADALUARSA' }}</span>
                </div>
              </div>

              <!-- Center Bar: Details & High Res QR -->
              <div class="grid grid-cols-12 gap-4 items-center relative z-10 py-1">
                <div class="col-span-7 space-y-2">
                  <div>
                    <span class="text-[10px] text-white/50 uppercase tracking-wider font-mono-metric block">Nama Pengendara:</span>
                    <h3 class="font-black text-base sm:text-lg text-white leading-tight truncate">{{ activeMember.nama_member }}</h3>
                  </div>

                  <div>
                    <span class="text-[10px] text-white/50 uppercase tracking-wider font-mono-metric block">Plat Terdaftar:</span>
                    <span class="inline-block px-2.5 py-0.5 rounded-lg bg-white/10 border border-white/20 font-mono-metric font-black text-sm text-amber-300 tracking-wider">
                      {{ activeMember.kendaraans?.[0]?.no_plat || 'B 1234 ABC' }}
                    </span>
                    <span class="text-[10px] text-white/60 ml-1.5 uppercase font-mono-metric">
                      ({{ activeMember.kendaraans?.[0]?.jenis_kendaraan || 'MOBIL' }})
                    </span>
                  </div>

                  <div>
                    <span class="text-[10px] text-white/50 uppercase tracking-wider font-mono-metric block">Berlaku Hingga:</span>
                    <span class="font-mono-metric text-xs font-bold text-emerald-400">{{ activeMember.tgl_kadaluarsa }}</span>
                  </div>
                </div>

                <div class="col-span-5 flex flex-col items-center justify-center">
                  <div class="w-24 h-24 sm:w-28 sm:h-28 bg-white p-2 rounded-2xl shadow-xl flex items-center justify-center border-2 border-white">
                    <img
                      v-if="cardQrUrl"
                      :src="cardQrUrl"
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

              <!-- Bottom Bar -->
              <div class="flex items-center justify-between pt-2 border-t border-white/10 relative z-10 text-[10px] text-white/60 font-mono-metric">
                <span class="flex items-center gap-1">
                  <span class="material-symbols-outlined text-[14px] text-emerald-400">verified</span>
                  <span>100% Optical QR Gate System</span>
                </span>
                <span>Fase F • XI RPL 2</span>
              </div>
            </div>

            <!-- Download or Direct Gate Test Actions -->
            <div class="flex items-center gap-3">
              <a
                v-if="cardQrUrl"
                :href="cardQrUrl"
                :download="`QR_${activeMember.id_member}_${activeMember.nama_member}.png`"
                class="apple-btn flex-1 py-3 px-4 rounded-2xl bg-black/[0.04] hover:bg-black/[0.08] text-[#1d1d1f] font-semibold text-xs flex items-center justify-center gap-2 border border-black/[0.06] transition-all shadow-xs"
              >
                <span class="material-symbols-outlined text-[18px]">download</span>
                <span>Unduh Gambar QR ke HP</span>
              </a>

              <NuxtLink
                to="/kios-gatein"
                class="apple-btn flex-1 py-3 px-4 rounded-2xl bg-[#10b981] hover:bg-[#059669] text-white font-bold text-xs flex items-center justify-center gap-2 shadow-md shadow-[#10b981]/20 transition-all text-center"
              >
                <span class="material-symbols-outlined text-[18px]">sensors</span>
                <span>Buka Kios Masuk (Uji Scan)</span>
              </NuxtLink>
            </div>

          </div>

          <!-- RIGHT: 42% (5 cols) - SUBSCRIPTION STATUS & RECENT PARKING -->
          <div class="lg:col-span-5 space-y-4">
            
            <!-- Subscription Countdown Widget -->
            <div class="apple-glass-card rounded-[28px] p-5 bg-white border border-black/[0.06] shadow-xs space-y-3">
              <span class="text-xs font-bold uppercase tracking-wider text-[#86868b] block">Status Langganan Bulanan:</span>
              
              <div class="flex items-center justify-between p-3.5 rounded-2xl" :class="activeMember.is_active ? 'bg-emerald-50 border border-emerald-200' : 'bg-rose-50 border border-rose-200'">
                <div>
                  <span class="text-xs font-bold block" :class="activeMember.is_active ? 'text-emerald-900' : 'text-rose-900'">
                    {{ activeMember.is_active ? 'Keanggotaan Aktif' : 'Keanggotaan Berakhir' }}
                  </span>
                  <span class="text-[11px] font-mono-metric" :class="activeMember.is_active ? 'text-emerald-700' : 'text-rose-700'">
                    {{ activeMember.sisa_hari > 0 ? `Sisa ${activeMember.sisa_hari} hari lagi` : `Kadaluarsa ${Math.abs(activeMember.sisa_hari)} hari yang lalu` }}
                  </span>
                </div>
                <span class="material-symbols-outlined text-2xl" :class="activeMember.is_active ? 'text-emerald-600' : 'text-rose-600'">
                  {{ activeMember.is_active ? 'check_circle' : 'cancel' }}
                </span>
              </div>

              <div class="text-xs space-y-1.5 pt-1 text-[#6e6e73]">
                <div class="flex justify-between">
                  <span>Tanggal Mulai:</span>
                  <span class="font-mono-metric font-semibold text-[#1d1d1f]">{{ activeMember.tgl_daftar }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Jatuh Tempo:</span>
                  <span class="font-mono-metric font-semibold text-[#1d1d1f]">{{ activeMember.tgl_kadaluarsa }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Biaya Parkir Per Kunjungan:</span>
                  <span class="font-bold text-emerald-700 font-mono-metric">Rp 0,- (GRATIS UNLIMITED)</span>
                </div>
              </div>
            </div>

            <!-- Vehicle Specification Card -->
            <div class="apple-glass-card rounded-[28px] p-5 bg-white border border-black/[0.06] shadow-xs space-y-3">
              <span class="text-xs font-bold uppercase tracking-wider text-[#86868b] block">Kendaraan Terdaftar di Sistem:</span>
              
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-black/[0.03] text-[#0071e3] flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-2xl">
                    {{ activeMember.kendaraans?.[0]?.jenis_kendaraan === 'motor' ? 'two_wheeler' : 'directions_car' }}
                  </span>
                </div>
                <div>
                  <span class="font-black text-sm text-[#1d1d1f] font-mono-metric block">
                    {{ activeMember.kendaraans?.[0]?.no_plat || 'B 1234 ABC' }}
                  </span>
                  <span class="text-xs text-[#6e6e73]">
                    {{ activeMember.kendaraans?.[0]?.merk || 'Honda HR-V' }} • {{ activeMember.kendaraans?.[0]?.warna || 'Hitam' }}
                  </span>
                </div>
              </div>
            </div>

          </div>

        </div>

      </div>

    </main>

  </div>
</template>

<script setup lang="ts">
import { useQrCode } from '~/composables/useQrCode'

const { qrLogin, getMembers } = useApi()
const { generateDataUrl } = useQrCode()

const qrInput = ref('QR-MBR-2026-001')
const activeMember = ref<any>(null)
const cardQrUrl = ref<string>('')
const isLoggingIn = ref(false)
const loginError = ref('')

onMounted(async () => {
  // Pre-load default demo member
  loginWithCode('QR-MBR-2026-001')
})

const loginWithCode = async (code: string) => {
  qrInput.value = code
  await handleQrLogin()
}

const handleQrLogin = async () => {
  if (!qrInput.value.trim()) return
  isLoggingIn.value = true
  loginError.value = ''

  try {
    const res: any = await qrLogin(qrInput.value.trim())
    if (res?.data) {
      activeMember.value = res.data
      const qrStr = res.data.qr_code || ('QR-' + res.data.id_member)
      cardQrUrl.value = await generateDataUrl(qrStr)
    }
  } catch (err: any) {
    // Fallback lookup from members list
    const fallbackList = [
      {
        id_member: 'MBR-2026-001',
        nama_member: 'Ahmad Favian',
        no_telp: '0812-9988-7766',
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
        no_telp: '0813-8877-6655',
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
        no_telp: '0857-1122-3344',
        qr_code: 'QR-MBR-2026-003',
        tgl_daftar: '2026-03-15',
        tgl_kadaluarsa: '2026-12-15',
        status_member: 'aktif',
        is_active: true,
        sisa_hari: 73,
        kendaraans: [{ no_plat: 'B 4567 DEF', jenis_kendaraan: 'motor', merk: 'Yamaha NMAX', warna: 'Abu-abu Matte' }]
      }
    ]

    const q = qrInput.value.toUpperCase().trim()
    const found = fallbackList.find(m =>
      m.qr_code.toUpperCase() === q ||
      m.id_member.toUpperCase() === q ||
      (m.kendaraans[0]?.no_plat || '').toUpperCase() === q
    )

    if (found) {
      activeMember.value = found
      cardQrUrl.value = await generateDataUrl(found.qr_code)
    } else {
      loginError.value = 'Kode QR Member atau Plat Nomor tidak ditemukan. Pastikan Anda telah terdaftar.'
    }
  } finally {
    isLoggingIn.value = false
  }
}

const logoutMember = () => {
  activeMember.value = null
  cardQrUrl.value = ''
  qrInput.value = ''
}

const printCard = () => {
  window.print()
}
</script>
