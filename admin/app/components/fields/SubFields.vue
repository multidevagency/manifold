<script setup lang="ts">
import FieldRenderer from '~/components/fields/FieldRenderer.vue'

defineProps<{ fields: any[] }>()
// An explicit null bypasses defineModel's default, so normalize continuously.
const model = defineModel<Record<string, any> | null>()
watchEffect(() => {
  if (model.value === null || typeof model.value !== 'object') model.value = {}
})
</script>

<template>
  <template v-if="model">
    <FieldRenderer v-for="child in fields" :key="child.column" v-model="model[child.name]" :field="child">
      <template #child="{ child: nested }">
        <FieldRenderer v-model="model[nested.name]" :field="nested" />
      </template>
    </FieldRenderer>
  </template>
</template>
