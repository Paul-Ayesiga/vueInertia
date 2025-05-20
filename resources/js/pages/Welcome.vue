<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { useColorMode } from '@vueuse/core';
import { ref, watch, computed } from 'vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { Sun, Moon, Laptop, Menu as MenuIcon, Music2, CalendarDays, Building2 } from 'lucide-vue-next';

interface PageProps {
    auth: {
        user: any;
    };
    popularArtists: Array<{
        id: number;
        name: string;
        profilePhoto: string | null;
        location: string;
        bio: string;
    }>;
    [key: string]: any;
}

const page = usePage<PageProps>();
const colorMode = useColorMode();

// Create a ref to track the selected theme
const selectedTheme = ref(colorMode.value);

// Computed property for theme text
const themeText = computed(() => {
    switch (selectedTheme.value) {
        case 'light': return 'Light';
        case 'dark': return 'Dark';
        case 'auto': return 'System';
        default: return 'System';
    }
});

// Watch for changes to selectedTheme and update colorMode
watch(selectedTheme, (newTheme) => {
    if (newTheme === 'light' || newTheme === 'dark' || newTheme === 'auto') {
        colorMode.value = newTheme;
    }
});

// Watch for external changes to colorMode
watch(colorMode, (newMode) => {
    selectedTheme.value = newMode;
});

// Theme icon component based on current theme
const ThemeIcon = computed(() => {
    switch (selectedTheme.value) {
        case 'light': return Sun;
        case 'dark': return Moon;
        default: return Laptop;
    }
});

defineProps<{
    popularArtists: Array<{
        id: number;
        name: string;
        profilePhoto: string | null;
        location: string;
        bio: string;
    }>;
}>();

</script>

