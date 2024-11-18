<template>
    <div v-if="links.length > 3" class="flex flex-wrap justify-center gap-1">
        <template v-for="(link, key) in links" :key="key">
            <div v-if="link.url === null"
                 class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 border rounded bg-gray-50 dark:bg-gray-800"
                 v-html="link.label"
            />
            <Link v-else
                  class="px-4 py-2 text-sm border rounded hover:bg-gray-50 dark:hover:bg-gray-700"
                  :class="{
                      'bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 text-white': link.active
                  }"
                  :href="link.url"
                  v-html="link.label"
            />
        </template>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true
    }
});
</script>
