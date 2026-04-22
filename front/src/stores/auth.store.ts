import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAuthStore = defineStore('auth', () => {
  const isAuthenticated = ref(false)

  function logout() {
    sessionStorage.removeItem('jwt_token')
    isAuthenticated.value = false;
  }

  return { isAuthenticated, logout }
})