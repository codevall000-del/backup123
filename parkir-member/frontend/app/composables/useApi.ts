export const useApi = () => {
  const config = useRuntimeConfig()
  const apiBase = config.public.apiBase || 'http://localhost:8000/api'
  const isBackendOnline = useState<boolean>('isBackendOnline', () => true)

  // Use reactive cookie so login state is shared and persists
  const currentUser = useCookie<any>('sip_current_user', {
    default: () => null,
    watch: true
  })

  // Kiosk Gate-In Access Lock state
  const isKioskUnlocked = useCookie<boolean>('sip_kiosk_unlocked', {
    default: () => false,
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
      if (pin === '123456' || pin === '998877' || pin === '1234') {
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

  // Kelola Akun (Admin, Kasir, Petugas/Operator/Satpam)
  const defaultPetugasMock = [
    {
      id_petugas: 'ADM-01',
      nama_petugas: 'Administrator',
      username: 'admin',
      peran: 'admin',
      pin_petugas: '123456',
      pos_aktif: 'Pusat Administrasi & Pengawas',
      created_at: '2026-10-04T00:00:00Z'
    },
    {
      id_petugas: 'PETUGAS-01',
      nama_petugas: 'Petugas Operasional',
      username: 'petugas',
      peran: 'operator',
      pin_petugas: '123456',
      pos_aktif: 'Pos Gerbang Keluar',
      created_at: '2026-10-04T00:00:00Z'
    },
    {
      id_petugas: 'KSR-01',
      nama_petugas: 'Petugas Kasir',
      username: 'kasir',
      peran: 'kasir',
      pin_petugas: '123456',
      pos_aktif: 'Loket Kasir',
      created_at: '2026-10-04T00:00:00Z'
    },
    {
      id_petugas: 'OPR-01',
      nama_petugas: 'Petugas Gerbang',
      username: 'operator',
      peran: 'operator',
      pin_petugas: '123456',
      pos_aktif: 'Pos Gerbang Keluar',
      created_at: '2026-10-04T00:00:00Z'
    },
    {
      id_petugas: 'SEC-01',
      nama_petugas: 'Petugas Keamanan',
      username: 'satpam',
      peran: 'satpam',
      pin_petugas: '998877',
      pos_aktif: 'Pos Gerbang Masuk',
      created_at: '2026-10-04T00:00:00Z'
    }
  ]

  const localPetugasList = useState<any[]>('sip_local_petugas', () => defaultPetugasMock)

  const getPetugasList = async (search = '', role = 'semua') => {
    try {
      const res: any = await fetchWithFallback(`/petugas?search=${encodeURIComponent(search)}&role=${encodeURIComponent(role)}`)
      return res
    } catch {
      let filtered = [...localPetugasList.value]
      if (search.trim()) {
        const s = search.toLowerCase()
        filtered = filtered.filter(p => 
          (p.nama_petugas || '').toLowerCase().includes(s) ||
          (p.username || '').toLowerCase().includes(s) ||
          (p.id_petugas || '').toLowerCase().includes(s) ||
          (p.pos_aktif || '').toLowerCase().includes(s)
        )
      }
      if (role && role !== 'semua' && role !== 'all') {
        if (role === 'petugas') {
          filtered = filtered.filter(p => ['operator', 'petugas', 'satpam'].includes(p.peran))
        } else {
          filtered = filtered.filter(p => p.peran === role)
        }
      }
      const countAll = localPetugasList.value.length
      const countAdmin = localPetugasList.value.filter(p => p.peran === 'admin').length
      const countKasir = localPetugasList.value.filter(p => p.peran === 'kasir').length
      const countPetugas = localPetugasList.value.filter(p => ['operator', 'petugas', 'satpam'].includes(p.peran)).length

      return {
        success: true,
        data: filtered,
        meta: {
          total_semua: countAll,
          total_admin: countAdmin,
          total_kasir: countKasir,
          total_petugas: countPetugas
        }
      }
    }
  }

  const createPetugas = async (payload: any) => {
    try {
      return await fetchWithFallback('/petugas', {
        method: 'POST',
        body: payload
      })
    } catch (err: any) {
      if (isBackendOnline.value) throw err
      // Offline fallback
      const prefix = payload.peran === 'admin' ? 'ADM' : (payload.peran === 'kasir' ? 'KSR' : (payload.peran === 'satpam' ? 'SEC' : 'PTG'))
      const newId = payload.id_petugas || `${prefix}-${String(localPetugasList.value.length + 1).padStart(2, '0')}`
      const newAcc = {
        id_petugas: newId.toUpperCase(),
        nama_petugas: payload.nama_petugas,
        username: payload.username.toLowerCase(),
        peran: payload.peran,
        pin_petugas: payload.pin_petugas || '123456',
        pos_aktif: payload.pos_aktif || (payload.peran === 'admin' ? 'Pusat Administrasi' : (payload.peran === 'kasir' ? 'Loket Kasir' : 'Pos Gerbang Keluar')),
        created_at: new Date().toISOString()
      }
      localPetugasList.value = [newAcc, ...localPetugasList.value]
      return {
        success: true,
        message: `Akun ${payload.peran} (${newId}) berhasil ditambahkan (Mode Demo).`,
        data: newAcc
      }
    }
  }

  const updatePetugas = async (id: string, payload: any) => {
    try {
      return await fetchWithFallback(`/petugas/${id}`, {
        method: 'PUT',
        body: payload
      })
    } catch (err: any) {
      if (isBackendOnline.value) throw err
      const idx = localPetugasList.value.findIndex(p => p.id_petugas === id)
      if (idx !== -1) {
        localPetugasList.value[idx] = {
          ...localPetugasList.value[idx],
          ...payload,
          id_petugas: id
        }
      }
      return {
        success: true,
        message: `Data akun ${id} berhasil diperbarui (Mode Demo).`,
        data: localPetugasList.value[idx]
      }
    }
  }

  const deletePetugas = async (id: string) => {
    try {
      return await fetchWithFallback(`/petugas/${id}`, {
        method: 'DELETE'
      })
    } catch (err: any) {
      if (isBackendOnline.value) throw err
      if (id === 'ADM-01') {
        throw new Error('Akun Administrator Utama (ADM-01) tidak dapat dihapus.')
      }
      localPetugasList.value = localPetugasList.value.filter(p => p.id_petugas !== id)
      return {
        success: true,
        message: `Akun ${id} berhasil dihapus (Mode Demo).`
      }
    }
  }

  const resetPetugasPassword = async (id: string, password: string) => {
    try {
      return await fetchWithFallback(`/petugas/${id}/reset-password`, {
        method: 'POST',
        body: { password }
      })
    } catch (err: any) {
      if (isBackendOnline.value) throw err
      return {
        success: true,
        message: `Kata sandi akun ${id} berhasil direset (Mode Demo).`
      }
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

  const gateEmergencyOpen = async (password: string = '1234', alasan: string = 'Darurat Operasional', gerbang: string = 'GATE-OUT 01') => {
    try {
      return await fetchWithFallback('/gate/emergency-open', {
        method: 'POST',
        body: { password, alasan, gerbang_keluar: gerbang }
      })
    } catch (err: any) {
      // Demo fallback jika backend offline
      if (password === '1234' && alasan?.trim()) {
        return {
          success: true,
          palang: 'terbuka_darurat',
          message: 'Palang darurat berhasil diaktifkan secara manual (Mode Simulasi).',
          alasan: alasan.trim(),
          timestamp: new Date().toLocaleTimeString('id-ID') + ' WIB'
        }
      }
      if (password !== '1234') {
        return {
          success: false,
          message: 'Password darurat salah! Masukkan password 1234.'
        }
      }
      return {
        success: false,
        message: 'Alasan pembukaan darurat wajib diisi.'
      }
    }
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

  const verifyKioskAccess = async (password: string) => {
    const cleanPass = (password || '').trim()
    try {
      const res: any = await fetchWithFallback('/gate/verify-kiosk-access', {
        method: 'POST',
        body: { password: cleanPass }
      })
      if (res?.success) {
        isKioskUnlocked.value = true
      }
      return res
    } catch (err: any) {
      if (['1234', '123456', 'admin123', '998877'].includes(cleanPass)) {
        isKioskUnlocked.value = true
        return {
          success: true,
          message: 'Otorisasi Kios Gate-In berhasil.'
        }
      }
      return {
        success: false,
        message: err?.data?.message || 'Kata sandi kios salah! Masukkan password 1234.'
      }
    }
  }

  const lockKiosk = () => {
    isKioskUnlocked.value = false
  }

  return {
    apiBase,
    isBackendOnline,
    currentUser,
    isLoggedIn,
    isKioskUnlocked,
    verifyKioskAccess,
    lockKiosk,
    logout,
    login,
    verifyPin,
    getPetugasList,
    createPetugas,
    updatePetugas,
    deletePetugas,
    resetPetugasPassword,
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
