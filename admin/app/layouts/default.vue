<script setup lang="ts">
const { collections, load } = useSchema()
const { token, user, logout } = useAuth()
const route = useRoute()

const collapsed = useState('mf.sidebar', () => localStorage.getItem('mf.sidebar') === '1')
function toggleSidebar() {
  collapsed.value = !collapsed.value
  localStorage.setItem('mf.sidebar', collapsed.value ? '1' : '0')
}

if (token.value) await load().catch(() => {})
watch(token, v => { if (v) load().catch(() => {}) })
</script>

<template>
  <div v-if="route.path === '/login'">
    <slot />
  </div>

  <div v-else class="flex min-h-screen">
    <aside
      class="fixed inset-y-0 left-0 z-30 flex flex-col border-r-2 border-line-strong bg-panel transition-[width] duration-200"
      :class="collapsed ? 'w-14' : 'w-60'"
    >
      <div class="flex items-center border-b-2 border-line-strong" :class="collapsed ? 'justify-center py-4' : 'justify-between px-5 py-4'">
        <NuxtLink v-if="!collapsed" to="/" class="flex items-baseline gap-2">
          <span class="text-xl font-black tracking-tight" style="font-stretch: 115%">MANIFOLD</span>
          <span class="mono-tag text-accent">v0.1</span>
        </NuxtLink>
        <button
          class="mono-tag text-ink-soft transition-colors hover:text-accent"
          :title="collapsed ? 'Expand sidebar' : 'Collapse sidebar'"
          @click="toggleSidebar"
        >
          {{ collapsed ? '»' : '«' }}
        </button>
      </div>

      <nav class="flex-1 overflow-y-auto py-4">
        <p v-if="!collapsed" class="mono-tag px-5 pb-2 text-ink-soft">Collections</p>
        <NuxtLink
          v-for="c in collections"
          :key="c.slug"
          :to="`/c/${c.slug}`"
          class="group flex items-center py-2 text-[15px] font-semibold transition-colors hover:bg-accent-soft"
          :class="[
            collapsed ? 'justify-center' : 'justify-between px-5',
            route.params.slug === c.slug ? 'bg-ink text-paper hover:bg-ink' : '',
          ]"
          :title="c.labelPlural"
        >
          <template v-if="collapsed">
            <span class="font-mono text-sm font-bold" :class="route.params.slug === c.slug ? 'text-accent' : ''">
              {{ c.labelPlural.slice(0, 2) }}
            </span>
          </template>
          <template v-else>
            {{ c.labelPlural }}
            <span
              class="mono-tag transition-colors"
              :class="route.params.slug === c.slug ? 'text-accent' : 'text-ink-soft group-hover:text-accent'"
            >/{{ c.slug }}</span>
          </template>
        </NuxtLink>

        <div v-if="!collapsed" class="mt-3 border-t border-line pt-3">
          <NewCollectionDialog />
        </div>
      </nav>

      <div class="border-t-2 border-line-strong" :class="collapsed ? 'py-4 text-center' : 'px-5 py-4'">
        <template v-if="!collapsed">
          <p class="truncate text-sm font-semibold">{{ user?.name ?? 'Signed in' }}</p>
          <button class="mono-tag mt-1 text-ink-soft underline-offset-2 hover:text-accent hover:underline" @click="logout">
            Sign out
          </button>
        </template>
        <button v-else class="mono-tag text-ink-soft hover:text-accent" title="Sign out" @click="logout">⏻</button>
      </div>
    </aside>

    <main class="min-w-0 flex-1 px-10 py-8 transition-[margin] duration-200" :class="collapsed ? 'ml-14' : 'ml-60'">
      <slot />
    </main>
  </div>
</template>
