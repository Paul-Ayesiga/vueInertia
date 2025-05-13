<script setup lang="ts">
import ArtistLayout from '@/layouts/app/ArtistHeaderLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <ArtistLayout :breadcrumbs="breadcrumbs">
        <slot />
    </ArtistLayout>
</template>
