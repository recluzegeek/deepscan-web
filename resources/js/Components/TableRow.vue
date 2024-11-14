<script setup>
import {formatDate, formatPercentage} from '@/Utils/formatters';
import {Link} from '@inertiajs/vue3'

defineProps({
    videos: {
        type: Array
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

</script>

<template>
    <div class="w-full flex flex-col md:flex-row justify-between items-start md:items-center mb-3 mt-1 p-4 rounded-xl bg-white dark:bg-gray-800">
        <div class="mb-4 md:mb-0">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Uploaded Video Reports</h3>
            <p class="text-gray-500 dark:text-gray-400">Overview of the uploaded videos.</p>
        </div>
        <div class="w-full md:w-auto">
            <div class="w-full max-w-sm min-w-[200px] relative">
                <div class="relative">
                    <input
                        class="bg-white w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                        placeholder="Search for video..."
                    />
                    <button
                        class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center bg-white rounded "
                        type="button"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-slate-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </button>
                </div>
            </div>
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
                    <p class="block font-semibold text-sm text-slate-800">{{ index + 1 }}</p>
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
