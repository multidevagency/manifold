<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'

defineProps<{ field: FieldSchema; error?: string; ai?: boolean; generating?: boolean }>()
const emit = defineEmits<{ generate: [] }>()
const model = defineModel<string | null>()
</script>

<template>
  <FieldShell :field="field" :error="error" :ai="ai" :generating="generating" @generate="emit('generate')">
    <textarea :id="field.column" v-model="model" rows="4" class="field-input resize-y" />
  </FieldShell>
</template>
