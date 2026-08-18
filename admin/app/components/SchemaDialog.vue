<script setup lang="ts">
import { DialogContent, DialogOverlay, DialogPortal, DialogRoot, DialogTitle, DialogTrigger } from 'reka-ui'

defineProps<{ title: string }>()
const open = defineModel<boolean>('open', { default: false })
</script>

<template>
  <DialogRoot v-model:open="open">
    <DialogTrigger as-child>
      <slot name="trigger" />
    </DialogTrigger>
    <DialogPortal>
      <DialogOverlay class="fixed inset-0 z-40 bg-ink/40" />
      <DialogContent
        class="rise fixed left-1/2 top-1/4 z-50 w-full max-w-md -translate-x-1/2 border-2 border-line-strong bg-panel p-6"
        style="box-shadow: 8px 8px 0 0 var(--color-ink)"
        @open-auto-focus.prevent
      >
        <DialogTitle class="mb-4 text-xl font-black">{{ title }}</DialogTitle>
        <slot />
      </DialogContent>
    </DialogPortal>
  </DialogRoot>
</template>
