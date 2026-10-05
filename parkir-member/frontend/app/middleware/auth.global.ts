export default defineNuxtRouteMiddleware((to, from) => {
  // Routes accessible without login:
  // 1. '/' (Portal Login Petugas & Administrator)
  // 2. '/kios-gatein' (Kios Mandiri Jalur Masuk Khusus Member)
  const isPublicRoute = to.path === '/' || to.path === '/kios-gatein'

  if (isPublicRoute) {
    return
  }

  const { currentUser } = useApi()

  // Protect all internal operational routes: must be logged in
  if (!currentUser.value || !currentUser.value.id_petugas) {
    return navigateTo({
      path: '/',
      query: {
        alert: 'need_login',
        target: to.path
      }
    })
  }

  // Strict RBAC: Halaman Pengaturan Member (/member) & Kelola Akun (/kelola-akun) HANYA BISA DIAKSES OLEH ADMIN
  if (to.path.startsWith('/member') || to.path.startsWith('/kelola-akun')) {
    const role = (currentUser.value.peran || '').toLowerCase()
    const username = (currentUser.value.username || currentUser.value.id_petugas || '').toLowerCase()
    const isAdmin = role === 'admin' || username.includes('adm')

    if (!isAdmin) {
      return navigateTo({
        path: '/',
        query: {
          alert: 'admin_only',
          target: to.path
        }
      })
    }
  }
})
