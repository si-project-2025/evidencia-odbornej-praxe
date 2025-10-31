import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '@/pages/HomePage.vue'
import LoginPage from '@/pages/LoginPage.vue'
import RegistrationPage from '@/pages/RegistrationPage.vue'
import ForgotPasswordPage from '@/pages/ForgotPasswordPage.vue'
import ResetPasswordPage from '@/pages/ResetPasswordPage.vue'
import SetPasswordPage from '@/pages/SetPasswordPage.vue'
import StudentInternshipsPage from '@/pages/StudentInternshipsPage.vue'
import InternshipDetailPage from '@/pages/InternshipDetailPage.vue'
import InternshipCreatePage from '@/pages/InternshipCreatePage.vue'

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
  },
  {
    path: '/internships/:id',
    name: 'internship-detail',
    component: InternshipDetailPage,
  },
  {
    path: '/internships/create',
    name: 'InternshipCreate',
    component: InternshipCreatePage,
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default router
