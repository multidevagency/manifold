<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'

defineProps<{ field: FieldSchema; error?: string }>()
const model = defineModel<string | null>()
</script>

<template>
  <FieldShell :field="field" :error="error">
    <input
      :id="field.column"
      v-model="model"
      :type="field.type === 'email' ? 'email' : 'text'"
      class="field-input"
      :class="{ 'font-mono text-sm': field.type === 'slug' }"
      :placeholder="field.type === 'slug' ? 'auto-generated' : ''"
    />
  </FieldShell>
</template>
