<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'

defineProps<{ field: FieldSchema; error?: string }>()
const model = defineModel<number | null>()
</script>

<template>
  <FieldShell :field="field" :error="error">
    <input :id="field.column" v-model.number="model" type="number" step="any" class="field-input font-mono text-sm" />
  </FieldShell>
</template>
