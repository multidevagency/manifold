<script setup lang="ts">
import FieldRenderer from '~/components/fields/FieldRenderer.vue'

const route = useRoute()
const api = useApi()
const { getGlobal, load } = useSchema()
await load().catch(() => {})

const schema = computed(() => getGlobal(route.params.global as string))
const form = reactive<Record<string, any>>({})
const errors = ref<Record<string, string[]>>({})
const busy = ref(false)
const savedFlash = ref(false)

const { data } = await useAsyncData(`global-${route.params.global}`, async () =>
  (await api<{ data: any }>(`/api/manifold/globals/${route.params.global}`)).data,
)
if (data.value) Object.assign(form, data.value)

async function save() {
  busy.value = true
  errors.value = {}
  try {
    const res = await api<{ data: any }>(`/api/manifold/globals/${route.params.global}`, { method: 'PATCH', body: form })
    Object.assign(form, res.data)
    savedFlash.value = true
    setTimeout(() => (savedFlash.value = false), 1600)
  } catch (e: any) {
    if (e?.status === 422) errors.value = e.data?.errors ?? {}
    else errors.value = { _: [e?.data?.message ?? 'Something went wrong'] }
  } finally {
    busy.value = false
  }
}
</script>

<template>
  <div v-if="schema" class="rise mx-auto flex max-w-4xl gap-8">
    <div class="min-w-0 flex-1">
      <div class="mb-6">
        <p class="mono-tag text-ink-soft">global · /api/manifold/globals/{{ schema.slug }}</p>
        <h1 class="mt-1 text-3xl font-black tracking-tight" style="font-stretch: 110%">{{ schema.label }}</h1>
      </div>

      <div class="border-2 border-line-strong bg-panel p-6" style="box-shadow: 6px 6px 0 0 var(--color-ink)">
        <form class="space-y-6" @submit.prevent="save">
          <p v-if="errors._" class="border-l-4 border-accent bg-accent-soft px-3 py-2 text-sm font-medium">{{ errors._[0] }}</p>
          <FieldRenderer
            v-for="f in schema.fields"
            :key="f.column"
            v-model="form[f.column]"
            :field="f"
            :error="errors[f.column]?.[0]"
          >
            <template #child="{ child }">
              <FieldRenderer v-model="form[child.column]" :field="child" :error="errors[child.column]?.[0]" />
            </template>
          </FieldRenderer>
        </form>
      </div>
    </div>

    <aside class="w-52 shrink-0 pt-14">
      <button
        class="w-full bg-ink px-4 py-2.5 text-sm font-bold uppercase tracking-widest text-paper transition-colors hover:bg-accent disabled:opacity-50"
        :disabled="busy"
        @click="save"
      >
        {{ savedFlash ? 'Saved ✓' : 'Save' }}
      </button>
    </aside>
  </div>
</template>
