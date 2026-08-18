<script setup lang="ts">
defineProps<{ field: { label?: string; children?: any[] } }>()
const open = ref(true)
</script>

<template>
  <div class="border border-line-strong">
    <button
      type="button"
      class="flex w-full items-center justify-between bg-panel px-4 py-2.5 text-sm font-bold"
      @click="open = !open"
    >
      {{ field.label }}
      <span class="mono-tag text-ink-soft">{{ open ? '−' : '+' }}</span>
    </button>
    <div v-show="open" class="space-y-6 border-t border-line p-4">
      <template v-for="child in field.children" :key="child.column">
        <slot name="field" :child="child" />
      </template>
    </div>
  </div>
</template>
