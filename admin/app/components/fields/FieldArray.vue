<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'
import SubFields from '~/components/fields/SubFields.vue'

defineProps<{ field: FieldSchema & { children?: any[] }; error?: string }>()
const model = defineModel<Record<string, any>[] | null>()
if (!Array.isArray(model.value)) model.value = []

function add() {
  model.value = [...(model.value ?? []), {}]
}
function remove(i: number) {
  model.value = model.value!.filter((_, idx) => idx !== i)
}
function move(i: number, dir: number) {
  const rows = [...model.value!]
  const [row] = rows.splice(i, 1)
  rows.splice(i + dir, 0, row!)
  model.value = rows
}
</script>

<template>
  <FieldShell :field="field" :error="error">
    <div class="space-y-3">
      <div v-for="(row, i) in model" :key="i" class="border border-line-strong bg-panel">
        <div class="flex items-center justify-between border-b border-line px-3 py-1.5">
          <span class="mono-tag text-ink-soft">{{ i + 1 }}</span>
          <div class="flex gap-2">
            <button v-if="i > 0" type="button" class="mono-tag text-ink-soft hover:text-accent" @click="move(i, -1)">↑</button>
            <button v-if="i < model!.length - 1" type="button" class="mono-tag text-ink-soft hover:text-accent" @click="move(i, 1)">↓</button>
            <button type="button" class="mono-tag text-ink-soft hover:text-accent" @click="remove(i)">✕</button>
          </div>
        </div>
        <div class="space-y-4 p-3">
          <SubFields v-model="model![i]" :fields="field.children ?? []" />
        </div>
      </div>
      <button type="button" class="mono-tag border border-line-strong bg-panel px-3 py-2 transition-colors hover:bg-accent-soft" @click="add">
        + Add row
      </button>
    </div>
  </FieldShell>
</template>
