<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'

defineProps<{ field: FieldSchema; error?: string }>()
const model = defineModel<{ lat: number; lng: number } | null>()

function set(axis: 'lat' | 'lng', value: string) {
  const n = value === '' ? null : Number(value)
  if (n === null) return
  model.value = { lat: model.value?.lat ?? 0, lng: model.value?.lng ?? 0, [axis]: n }
}
</script>

<template>
  <FieldShell :field="field" :error="error">
    <div class="flex gap-3">
      <label class="flex-1">
        <span class="mono-tag text-ink-soft">lat</span>
        <input :value="model?.lat ?? ''" type="number" step="any" min="-90" max="90" class="field-input font-mono text-sm" @input="set('lat', ($event.target as HTMLInputElement).value)" />
      </label>
      <label class="flex-1">
        <span class="mono-tag text-ink-soft">lng</span>
        <input :value="model?.lng ?? ''" type="number" step="any" min="-180" max="180" class="field-input font-mono text-sm" @input="set('lng', ($event.target as HTMLInputElement).value)" />
      </label>
    </div>
  </FieldShell>
</template>
