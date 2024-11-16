<template>
    <div class="p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center min-w-0">
                    <svg class="flex-shrink-0 w-6 h-6 text-gray-400 dark:text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                            {{ name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ size }}
                        </p>
                    </div>
                </div>
            </div>
            <button
                @click="$emit('remove-file')"
                :disabled="is_form_processing"
                type="button"
                class="text-red-500 hover:text-red-600 disabled:opacity-50"
            >
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            </button>
        </div>
        <div v-if="error" class="mt-2 text-sm text-red-600 dark:text-red-400">
            Error: {{ error }}
        </div>
        <div class="mt-2 text-xs text-gray-500">
            Debug - Index: {{ index }}, Has Error: {{ !!error }}
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    name: String,
    size: String,
    index: Number,
    error: String,
    is_form_processing: Boolean
});

console.log('FileUploadList props:', props);

defineEmits(['remove-file']);
</script>
