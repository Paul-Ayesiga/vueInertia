<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import { 
    LucideInstagram, 
    LucideTwitter, 
    LucideYoutube, 
    LucideMusic2, 
    LucideCalendar,
    LucideMapPin,
    LucideUsers,
    LucideHeart,
    LucideShare2,
    X
} from 'lucide-vue-next';

interface Props {
    artist: {
        id: number;
        name: string;
        profilePhoto: string;
        coverPhoto: string;
        bio: string;
        location: string;
        genres: string[];
        socialLinks: {
            spotify?: string;
            soundcloud?: string;
            youtube?: string;
            instagram?: string;
            twitter?: string;
        };
        stats: {
            followers: number;
            likes: number;
        };
        isFollowing: boolean;
    };
    upcomingEvents: Array<{
        id: number;
        title: string;
        date: string;
        venue: string;
        location: string;
        status: string;
    }>;
    auth: {
        user: any;
    };
}

const props = defineProps<Props>();
const isFollowing = ref(props.artist.isFollowing);
const followersCount = ref(props.artist.stats.followers);
const isLoading = ref(false);

const toggleFollow = async () => {
    if (isLoading.value) return;

    // Check if user is authenticated
    if (!props.auth.user) {
        toast.error('You need to first log in', {
            description: 'Create an account or login to follow your favorite artists.',
            action: {
                label: 'Login',
                onClick: () => router.visit(route('login'))
            },
            duration: 5000
        });
        return;
    }
    
    // Store the current state
    const previousState = {
        isFollowing: isFollowing.value,
        followersCount: followersCount.value
    };
    
    // Update UI optimistically
    isFollowing.value = !isFollowing.value;
    followersCount.value = isFollowing.value ? followersCount.value + 1 : followersCount.value - 1;
    isLoading.value = true;

    try {
        await router.post(route('artist.follow', props.artist.id), {}, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: (page) => {
                // Update with server response
                isFollowing.value = page.props.artist.isFollowing;
                followersCount.value = page.props.artist.stats.followers;
                
                // Show success toast
                toast.success(
                    isFollowing.value ? 'Successfully followed artist' : 'Successfully unfollowed artist',
                    {
                        description: isFollowing.value 
                            ? `You are now following ${props.artist.name}`
                            : `You have unfollowed ${props.artist.name}`,
                    }
                );
            },
            onError: () => {
                // Revert to previous state on error
                isFollowing.value = previousState.isFollowing;
                followersCount.value = previousState.followersCount;
                
                // Show error toast
                toast.error('Failed to update follow status', {
                    description: 'Please try again later.',
                });
            },
            onFinish: () => {
                isLoading.value = false;
            }
        });
    } catch (error) {
        // Revert to previous state on error
        isFollowing.value = previousState.isFollowing;
        followersCount.value = previousState.followersCount;
        isLoading.value = false;
        
        // Show error toast
        toast.error('Failed to update follow status', {
            description: 'Please try again later.',
        });
    }
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        weekday: 'short',
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
};

const goToWelcome = () => {
    router.visit(route('home'));
};
</script>

