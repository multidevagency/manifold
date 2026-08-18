<script setup lang="ts">
const { login } = useAuth()
const email = ref('admin@manifold.test')
const password = ref('password')
const error = ref('')
const busy = ref(false)

async function submit() {
  busy.value = true
  error.value = ''
  try {
    await login(email.value, password.value)
    navigateTo('/')
  } catch (e: any) {
    error.value = e?.data?.message ?? 'Login failed'
  } finally {
    busy.value = false
  }
}
</script>

<template>
  <div class="flex min-h-screen items-center justify-center px-6">
    <div class="rise w-full max-w-sm">
      <div class="mb-6 flex items-baseline gap-2">
        <h1 class="text-3xl font-black tracking-tight" style="font-stretch: 115%">MANIFOLD</h1>
        <span class="mono-tag text-accent">admin</span>
      </div>

      <form
        class="border-2 border-line-strong bg-panel p-6"
        style="box-shadow: 6px 6px 0 0 var(--color-ink)"
        @submit.prevent="submit"
      >
        <label class="mono-tag mb-1.5 block text-ink-soft" for="email">Email</label>
        <input id="email" v-model="email" type="email" class="field-input mb-4" autocomplete="username" />

        <label class="mono-tag mb-1.5 block text-ink-soft" for="password">Password</label>
        <input id="password" v-model="password" type="password" class="field-input mb-5" autocomplete="current-password" />

        <p v-if="error" class="mb-4 border-l-4 border-accent bg-accent-soft px-3 py-2 text-sm font-medium">{{ error }}</p>

        <button
          type="submit"
          :disabled="busy"
          class="w-full bg-ink px-4 py-3 text-sm font-bold uppercase tracking-widest text-paper transition-colors hover:bg-accent disabled:opacity-50"
        >
          {{ busy ? 'Signing in…' : 'Sign in' }}
        </button>
      </form>

      <p class="mono-tag mt-4 text-center text-ink-soft">demo: admin@manifold.test / password</p>
    </div>
  </div>
</template>
