<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';


defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="min-h-screen bg-gray-900 flex items-center justify-center p-4 md:p-8">

        <div class="w-full max-w-4xl space-y-6">
            <!-- Header -->
            <div class="space-y-2 text-center">
                <Link :href="route('home')" class="">
                    <AppLogoIcon class="size-14 mb-15 fill-current text-black dark:text-white " />
                </Link>
                <h1 class="text-2xl font-semibold tracking-tight" v-if="title">
                    {{ title }}
                </h1>
                <p class="text-sm text-muted-foreground" v-if="description">
                    {{ description }}
                </p>
            </div>

            <!-- Content -->
            <div class="w-full bg-card rounded-lg border p-6 md:p-8">
                <slot />
            </div>
        </div>
    </div>
</template>
