<template>
    <div class="p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ name }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ size }}
                    </p>
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
        <div v-if="errors && errors.length > 0" 
             class="mt-2 space-y-1 border-l-4 border-red-400 pl-3">
            <p v-for="(error, index) in errors" 
               :key="index"
               class="text-sm text-red-600 dark:text-red-400"
            >
                {{ error }}
            </p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    name: String,
    size: String,
    index: Number,
    errors: {
        type: Array,
        default: () => []
    },
    is_form_processing: Boolean
});

defineEmits(['remove-file']);
</script>
