<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import VideoUpload from "@/Pages/VideoUpload.vue";

defineProps({
    flash: {
        type: Object,
        default: () => ({})
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Video Upload Dashboard
                </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Upload Component -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <VideoUpload />
                    </div>
                </div>

                <!-- Quick Tips -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-indigo-500 dark:text-indigo-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Quick Upload</h4>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Drag and drop your videos directly into the upload area for faster processing.
                        </p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-indigo-500 dark:text-indigo-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Supported Formats</h4>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            We support MP4, MKV, and GIF formats with a maximum file size of 20MB.
                        </p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-indigo-500 dark:text-indigo-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">AI Vision Insights</h4>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Watch key detection areas which caught our AI attention in his decision making.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
