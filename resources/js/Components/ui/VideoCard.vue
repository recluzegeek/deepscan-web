<script setup>

</script>

<template>
    <div class="mt-2 flex flex-col divide-y divide-gray-200 dark:divide-gray-800">
        <div v-for="(file, index) in files" :key="index" class="py-2">
            <div class="flex items-center gap-2 truncate text-sm">
<!--                <PiImageDuotone class="size-5" />-->
                <span class="font-mono font-medium">{{ file.name }}</span>
            </div>
            <div class="progress-bar mt-1 flex h-2 w-full items-center gap-6 rounded-full bg-gray-100">
                <div
                    class="h-2 w-full rounded-full bg-black backdrop-blur-sm transition-all duration-500 ease-in-out dark:bg-blue-900"
                    :style="{ width: `${uploadProgress[file.name] || 0}%` }"
                ></div>
            </div>
        </div>
    </div>

</template>

<style scoped>

</style>
