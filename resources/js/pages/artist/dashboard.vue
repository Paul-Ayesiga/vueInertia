<script setup lang="ts">
import ArtistLayout from '@/layouts/ArtistLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Progress } from '@/components/ui/progress';
import {
    LucideUsers,
    LucideHeart,
    LucideCalendar,
    LucideShare2,
    LucideMessageCircle,
    LucideBarChart,
    LucideArrowUpRight,
    LucideArrowRight
} from 'lucide-vue-next';
import { Skeleton } from '@/components/ui/skeleton';
import { ref, onMounted } from 'vue';
import { getInitials } from '@/composables/useInitials';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/artist/dashboard',
    },
];

// Define props to receive data from the controller
const props = defineProps<{
    artist: {
        name: string;
        stageName: string;
        profilePhoto: string;
        bio: string;
        country: string;
        region: string;
        address: string;
        nationality: string;
        gender: string;
        phoneNumber: string;
        socialMediaLinks: Array<{
            platform: string;
            url: string;
        }>;
        musicLinks: Array<{
            platform: string;
            url: string;
        }>;
        totalFollowers: number;
        totalLikes: number;
    };
    stats: {
        monthlyListeners: number;
        totalStreams: number;
        engagement: number;
    };
    recentActivity: Array<{
        id: number;
        type: string;
        message: string;
        timestamp: string;
    }>;
    upcomingEvents: Array<{
        id: number;
        title: string;
        date: string;
        venue: string;
        ticketsSold: number;
        totalCapacity: number;
    }>;
}>();

// Add loading state
const isLoading = ref(true);

// Simulate loading delay (remove this in production)
onMounted(() => {
    setTimeout(() => {
        isLoading.value = false;
    }, 0);
});
</script>

