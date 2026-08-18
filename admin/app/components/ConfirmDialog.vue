<script setup lang="ts">
import {
  AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription,
  AlertDialogOverlay, AlertDialogPortal, AlertDialogRoot, AlertDialogTitle, AlertDialogTrigger,
} from 'reka-ui'

defineProps<{ title: string; description: string; actionLabel?: string }>()
const emit = defineEmits<{ confirm: [] }>()
</script>

<template>
  <AlertDialogRoot>
    <AlertDialogTrigger as-child>
      <slot />
    </AlertDialogTrigger>
    <AlertDialogPortal>
      <AlertDialogOverlay class="fixed inset-0 z-40 bg-ink/40" />
      <AlertDialogContent
        class="rise fixed left-1/2 top-1/3 z-50 w-full max-w-md -translate-x-1/2 border-2 border-line-strong bg-panel p-6"
        style="box-shadow: 8px 8px 0 0 var(--color-ink)"
      >
        <AlertDialogTitle class="text-xl font-black">{{ title }}</AlertDialogTitle>
        <AlertDialogDescription class="mt-2 text-[15px] text-ink-soft">
          {{ description }}
        </AlertDialogDescription>
        <div class="mt-6 flex justify-end gap-3">
          <AlertDialogCancel class="border border-line-strong bg-panel px-4 py-2 text-sm font-semibold hover:bg-accent-soft">
            Cancel
          </AlertDialogCancel>
          <AlertDialogAction
            class="bg-accent px-4 py-2 text-sm font-bold uppercase tracking-widest text-paper hover:bg-ink"
            @click="emit('confirm')"
          >
            {{ actionLabel ?? 'Delete' }}
          </AlertDialogAction>
        </div>
      </AlertDialogContent>
    </AlertDialogPortal>
  </AlertDialogRoot>
</template>
