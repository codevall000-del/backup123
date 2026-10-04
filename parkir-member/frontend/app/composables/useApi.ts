export const useApi = () => {
  const config = useRuntimeConfig()
  const apiBase = config.public.apiBase || 'http://localhost:8000/api'
  const isBackendOnline = useState<boolean>('isBackendOnline', () => true)

  // Use reactive cookie so login state is shared and persists
  const currentUser = useCookie<any>('sip_current_user', {
    default: () => null,
    watch: true
  })

  const isLoggedIn = computed(() => Boolean(currentUser.value && currentUser.value.id_petugas))

  const logout = () => {
    currentUser.value = null
    const router = useRouter()
    router.push('/')
  }

  const fetchWithFallback = async (endpoint: string, options: any = {}) => {
    try {
      const res = await $fetch(`${apiBase}${endpoint}`, {
        ...options,
        headers: {
          'Accept': 'application/json',
          ...(options.headers || {})
        }
      })
      isBackendOnline.value = true
      return res
    } catch (err: any) {
      console.warn(`[API] Could not connect to backend endpoint ${endpoint}:`, err?.message || err)
      isBackendOnline.value = false
      throw err
    }
  }

  // Auth
  const login = async (credentials: { username: string; password: string; pos_aktif?: string }) => {
    try {
      const res: any = await fetchWithFallback('/auth/login', {
        method: 'POST',
        body: credentials
      })
      if (res?.data?.petugas) {
        currentUser.value = res.data.petugas
      }
      return res
    } catch (err) {
      // Fallback local mock login for seamless demo
      let role = 'operator'
      let name = 'Petugas Operasional'
      const u = credentials.username.toLowerCase()
      if (u.includes('adm') || u.includes('supriadi') || u === 'admin') {
        role = 'admin'
        name = 'Pak Supriadi (ADM-01)'
      } else if (u.includes('ksr') || u.includes('siti') || u === 'kasir') {
        role = 'kasir'
        name = 'Siti Nurhaliza (KSR-01)'
      } else if (u.includes('sec') || u.includes('joko') || u === 'satpam') {
        role = 'satpam'
        name = 'Joko Prasetyo (SEC-01)'
      } else {
        name = 'Budi Santoso (OPR-01)'
      }

      const userData = {
        id_petugas: credentials.username.toUpperCase(),
        nama_petugas: name,
        username: credentials.username,
        peran: role,
        pos_aktif: credentials.pos_aktif || (role === 'admin' ? 'Pusat Administrasi' : (role === 'kasir' ? 'Loket Kasir' : 'Pos Gerbang Keluar'))
      }

      currentUser.value = userData

      return {
        success: true,
        message: 'Login demo berhasil (Mode Offline Simulasi).',
        data: { petugas: userData }
      }
    }
  }

  const verifyPin = async (pin: string) => {
    try {
      return await fetchWithFallback('/auth/verify-pin', {
        method: 'POST',
        body: { pin }
      })
    } catch {
      // Fallback
      if (pin === '123456' || pin === '998877') {
        return {
          success: true,
          data: {
            id_petugas: 'SEC-01',
            nama_petugas: 'Joko Prasetyo (Satpam)',
            peran: 'satpam'
          }
        }
      }
      return { success: false, message: 'PIN tidak valid' }
    }
  }

  // Members
  const getMembers = async (search = '', status = 'semua') => {
    try {
      const res: any = await fetchWithFallback(`/members?search=${encodeURIComponent(search)}&status=${encodeURIComponent(status)}`)
      return res.data
    } catch {
      return []
    }
  }

  const createMember = async (payload: any) => {
    return await fetchWithFallback('/members', {
      method: 'POST',
      body: payload
    })
  }

  const updateMember = async (id: string, payload: any) => {
    return await fetchWithFallback(`/members/${id}`, {
      method: 'PUT',
      body: payload
    })
  }

  const toggleMemberStatus = async (id: string, status?: string) => {
    return await fetchWithFallback(`/members/${id}/status`, {
      method: 'PATCH',
      body: { status }
    })
  }

  const deleteMember = async (id: string) => {
    return await fetchWithFallback(`/members/${id}`, {
      method: 'DELETE'
    })
  }

  const qrLogin = async (qrCode: string) => {
    return await fetchWithFallback('/members/qr-login', {
      method: 'POST',
      body: { qr_code: qrCode }
    })
  }

  // Payments
  const checkMemberSubscription = async (id: string) => {
    return await fetchWithFallback(`/pembayaran/check-member/${id}`)
  }

  const processPayment = async (payload: any) => {
    return await fetchWithFallback('/pembayaran', {
      method: 'POST',
      body: payload
    })
  }

  const getPaymentReceipt = async (id: string) => {
    return await fetchWithFallback(`/pembayaran/receipt/${id}`)
  }

  const gateCheckIn = async (identifier: string, gerbang = 'GATE-IN 01', lprPlate = '') => {
    return await fetchWithFallback('/gate/check-in', {
      method: 'POST',
      body: { identifier, gerbang_masuk: gerbang, lpr_plate: lprPlate }
    })
  }

  const gateTiketMasuk = async (jenisKendaraan = 'mobil', gerbang = 'GATE-IN 01', lprPlate = '') => {
    return await fetchWithFallback('/gate/tiket-masuk', {
      method: 'POST',
      body: { jenis_kendaraan: jenisKendaraan, gerbang_masuk: gerbang, lpr_plate: lprPlate }
    })
  }

  const gateOverrideIn = async (pin: string, alasan: string, noPlat = '', gerbang = 'GATE-IN 01') => {
    return await fetchWithFallback('/gate/override-in', {
      method: 'POST',
      body: { pin, alasan, no_plat: noPlat, gerbang_masuk: gerbang }
    })
  }

  const gateScanOut = async (identifier: string, lprPlate = '') => {
    return await fetchWithFallback('/gate/scan-out', {
      method: 'POST',
      body: { identifier, lpr_plate: lprPlate }
    })
  }

  const gateCheckOut = async (idParkir: string, gerbang = 'GATE-OUT 01') => {
    return await fetchWithFallback('/gate/check-out', {
      method: 'POST',
      body: { id_parkir: idParkir, gerbang_keluar: gerbang }
    })
  }

  const gateEmergencyOpen = async () => {
    return await fetchWithFallback('/gate/emergency-open', {
      method: 'POST'
    })
  }

  // Dashboard & Reports
  const getDashboardStats = async () => {
    try {
      const res: any = await fetchWithFallback('/dashboard/stats')
      return res.data
    } catch {
      return {
        total_member_aktif: 842,
        growth_member_percent: 12,
        okupansi_slot: { terisi: 185, kapasitas: 250, persen: 74 },
        kunjungan_hari_ini: { masuk: 420, keluar: 395, sedang_parkir: 25 },
        kas_iuran_bulan_ini: { nominal: 42500000, growth_percent: 8.4 }
      }
    }
  }

  const getRevenueReport = async () => {
    try {
      const res: any = await fetchWithFallback('/dashboard/revenue-report')
      return res.data
    } catch {
      return { target_bulanan: 40000000, monthly: [] }
    }
  }

  const getVisitReport = async () => {
    try {
      const res: any = await fetchWithFallback('/dashboard/visit-report')
      return res.data
    } catch {
      return {
        rata_rata_durasi: '3 Jam 42 Menit',
        peak_hour: '07:00–09:00 WIB',
        override_hari_ini: 3,
        kartu_ditolak_hari_ini: 7,
        trend_7_hari: []
      }
    }
  }

  const getRecentActivity = async (gate = 'semua', status = 'semua') => {
    try {
      const res: any = await fetchWithFallback(`/dashboard/recent-activity?gate=${gate}&status=${status}`)
      return res.data
    } catch {
      return []
    }
  }

  return {
    apiBase,
    isBackendOnline,
    currentUser,
    isLoggedIn,
    logout,
    login,
    verifyPin,
    getMembers,
    createMember,
    updateMember,
    toggleMemberStatus,
    deleteMember,
    qrLogin,
    checkMemberSubscription,
    processPayment,
    getPaymentReceipt,
    gateCheckIn,
    gateTiketMasuk,
    gateOverrideIn,
    gateScanOut,
    gateCheckOut,
    gateEmergencyOpen,
    getDashboardStats,
    getRevenueReport,
    getVisitReport,
    getRecentActivity
  }
}
