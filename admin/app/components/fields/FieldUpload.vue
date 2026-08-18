<script setup lang="ts">
import type { FieldSchema } from '~/composables/useSchema'
import FieldShell from '~/components/fields/FieldShell.vue'

defineProps<{ field: FieldSchema; error?: string }>()
const model = defineModel<{ path: string; url: string } | null>()
const busy = ref(false)
const uploadError = ref('')

const isImage = computed(() => !!model.value && /\.(jpe?g|png|gif|webp|avif)$/i.test(model.value.path))

async function upload(event: Event) {
  const file = (event.target as HTMLInputElement).files?.[0]
  if (!file) return
  busy.value = true
  uploadError.value = ''
  try {
    const body = new FormData()
    body.append('file', file)
    const res = await useApi()<{ data: { path: string; url: string } }>('/api/manifold/uploads', { method: 'POST', body })
    model.value = res.data
  } catch (e: any) {
    uploadError.value = e?.data?.message ?? 'Upload failed'
  } finally {
    busy.value = false
  }
}
</script>

<template>
  <FieldShell :field="field" :error="error ?? (uploadError || undefined)">
    <div class="flex items-start gap-4">
      <div v-if="model" class="shrink-0">
        <img v-if="isImage" :src="model.url" class="h-24 w-24 border border-line-strong object-cover" alt="" />
        <span v-else class="mono-tag block max-w-40 truncate border border-line-strong bg-panel px-2 py-1">{{ model.path }}</span>
      </div>
      <div class="flex flex-col gap-2">
        <label class="mono-tag cursor-pointer border border-line-strong bg-panel px-3 py-2 transition-colors hover:bg-accent-soft">
          {{ busy ? 'Uploading…' : model ? 'Replace file' : 'Choose file' }}
          <input type="file" class="hidden" accept="image/jpeg,image/png,image/gif,image/webp,image/avif,application/pdf,video/mp4,video/webm" @change="upload" />
        </label>
        <button v-if="model" type="button" class="mono-tag text-left text-ink-soft hover:text-accent" @click="model = null">Remove</button>
      </div>
    </div>
  </FieldShell>
</template>
