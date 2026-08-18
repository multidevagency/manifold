<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'

defineProps<{ field: FieldSchema; error?: string }>()
const model = defineModel<string | null>()
</script>

<template>
  <FieldShell :field="field" :error="error">
    <div class="flex flex-col gap-2">
      <label v-for="(label, value) in field.options" :key="value" class="flex cursor-pointer items-center gap-2.5 text-[15px]">
        <input
          v-model="model"
          type="radio"
          :name="field.column"
          :value="value"
          class="h-4 w-4 accent-[--color-accent]"
        />
        {{ label }}
      </label>
    </div>
  </FieldShell>
</template>
