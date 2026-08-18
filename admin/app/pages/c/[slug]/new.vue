<script setup lang="ts">
const route = useRoute()
const { get, load } = useSchema()
await load().catch(() => {})

const collection = computed(() => get(route.params.slug as string))
const formRef = ref()
</script>

<template>
  <div v-if="collection" class="rise mx-auto max-w-2xl">
    <div class="mb-6 flex items-end justify-between">
      <div>
        <NuxtLink :to="`/c/${collection.slug}`" class="mono-tag text-ink-soft hover:text-accent">← {{ collection.labelPlural }}</NuxtLink>
        <h1 class="mt-1 text-3xl font-black tracking-tight" style="font-stretch: 110%">New {{ collection.labelSingular }}</h1>
      </div>
      <button
        class="bg-ink px-5 py-2.5 text-sm font-bold uppercase tracking-widest text-paper transition-colors hover:bg-accent disabled:opacity-50"
        :disabled="formRef?.busy"
        @click="formRef?.save()"
      >
        Create
      </button>
    </div>

    <div class="border-2 border-line-strong bg-panel p-6" style="box-shadow: 6px 6px 0 0 var(--color-ink)">
      <EntryForm
        ref="formRef"
        :collection="collection"
        @saved="e => navigateTo(`/c/${collection!.slug}/${e.id}`)"
      />
    </div>
  </div>
</template>