<template>
    <Head title="Artist Dashboard" />

    <ArtistLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 md:gap-8 md:p-6">
            <!-- Artist Profile Overview -->
            <div class="flex flex-col gap-4 lg:flex-row">
                <Card class="flex-[2]">
                    <CardHeader>
                        <CardTitle>Profile Overview</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col sm:flex-row items-start gap-4">
                            <Avatar class="h-20 w-20">
                                <AvatarImage v-if="!isLoading && artist.profilePhoto" :src="artist.profilePhoto" :alt="artist.name" />
                                <AvatarFallback v-else class="text-lg">
                                    {{ getInitials(artist.stageName || artist.name) }}
                                </AvatarFallback>
                            </Avatar>
                            <div class="flex-1 w-full">
                                <Skeleton v-if="isLoading" class="h-6 w-32 mb-2" />
                                <div v-else>
                                    <h3 class="text-xl font-semibold mb-1">{{ artist.stageName }}</h3>
                                    <p class="text-sm text-muted-foreground">{{ artist.name }}</p>
                                </div>

                                <Skeleton v-if="isLoading" class="h-4 w-48 mb-4" />
                                <div v-else class="space-y-2 my-4">
                                    <p class="text-sm text-muted-foreground">{{ artist.bio }}</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                        <div class="space-y-1">
                                            <span class="text-muted-foreground block">Location:</span>
                                            <p class="truncate">{{ artist.region }}, {{ artist.country }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <span class="text-muted-foreground block">Nationality:</span>
                                            <p class="truncate">{{ artist.nationality }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <span class="text-muted-foreground block">Gender:</span>
                                            <p class="truncate">{{ artist.gender }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <span class="text-muted-foreground block">Contact:</span>
                                            <p class="truncate">{{ artist.phoneNumber }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <Link :href="route('artist.profile.edit')">
                                        <Button size="sm">Edit Profile</Button>
                                    </Link>
                                    <Button size="sm" variant="outline">Share Profile</Button>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        <div v-if="!isLoading && (artist.socialMediaLinks.length > 0 || artist.musicLinks.length > 0)" class="mt-6 pt-6 border-t">
                            <h4 class="text-sm font-medium mb-4">Platforms & Links</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div v-if="artist.socialMediaLinks.length > 0">
                                    <h5 class="text-sm text-muted-foreground mb-2">Social Media</h5>
                                    <div class="space-y-2">
                                        <a
                                            v-for="link in artist.socialMediaLinks"
                                            :key="link.platform"
                                            :href="link.url"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="flex items-center gap-2 text-sm hover:text-primary transition-colors"
                                        >
                                            <LucideShare2 class="h-4 w-4 shrink-0" />
                                            <span class="truncate">{{ link.platform }}</span>
                                        </a>
                                    </div>
                                </div>
                                <div v-if="artist.musicLinks.length > 0">
                                    <h5 class="text-sm text-muted-foreground mb-2">Music Platforms</h5>
                                    <div class="space-y-2">
                                        <a
                                            v-for="link in artist.musicLinks"
                                            :key="link.platform"
                                            :href="link.url"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="flex items-center gap-2 text-sm hover:text-primary transition-colors"
                                        >
                                            <LucideShare2 class="h-4 w-4 shrink-0" />
                                            <span class="truncate">{{ link.platform }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Quick Stats -->
                <div class="flex-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4 h-full">
                        <Card hoverable class="lg:flex-1">
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2 text-base">
                                    <LucideUsers class="h-4 w-4" />
                                    Followers
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <Skeleton v-if="isLoading" class="h-8 w-20" />
                                <div v-else class="text-2xl font-bold">{{ artist.totalFollowers.toLocaleString() }}</div>
                            </CardContent>
                        </Card>

                        <Card hoverable class="lg:flex-1">
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2 text-base">
                                    <LucideHeart class="h-4 w-4" />
                                    Total Likes
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <Skeleton v-if="isLoading" class="h-8 w-20" />
                                <div v-else class="text-2xl font-bold">{{ artist.totalLikes.toLocaleString() }}</div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Monthly Performance -->
                <Card hoverable class="flex-1">
                    <CardHeader>
                        <CardTitle>Monthly Performance</CardTitle>
                        <CardDescription>Listener and engagement metrics</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div v-if="isLoading">
                                <Skeleton class="h-4 w-24 mb-2" />
                                <Skeleton class="h-2 w-full" />
                            </div>
                            <div v-else>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-muted-foreground">Monthly Listeners</span>
                                    <span class="text-sm font-medium">{{ stats.monthlyListeners.toLocaleString() }}</span>
                                </div>
                                <Progress :value="75" class="h-2" />
                            </div>

                            <div v-if="isLoading">
                                <Skeleton class="h-4 w-24 mb-2" />
                                <Skeleton class="h-2 w-full" />
                            </div>
                            <div v-else>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-muted-foreground">Total Streams</span>
                                    <span class="text-sm font-medium">{{ stats.totalStreams.toLocaleString() }}</span>
                                </div>
                                <Progress :value="85" class="h-2" />
                            </div>

                            <div v-if="isLoading">
                                <Skeleton class="h-4 w-24 mb-2" />
                                <Skeleton class="h-2 w-full" />
                            </div>
                            <div v-else>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-muted-foreground">Engagement Rate</span>
                                    <span class="text-sm font-medium">{{ stats.engagement }}%</span>
                                </div>
                                <Progress :value="stats.engagement" class="h-2" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Recent Activity -->
                <Card hoverable class="flex-1">
                    <CardHeader>
                        <CardTitle>Recent Activity</CardTitle>
                        <CardDescription>Latest interactions and updates</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div v-if="isLoading" v-for="i in 3" :key="i" class="flex items-center gap-4">
                                <Skeleton class="h-8 w-8 rounded-full" />
                                <div class="flex-1">
                                    <Skeleton class="h-4 w-3/4 mb-2" />
                                    <Skeleton class="h-3 w-1/4" />
                                </div>
                            </div>
                            <div v-else v-for="activity in recentActivity" :key="activity.id" class="flex items-start gap-4">
                                <div class="rounded-full p-2 bg-primary/10">
                                    <LucideMessageCircle v-if="activity.type === 'comment'" class="h-4 w-4 text-primary" />
                                    <LucideHeart v-else-if="activity.type === 'like'" class="h-4 w-4 text-primary" />
                                    <LucideShare2 v-else-if="activity.type === 'share'" class="h-4 w-4 text-primary" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm">{{ activity.message }}</p>
                                    <span class="text-xs text-muted-foreground">{{ activity.timestamp }}</span>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Bottom Section -->
            <div class="grid grid-cols-1 gap-4">
                <!-- Upcoming Events -->
                <Card hoverable>
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <div>
                                <CardTitle>Upcoming Events</CardTitle>
                                <CardDescription>Your scheduled performances</CardDescription>
                            </div>
                            <Button variant="ghost" size="sm" class="gap-2">
                                View All
                                <LucideArrowRight class="h-4 w-4" />
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div v-if="isLoading" v-for="i in 3" :key="i" class="flex items-start gap-4">
                                <Skeleton class="h-12 w-12 rounded" />
                                <div class="flex-1">
                                    <Skeleton class="h-4 w-3/4 mb-2" />
                                    <Skeleton class="h-3 w-1/2 mb-2" />
                                    <Skeleton class="h-2 w-full" />
                                </div>
                            </div>
                            <div v-else v-for="event in upcomingEvents" :key="event.id" class="flex items-start gap-4">
                                <div class="flex h-12 w-12 flex-col items-center justify-center rounded border bg-muted">
                                    <span class="text-sm font-medium">{{ new Date(event.date).toLocaleDateString('en-US', { month: 'short' }) }}</span>
                                    <span class="text-xl font-bold">{{ new Date(event.date).getDate() }}</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium">{{ event.title }}</h4>
                                    <p class="text-sm text-muted-foreground mb-2">{{ event.venue }}</p>
                                    <Progress
                                        :value="(event.ticketsSold / event.totalCapacity) * 100"
                                        class="h-2"
                                    />
                                    <p class="text-xs text-muted-foreground mt-1">
                                        {{ event.ticketsSold }} / {{ event.totalCapacity }} tickets sold
                                    </p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </ArtistLayout>
</template>
