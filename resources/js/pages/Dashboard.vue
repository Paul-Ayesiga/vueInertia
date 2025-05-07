<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { LucideBarChart, LucideUsers, LucideActivity } from 'lucide-vue-next';
import { Skeleton } from '@/components/ui/skeleton';
import { ref, onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Define props to receive data from the controller
const props = defineProps<{
    userCount: number;
    revenue: number;
    activeSessions: number;
}>();

// Add loading state
const isLoading = ref(true);

// Simulate loading delay (remove this in production)
onMounted(() => {
    setTimeout(() => {
        isLoading.value = false;
    }, 2500);
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <Card hoverable>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <LucideUsers class="h-5 w-5 font-mono" />
                            Users
                        </CardTitle>
                        <CardDescription>Total registered users</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Skeleton v-if="isLoading" class="h-8 w-24" />
                        <div v-else class="text-2xl font-bold">{{ userCount.toLocaleString() }}</div>
                    </CardContent>
                </Card>

                <Card hoverable>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <LucideBarChart class="h-5 w-5" />
                            Revenue
                        </CardTitle>
                        <CardDescription>Monthly revenue</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Skeleton v-if="isLoading" class="h-8 w-24" />
                        <div v-else class="text-2xl font-bold">${{ revenue.toLocaleString() }}</div>
                    </CardContent>
                </Card>

                <Card hoverable>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <LucideActivity class="h-5 w-5" />
                            Activity
                        </CardTitle>
                        <CardDescription>Active sessions</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Skeleton v-if="isLoading" class="h-8 w-24" />
                        <div v-else class="text-2xl font-bold">{{ activeSessions.toLocaleString() }}</div>
                    </CardContent>
                </Card>
            </div>

            <Card hoverable class="flex-1">
                <CardHeader>
                    <CardTitle>Performance Overview</CardTitle>
                    <CardDescription>System metrics for the past 30 days</CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="isLoading" class="h-[300px] w-full flex flex-col gap-4 p-4">
                        <Skeleton class="h-8 w-full" />
                        <Skeleton class="h-[250px] w-full" />
                    </div>
                    <div v-else class="h-[300px] w-full flex items-center justify-center text-muted-foreground">
                        Chart data will be displayed here
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
