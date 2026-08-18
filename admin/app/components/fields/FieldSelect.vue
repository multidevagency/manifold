<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'

defineProps<{ field: FieldSchema; error?: string }>()
const model = defineModel<string | null>()
</script>

<template>
  <FieldShell :field="field" :error="error">
    <div class="flex flex-wrap gap-2">
      <button
        v-for="(label, value) in field.options"
        :key="value"
        type="button"
        class="border border-line-strong px-3.5 py-1.5 text-sm font-semibold transition-all"
        :class="model === value ? 'bg-ink text-paper' : 'bg-panel hover:bg-accent-soft'"
        @click="model = value"
      >
        {{ label }}
      </button>
    </div>
  </FieldShell>
</template>
