<script setup lang="ts">
const route = useRoute()
const api = useApi()
const { get, load } = useSchema()
await load().catch(() => {})

const collection = computed(() => get(route.params.slug as string))
const rows = ref<any[]>([])
const meta = ref({ total: 0, page: 1, perPage: 25, lastPage: 1 })
const search = ref('')
const page = ref(1)
const pending = ref(false)

const listFields = computed(() =>
  (collection.value?.fields ?? []).filter(f => !['richtext', 'textarea'].includes(f.type)).slice(0, 5),
)

async function fetchRows() {
  if (!collection.value) return
  pending.value = true
  try {
    const res = await api<{ data: any[]; meta: any }>(`/api/manifold/${collection.value.slug}`, {
      params: { page: page.value, search: search.value || undefined },
    })
    rows.value = res.data
    meta.value = res.meta
  } finally {
    pending.value = false
  }
}

watch([() => route.params.slug, page], fetchRows, { immediate: true })
watchDebounced(search, () => { page.value = 1; fetchRows() }, 350)

function watchDebounced(source: any, cb: () => void, ms: number) {
  let t: ReturnType<typeof setTimeout>
  watch(source, () => { clearTimeout(t); t = setTimeout(cb, ms) })
}

function cellValue(row: any, field: any): string {
  const v = row[field.column]
  if (v === null || v === undefined) return '—'
  if (field.type === 'boolean') return v ? '●' : '○'
  if (field.type === 'datetime') return new Date(v).toLocaleDateString()
  if (field.type === 'relationship') return `#${v}`
  return String(v)
}

async function destroy(row: any) {
  await api(`/api/manifold/${collection.value!.slug}/${row.id}`, { method: 'DELETE' })
  fetchRows()
}
</script>

<template>
  <div v-if="collection" class="rise mx-auto max-w-5xl">
    <div class="mb-6 flex items-end justify-between">
      <div>
        <p class="mono-tag text-ink-soft">/api/manifold/{{ collection.slug }}</p>
        <h1 class="mt-1 text-4xl font-black tracking-tight" style="font-stretch: 110%">
          {{ collection.labelPlural }}
          <span class="align-top text-base font-bold text-accent">{{ meta.total }}</span>
        </h1>
      </div>
      <div class="flex items-center gap-3">
        <AddFieldDialog :collection="collection" @added="fetchRows" />
        <NuxtLink
          :to="`/c/${collection.slug}/new`"
          class="bg-ink px-5 py-2.5 text-sm font-bold uppercase tracking-widest text-paper transition-colors hover:bg-accent"
        >
          + New {{ collection.labelSingular }}
        </NuxtLink>
      </div>
    </div>

    <input
      v-if="collection.titleField"
      v-model="search"
      type="search"
      :placeholder="`Search ${collection.labelPlural.toLowerCase()}…`"
      class="field-input mb-4 max-w-sm"
    />

    <div class="border-2 border-line-strong bg-panel" style="box-shadow: 6px 6px 0 0 var(--color-ink)">
      <table class="w-full text-left text-[15px]">
        <thead>
          <tr class="border-b-2 border-line-strong">
            <th class="mono-tag px-4 py-3 text-ink-soft">id</th>
            <th v-for="f in listFields" :key="f.column" class="mono-tag px-4 py-3 text-ink-soft">{{ f.name }}</th>
            <th class="w-10" />
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="row in rows"
            :key="row.id"
            class="group cursor-pointer border-b border-line transition-colors last:border-b-0 hover:bg-accent-soft"
            @click="navigateTo(`/c/${collection.slug}/${row.id}`)"
          >
            <td class="px-4 py-3 font-mono text-sm text-ink-soft">{{ row.id }}</td>
            <td v-for="f in listFields" :key="f.column" class="max-w-56 truncate px-4 py-3">
              <span
                v-if="f.type === 'select'"
                class="mono-tag px-1.5 py-0.5"
                :class="{
                  'bg-ok text-paper': row[f.column] === 'published',
                  'bg-warn text-paper': row[f.column] === 'review',
                  'bg-line': !['published', 'review'].includes(row[f.column]),
                }"
              >{{ row[f.column] ?? '—' }}</span>
              <span v-else-if="f.type === 'slug'" class="font-mono text-sm text-ink-soft">{{ cellValue(row, f) }}</span>
              <span v-else :class="{ 'font-bold': f.useAsTitle, 'text-accent': f.type === 'boolean' && row[f.column] }">
                {{ cellValue(row, f) }}
              </span>
            </td>
            <td class="px-3" @click.stop>
              <ConfirmDialog
                :title="`Delete ${collection.labelSingular.toLowerCase()}?`"
                :description="`“${row[collection.titleField ?? 'id']}” will be permanently removed.`"
                @confirm="destroy(row)"
              >
                <button class="invisible font-mono text-sm text-ink-soft hover:text-accent group-hover:visible" title="Delete">✕</button>
              </ConfirmDialog>
            </td>
          </tr>
          <tr v-if="!rows.length && !pending">
            <td :colspan="listFields.length + 2" class="px-4 py-12 text-center text-ink-soft">
              Nothing here yet — create the first {{ collection.labelSingular.toLowerCase() }}.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="meta.lastPage > 1" class="mt-5 flex items-center gap-3">
      <button class="mono-tag border border-line-strong bg-panel px-3 py-1.5 hover:bg-accent-soft disabled:opacity-40" :disabled="page <= 1" @click="page--">← prev</button>
      <span class="mono-tag text-ink-soft">page {{ meta.page }} / {{ meta.lastPage }}</span>
      <button class="mono-tag border border-line-strong bg-panel px-3 py-1.5 hover:bg-accent-soft disabled:opacity-40" :disabled="page >= meta.lastPage" @click="page++">next →</button>
    </div>
  </div>
</template>
