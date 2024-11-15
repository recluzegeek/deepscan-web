<script setup>
import FilterDropdown from './Reports/FilterDropdown.vue';
import { formatDate, formatPercentage } from '@/Utils/formatters';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    videos: {
        type: Array,
        required: true
    },

    filters: {
        type: Object,
        default: () => ({})
    },
    currentPage: {
        type: Number,
        required: true
    },
    perPage: {
        type: Number,
        required: true
    },
    tableHeaders: {
        type: Array,
        default: () => [
            '#',
            'File Name',
            'Video Status',
            'Prediction Class',
            'Prediction Probability',
            'Upload Time'
        ]
    }
});

const statusOptions = [
    { label: 'Queued', value: 'queued' },
    { label: 'Processing', value: 'processing' },
    { label: 'Completed', value: 'completed' }
];

const predictionOptions = [
    { label: 'Real', value: 'real' },
    { label: 'Fake', value: 'fake' }
];

const periodOptions = [
    { label: 'Today', value: 'today' },
    { label: 'This Week', value: 'week' },
    { label: 'This Month', value: 'month' }
];

const getIndex = (index) => {
    return (props.currentPage - 1) * props.perPage + index + 1;
};
</script>

<template>
    <div class="w-full flex flex-col md:flex-row justify-between items-start md:items-center mb-3 mt-1 p-4 rounded-xl bg-white dark:bg-gray-800">
        <div class="mb-4 md:mb-0">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Uploaded Video Reports</h3>
            <p class="text-gray-500 dark:text-gray-400">Overview of the uploaded videos.</p>
        </div>

        <!-- Filters Section -->
        <div class="flex flex-wrap gap-4">
            <FilterDropdown
                label="Filter by Status"
                :options="statusOptions"
                :model-value="filters.status"
                param-name="status"
            />
            <FilterDropdown
                label="Filter by Prediction"
                :options="predictionOptions"
                :model-value="filters.prediction"
                param-name="prediction"
            />
            <FilterDropdown
                label="Filter by Period"
                :options="periodOptions"
                :model-value="filters.period"
                param-name="period"
            />
        </div>
    </div>

    <div class="relative flex flex-col w-full h-full overflow-x-auto text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <table class="w-full text-left table-auto min-w-max">
            <thead>
            <tr>
                <th class="p-4 border-b border-gray-200 dark:border-gray-700 bg-slate-50 border-blue-gray-100"  v-for="(header, index) in tableHeaders" :key="index">
                    <p class="block text-sm font-bold leading-none text-slate-500">
                        {{ header }}
                    </p>
                </th>

            </tr>
            </thead>
            <tbody>
            <tr class="hover:bg-slate-50" v-for="(video, index) in videos" :key="index">
                <td class="p-4 border-b border-gray-200 dark:border-gray-700 py-5">
                    <p class="block font-semibold text-sm text-slate-800">{{ getIndex(index) }}</p>
                </td>
                <td class="p-4 border-b border-gray-200 dark:border-gray-700 py-5">
                    <Link
                    :href="`/report/${video.id}`"
                    class="block font-semibold text-sm text-slate-800 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                >
                    {{ video.filename }}
                </Link>

                </td>
                <td class="p-4 border-b border-gray-200 dark:border-gray-700 py-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ video.video_status }}</p>
                </td>
                <td class="p-4 border-b border-gray-200 dark:border-gray-700 py-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ video.predicted_class }}</p>
                </td>
                <td class="p-4 border-b border-gray-200 dark:border-gray-700 py-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ formatPercentage(video.prediction_probability) }}
                    </p>
                </td>
                <td class="p-4 border-b border-gray-200 dark:border-gray-700 py-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ formatDate(video.created_at) }}
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

</template>

<style scoped>

</style>
