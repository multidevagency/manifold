<script setup lang="ts">
const { collections, load } = useSchema()
await load().catch(() => {})
if (collections.value.length) navigateTo(`/c/${collections.value[0]!.slug}`, { replace: true })
</script>

<template>
  <div class="rise mx-auto max-w-lg pt-24 text-center">
    <h1 class="text-2xl font-black">No collections registered</h1>
    <p class="mt-3 text-ink-soft">
      Add a collection class to <code class="font-mono text-sm">config/manifold.php</code> and run
      <code class="font-mono text-sm">php artisan manifold:migrate</code>.
    </p>
  </div>
</template>
