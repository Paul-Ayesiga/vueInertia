<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import ArtistMenuContent from '@/components/ArtistMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import type { BreadcrumbItem, NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Menu, Search } from 'lucide-vue-next';
import { computed } from 'vue';
import { useMagicKeys } from '@vueuse/core';
import { ref, watch } from 'vue';
import {
  CommandDialog,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
  CommandSeparator,
} from '@/components/ui/command';

import { useColorMode } from '@vueuse/core';
import { ToggleGroup, ToggleGroupItem } from '@/components/ui/toggle-group';

interface Props {
    breadcrumbs?: BreadcrumbItem[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
const auth = computed(() => page.props.auth);

const isCurrentRoute = computed(() => (url: string) => page.url === url);

const activeItemStyles = computed(
    () => (url: string) => (isCurrentRoute.value(url) ? 'text-white bg-primary dark:bg-primary dark:text-white' : ''),
);

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/artist/dashboard',
        icon: LayoutGrid,
    },
];

const rightNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];

const open = ref(false);

const { Meta_J, Ctrl_J } = useMagicKeys({
  passive: false,
  onEventFired(e) {
    if (e.key === 'j' && (e.metaKey || e.ctrlKey))
      e.preventDefault();
  },
});

watch([Meta_J, Ctrl_J], (v) => {
  if (v[0] || v[1])
    handleOpenChange();
});

function handleOpenChange() {
  open.value = !open.value;
}


const colorMode = useColorMode();

// Create a ref to track the selected theme
const selectedTheme = ref(colorMode.value);

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

</script>

