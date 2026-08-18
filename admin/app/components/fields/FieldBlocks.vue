<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'
import SubFields from '~/components/fields/SubFields.vue'

const props = defineProps<{ field: FieldSchema & { blocks?: Record<string, any[]> }; error?: string }>()
const model = defineModel<Record<string, any>[] | null>()
if (!Array.isArray(model.value)) model.value = []

function add(blockType: string) {
  model.value = [...(model.value ?? []), { blockType }]
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
          <span class="mono-tag bg-ink px-1.5 py-0.5 text-paper">{{ row.blockType }}</span>
          <div class="flex gap-2">
            <button v-if="i > 0" type="button" class="mono-tag text-ink-soft hover:text-accent" @click="move(i, -1)">↑</button>
            <button v-if="i < model!.length - 1" type="button" class="mono-tag text-ink-soft hover:text-accent" @click="move(i, 1)">↓</button>
            <button type="button" class="mono-tag text-ink-soft hover:text-accent" @click="remove(i)">✕</button>
          </div>
        </div>
        <div class="space-y-4 p-3">
          <SubFields v-model="model![i]" :fields="props.field.blocks?.[row.blockType] ?? []" />
        </div>
      </div>
      <div class="flex flex-wrap gap-2">
        <button
          v-for="(_, blockType) in props.field.blocks"
          :key="blockType"
          type="button"
          class="mono-tag border border-line-strong bg-panel px-3 py-2 transition-colors hover:bg-accent-soft"
          @click="add(String(blockType))"
        >
          + {{ blockType }}
        </button>
      </div>
    </div>
  </FieldShell>
</template>
