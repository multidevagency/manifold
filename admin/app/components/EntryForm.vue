<script setup lang="ts">
import type { CollectionSchema, FieldSchema } from '~/composables/useSchema'
import FieldText from '~/components/fields/FieldText.vue'
import FieldNumber from '~/components/fields/FieldNumber.vue'
import FieldTextarea from '~/components/fields/FieldTextarea.vue'
import FieldRichText from '~/components/fields/FieldRichText.vue'
import FieldSelect from '~/components/fields/FieldSelect.vue'
import FieldBoolean from '~/components/fields/FieldBoolean.vue'
import FieldDateTime from '~/components/fields/FieldDateTime.vue'
import FieldRelationship from '~/components/fields/FieldRelationship.vue'

const props = defineProps<{ collection: CollectionSchema; entry?: Record<string, any> }>()
const emit = defineEmits<{ saved: [entry: Record<string, any>] }>()

const api = useApi()
const form = reactive<Record<string, any>>({})
const errors = ref<Record<string, string[]>>({})
const busy = ref(false)
const savedFlash = ref(false)

for (const f of props.collection.fields) {
  form[f.column] = props.entry?.[f.column] ?? f.default ?? null
}

const componentFor = (f: FieldSchema) =>
  ({
    text: FieldText,
    email: FieldText,
    slug: FieldText,
    number: FieldNumber,
    textarea: FieldTextarea,
    richtext: FieldRichText,
    select: FieldSelect,
    boolean: FieldBoolean,
    datetime: FieldDateTime,
    relationship: FieldRelationship,
  })[f.type] ?? FieldText

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

    <component
      :is="componentFor(f)"
      v-for="(f, i) in collection.fields"
      :key="f.column"
      v-model="form[f.column]"
      :field="f"
      :error="errors[f.column]?.[0]"
      class="rise"
      :style="{ animationDelay: `${i * 40}ms` }"
    />
  </form>
</template>
