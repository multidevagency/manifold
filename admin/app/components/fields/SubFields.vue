<script setup lang="ts">
import FieldRenderer from '~/components/fields/FieldRenderer.vue'

defineProps<{ fields: any[] }>()
const model = defineModel<Record<string, any>>({ default: () => ({}) })
</script>

<template>
  <FieldRenderer v-for="child in fields" :key="child.column" v-model="model[child.name]" :field="child">
    <template #child="{ child: nested }">
      <FieldRenderer v-model="model[nested.name]" :field="nested" />
    </template>
  </FieldRenderer>
</template>
