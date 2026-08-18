<script setup lang="ts">
import SchemaDialog from '~/components/SchemaDialog.vue'

const api = useApi()
const { refresh } = useSchema()
const open = ref(false)
const name = ref('')
const error = ref('')
const busy = ref(false)

async function create() {
  busy.value = true
  error.value = ''
  try {
    const res = await api<{ data: { slug: string } }>('/api/manifold/schema/collections', {
      method: 'POST',
      body: { name: name.value },
    })
    await refresh()
    open.value = false
    name.value = ''
    navigateTo(`/c/${res.data.slug}`)
  } catch (e: any) {
    error.value = e?.data?.message ?? 'Could not create collection'
  } finally {
    busy.value = false
  }
}
</script>

<template>
  <SchemaDialog v-model:open="open" title="New collection">
    <template #trigger>
      <button class="mono-tag w-full px-5 py-2 text-left text-ink-soft transition-colors hover:bg-accent-soft hover:text-accent">
        + New collection
      </button>
    </template>

    <form @submit.prevent="create">
      <label class="mono-tag mb-1.5 block text-ink-soft" for="collection-name">Name</label>
      <input id="collection-name" v-model="name" class="field-input mb-2" placeholder="Products" />
      <p class="mb-4 text-[13px] text-ink-soft">
        Writes <code class="font-mono">app/Collections/&lt;Name&gt;.php</code>, registers it, and runs
        <code class="font-mono">manifold:migrate</code>. Local environment only.
      </p>
      <p v-if="error" class="mb-3 border-l-4 border-accent bg-accent-soft px-3 py-2 text-sm font-medium">{{ error }}</p>
      <button
        type="submit"
        :disabled="busy || !name"
        class="w-full bg-ink px-4 py-2.5 text-sm font-bold uppercase tracking-widest text-paper transition-colors hover:bg-accent disabled:opacity-50"
      >
        {{ busy ? 'Creating…' : 'Create collection' }}
      </button>
    </form>
  </SchemaDialog>
</template>
