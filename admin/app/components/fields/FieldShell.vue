<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'

defineProps<{ field: FieldSchema; error?: string }>()
</script>

<template>
  <div>
    <div class="mb-1.5 flex items-baseline justify-between">
      <label :for="field.column" class="text-sm font-bold">
        {{ field.label }}<span v-if="field.required" class="text-accent"> *</span>
      </label>
      <span class="mono-tag text-ink-soft">{{ field.type }}</span>
    </div>
    <slot />
    <p v-if="error" class="mt-1.5 text-[13px] font-medium text-accent">{{ error }}</p>
    <p v-else-if="field.help" class="mt-1.5 text-[13px] text-ink-soft">{{ field.help }}</p>
  </div>
</template>
