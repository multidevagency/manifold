<script setup lang="ts">
const props = defineProps<{ field: { children?: any[] } }>()
const active = ref(0)
</script>

<template>
  <div>
    <div class="mb-5 flex gap-1 border-b-2 border-line-strong">
      <button
        v-for="(tab, i) in field.children"
        :key="i"
        type="button"
        class="px-4 py-2 text-sm font-bold transition-colors"
        :class="active === i ? 'bg-ink text-paper' : 'text-ink-soft hover:text-ink'"
        @click="active = i"
      >
        {{ tab.label }}
      </button>
    </div>
    <div v-for="(tab, i) in field.children" v-show="active === i" :key="i" class="space-y-6">
      <template v-for="child in tab.children" :key="child.column">
        <slot name="field" :child="child" />
      </template>
    </div>
  </div>
</template>
