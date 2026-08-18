<script setup lang="ts">
const { collections, load } = useSchema()
const { token, user, logout } = useAuth()
const route = useRoute()

if (token.value) await load().catch(() => {})
watch(token, v => { if (v) load().catch(() => {}) })
</script>

<template>
  <div v-if="route.path === '/login'">
    <slot />
  </div>

  <div v-else class="flex min-h-screen">
    <aside class="fixed inset-y-0 left-0 flex w-60 flex-col border-r-2 border-line-strong bg-panel">
      <NuxtLink to="/" class="flex items-baseline gap-2 border-b-2 border-line-strong px-5 py-4">
        <span class="text-xl font-black tracking-tight" style="font-stretch: 115%">MANIFOLD</span>
        <span class="mono-tag text-accent">v0.1</span>
      </NuxtLink>

      <nav class="flex-1 overflow-y-auto py-4">
        <p class="mono-tag px-5 pb-2 text-ink-soft">Collections</p>
        <NuxtLink
          v-for="c in collections"
          :key="c.slug"
          :to="`/c/${c.slug}`"
          class="group flex items-center justify-between px-5 py-2 text-[15px] font-semibold transition-colors hover:bg-accent-soft"
          :class="route.params.slug === c.slug ? 'bg-ink text-paper hover:bg-ink' : ''"
        >
          {{ c.labelPlural }}
          <span
            class="mono-tag transition-colors"
            :class="route.params.slug === c.slug ? 'text-accent' : 'text-ink-soft group-hover:text-accent'"
          >/{{ c.slug }}</span>
        </NuxtLink>

        <div class="mt-3 border-t border-line pt-3">
          <NewCollectionDialog />
        </div>
      </nav>

      <div class="border-t-2 border-line-strong px-5 py-4">
        <p class="truncate text-sm font-semibold">{{ user?.name ?? 'Signed in' }}</p>
        <button class="mono-tag mt-1 text-ink-soft underline-offset-2 hover:text-accent hover:underline" @click="logout">
          Sign out
        </button>
      </div>
    </aside>

    <main class="ml-60 flex-1 px-10 py-8">
      <slot />
    </main>
  </div>
</template>
