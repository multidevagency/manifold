<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'

const props = defineProps<{ field: FieldSchema & { via?: string }; entryId?: number }>()

const { get } = useSchema()
const rows = ref<any[]>([])
const target = get(props.field.to ?? '')

onMounted(async () => {
  if (!target || !props.entryId || !props.field.via) return
  const res = await useApi()<{ data: any[] }>(`/api/manifold/${target.slug}`, {
    params: { perPage: 50, [`filter[${props.field.via}]`]: props.entryId },
  })
  rows.value = res.data
})
</script>

<template>
  <FieldShell :field="field">
    <div class="border border-line bg-panel">
      <NuxtLink
        v-for="row in rows"
        :key="row.id"
        :to="`/c/${target?.slug}/${row.id}`"
        class="flex items-center justify-between border-b border-line px-3 py-2 text-sm last:border-b-0 hover:bg-accent-soft"
      >
        {{ target?.titleField ? row[target.titleField] : `#${row.id}` }}
        <span class="mono-tag text-ink-soft">#{{ row.id }}</span>
      </NuxtLink>
      <p v-if="!rows.length" class="px-3 py-2 text-sm text-ink-soft">No linked entries.</p>
    </div>
  </FieldShell>
</template>
