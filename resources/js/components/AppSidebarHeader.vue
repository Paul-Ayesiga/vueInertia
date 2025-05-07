<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import {
  CommandDialog,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
  CommandSeparator,
} from '@/components/ui/command';
import { Button } from '@/components/ui/button';
import { Search } from 'lucide-vue-next';
import type { BreadcrumbItemType } from '@/types';
import { useMagicKeys } from '@vueuse/core';
import { ref, watch } from 'vue';

withDefaults(defineProps<{
    breadcrumbs?: BreadcrumbItemType[];
}>(),{
    breadcrumbs:()=>[]
});

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
</script>

<template>
    <header
        class="sticky top-0 z-10 flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 bg-sidebar backdrop-blur-xl px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex w-full items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>

            <div class="ml-auto flex items-center gap-4">
                <Button variant="outline" size="sm" class="flex items-center gap-2" @click="handleOpenChange">
                    <Search class="h-4 w-4" />
                    <span class="hidden sm:inline">Search...</span>
                    <kbd class="pointer-events-none hidden h-5 select-none items-center gap-1 rounded border bg-muted px-1.5 font-mono text-[10px] font-medium text-muted-foreground opacity-100 sm:inline-flex">
                        <span class="text-xs">⌘</span>J
                    </kbd>
                </Button>
            </div>

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
        </div>
    </header>
</template>
