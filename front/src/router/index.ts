import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/HomeView.vue'),
    },
    {
      path: '/profil',
      name: 'profil',
      component: () => import('../views/Profil.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/connexion',
      name: 'connexion',
      component: () => import('../views/Connexion.vue'),
    }
  ],
})

router.beforeEach(async (to, from) => {
  const authStore = useAuthStore();
  
  if (
    !authStore.isAuthenticated &&
    to.meta.requiresAuth
  ) {
    return { name: 'connexion' }
  }
})

export default router
