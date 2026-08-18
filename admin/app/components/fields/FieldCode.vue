<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'

defineProps<{ field: FieldSchema; error?: string }>()
const model = defineModel<string | null>()
</script>

<template>
  <FieldShell :field="field" :error="error">
    <textarea
      :id="field.column"
      v-model="model"
      rows="8"
      spellcheck="false"
      class="field-input resize-y bg-ink font-mono text-[13px] leading-relaxed text-paper"
    />
  </FieldShell>
</template>
