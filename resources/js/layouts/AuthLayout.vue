<script setup lang="ts">
import AuthSplitLayout from '@/layouts/auth/AuthSimpleLayout.vue';
import AuthCenteredLayout from '@/layouts/auth/AuthCenteredLayout.vue';

interface Props {
    title?: string;
    description?: string;
    variant?: 'split' | 'centered';
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'split'
});
</script>

<template>
    <AuthSplitLayout v-if="variant === 'split'" :title="title" :description="description">
        <slot />
    </AuthSplitLayout>
    <AuthCenteredLayout v-else :title="title" :description="description">
        <slot />
    </AuthCenteredLayout>
</template>
