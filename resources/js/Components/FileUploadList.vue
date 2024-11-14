<template>
    <div class="flex items-center justify-between px-4 py-3">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-gray-400 dark:text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ name }}</span>
        </div>
        <button
            type="button"
            @click="$emit('remove-file')"
            :disabled="is_form_processing"
            class="inline-flex items-center p-1 border border-transparent rounded-full text-gray-400 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-50"
        >
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</template>

<script setup>
defineProps({
    name: {
        type: String,
        required: true
    },
    index: {
        type: Number,
        required: true
    },
    is_form_processing: {
        type: Boolean,
        default: false
    }
});
</script>