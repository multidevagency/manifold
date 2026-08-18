<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'

defineProps<{ field: FieldSchema; error?: string }>()
const model = defineModel<string | null>()

const local = computed({
  get: () => (model.value ? model.value.slice(0, 16) : ''),
  set: (v: string) => { model.value = v || null },
})
</script>

<template>
  <FieldShell :field="field" :error="error">
    <input :id="field.column" v-model="local" type="datetime-local" class="field-input font-mono text-sm" />
  </FieldShell>
</template>
