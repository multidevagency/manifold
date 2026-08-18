<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'

defineProps<{ field: FieldSchema; error?: string; ai?: boolean; generating?: boolean }>()
const emit = defineEmits<{ generate: [] }>()
</script>

<template>
  <div>
    <div class="mb-1.5 flex items-baseline justify-between">
      <label :for="field.column" class="text-sm font-bold">
        {{ field.label }}<span v-if="field.required" class="text-accent"> *</span>
      </label>
      <div class="flex items-baseline gap-3">
        <button
          v-if="ai"
          type="button"
          class="mono-tag text-ink-soft transition-colors hover:text-accent disabled:opacity-50"
          :disabled="generating"
          @click="emit('generate')"
        >
          {{ generating ? '✨ generating…' : '✨ generate' }}
        </button>
        <span class="mono-tag text-ink-soft">{{ field.type }}</span>
      </div>
    </div>
    <slot />
    <p v-if="error" class="mt-1.5 text-[13px] font-medium text-accent">{{ error }}</p>
    <p v-else-if="field.help" class="mt-1.5 text-[13px] text-ink-soft">{{ field.help }}</p>
  </div>
</template>
