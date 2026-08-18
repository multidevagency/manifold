<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'

defineProps<{ field: FieldSchema; error?: string }>()
const model = defineModel<string | null>()
const editor = ref<HTMLElement>()

// contenteditable can't be v-modelled; sync manually and only push external
// changes in when the editor is not focused, or the caret jumps to the start.
onMounted(() => { if (editor.value) editor.value.innerHTML = model.value ?? '' })
watch(model, (v) => {
  if (editor.value && document.activeElement !== editor.value) editor.value.innerHTML = v ?? ''
})

function onInput() {
  model.value = editor.value?.innerHTML ?? ''
}
function exec(command: string, arg?: string) {
  editor.value?.focus()
  document.execCommand(command, false, arg)
  onInput()
}
</script>

<template>
  <FieldShell :field="field" :error="error">
    <div class="border border-line-strong bg-panel focus-within:border-accent" style="transition: box-shadow 0.15s"
      :style="{ boxShadow: 'var(--rt-shadow, none)' }">
      <div class="flex gap-1 border-b border-line px-2 py-1.5">
        <button type="button" class="mono-tag px-2 py-1 font-bold hover:bg-accent-soft" @click="exec('bold')">B</button>
        <button type="button" class="mono-tag px-2 py-1 italic hover:bg-accent-soft" @click="exec('italic')">I</button>
        <button type="button" class="mono-tag px-2 py-1 hover:bg-accent-soft" @click="exec('formatBlock', '<h2>')">H2</button>
        <button type="button" class="mono-tag px-2 py-1 hover:bg-accent-soft" @click="exec('formatBlock', '<p>')">P</button>
        <button type="button" class="mono-tag px-2 py-1 hover:bg-accent-soft" @click="exec('insertUnorderedList')">List</button>
      </div>
      <div
        :id="field.column"
        ref="editor"
        contenteditable="true"
        class="prose-editor min-h-36 px-3.5 py-2.5 text-[15px] outline-none"
        @input="onInput"
      />
    </div>
  </FieldShell>
</template>

<style>
.prose-editor h2 { font-size: 1.2rem; font-weight: 800; margin: 0.6em 0 0.3em; }
.prose-editor p { margin: 0.4em 0; }
.prose-editor ul { list-style: disc; padding-left: 1.4em; margin: 0.4em 0; }
</style>
