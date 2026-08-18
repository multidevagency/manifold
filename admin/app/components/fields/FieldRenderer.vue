<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldText from '~/components/fields/FieldText.vue'
import FieldNumber from '~/components/fields/FieldNumber.vue'
import FieldTextarea from '~/components/fields/FieldTextarea.vue'
import FieldRichText from '~/components/fields/FieldRichText.vue'
import FieldSelect from '~/components/fields/FieldSelect.vue'
import FieldBoolean from '~/components/fields/FieldBoolean.vue'
import FieldDateTime from '~/components/fields/FieldDateTime.vue'
import FieldRelationship from '~/components/fields/FieldRelationship.vue'
import FieldCode from '~/components/fields/FieldCode.vue'
import FieldJson from '~/components/fields/FieldJson.vue'
import FieldDate from '~/components/fields/FieldDate.vue'
import FieldRadio from '~/components/fields/FieldRadio.vue'
import FieldPoint from '~/components/fields/FieldPoint.vue'
import FieldUpload from '~/components/fields/FieldUpload.vue'
import FieldJoin from '~/components/fields/FieldJoin.vue'
import FieldGroup from '~/components/fields/FieldGroup.vue'
import FieldArray from '~/components/fields/FieldArray.vue'
import FieldBlocks from '~/components/fields/FieldBlocks.vue'
import LayoutTabs from '~/components/fields/LayoutTabs.vue'
import LayoutCollapsible from '~/components/fields/LayoutCollapsible.vue'

const props = defineProps<{
  field: FieldSchema & { children?: any[]; blocks?: Record<string, any[]>; via?: string }
  error?: string
  ai?: boolean
  generating?: boolean
  entryId?: number
}>()
const emit = defineEmits<{ generate: [field: any] }>()
const model = defineModel<any>()

const SIMPLE = {
  text: FieldText,
  email: FieldText,
  slug: FieldText,
  number: FieldNumber,
  textarea: FieldTextarea,
  richtext: FieldRichText,
  select: FieldSelect,
  boolean: FieldBoolean,
  datetime: FieldDateTime,
  relationship: FieldRelationship,
  code: FieldCode,
  json: FieldJson,
  date: FieldDate,
  radio: FieldRadio,
  point: FieldPoint,
  upload: FieldUpload,
} as Record<string, any>
</script>

<template>
  <!-- Layout containers: no data binding, they recurse into children -->
  <LayoutTabs v-if="field.type === 'tabs'" :field="field">
    <template #field="{ child }">
      <slot name="child" :child="child" />
    </template>
  </LayoutTabs>

  <LayoutCollapsible v-else-if="field.type === 'collapsible'" :field="field">
    <template #field="{ child }">
      <slot name="child" :child="child" />
    </template>
  </LayoutCollapsible>

  <div v-else-if="field.type === 'row'" class="grid gap-5 sm:grid-cols-2">
    <template v-for="child in field.children" :key="child.column">
      <slot name="child" :child="child" />
    </template>
  </div>

  <p v-else-if="field.type === 'ui'" class="border-l-4 border-line-strong bg-panel px-3 py-2 text-[13px] text-ink-soft">
    {{ field.help }}
  </p>

  <FieldJoin v-else-if="field.type === 'join'" :field="field" :entry-id="entryId" />

  <FieldGroup v-else-if="field.type === 'group'" v-model="model" :field="field" :error="error" />

  <FieldArray v-else-if="field.type === 'array'" v-model="model" :field="field" :error="error" />

  <FieldBlocks v-else-if="field.type === 'blocks'" v-model="model" :field="field" :error="error" />

  <component
    :is="SIMPLE[field.type] ?? FieldText"
    v-else
    v-model="model"
    :field="field"
    :error="error"
    :ai="ai"
    :generating="generating"
    @generate="emit('generate', field)"
  />
</template>
