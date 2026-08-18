<script setup lang="ts">
import { SwitchRoot, SwitchThumb } from 'reka-ui'
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'

defineProps<{ field: FieldSchema; error?: string }>()
const model = defineModel<boolean | null>()
</script>

<template>
  <FieldShell :field="field" :error="error">
    <div class="flex items-center gap-3">
      <SwitchRoot
        :id="field.column"
        :model-value="!!model"
        class="relative h-6 w-11 border border-line-strong bg-panel transition-colors data-[state=checked]:bg-accent"
        @update:model-value="v => (model = v)"
      >
        <SwitchThumb
          class="block h-[18px] w-[18px] translate-x-0.5 bg-ink transition-transform data-[state=checked]:translate-x-[22px] data-[state=checked]:bg-paper"
        />
      </SwitchRoot>
      <label :for="field.column" class="mono-tag text-ink-soft">{{ model ? 'yes' : 'no' }}</label>
    </div>
  </FieldShell>
</template>
