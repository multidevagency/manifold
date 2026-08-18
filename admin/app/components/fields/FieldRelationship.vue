<script setup lang="ts">
import {
  SelectContent, SelectItem, SelectItemIndicator, SelectItemText,
  SelectPortal, SelectRoot, SelectTrigger, SelectValue, SelectViewport,
} from 'reka-ui'
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'

const props = defineProps<{ field: FieldSchema; error?: string }>()
const model = defineModel<number | null>()

const { get } = useSchema()
const options = ref<{ id: number; title: string }[]>([])

onMounted(async () => {
  const target = get(props.field.to ?? '')
  if (!target) return
  const res = await useApi()<{ data: any[] }>(`/api/manifold/${target.slug}`, { params: { perPage: 100 } })
  options.value = res.data.map(row => ({
    id: row.id,
    title: target.titleField ? row[target.titleField] : `#${row.id}`,
  }))
})

// Reka's Select forbids empty-string item values; "none" encodes null.
const selected = computed({
  get: () => (model.value == null ? 'none' : String(model.value)),
  set: (v: string) => (model.value = v === 'none' ? null : Number(v)),
})
const selectedLabel = computed(() => options.value.find(o => o.id === model.value)?.title)
</script>

<template>
  <FieldShell :field="field" :error="error">
    <SelectRoot v-model="selected">
      <SelectTrigger
        :id="field.column"
        class="field-input flex items-center justify-between text-left data-[state=open]:border-accent"
      >
        <SelectValue :placeholder="'—'">{{ selectedLabel ?? '—' }}</SelectValue>
        <span class="mono-tag text-ink-soft">▾</span>
      </SelectTrigger>
      <SelectPortal>
        <SelectContent
          position="popper"
          :side-offset="4"
          class="z-50 w-[var(--reka-select-trigger-width)] border-2 border-line-strong bg-panel"
          style="box-shadow: 4px 4px 0 0 var(--color-ink)"
        >
          <SelectViewport class="max-h-64 p-1">
            <SelectItem value="none" class="cursor-pointer px-3 py-2 text-[15px] text-ink-soft outline-none data-[highlighted]:bg-accent-soft">
              <SelectItemText>—</SelectItemText>
            </SelectItem>
            <SelectItem
              v-for="o in options"
              :key="o.id"
              :value="String(o.id)"
              class="flex cursor-pointer items-center justify-between px-3 py-2 text-[15px] outline-none data-[highlighted]:bg-accent-soft"
            >
              <SelectItemText>{{ o.title }}</SelectItemText>
              <SelectItemIndicator class="mono-tag text-accent">●</SelectItemIndicator>
            </SelectItem>
          </SelectViewport>
        </SelectContent>
      </SelectPortal>
    </SelectRoot>
  </FieldShell>
</template>
