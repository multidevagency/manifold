<script setup lang="ts">
const route = useRoute()
const api = useApi()
const { get, load } = useSchema()
await load().catch(() => {})

const collection = computed(() => get(route.params.slug as string))
const { data: entry } = await useAsyncData(
  `entry-${route.params.slug}-${route.params.id}`,
  async () => (await api<{ data: any }>(`/api/manifold/${route.params.slug}/${route.params.id}`)).data,
)

const formRef = ref()

async function destroy() {
  await api(`/api/manifold/${collection.value!.slug}/${entry.value.id}`, { method: 'DELETE' })
  navigateTo(`/c/${collection.value!.slug}`)
}
</script>

<template>
  <div v-if="collection && entry" class="rise mx-auto flex max-w-4xl gap-8">
    <div class="min-w-0 flex-1">
      <div class="mb-6">
        <NuxtLink :to="`/c/${collection.slug}`" class="mono-tag text-ink-soft hover:text-accent">← {{ collection.labelPlural }}</NuxtLink>
        <h1 class="mt-1 truncate text-3xl font-black tracking-tight" style="font-stretch: 110%">
          {{ collection.titleField ? entry[collection.titleField] : `#${entry.id}` }}
        </h1>
      </div>

      <div class="border-2 border-line-strong bg-panel p-6" style="box-shadow: 6px 6px 0 0 var(--color-ink)">
        <EntryForm ref="formRef" :collection="collection" :entry="entry" @saved="e => Object.assign(entry, e)" />
      </div>
    </div>

    <aside class="w-52 shrink-0 pt-14">
      <div class="sticky top-8 space-y-5">
        <button
          class="w-full bg-ink px-4 py-2.5 text-sm font-bold uppercase tracking-widest text-paper transition-colors hover:bg-accent disabled:opacity-50"
          :disabled="formRef?.busy"
          @click="formRef?.save()"
        >
          {{ formRef?.savedFlash ? 'Saved ✓' : 'Save' }}
        </button>

        <dl class="border-t-2 border-line-strong pt-4 text-[13px]">
          <dt class="mono-tag text-ink-soft">id</dt>
          <dd class="mb-3 font-mono">{{ entry.id }}</dd>
          <dt class="mono-tag text-ink-soft">created</dt>
          <dd class="mb-3 font-mono">{{ entry.created_at?.slice(0, 16) }}</dd>
          <dt class="mono-tag text-ink-soft">updated</dt>
          <dd class="font-mono">{{ entry.updated_at?.slice(0, 16) }}</dd>
        </dl>

        <ConfirmDialog
          title="Delete this entry?"
          :description="`“${collection.titleField ? entry[collection.titleField] : '#' + entry.id}” will be permanently removed.`"
          @confirm="destroy"
        >
          <button class="mono-tag border border-accent px-3 py-1.5 text-accent transition-colors hover:bg-accent hover:text-paper">
            Delete entry
          </button>
        </ConfirmDialog>
      </div>
    </aside>
  </div>
</template>
