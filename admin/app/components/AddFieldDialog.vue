<script setup lang="ts">
import type { CollectionSchema } from '~/composables/useSchema'
import SchemaDialog from '~/components/SchemaDialog.vue'

const props = defineProps<{ collection: CollectionSchema }>()
const emit = defineEmits<{ added: [] }>()

const api = useApi()
const { collections, refresh } = useSchema()

const FIELD_TYPES = ['text', 'textarea', 'richtext', 'email', 'number', 'boolean', 'datetime', 'date', 'slug', 'select', 'radio', 'relationship', 'code', 'json', 'point', 'upload']

const open = ref(false)
const busy = ref(false)
const error = ref('')
const form = reactive({ name: '', type: 'text', required: false, options: '', to: '' })

async function submit() {
  busy.value = true
  error.value = ''
  try {
    await api(`/api/manifold/schema/collections/${props.collection.slug}/fields`, {
      method: 'POST',
      body: {
        name: form.name,
        type: form.type,
        required: form.required,
        options: form.type === 'select' ? form.options.split(',').map(o => o.trim()).filter(Boolean) : undefined,
        to: form.type === 'relationship' ? form.to : undefined,
      },
    })
    await refresh()
    open.value = false
    Object.assign(form, { name: '', type: 'text', required: false, options: '', to: '' })
    emit('added')
  } catch (e: any) {
    error.value = e?.data?.message ?? 'Could not add field'
  } finally {
    busy.value = false
  }
}
</script>

<template>
  <SchemaDialog v-model:open="open" :title="`Add field to ${collection.labelPlural}`">
    <template #trigger>
      <button class="mono-tag border border-line-strong bg-panel px-4 py-2.5 transition-colors hover:bg-accent-soft hover:text-accent">
        + Add field
      </button>
    </template>

    <form @submit.prevent="submit">
      <label class="mono-tag mb-1.5 block text-ink-soft" for="field-name">Name (snake_case)</label>
      <input id="field-name" v-model="form.name" class="field-input mb-4 font-mono text-sm" placeholder="subtitle" />

      <label class="mono-tag mb-1.5 block text-ink-soft">Type</label>
      <div class="mb-4 flex flex-wrap gap-1.5">
        <button
          v-for="t in FIELD_TYPES"
          :key="t"
          type="button"
          class="mono-tag border border-line-strong px-2.5 py-1.5 transition-colors"
          :class="form.type === t ? 'bg-ink text-paper' : 'bg-panel hover:bg-accent-soft'"
          @click="form.type = t"
        >
          {{ t }}
        </button>
      </div>

      <template v-if="form.type === 'select' || form.type === 'radio'">
        <label class="mono-tag mb-1.5 block text-ink-soft" for="field-options">Options (comma-separated)</label>
        <input id="field-options" v-model="form.options" class="field-input mb-4 font-mono text-sm" placeholder="small, medium, large" />
      </template>

      <template v-if="form.type === 'relationship'">
        <label class="mono-tag mb-1.5 block text-ink-soft" for="field-to">Related collection</label>
        <select id="field-to" v-model="form.to" class="field-input mb-4">
          <option value="" disabled>Choose…</option>
          <option v-for="c in collections" :key="c.slug" :value="c.slug">{{ c.labelPlural }}</option>
        </select>
      </template>

      <label v-if="form.type !== 'boolean'" class="mb-4 flex items-center gap-2 text-sm font-semibold">
        <input v-model="form.required" type="checkbox" class="h-4 w-4 accent-[--color-accent]" />
        Required
      </label>

      <p v-if="error" class="mb-3 border-l-4 border-accent bg-accent-soft px-3 py-2 text-sm font-medium">{{ error }}</p>

      <button
        type="submit"
        :disabled="busy || !form.name"
        class="w-full bg-ink px-4 py-2.5 text-sm font-bold uppercase tracking-widest text-paper transition-colors hover:bg-accent disabled:opacity-50"
      >
        {{ busy ? 'Adding… (runs manifold:migrate)' : 'Add field' }}
      </button>
    </form>
  </SchemaDialog>
</template>
