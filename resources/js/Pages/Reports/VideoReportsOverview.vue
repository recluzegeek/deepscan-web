<script setup>
import {Head, Link} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TableRow from "@/Components/TableRow.vue";
import Pagination from '@/Components/Pagination.vue';

defineProps({
    videos: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        required: true
    }
});

</script>

<template>
    <Head title="Reports" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Reports</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- No Videos State -->
                <div v-if="!videos.data.length" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">No Videos Found</h3>
                        <p class="text-gray-500 dark:text-gray-400 max-w-sm">
                            Start by uploading a video from your dashboard to analyze it for deepfake content.
                        </p>
                        <Link
                            :href="route('dashboard')"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Upload Video
                        </Link>
                    </div>
                </div>

                <TableRow 
                    :videos="videos.data" 
                    :filters="filters"
                    :current-page="videos.current_page"
                    :per-page="videos.per_page"
                />

                <!-- Pagination -->
                <div class="mt-6">
                    <Pagination :links="videos.links" />
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
