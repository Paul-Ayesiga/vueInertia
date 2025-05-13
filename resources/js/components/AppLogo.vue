<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { useSidebar } from '@/components/ui/sidebar';

const { state } = useSidebar();
</script>

<template>
    <div class="flex items-center justify-center">
        <AppLogoIcon
            :class="[
                'transition-all duration-200',
                state === 'collapsed' ? 'size-9' : 'size-13'
            ]"
        />
    </div>
    <div
        class="ml-1 grid flex-1 text-left text-sm"
        :class="{ 'opacity-0': state === 'collapsed' }"
    >
    <!-- <span class="mb-0.5 truncate font-semibold leading-none">SoundStage</span> -->
    </div>
</template>
