<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'

defineProps<{ field: FieldSchema; error?: string }>()
const model = defineModel<unknown>()
const invalid = ref(false)

// The editor holds a string; the model holds parsed JSON.
const text = ref(model.value == null ? '' : JSON.stringify(model.value, null, 2))

watch(text, (v) => {
  if (v.trim() === '') {
    model.value = null
    invalid.value = false
    return
  }
  try {
    model.value = JSON.parse(v)
    invalid.value = false
  } catch {
    invalid.value = true
  }
})
</script>

<template>
  <FieldShell :field="field" :error="error ?? (invalid ? 'Invalid JSON' : undefined)">
    <textarea
      :id="field.column"
      v-model="text"
      rows="6"
      spellcheck="false"
      class="field-input resize-y font-mono text-[13px]"
      :class="{ 'border-accent': invalid }"
    />
  </FieldShell>
</template>
