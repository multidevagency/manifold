<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'

defineProps<{ field: FieldSchema; error?: string }>()
const model = defineModel<string | null>()
</script>

<template>
  <FieldShell :field="field" :error="error">
    <textarea :id="field.column" v-model="model" rows="4" class="field-input resize-y" />
  </FieldShell>
</template>
