<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { ref } from 'vue';
import { Sparkles, X, Send } from 'lucide-vue-next';
import { Motion, AnimatePresence } from 'motion-v';

const isOpen = ref(false);

const toggleChatbot = () => {
  isOpen.value = !isOpen.value;
};

// Animation variants for the chatbot modal
const chatbotVariants = {
  initial: {
    opacity: 0,
    scale: 0.95,
    y: 20
  },
  animate: {
    opacity: 1,
    scale: 1,
    y: 0,
    transition: {
      type: 'spring',
      stiffness: 300,
      damping: 25
    }
  },
  exit: {
    opacity: 0,
    scale: 0.95,
    y: 20,
    transition: {
      duration: 0.2
    }
  }
};

// Animation for the button
const buttonVariants = {
  tap: { scale: 0.95 }
};
</script>

<template>
  <div class="fixed bottom-6 right-6 z-50 sm:bottom-8 sm:right-8 mx-auto max-w-screen-2xl">
    <!-- Chatbot Modal -->
    <AnimatePresence>
      <Motion
        v-if="isOpen"
        :initial="chatbotVariants.initial"
        :animate="chatbotVariants.animate"
        :exit="chatbotVariants.exit"
        class="absolute bottom-20 right-0 w-[330px] bg-background rounded-lg border shadow-lg sm:mx-auto sm:w-[400px]"
      >
        <div class="flex flex-col h-[450px]">
          <div class="flex items-center justify-between p-4 border-b">
            <h2 class="text-lg font-semibold">Chatbot</h2>
            <Motion :whileTap="{ scale: 0.9 }">
              <Button variant="ghost" size="icon" @click="isOpen = false">
                <span class="sr-only">Close</span>
                <X class="h-4 w-4" />
              </Button>
            </Motion>
          </div>
          <div class="flex-1 overflow-y-auto p-4">
            <!-- Chat messages would go here -->
            <p class="text-center text-muted-foreground">How can I help you today?</p>
          </div>
          <div class="p-4 border-t">
            <div class="flex gap-2">
              <input
                type="text"
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="Type your message..."
              />
              <Motion :whileTap="{ scale: 0.9 }">
                <Button size="icon">
                  <Send class="h-4 w-4" />
                </Button>
              </Motion>
            </div>
          </div>
        </div>
        <!-- Triangle pointer at the bottom -->
        <div class="absolute -bottom-2 right-6 w-4 h-4 bg-background border-r border-b transform rotate-45"></div>
      </Motion>
    </AnimatePresence>

    <!-- Chatbot Button -->
    <Motion :whileTap="buttonVariants.tap">
      <Button
        size="icon"
        class="h-14 w-14 rounded-full shadow-lg bg-sidebar-primary text-sidebar-primary-foreground hover:bg-sidebar-primary/90"
        @click="toggleChatbot"
      >
        <Sparkles class="h-6 w-6" />
      </Button>
    </Motion>
  </div>
</template>

<style scoped>
.scale-95 {
  transform: scale(0.95);
}
.scale-100 {
  transform: scale(1);
}

/* Add custom button styles to ensure the orangeish color is applied */
:deep(.bg-sidebar-primary) {
  background-color: var(--sidebar-primary);
}
:deep(.text-sidebar-primary-foreground) {
  color: var(--sidebar-primary-foreground);
}
:deep(.hover\:bg-sidebar-primary\/90:hover) {
  background-color: color-mix(in srgb, var(--sidebar-primary) 90%, transparent);
}
</style>
