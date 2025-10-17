<template>
  <div id="app">
    <RouterView />
    <BaseToast />
  </div>
</template>

<script setup lang="ts">
import { RouterView } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import BaseToast from "@/components/base/Toast.vue";

const authStore = useAuthStore();

// Initialize auth on app start
authStore.initializeAuth();
</script>

<style>
#app {
  min-height: 100vh;
}
</style>
