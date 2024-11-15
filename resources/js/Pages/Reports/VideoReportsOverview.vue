<script setup>
import {Head} from "@inertiajs/vue3";
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
