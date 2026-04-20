<script setup lang="ts">
import { useAuthStore } from '@/stores/auth.store'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

function handleLogout() {
  authStore.logout()
  router.push({ name: 'connexion' })
}
</script>

<template>
  <v-app-bar>
    <v-app-bar-title>✨ Shiny Dex</v-app-bar-title>

    <template #append>
      <v-btn :to="{ name: 'home' }">Accueil</v-btn>

      <template v-if="authStore.isAuthenticated">
        <v-btn :to="{ name: 'profil' }">Mon profil</v-btn>
        <v-btn @click="handleLogout">Déconnexion</v-btn>
      </template>

      <template v-else>
        <v-btn :to="{ name: 'connexion' }">Connexion</v-btn>
      </template>
    </template>
  </v-app-bar>
</template>