<template>
    <div>
        <div class="border-b border-sidebar-border/80 sticky top-0 z-10 backdrop-blur-xl">
            <div class="mx-auto flex h-16 items-center px-4 md:max-w-7xl">
                <!-- Mobile Menu -->
                <div class="lg:hidden">
                    <Sheet>
                        <SheetTrigger :as-child="true">
                            <Button variant="ghost" size="icon" class="mr-2 h-9 w-9">
                                <Menu class="h-5 w-5" />
                            </Button>
                        </SheetTrigger>
                        <SheetContent side="left" class="w-[300px] p-6">
                            <SheetTitle class="sr-only">Navigation Menu</SheetTitle>
                            <SheetHeader class="flex justify-start text-left">
                                <AppLogoIcon class="size-13 fill-current text-black dark:text-white" />
                            </SheetHeader>
                            <div class="flex h-full flex-1 flex-col justify-between space-y-4 py-6">
                                <nav class="-mx-3 space-y-1">
                                    <Link
                                        v-for="item in mainNavItems"
                                        :key="item.title"
                                        :href="item.href"
                                        class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent"
                                        :class="activeItemStyles(item.href)"
                                    >
                                        <component v-if="item.icon" :is="item.icon" class="h-5 w-5" />
                                        {{ item.title }}
                                    </Link>
                                </nav>
                                <div class="flex flex-col space-y-4">
                                    <a
                                        v-for="item in rightNavItems"
                                        :key="item.title"
                                        :href="item.href"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="flex items-center space-x-2 text-sm font-medium"
                                    >
                                        <component v-if="item.icon" :is="item.icon" class="h-5 w-5" />
                                        <span>{{ item.title }}</span>
                                    </a>
                                </div>
                            </div>
                        </SheetContent>
                    </Sheet>
                </div>

                <Link :href="route('artist.dashboard')" class="flex items-center gap-x-2">
                    <AppLogo />
                </Link>

                <!-- Desktop Menu -->
                <div class="hidden h-full lg:flex lg:flex-1">
                    <NavigationMenu class="ml-10 flex h-full items-stretch">
                        <NavigationMenuList class="flex h-full items-stretch space-x-2">
                            <NavigationMenuItem v-for="(item, index) in mainNavItems" :key="index" class="relative flex h-full items-center">
                                <Link :href="item.href">
                                    <NavigationMenuLink
                                        :class="[navigationMenuTriggerStyle(), activeItemStyles(item.href), 'h-9 cursor-pointer px-3']"
                                    >
                                        <component v-if="item.icon" :is="item.icon" class="mr-2 h-4 w-4" />
                                        {{ item.title }}
                                    </NavigationMenuLink>
                                </Link>
                                <div
                                    v-if="isCurrentRoute(item.href)"
                                    class="absolute bottom-0 left-0 h-0.5 w-full translate-y-px bg-black dark:bg-white"
                                ></div>
                            </NavigationMenuItem>
                        </NavigationMenuList>
                    </NavigationMenu>
                </div>

                <div class="ml-auto flex items-center space-x-2">
                    <div class="relative flex items-center space-x-1">
                        <TooltipProvider :delay-duration="0">
                            <Tooltip>
                                <TooltipTrigger>
                                    <Button variant="ghost" size="icon" class="group h-9 w-9 cursor-pointer" @click="handleOpenChange">
                                        <Search class="size-5 opacity-80 group-hover:opacity-100" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent>
                                    <p>
                                        <span>click or press</span>
                                        <kbd class="ml-2 pointer-events-none hidden h-5 select-none items-center gap-1 rounded border bg-muted px-1.5 font-mono text-[10px] font-medium text-muted-foreground opacity-100 sm:inline-flex">
                                            <span class="text-xs">⌘</span>J
                                        </kbd>
                                    </p>
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>

                        <!-- command palette -->
                        <CommandDialog v-model:open="open">
                            <CommandInput placeholder="Type a command or search..." />
                            <CommandList>
                                <CommandEmpty>No results found.</CommandEmpty>
                                <CommandGroup heading="Suggestions">
                                    <CommandItem value="calendar">
                                        Calendar
                                    </CommandItem>
                                    <CommandItem value="search-emoji">
                                        Search Emoji
                                    </CommandItem>
                                    <CommandItem value="calculator">
                                        Calculator
                                    </CommandItem>
                                </CommandGroup>
                                <CommandSeparator />
                                <CommandGroup heading="Settings">
                                    <CommandItem value="profile">
                                        Profile
                                    </CommandItem>
                                    <CommandItem value="billing">
                                        Billing
                                    </CommandItem>
                                    <CommandItem value="settings">
                                        Settings
                                    </CommandItem>
                                </CommandGroup>
                            </CommandList>
                        </CommandDialog>

                        <div class="hidden space-x-1 lg:flex">
                            <template v-for="item in rightNavItems" :key="item.title">
                                <TooltipProvider :delay-duration="0">
                                    <Tooltip>
                                        <TooltipTrigger>
                                            <Button variant="ghost" size="icon" as-child class="group h-9 w-9 cursor-pointer">
                                                <a :href="item.href" target="_blank" rel="noopener noreferrer">
                                                    <span class="sr-only">{{ item.title }}</span>
                                                    <component :is="item.icon" class="size-5 opacity-80 group-hover:opacity-100" />
                                                </a>
                                            </Button>
                                        </TooltipTrigger>
                                        <TooltipContent>
                                            <p>{{ item.title }}</p>
                                        </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                            </template>
                        </div>
                    </div>

                    <DropdownMenu>
                        <DropdownMenuTrigger :as-child="true">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="relative size-10 w-auto rounded-full p-1 focus-within:ring-2 focus-within:ring-primary"
                            >
                                <Avatar class="size-8 overflow-hidden rounded-full">
                                    <AvatarImage v-if="auth.user.avatar" :src="auth.user.avatar" :alt="auth.user.name" />
                                    <AvatarFallback class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white">
                                        {{ getInitials(auth.user?.name) }}
                                    </AvatarFallback>
                                </Avatar>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-56">
                            <div class="p-2">
                                <ToggleGroup
                                    type="single"
                                    variant="outline"
                                    size="sm"
                                    class="w-full grid grid-cols-3 gap-1"
                                    v-model="selectedTheme"
                                >
                                    <ToggleGroupItem value="light" class="flex items-center justify-center gap-1">
                                        <Sun class="h-3.5 w-3.5" />
                                        <span class="text-xs">Light</span>
                                    </ToggleGroupItem>
                                    <ToggleGroupItem value="dark" class="flex items-center justify-center gap-1">
                                        <Moon class="h-3.5 w-3.5" />
                                        <span class="text-xs">Dark</span>
                                    </ToggleGroupItem>
                                    <ToggleGroupItem value="auto" class="flex items-center justify-center gap-1">
                                        <Laptop class="h-3.5 w-3.5" />
                                        <span class="text-xs">System</span>
                                    </ToggleGroupItem>
                                </ToggleGroup>
                            </div>
                            <DropdownMenuSeparator />
                            <ArtistMenuContent :user="auth.user" />
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>
        </div>

        <div v-if="props.breadcrumbs.length > 1" class="flex w-full border-b border-sidebar-border/70">
            <div class="mx-auto flex h-12 w-full items-center justify-start px-4 text-neutral-500 md:max-w-7xl">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </div>
        </div>
    </div>
</template>
