import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '@/pages/HomePage.vue'
import LoginPage from '@/pages/LoginPage.vue'
import RegistrationPage from '@/pages/RegistrationPage.vue'
import ForgotPasswordPage from '@/pages/ForgotPasswordPage.vue'
import ResetPasswordPage from '@/pages/ResetPasswordPage.vue'
import SetPasswordPage from '@/pages/SetPasswordPage.vue'
import StudentInternshipsPage from '@/pages/StudentInternshipsPage.vue'
import { useUserStore } from '@/stores/userStore.ts'

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
    component: StudentInternshipsPage,
    meta: { requiresAuth: true, requiresStudent: true },
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
  const isStudent = userStore.user?.role === 'student'

  if (to.meta.requiresAuth && !isAuthenticated) {
    return next({ name: 'Login' })
  }

  if (to.meta.requiresStudent && !isStudent) {
    return next({ name: 'Home' })
  }

  if (
    isAuthenticated &&
    ['Login', 'Registration'].includes(to.name as string) &&
    !(to.name === 'Registration' && userStore.user?.role === 'garant')
  ) {
    return next({ name: 'Home' })
  }

  next()
})

export default router