<template>
    <Head :title="artist.name" />
    
    <!-- Add Toaster component at the root -->
    <Toaster position="top-right" />
    
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Close Button -->
        <button 
            @click="goToWelcome"
            class="fixed top-4 right-4 z-50 p-2 rounded-full bg-black/50 hover:bg-black/70 text-white transition-colors backdrop-blur-sm"
            title="Close and return to home"
        >
            <X class="w-6 h-6" />
        </button>

        <!-- Cover Photo -->
        <div class="relative h-64 md:h-96">
            <!-- Debug info - can be removed after debugging -->
            <div class="absolute top-0 right-0 bg-black/70 text-white p-2 text-sm z-50">
                Cover Photo Path: {{ artist.coverPhoto || 'No path' }}
            </div>
            
            <img 
                :src="artist['coverPhoto']" 
                :alt="artist.name + ' cover photo'"
                class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        </div>

        <!-- Artist Info -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-10">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 md:p-8">
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Profile Photo -->
                    <div class="flex-shrink-0">
                        <div class="w-32 h-32 md:w-48 md:h-48 rounded-full overflow-hidden border-4 border-white dark:border-gray-800 shadow-lg">
                            <img 
                                :src="artist.profilePhoto || '/images/default-profile.jpg'" 
                                :alt="artist.name"
                                class="w-full h-full object-cover"
                            />
                        </div>
                    </div>

                    <!-- Artist Details -->
                    <div class="flex-grow">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ artist.name }}</h1>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">
                                    <LucideMapPin class="inline-block w-4 h-4 mr-1" />
                                    {{ artist.location }}
                                </p>
                            </div>
                            <div class="flex gap-3">
                                <button 
                                    @click="toggleFollow"
                                    :disabled="isLoading"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors"
                                    :class="[
                                        isFollowing
                                            ? 'bg-gray-100 text-gray-900 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700'
                                            : 'bg-primary text-white hover:bg-primary/90'
                                    ]"
                                >
                                    <LucideHeart
                                        class="w-4 h-4 mr-2"
                                        :class="{ 'fill-current': isFollowing }"
                                    />
                                    {{ isFollowing ? 'Following' : 'Follow' }}
                                </button>
                                <button class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600">
                                    <LucideShare2 class="w-5 h-5" />
                                </button>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="flex gap-6 mt-6">
                            <div class="flex items-center gap-2">
                                <LucideUsers class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                                <span class="text-gray-900 dark:text-white font-medium">{{ followersCount }} Followers</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <LucideHeart class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                                <span class="text-gray-900 dark:text-white font-medium">{{ artist.stats.likes }}</span>
                                <span class="text-gray-600 dark:text-gray-400">Likes</span>
                            </div>
                        </div>

                        <!-- Social Links -->
                        <div class="flex gap-4 mt-6">
                            <a v-if="artist.socialLinks.instagram" :href="artist.socialLinks.instagram" target="_blank" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                                <LucideInstagram class="w-6 h-6" />
                            </a>
                            <a v-if="artist.socialLinks.twitter" :href="artist.socialLinks.twitter" target="_blank" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                                <LucideTwitter class="w-6 h-6" />
                            </a>
                            <a v-if="artist.socialLinks.youtube" :href="artist.socialLinks.youtube" target="_blank" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                                <LucideYoutube class="w-6 h-6" />
                            </a>
                            <a v-if="artist.socialLinks.spotify" :href="artist.socialLinks.spotify" target="_blank" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                                <LucideMusic2 class="w-6 h-6" />
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Bio -->
                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">About</h2>
                    <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ artist.bio }}</p>
                </div>

                <!-- Genres -->
                <div class="mt-6">
                    <div class="flex flex-wrap gap-2">
                        <span 
                            v-for="genre in artist.genres" 
                            :key="genre"
                            class="px-3 py-1 rounded-full text-sm bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200"
                        >
                            {{ genre }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 md:p-8">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Upcoming Events</h2>
                <div class="space-y-4">
                    <div 
                        v-for="event in upcomingEvents" 
                        :key="event.id"
                        class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-700"
                    >
                        <div class="flex-shrink-0">
                            <LucideCalendar class="w-8 h-8 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div class="flex-grow">
                            <h3 class="font-medium text-gray-900 dark:text-white">{{ event.title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ formatDate(event.date) }} • {{ event.venue }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-500">
                                <LucideMapPin class="inline-block w-4 h-4 mr-1" />
                                {{ event.location }}
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <span 
                                class="px-3 py-1 rounded-full text-sm"
                                :class="{
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': event.status === 'confirmed',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': event.status === 'pending'
                                }"
                            >
                                {{ event.status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-16">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- About Section -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">About {{ artist.name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">{{ artist.bio }}</p>
                        <div class="flex gap-4">
                            <a v-if="artist.socialLinks.instagram" :href="artist.socialLinks.instagram" target="_blank" 
                               class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                <LucideInstagram class="w-6 h-6" />
                            </a>
                            <a v-if="artist.socialLinks.twitter" :href="artist.socialLinks.twitter" target="_blank"
                               class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                <LucideTwitter class="w-6 h-6" />
                            </a>
                            <a v-if="artist.socialLinks.youtube" :href="artist.socialLinks.youtube" target="_blank"
                               class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                <LucideYoutube class="w-6 h-6" />
                            </a>
                            <a v-if="artist.socialLinks.spotify" :href="artist.socialLinks.spotify" target="_blank"
                               class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                <LucideMusic2 class="w-6 h-6" />
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="#events" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    Upcoming Events
                                </a>
                            </li>
                            <li>
                                <a href="#about" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    About
                                </a>
                            </li>
                            <li>
                                <a href="#contact" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    Contact
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact</h3>
                        <div class="space-y-2">
                            <p class="text-gray-600 dark:text-gray-400">
                                <LucideMapPin class="inline-block w-4 h-4 mr-2" />
                                {{ artist.location }}
                            </p>
                            <button 
                                @click="toggleFollow"
                                :disabled="isLoading"
                                class="w-full px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                :class="[
                                    isFollowing
                                        ? 'bg-gray-100 text-gray-900 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700'
                                        : 'bg-primary text-white hover:bg-primary/90'
                                ]"
                            >
                                <LucideHeart
                                    class="w-4 h-4 mr-2"
                                    :class="{ 'fill-current': isFollowing }"
                                />
                                {{ isFollowing ? 'Following' : 'Follow Artist' }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bottom Bar -->
                <div class="border-t border-gray-200 dark:border-gray-700 mt-8 pt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            © {{ new Date().getFullYear() }} {{ artist.name }}. All rights reserved.
                        </p>
                        <div class="flex gap-4 mt-4 md:mt-0">
                            <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors">
                                Privacy Policy
                            </a>
                            <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors">
                                Terms of Service
                            </a>
                            <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors">
                                Cookie Policy
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template> 