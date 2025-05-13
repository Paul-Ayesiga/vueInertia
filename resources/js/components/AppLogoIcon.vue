<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <div class="flex">
        <img
            src="/logo.png"
            alt="Logo"
            :class="className"
            v-bind="$attrs"
        />
        <span class="mt-4 truncate font-semibold leading-none">SoundStage</span>
    </div>
</template>
