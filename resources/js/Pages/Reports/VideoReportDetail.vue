<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {formatDate, formatPercentage} from '@/Utils/formatters';

const props = defineProps({
    report: {
        type: Object,
        required: true
    },
    frames: {
        type: Array,
        required: true
    }
});

</script>

<template>
    <Head title="Report Details"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Video Report Details
                </h2>
                <button
                    @click="$inertia.visit('/reports')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Back to Reports
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Video Information Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Video Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">File Name</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ report.filename }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ report.video_status }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Upload Time</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ formatDate(report.created_at) }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Prediction</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ report.predicted_class }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Confidence</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">
                                {{ formatPercentage(report.prediction_probability) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Frame Comparison Gallery -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Frame Analysis</h3>
                    <div class="space-y-6">
                        <template v-for="frame in frames" :key="frame.id">
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Original Frame -->
                                <div class="w-full md:w-1/2 relative group">
                                    <img :src="frame.original"
                                         :alt="`Frame ${frame.id}`"
                                         class="w-full h-auto rounded-lg shadow-sm transition duration-300 group-hover:shadow-lg"/>
                                    <span class="absolute top-2 left-2 bg-white/90 dark:bg-gray-800/90 px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Original Frame {{ frame.id }}
                                    </span>
                                </div>

                                <!-- Visualized Frame -->
                                <div class="w-full md:w-1/2 relative group">
                                    <img :src="frame.visualized"
                                         :alt="`Frame ${frame.id} Visualization`"
                                         class="w-full h-auto rounded-lg shadow-sm transition duration-300 group-hover:shadow-lg"/>
                                    <span class="absolute top-2 left-2 bg-white/90 dark:bg-gray-800/90 px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Grad-CAM Visualization
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
