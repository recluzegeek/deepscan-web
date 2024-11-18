<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50 dark:bg-gray-800/30">
        <div>
            <Link href="/">
                <ApplicationLogo class="w-20 h-20" />
            </Link>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white dark:bg-gray-800/60 shadow-lg overflow-hidden sm:rounded-xl border border-gray-200 dark:border-gray-700/50 backdrop-blur-sm">
            <slot />
        </div>
    </div>
</template>
