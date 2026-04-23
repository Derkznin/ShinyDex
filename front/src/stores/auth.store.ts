import { defineStore } from 'pinia'
import { ref } from 'vue'
 
export const useAuthStore = defineStore('auth', () => {
  const isAuthenticated = ref(!!sessionStorage.getItem('jwt_token'))
 
  function login(token: string) {
    sessionStorage.setItem('jwt_token', token)
    isAuthenticated.value = true
  }
 
  function logout() {
    sessionStorage.removeItem('jwt_token')
    isAuthenticated.value = false
  }
 
  return { isAuthenticated, login, logout }
})
 