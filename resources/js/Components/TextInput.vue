<script setup>
defineProps({
    modelValue: {
        type: String,
        required: true,
    },
});

defineEmits(['update:modelValue']);
</script>

<template>
    <input
        class="border-gray-300 dark:border-gray-600/50 dark:bg-gray-700/50 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
