<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {formatDate, formatPercentage} from '@/Utils/formatters';
import LazyImage from '@/Components/LazyImage.vue';

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

// Helper function to get status message and icon
function getStatusInfo(status) {
    const statusMap = {
        'queued': {
            message: 'Your video is currently in the processing queue. Please check back later.',
            icon: `<svg class="w-8 h-8 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>`,
            color: 'text-yellow-600 dark:text-yellow-400'
        },
        'processing': {
            message: 'Your video is currently being analyzed. This may take a few minutes.',
            icon: `<svg class="w-8 h-8 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>`,
            color: 'text-blue-600 dark:text-blue-400'
        },
        'error': {
            message: 'An error occurred while processing your video. Please try uploading it again.',
            icon: `<svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>`,
            color: 'text-red-600 dark:text-red-400'
        },
        'completed': {
            message: 'Analysis completed successfully.',
            icon: `<svg class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>`,
            color: 'text-green-600 dark:text-green-400'
        }
    };

    return statusMap[status] || statusMap['error'];
}

const statusInfo = getStatusInfo(props.report.video_status);
</script>

<template>
    <Head title="Report Details"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Video Report Details
                </h2>
                <div class="flex items-center space-x-4">
                    <button
                        @click="$inertia.visit('/reports')"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    >
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Back to Reports
                    </button>
                </div>
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
                    
                    <!-- Status Message for non-completed states -->
                    <div v-if="report.video_status !== 'completed'" 
                         class="flex flex-col items-center justify-center p-8 space-y-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div v-html="statusInfo.icon"></div>
                        <p class="text-lg font-medium" :class="statusInfo.color">
                            {{ statusInfo.message }}
                        </p>
                        
                        <!-- Additional guidance based on status -->
                        <div class="text-sm text-gray-500 dark:text-gray-400 text-center max-w-md">
                            <p v-if="report.video_status === 'queued' && report.queue_info">
                                <span v-if="report.queue_info.position === 1">
                                    Your video is next in line for processing.
                                </span>
                                <span v-else>
                                    There {{ report.queue_info.position - 1 === 1 ? 'is' : 'are' }} 
                                    {{ report.queue_info.position - 1 }} 
                                    {{ report.queue_info.position - 1 === 1 ? 'video' : 'videos' }} ahead of yours.
                                </span>
                                <br>
                                <span class="mt-2 block">
                                    Estimated wait time: {{ report.queue_info.estimated_time }}
                                </span>
                            </p>
                        </div>

                        <!-- Refresh button -->
                        <button
                            @click="$inertia.reload({ preserveScroll: true })"
                            class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Refresh Status
                        </button>
                    </div>

                    <!-- Frame comparison gallery for completed videos -->
                    <div v-else-if="frames.length > 0" class="space-y-6">
                        <template v-for="(frame, index) in frames" :key="index">
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Original Frame -->
                                <div class="w-full md:w-1/2">
                                    <LazyImage
                                        :src="frame.original"
                                        :alt="`Original Frame ${index + 1}`"
                                        :label="`Original Frame ${index + 1}`"
                                    />
                                </div>

                                <!-- Visualized Frame -->
                                <div class="w-full md:w-1/2">
                                    <LazyImage
                                        :src="frame.visualized"
                                        :alt="`Grad-CAM Visualization ${index + 1}`"
                                        :label="'Grad-CAM Visualization'"
                                    />
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- No frames available message -->
                    <div v-else class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <p>No frames are available for this video.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
