<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ChatbotButton from '@/components/ChatbotButton.vue';

// Check if current route is an auth route
const page = usePage();
const isAuthPage = computed(() => {
  // Get the current component name from Inertia
  const currentRoute = page.component.value;

  // Debug check to prevent errors
  if (!currentRoute) {
    return false;
  }

  return currentRoute.startsWith('Auth/') ||
         ['Login', 'Register', 'ForgotPassword', 'ResetPassword'].includes(currentRoute);
});
</script>

<template>
  <ChatbotButton v-if="!isAuthPage" />
</template>
