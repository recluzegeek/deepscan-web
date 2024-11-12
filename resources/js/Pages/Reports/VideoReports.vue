<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TableRow from "@/Components/TableRow.vue";

defineProps({
    videos: {
        type: Array
    }
});

</script>

<template>
    <Head title="Video Reports" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Video Reports</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <TableRow :videos="videos" />

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
