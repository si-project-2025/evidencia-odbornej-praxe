import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '@/pages/HomePage.vue'
import LoginPage from '@/pages/LoginPage.vue'
import RegistrationPage from '@/pages/RegistrationPage.vue'
import ForgotPasswordPage from '@/pages/ForgotPasswordPage.vue'
import ResetPasswordPage from '@/pages/ResetPasswordPage.vue'
import SetPasswordPage from '@/pages/SetPasswordPage.vue'
import InternshipsPage from '@/pages/InternshipsPage.vue'
import InternshipDetailPage from '@/pages/InternshipDetailPage.vue'
import { useUserStore } from '@/stores/user.ts'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomePage,
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginPage,
  },
  {
    path: '/registration',
    name: 'Registration',
    component: RegistrationPage,
  },
  {
    path: '/forgot-password',
    name: 'Forgot_Password',
    component: ForgotPasswordPage,
  },
  {
    path: '/reset-password',
    name: 'Reset_Password',
    component: ResetPasswordPage,
  },
  {
    path: '/set-password',
    name: 'SetPassword',
    component: SetPasswordPage,
  },
  {
    path: '/internships',
    name: 'StudentInternships',
    component: InternshipsPage,
    meta: { requiresAuth: true },
  },
  {
    path: '/internships/:id',
    name: 'internship-detail',
    component: InternshipDetailPage,
    meta: { requiresAuth: true },
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

router.beforeEach((to, from, next) => {
  const userStore = useUserStore()
  userStore.loadUser()
  const isAuthenticated = !!userStore.token
  const isGarant = userStore.user?.role === 'garant'

  if (to.meta.requiresAuth && !isAuthenticated) {
    return next({ name: 'Login' })
  }

  if (
    isAuthenticated &&
    ['Login', 'Registration'].includes(to.name as string) &&
    !(to.name === 'Registration' && isGarant)
  ) {
    return next({ name: 'Home' })
  }

  next()
})

export default router
