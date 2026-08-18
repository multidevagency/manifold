<script setup lang="ts">
import type { CollectionSchema, FieldSchema } from '~/composables/useSchema'
import FieldRenderer from '~/components/fields/FieldRenderer.vue'

const props = defineProps<{ collection: CollectionSchema; entry?: Record<string, any> }>()
const emit = defineEmits<{ saved: [entry: Record<string, any>] }>()

const api = useApi()
const form = reactive<Record<string, any>>({})
const errors = ref<Record<string, string[]>>({})
const busy = ref(false)
const savedFlash = ref(false)
const generating = ref<string | null>(null)

const AI_TYPES = ['textarea', 'richtext']
const LAYOUT_TYPES = ['tabs', 'tab', 'row', 'collapsible', 'ui']

function columnFields(fields: any[]): any[] {
  return fields.flatMap((f) =>
    LAYOUT_TYPES.includes(f.type) ? columnFields(f.children ?? []) : f.type === 'join' ? [] : [f],
  )
}

for (const f of columnFields(props.collection.fields)) {
  form[f.column] = props.entry?.[f.column] ?? f.default ?? null
}

async function generate(f: FieldSchema) {
  generating.value = f.column
  try {
    const res = await api<{ data: { text: string } }>('/api/manifold/ai/generate', {
      method: 'POST',
      body: { collection: props.collection.slug, field: f.name, context: form },
    })
    form[f.column] = res.data.text
  } catch (e: any) {
    errors.value = { [f.column]: [e?.data?.message ?? 'AI generation failed'] }
  } finally {
    generating.value = null
  }
}

async function save() {
  busy.value = true
  errors.value = {}
  try {
    const res = props.entry
      ? await api<{ data: any }>(`/api/manifold/${props.collection.slug}/${props.entry.id}`, { method: 'PATCH', body: form })
      : await api<{ data: any }>(`/api/manifold/${props.collection.slug}`, { method: 'POST', body: form })
    savedFlash.value = true
    setTimeout(() => (savedFlash.value = false), 1600)
    emit('saved', res.data)
  } catch (e: any) {
    if (e?.status === 422) errors.value = e.data?.errors ?? {}
    else errors.value = { _: [e?.data?.message ?? 'Something went wrong'] }
  } finally {
    busy.value = false
  }
}

defineExpose({ save, busy, savedFlash })
</script>

<template>
  <form class="space-y-6" @submit.prevent="save">
    <p v-if="errors._" class="border-l-4 border-accent bg-accent-soft px-3 py-2 text-sm font-medium">
      {{ errors._[0] }}
    </p>

    <FieldRenderer
      v-for="(f, i) in collection.fields"
      :key="f.column"
      v-model="form[f.column]"
      :field="f"
      :error="errors[f.column]?.[0]"
      :ai="AI_TYPES.includes(f.type)"
      :generating="generating === f.column"
      :entry-id="entry?.id"
      class="rise"
      :style="{ animationDelay: `${Math.min(i, 8) * 40}ms` }"
      @generate="generate"
    >
      <template #child="{ child }">
        <FieldRenderer
          v-model="form[child.column]"
          :field="child"
          :error="errors[child.column]?.[0]"
          :ai="AI_TYPES.includes(child.type)"
          :generating="generating === child.column"
          :entry-id="entry?.id"
          @generate="generate"
        >
          <template #child="{ child: nested }">
            <FieldRenderer
              v-model="form[nested.column]"
              :field="nested"
              :error="errors[nested.column]?.[0]"
              :entry-id="entry?.id"
            />
          </template>
        </FieldRenderer>
      </template>
    </FieldRenderer>
  </form>
</template>
