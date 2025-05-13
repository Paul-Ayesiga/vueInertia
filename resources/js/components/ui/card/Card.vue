<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
  hoverable?: boolean
}>()
</script>

<template>
  <div
    data-slot="card"
    :class="
      cn(
        'bg-card dark:bg-white/10 text-card-foreground flex flex-col gap-6 rounded-xl border py-6 shadow-sm',
        props.hoverable && 'hover:shadow-md transition-shadow duration-200',
        props.class,
      )
    "
  >
    <slot />
  </div>
</template>
