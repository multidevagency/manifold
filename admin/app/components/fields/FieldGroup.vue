<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'
import SubFields from '~/components/fields/SubFields.vue'

defineProps<{ field: FieldSchema & { children?: any[] }; error?: string }>()
const model = defineModel<Record<string, any> | null>()
if (!model.value || typeof model.value !== 'object') model.value = {}
</script>

<template>
  <FieldShell :field="field" :error="error">
    <div class="space-y-5 border border-line-strong bg-panel p-4">
      <SubFields v-model="model" :fields="field.children ?? []" />
    </div>
  </FieldShell>
</template>
