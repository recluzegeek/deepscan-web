<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    label: {
        type: String,
        required: true
    },
    options: {
        type: Array,
        required: true
    },
    modelValue: {
        type: String,
        default: ''
    },
    paramName: {
        type: String,
        required: true
    }
});

const updateFilter = (value) => {
    router.get(
        route('reports.index'),
        { 
            ...route().params,
            [props.paramName]: value || null 
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        }
    );
};
</script>

<template>
    <div class="relative">
        <select
            :value="modelValue"
            @change="updateFilter($event.target.value)"
            class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-200"
        >
            <option value="">{{ label }}</option>
            <option 
                v-for="option in options" 
                :key="option.value" 
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>
    </div>
</template> 