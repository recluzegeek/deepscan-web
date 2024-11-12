<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

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

                Here lies video report tables

                <ul>
                    <li v-for="(video, index) in videos" :key="index">{{ video.filename }} - {{ video.video_status }} - {{ video.predicted_class }}</li>
                </ul>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