<template>
    <Head title="SoundStage - Discover Live Music">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="bg-gray-900 text-gray-50 dark:bg-gray-900 dark:text-gray-100">
        <div class="relative top-0">
            <div class="absolute inset-0 overflow-hidden">
                    <img src="/home2.jpg" alt="Concert background" class="w-full h-full object-cover opacity-70">
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-gray-900 dark:to-gray-800"></div>
            </div>
            <header class="shadow-md backdrop-blur-lg sticky top-0 z-50 transition-colors duration-300">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                    <div class="flex items-center">
                        <img src="/logo.png" alt="SoundStage Logo" class="h-8 mr-2">
                        <span>SoundStage</span>
                    </div>

                    <!-- Mobile Menu -->
                    <div class="flex md:hidden">
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant="ghost" size="icon" class="text-primary hover:bg-primary/10 dark:text-primary-foreground">
                                    <MenuIcon class="h-5 w-5" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-56">
                                <DropdownMenuItem @select="selectedTheme = 'light'" class="cursor-pointer">
                                    <Sun class="h-4 w-4 text-orange-500 mr-2" />
                                    <span>Light</span>
                                </DropdownMenuItem>
                                <DropdownMenuItem @select="selectedTheme = 'dark'" class="cursor-pointer">
                                    <Moon class="h-4 w-4 text-indigo-500 mr-2" />
                                    <span>Dark</span>
                                </DropdownMenuItem>
                                <DropdownMenuItem @select="selectedTheme = 'auto'" class="cursor-pointer">
                                    <Laptop class="h-4 w-4 text-gray-500 dark:text-gray-400 mr-2" />
                                    <span>System</span>
                                </DropdownMenuItem>

                                <div class="h-px bg-muted my-2" />

                                <template v-if="page.props.auth.user">
                                    <DropdownMenuItem asChild>
                                        <Link :href="route('dashboard')" class="w-full mb-2 bg-gradient-to-br bg-orange-800 font-bold justify-center">
                                            Dashboard
                                        </Link>
                                    </DropdownMenuItem>
                                </template>
                                <template v-else>
                                    <DropdownMenuItem asChild>
                                        <Link :href="route('login')" class="w-full mb-2 bg-gradient-to-br bg-orange-800 font-bold justify-center">
                                            Log in
                                        </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem asChild>
                                        <Link :href="route('register')" class="w-full bg-primary font-bold justify-center">
                                            Register
                                        </Link>
                                    </DropdownMenuItem>
                                </template>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex items-center space-x-4">
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant="outline" size="sm" class="gap-2">
                                    <component :is="ThemeIcon" class="h-4 w-4 text-orange-400" />
                                    <span class="hidden sm:inline-block text-blue-600">{{ themeText }}</span>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-40">
                                <DropdownMenuItem @select="selectedTheme = 'light'" class="cursor-pointer ">
                                    <Sun class="h-4 w-4 text-orange-500 mr-2" />
                                    <span>Light</span>
                                </DropdownMenuItem>
                                <DropdownMenuItem @select="selectedTheme = 'dark'" class="cursor-pointer">
                                    <Moon class="h-4 w-4 text-indigo-500 mr-2" />
                                    <span>Dark</span>
                                </DropdownMenuItem>
                                <DropdownMenuItem @select="selectedTheme = 'auto'" class="cursor-pointer">
                                    <Laptop class="h-4 w-4 text-gray-500 dark:text-gray-400 mr-2" />
                                    <span>System</span>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>

                        <template v-if="page.props.auth.user">
                            <Link
                                :href="route('dashboard')"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow-sm hover:bg-primary/90 h-9 px-4 py-2"
                            >
                                Dashboard
                            </Link>
                        </template>
                        <template v-else>
                            <div class="flex items-center gap-3">
                                <Link
                                    :href="route('login')"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2"
                                >
                                    Log in
                                </Link>
                                <Link
                                    :href="route('register')"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow-sm hover:bg-primary/90 h-9 px-4 py-2"
                                >
                                    Register
                                </Link>
                            </div>
                        </template>
                    </div>
                </div>
            </header>
            <section class="pt-0 pb-16 md:pt-40 md:pb-24  overflow-hidden bg-gray-900 dark:bg-gray-800">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="flex flex-col items-center text-center">
                        <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 max-w-3xl dark:text-gray-100">Discover Live Music Everywhere</h1>
                        <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl dark:text-gray-400">Find concerts, track your favorite artists, and never miss a live show again.</p>
                        <div class="flex flex-col sm:flex-row gap-4 w-full max-w-lg">
                            <div class="relative flex-grow">
                                <input id="location-search" type="text" class="w-full px-6 py-3 rounded-full text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-100" placeholder="Artist, venue, or city">
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            </div>
                            <button class="px-8 py-3 rounded-full bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition shadow-lg flex items-center justify-center group dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                <i class="fas fa-search mr-2 group-hover:animate-pulse"></i>
                                Find Events
                            </button>
                        </div>
                        <div class="mt-8 flex flex-wrap justify-center gap-2">
                            <span class="px-3 py-1 bg-white/10 text-white rounded-full text-sm hover:bg-indigo-600 cursor-pointer transition dark:bg-gray-700 dark:hover:bg-indigo-500">Live Tonight</span>
                            <span class="px-3 py-1 bg-white/10 text-white rounded-full text-sm hover:bg-indigo-600 cursor-pointer transition dark:bg-gray-700 dark:hover:bg-indigo-500">This Weekend</span>
                            <span class="px-3 py-1 bg-white/10 text-white rounded-full text-sm hover:bg-indigo-600 cursor-pointer transition dark:bg-gray-700 dark:hover:bg-indigo-500">Electronic</span>
                            <span class="px-3 py-1 bg-white/10 text-white rounded-full text-sm hover:bg-indigo-600 cursor-pointer transition dark:bg-gray-700 dark:hover:bg-indigo-500">Rock</span>
                            <span class="px-3 py-1 bg-white/10 text-white rounded-full text-sm hover:bg-indigo-600 cursor-pointer transition dark:bg-gray-700 dark:hover:bg-indigo-500">Hip-Hop</span>
                        </div>

                        <div class="mt-16 flex flex-wrap justify-center gap-8">
                            <div class="flex items-center">
                                <span class="text-3xl font-bold text-white dark:text-gray-100">500K+</span>
                                <span class="ml-2 text-gray-400 dark:text-gray-500">Artists</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-3xl font-bold text-white dark:text-gray-100">5M+</span>
                                <span class="ml-2 text-gray-400 dark:text-gray-500">Users</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-3xl font-bold text-white dark:text-gray-100">100K+</span>
                                <span class="ml-2 text-gray-400 dark:text-gray-500">Events</span>
                            </div>
                        </div>

                         <!-- Role-specific Registration Section -->
                        <div class="mt-16 mb-8">
                            <h3 class="text-xl text-white mb-6 dark:text-gray-100">Join Our Community</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-3xl mx-auto">
                                <!-- Artist Registration Card -->
                                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 hover:bg-white/20 transition duration-300 group dark:bg-gray-800/50 dark:border-gray-700/50">
                                    <div class="text-center h-full flex flex-col justify-between">
                                        <div>
                                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-600/10 mb-4 group-hover:scale-110 transition-transform">
                                                <Music2 class="w-6 h-6 text-indigo-400" />
                                            </div>
                                            <h4 class="text-white text-lg font-semibold mb-2 dark:text-gray-100">Artist</h4>
                                            <p class="text-gray-300 text-sm mb-4 dark:text-gray-400">Share your music & connect with fans</p>
                                        </div>
                                        <Link
                                            :href="route('artist.register')"
                                            class="inline-flex items-center justify-center rounded-full text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:pointer-events-none bg-indigo-600 text-white hover:bg-indigo-700 h-9 px-6 py-2 dark:bg-indigo-500 dark:hover:bg-indigo-600"
                                        >
                                            Join as Artist
                                        </Link>
                                    </div>
                                </div>

                                <!-- Event Organizer Registration Card -->
                                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 hover:bg-white/20 transition duration-300 group dark:bg-gray-800/50 dark:border-gray-700/50">
                                    <div class="text-center h-full flex flex-col justify-between">
                                        <div>
                                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-600/10 mb-4 group-hover:scale-110 transition-transform">
                                                <CalendarDays class="w-6 h-6 text-indigo-400" />
                                            </div>
                                            <h4 class="text-white text-lg font-semibold mb-2 dark:text-gray-100">Organizer</h4>
                                            <p class="text-gray-300 text-sm mb-4 dark:text-gray-400">Create & manage live events</p>
                                        </div>
                                        <Link
                                            :href="route('organiser.register')"
                                            class="inline-flex items-center justify-center rounded-full text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:pointer-events-none bg-indigo-600 text-white hover:bg-indigo-700 h-9 px-6 py-2 dark:bg-indigo-500 dark:hover:bg-indigo-600"
                                        >
                                            Join as Organizer
                                        </Link>
                                    </div>
                                </div>

                                <!-- Venue Manager Registration Card -->
                                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 hover:bg-white/20 transition duration-300 group dark:bg-gray-800/50 dark:border-gray-700/50">
                                    <div class="text-center h-full flex flex-col justify-between">
                                        <div>
                                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-600/10 mb-4 group-hover:scale-110 transition-transform">
                                                <Building2 class="w-6 h-6 text-indigo-400" />
                                            </div>
                                            <h4 class="text-white text-lg font-semibold mb-2 dark:text-gray-100">Venue</h4>
                                            <p class="text-gray-300 text-sm mb-4 dark:text-gray-400">List & promote your venue</p>
                                        </div>
                                        <Link
                                            :href="route('venue.register')"
                                            class="inline-flex items-center justify-center rounded-full text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:pointer-events-none bg-indigo-600 text-white hover:bg-indigo-700 h-9 px-6 py-2 dark:bg-indigo-500 dark:hover:bg-indigo-600"
                                        >
                                            Join as Venue
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Testimonials Section -->
        <section class="py-16 md:py-24 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-2 dark:text-gray-100">What Our Users Say</h2>
                    <p class="text-gray-600 dark:text-gray-400">Join thousands of music lovers who've discovered their next favorite show</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Testimonial 1 -->
                    <div class="bg-gray-50 p-6 rounded-xl dark:bg-gray-800">
                        <div class="flex items-center mb-4">
                            <div class="text-indigo-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-6 dark:text-gray-300">"SoundStage has completely changed how I discover concerts. I used to miss my favorite artists when they were in town, but now I get notified immediately!"</p>
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                                <img src="/api/placeholder/100/100" alt="User" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-semibold dark:text-gray-100">Sarah Johnson</h4>
                                <p class="text-gray-500 text-sm dark:text-gray-400">Music Enthusiast</p>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonial 2 -->
                    <div class="bg-gray-50 p-6 rounded-xl dark:bg-gray-800">
                        <div class="flex items-center mb-4">
                            <div class="text-indigo-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-6 dark:text-gray-300">"The mobile app is incredibly user-friendly. I can track all my favorite artists and buy tickets with just a few taps. Couldn't be easier!"</p>
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                                <img src="/api/placeholder/100/100" alt="User" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-semibold dark:text-gray-100">Michael Chen</h4>
                                <p class="text-gray-500 text-sm dark:text-gray-400">Concert Goer</p>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonial 3 -->
                    <div class="bg-gray-50 p-6 rounded-xl dark:bg-gray-800">
                        <div class="flex items-center mb-4">
                            <div class="text-indigo-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-6 dark:text-gray-300">"As a touring musician, SoundStage has been amazing for connecting with fans. The platform makes it so easy to promote our shows and reach new audiences."</p>
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                                <img src="/api/placeholder/100/100" alt="User" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-semibold dark:text-gray-100">Alex Rivers</h4>
                                <p class="text-gray-500 text-sm dark:text-gray-400">Independent Artist</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Concerts Section -->
        <section id="concerts" class="py-16 md:py-24 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-12">
                    <div>
                        <h2 class="text-3xl font-bold mb-2 dark:text-gray-100">Featured Concerts</h2>
                        <p class="text-gray-600 dark:text-gray-400">Upcoming events you won't want to miss</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="#" class="text-indigo-600 font-medium hover:text-indigo-800 flex items-center dark:text-indigo-400 dark:hover:text-indigo-600">
                            View all concerts
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <div class="flex overflow-x-auto pb-4 mb-8 gap-3 scrollbar-hide">
                    <button class="filter-btn px-4 py-2 rounded-full bg-indigo-600 text-white whitespace-nowrap hover:bg-indigo-700 transition dark:bg-indigo-500 dark:hover:bg-indigo-600" data-filter="all">All Genres</button>
                    <button class="filter-btn px-4 py-2 rounded-full bg-gray-100 text-gray-800 whitespace-nowrap hover:bg-gray-200 transition dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600" data-filter="pop">Pop</button>
                    <button class="filter-btn px-4 py-2 rounded-full bg-gray-100 text-gray-800 whitespace-nowrap hover:bg-gray-200 transition dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600" data-filter="rock">Rock</button>
                    <button class="filter-btn px-4 py-2 rounded-full bg-gray-100 text-gray-800 whitespace-nowrap hover:bg-gray-200 transition dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600" data-filter="hiphop">Hip-Hop</button>
                    <button class="filter-btn px-4 py-2 rounded-full bg-gray-100 text-gray-800 whitespace-nowrap hover:bg-gray-200 transition dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600" data-filter="electronic">Electronic</button>
                    <button class="filter-btn px-4 py-2 rounded-full bg-gray-100 text-gray-800 whitespace-nowrap hover:bg-gray-200 transition dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600" data-filter="rnb">R&B</button>
                    <button class="filter-btn px-4 py-2 rounded-full bg-gray-100 text-gray-800 whitespace-nowrap hover:bg-gray-200 transition dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600" data-filter="latin">Latin</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <!-- Concert Card 1 -->
                    <div class="concert-card bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 transition duration-300 card-hover dark:bg-gray-800 dark:border-gray-700" data-genre="pop">
                        <div class="relative">
                            <img src="/api/placeholder/500/300" alt="Concert" class="w-full h-48 object-cover">
                            <div class="absolute top-0 right-0 p-2">
                                <span class="bg-indigo-600 text-white px-2 py-1 rounded-lg text-xs dark:bg-indigo-500">Pop</span>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                <span class="text-white font-semibold dark:text-gray-100">May 15, 2025</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-1 dark:text-gray-100">The Weeknd</h3>
                            <p class="text-gray-600 text-sm mb-3 dark:text-gray-400">Madison Square Garden • New York, NY</p>
                            <div class="flex items-center justify-between">
                                <span class="text-indigo-600 font-medium dark:text-indigo-400">$89.00</span>
                                <button class="px-4 py-2 rounded-full bg-indigo-600 text-white text-sm hover:bg-indigo-700 transition dark:bg-indigo-500 dark:hover:bg-indigo-600">Get Tickets</button>
                            </div>
                        </div>
                    </div>
                    <!-- Concert Card 2 -->
                    <div class="concert-card bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 transition duration-300 card-hover dark:bg-gray-800 dark:border-gray-700" data-genre="pop">
                        <div class="relative">
                            <img src="/api/placeholder/500/300" alt="Concert" class="w-full h-48 object-cover">
                            <div class="absolute top-0 right-0 p-2">
                                <span class="bg-indigo-600 text-white px-2 py-1 rounded-lg text-xs dark:bg-indigo-500">Pop</span>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                <span class="text-white font-semibold dark:text-gray-100">May 20, 2025</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-1 dark:text-gray-100">Dua Lipa</h3>
                            <p class="text-gray-600 text-sm mb-3 dark:text-gray-400">The Forum • Los Angeles, CA</p>
                            <div class="flex items-center justify-between">
                                <span class="text-indigo-600 font-medium dark:text-indigo-400">$75.00</span>
                                <button class="px-4 py-2 rounded-full bg-indigo-600 text-white text-sm hover:bg-indigo-700 transition dark:bg-indigo-500 dark:hover:bg-indigo-600">Get Tickets</button>
                            </div>
                        </div>
                    </div>
                    <!-- Concert Card 3 -->
                    <div class="concert-card bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 transition duration-300 card-hover dark:bg-gray-800 dark:border-gray-700" data-genre="hiphop">
                        <div class="relative">
                            <img src="/api/placeholder/500/300" alt="Concert" class="w-full h-48 object-cover">
                            <div class="absolute top-0 right-0 p-2">
                                <span class="bg-indigo-600 text-white px-2 py-1 rounded-lg text-xs dark:bg-indigo-500">Hip-Hop</span>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                <span class="text-white font-semibold dark:text-gray-100">May 23, 2025</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-1 dark:text-gray-100">Kendrick Lamar</h3>
                            <p class="text-gray-600 text-sm mb-3 dark:text-gray-400">United Center • Chicago, IL</p>
                            <div class="flex items-center justify-between">
                                <span class="text-indigo-600 font-medium dark:text-indigo-400">$95.00</span>
                                <button class="px-4 py-2 rounded-full bg-indigo-600 text-white text-sm hover:bg-indigo-700 transition dark:bg-indigo-500 dark:hover:bg-indigo-600">Get Tickets</button>
                            </div>
                        </div>
                    </div>
                    <!-- Concert Card 4 -->
                    <div class="concert-card bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 transition duration-300 card-hover dark:bg-gray-800 dark:border-gray-700" data-genre="pop">
                        <div class="relative">
                            <img src="/api/placeholder/500/300" alt="Concert" class="w-full h-48 object-cover">
                            <div class="absolute top-0 right-0 p-2">
                                <span class="bg-indigo-600 text-white px-2 py-1 rounded-lg text-xs dark:bg-indigo-500">Pop</span>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                <span class="text-white font-semibold dark:text-gray-100">May 28, 2025</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-1 dark:text-gray-100">Billie Eilish</h3>
                            <p class="text-gray-600 text-sm mb-3 dark:text-gray-400">TD Garden • Boston, MA</p>
                            <div class="flex items-center justify-between">
                                <span class="text-indigo-600 font-medium dark:text-indigo-400">$85.00</span>
                                <button class="px-4 py-2 rounded-full bg-indigo-600 text-white text-sm hover:bg-indigo-700 transition dark:bg-indigo-500 dark:hover:bg-indigo-600">Get Tickets</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-10 text-center">
                    <button class="px-6 py-3 bg-indigo-50 text-indigo-600 rounded-full font-medium hover:bg-indigo-100 transition inline-flex items-center dark:bg-indigo-100 dark:text-indigo-500 dark:hover:bg-indigo-200">
                        Load more concerts
                        <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                </div>
            </div>
        </section>

        <!-- Popular Artists Section -->
        <section id="artists" class="py-16 md:py-24 bg-gray-50 dark:bg-gray-900">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-12">
                    <div>
                        <h2 class="text-3xl font-bold mb-2 dark:text-gray-100">Popular Artists</h2>
                        <p class="text-gray-600 dark:text-gray-400">Follow your favorites and never miss a show</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="#" class="text-indigo-600 font-medium hover:text-indigo-800 flex items-center dark:text-indigo-400 dark:hover:text-indigo-600">
                            Explore all artists
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-6">
                    <Link 
                        v-for="artist in popularArtists" 
                        :key="artist.id" 
                        :href="route('artist.show', artist.id)"
                        class="flex flex-col items-center text-center group"
                    >
                        <div class="relative w-full pb-[100%] mb-4">
                            <div class="absolute inset-0 rounded-full overflow-hidden border-2 border-indigo-600 p-1 dark:border-indigo-500 group-hover:border-indigo-700 dark:group-hover:border-indigo-400 transition-colors">
                                <img 
                                    :src="artist.profilePhoto || '/api/placeholder/200/200'" 
                                    :alt="artist.name" 
                                    class="w-full h-full object-cover"
                                    @error="(e: Event) => {
                                        const target = e.target as HTMLImageElement;
                                        if (target) target.src = '/api/placeholder/200/200';
                                    }"
                                >
                            </div>
                        </div>
                        <h3 class="font-medium text-base mb-1 text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ artist.name }}</h3>
                        <p class="text-gray-500 text-sm dark:text-gray-400">{{ artist.location }}</p>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Venues Section -->
        <section id="venues" class="py-16 md:py-24 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-12">
                    <div>
                        <h2 class="text-3xl font-bold mb-2 dark:text-gray-100">Top Venues</h2>
                        <p class="text-gray-600 dark:text-gray-400">Discover the best music venues around you</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="#" class="text-indigo-600 font-medium hover:text-indigo-800 flex items-center dark:text-indigo-400 dark:hover:text-indigo-600">
                            View all venues
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Venue 1 -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 transition duration-300 card-hover dark:bg-gray-800 dark:border-gray-700">
                        <img src="/api/placeholder/600/400" alt="Venue" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-1 dark:text-gray-100">Madison Square Garden</h3>
                            <div class="flex items-center text-gray-600 mb-3 dark:text-gray-400">
                                <i class="fas fa-map-marker-alt mr-2 text-indigo-600 dark:text-indigo-400"></i>
                                <span>New York, NY</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4 dark:text-gray-400">One of the most famous arenas in the world, hosting major concerts and events.</p>
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <span class="mr-4"><i class="fas fa-calendar-alt mr-1"></i> 15 Upcoming Events</span>
                                <span><i class="fas fa-music mr-1"></i> All Genres</span>
                            </div>
                        </div>
                    </div>
                    <!-- Venue 2 -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 transition duration-300 card-hover dark:bg-gray-800 dark:border-gray-700">
                        <img src="/api/placeholder/600/400" alt="Venue" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-1 dark:text-gray-100">The Forum</h3>
                            <div class="flex items-center text-gray-600 mb-3 dark:text-gray-400">
                                <i class="fas fa-map-marker-alt mr-2 text-indigo-600 dark:text-indigo-400"></i>
                                <span>Los Angeles, CA</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4 dark:text-gray-400">A historic venue known for its unique architecture and amazing acoustics.</p>
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <span class="mr-4"><i class="fas fa-calendar-alt mr-1"></i> 12 Upcoming Events</span>
                                <span><i class="fas fa-music mr-1"></i> All Genres</span>
                            </div>
                        </div>
                    </div>
                    <!-- Venue 3 -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 transition duration-300 card-hover dark:bg-gray-800 dark:border-gray-700">
                        <img src="/api/placeholder/600/400" alt="Venue" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-1 dark:text-gray-100">Red Rocks Amphitheatre</h3>
                            <div class="flex items-center text-gray-600 mb-3 dark:text-gray-400">
                                <i class="fas fa-map-marker-alt mr-2 text-indigo-600 dark:text-indigo-400"></i>
                                <span>Morrison, CO</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4 dark:text-gray-400">A natural rock structure that creates an unparalleled concert experience.</p>
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <span class="mr-4"><i class="fas fa-calendar-alt mr-1"></i> 20 Upcoming Events</span>
                                <span><i class="fas fa-music mr-1"></i> Rock, Indie</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mobile App Section -->
        <section id="app" class="py-16 md:py-24 bg-indigo-50 dark:bg-gray-900">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-16">
                    <div class="w-full lg:w-1/2 order-2 lg:order-1">
                        <h2 class="text-3xl font-bold mb-4 dark:text-gray-100">Take SoundStage With You</h2>
                        <p class="text-lg text-gray-700 mb-6 dark:text-gray-300">Download our app and never miss another show. Get notified when your favorite artists announce new concerts near you.</p>
                        <div class="space-y-4 mb-8">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-indigo-100 rounded-full p-2 dark:bg-indigo-500">
                                    <i class="fas fa-bell text-indigo-600 dark:text-indigo-400"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-lg dark:text-gray-100">Artist Alerts</h3>
                                    <p class="text-gray-600 dark:text-gray-400">Get notified when your favorite artists announce tour dates.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-indigo-100 rounded-full p-2 dark:bg-indigo-500">
                                    <i class="fas fa-ticket-alt text-indigo-600 dark:text-indigo-400"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-lg dark:text-gray-100">Easy Ticketing</h3>
                                    <p class="text-gray-600 dark:text-gray-400">Buy tickets directly in the app with just a few taps.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-indigo-100 rounded-full p-2 dark:bg-indigo-500">
                                    <i class="fas fa-map-marked-alt text-indigo-600 dark:text-indigo-400"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-lg dark:text-gray-100">Local Discoveries</h3>
                                    <p class="text-gray-600 dark:text-gray-400">Find concerts near you based on your location and preferences.</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-4">
                            <a href="#" class="flex items-center px-6 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">
                                <i class="fab fa-apple text-2xl mr-3"></i>
                                <div>
                                    <div class="text-xs">Download on the</div>
                                    <div class="font-medium">App Store</div>
                                </div>
                            </a>
                            <a href="#" class="flex items-center px-6 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">
                                <i class="fab fa-google-play text-2xl mr-3"></i>
                                <div>
                                    <div class="text-xs">Get it on</div>
                                    <div class="font-medium">Google Play</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/2 order-1 lg:order-2 flex justify-center">
                        <div class="relative w-64 md:w-80">
                            <div class="absolute -inset-4 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl blur-lg opacity-50"></div>
                            <img src="/api/placeholder/400/800" alt="Mobile App Screenshot" class="relative z-10 rounded-3xl shadow-xl border-8 border-white w-full">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter Section -->
        <section class="py-16 bg-gray-50 text-white dark:bg-gray-800">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto text-center">
                    <h2 class="text-3xl font-bold mb-6 dark:text-gray-100">Stay in the Loop</h2>
                    <p class="text-gray-300 mb-8 dark:text-gray-400">Subscribe to our newsletter for the latest concert announcements, exclusive presale codes, and special offers.</p>
                    <form class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto">
                        <input type="email" class="flex-grow px-5 py-3 rounded-full text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-100" placeholder="Your email address">
                        <button type="submit" class="px-8 py-3 rounded-full bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition shadow-lg dark:bg-indigo-500 dark:hover:bg-indigo-600">Subscribe</button>
                    </form>
                </div>
        </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8 dark:bg-gray-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
                <!-- Brand -->
                <div class="lg:col-span-2">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-music text-indigo-500 text-2xl mr-2 dark:text-indigo-400"></i>
                        <span class="text-xl font-bold text-white dark:text-gray-100">SoundStage</span>
                    </div>
                    <p class="text-gray-400 mb-6 dark:text-gray-500">Connecting music lovers with live performances worldwide. Find concerts, track artists, and discover new venues all in one place.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition dark:text-gray-500 dark:hover:text-gray-100">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition dark:text-gray-500 dark:hover:text-gray-100">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition dark:text-gray-500 dark:hover:text-gray-100">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition dark:text-gray-500 dark:hover:text-gray-100">
                            <i class="fab fa-youtube text-lg"></i>
                        </a>
                    </div>
                </div>
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 dark:text-gray-100">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition dark:text-gray-500 dark:hover:text-gray-100">Home</a></li>
                        <li><a href="#concerts" class="text-gray-400 hover:text-white transition dark:text-gray-500 dark:hover:text-gray-100">Concerts</a></li>
                        <li><a href="#artists" class="text-gray-400 hover:text-white transition dark:text-gray-500 dark:hover:text-gray-100">Artists</a></li>
                        <li><a href="#venues" class="text-gray-400 hover:text-white transition dark:text-gray-500 dark:hover:text-gray-100">Venues</a></li>
                        <li><a href="#app" class="text-gray-400 hover:text-white transition dark:text-gray-500 dark:hover:text-gray-100">Mobile App</a></li>
                    </ul>
                </div>
                <!-- Support -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 dark:text-gray-100">Support</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition dark:text-gray-500 dark:hover:text-gray-100">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition dark:text-gray-500 dark:hover:text-gray-100">FAQ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition dark:text-gray-500 dark:hover:text-gray-100">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition dark:text-gray-500 dark:hover:text-gray-100">Ticket Support</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition dark:text-gray-500 dark:hover:text-gray-100">Privacy Policy</a></li>
                    </ul>
                </div>
                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 dark:text-gray-100">Contact</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt text-indigo-500 mt-1 mr-3 dark:text-indigo-400"></i>
                            <span class="text-gray-400 dark:text-gray-500">123 Music Avenue, New York, NY 10001</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone text-indigo-500 mr-3 dark:text-indigo-400"></i>
                            <span class="text-gray-400 dark:text-gray-500">(123) 456-7890</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope text-indigo-500 mr-3 dark:text-indigo-400"></i>
                            <span class="text-gray-400 dark:text-gray-500">info@soundstage.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                    <p class="text-gray-400 text-sm dark:text-gray-500">&copy; 2025 SoundStage. All rights reserved.</p>
                    <div class="mt-4 md:mt-0">
                        <ul class="flex space-x-6">
                            <li><a href="#" class="text-gray-400 hover:text-white text-sm transition dark:text-gray-500 dark:hover:text-gray-100">Terms of Service</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white text-sm transition dark:text-gray-500 dark:hover:text-gray-100">Privacy Policy</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white text-sm transition dark:text-gray-500 dark:hover:text-gray-100">Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<script lang="ts">
window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    if (header) {
        if (window.scrollY > 50) {
            header.classList.add('shadow-md');
        } else {
            header.classList.remove('shadow-md');
        }
    }
});
</script>
