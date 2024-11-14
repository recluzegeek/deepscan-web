<template>
    <form @submit.prevent="submit">
        <div class="flex items-center justify-center w-full">
            <div class="w-full space-y-6">
                <!-- Success Message -->
                <div v-if="success_flash_message" 
                     class="flex items-center p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-700/50 dark:text-green-400" 
                     role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <span class="font-medium">{{ success_flash_message }}</span>
                </div>

                <!-- Upload Area -->
                <div class="flex flex-col items-center justify-center w-full">
                    <label 
                        :class="[
                            'relative flex flex-col items-center justify-center w-full transition-all duration-300 ease-in-out',
                            'min-h-[300px] rounded-xl cursor-pointer',
                            'border-2 border-dashed',
                            isDragging 
                                ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-950/20' 
                                : 'border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800/50',
                            'hover:border-indigo-500 dark:hover:border-indigo-400',
                            'hover:bg-indigo-50 dark:hover:bg-indigo-950/20'
                        ]"
                        @drop.prevent="handleDrop"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @click.stop="triggerFileInput"
                    >
                        <div class="flex flex-col items-center justify-center p-6 text-center">
                            <!-- Upload Icon -->
                            <div class="mb-4">
                                <svg 
                                    class="w-16 h-16 text-gray-400 dark:text-gray-500" 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor"
                                >
                                    <path 
                                        stroke-linecap="round" 
                                        stroke-linejoin="round" 
                                        stroke-width="1.5" 
                                        d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"
                                    />
                                </svg>
                            </div>

                            <!-- Upload Text -->
                            <div class="space-y-2">
                                <p class="text-xl font-medium text-gray-700 dark:text-gray-200">
                                    Drop your videos here
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    or <span class="text-indigo-500 dark:text-indigo-400">browse</span> to choose files
                                </p>
                                <div class="flex flex-wrap justify-center gap-2 mt-2">
                                    <span class="px-3 py-1 text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700/50 rounded-full">
                                        MP4
                                    </span>
                                    <span class="px-3 py-1 text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700/50 rounded-full">
                                        MKV
                                    </span>
                                    <span class="px-3 py-1 text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700/50 rounded-full">
                                        GIF
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    Maximum file size: 10MB
                                </p>
                            </div>
                        </div>

                        <input 
                            id="dropzone-file" 
                            type="file" 
                            class="hidden" 
                            @change="handleFileChange" 
                            multiple 
                            accept="video/mp4,video/x-matroska,image/gif" 
                        />
                    </label>
                </div>

                <!-- Error Message -->
                <InputError class="mt-2" :message="form.errors.video"/>

                <!-- File List -->
                <div v-if="form.video.length > 0" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            Selected Files ({{ form.video.length }})
                        </h4>
                        <button 
                            @click="form.video = []"
                            type="button"
                            class="text-sm text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300"
                        >
                            Clear All
                        </button>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm divide-y divide-gray-200 dark:divide-gray-700">
                        <FileUploadList
                            v-for="(file, index) in form.video"
                            :key="index"
                            :name="file.name"
                            :size="formatFileSize(file.size)"
                            :index="index"
                            :is_form_processing="form.processing"
                            @remove-file="handleRemoveFile(index)"
                        />
                    </div>

                    <!-- Progress Bar -->
                    <div v-if="form.progress" class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold inline-block text-gray-600 dark:text-gray-400">
                                    Upload Progress
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold inline-block text-gray-600 dark:text-gray-400">
                                    {{ form.progress.percentage }}%
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded-full bg-gray-200 dark:bg-gray-700">
                            <div 
                                class="transition-all duration-300 shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500"
                                :style="`width: ${form.progress.percentage}%`"
                            >
                            </div>
                        </div>
                    </div>

                    <!-- Upload Button -->
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            :disabled="form.processing || form.video.length === 0"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-lg font-semibold text-sm text-white dark:text-white uppercase tracking-wider hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 dark:focus:bg-indigo-600 active:bg-indigo-800 dark:active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 disabled:opacity-50"
                        >
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ form.processing ? 'Uploading...' : 'Upload Videos' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script setup>
import {ref} from "vue";
import {useForm} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import FileUploadList from "@/Components/FileUploadList.vue";

const isDragging = ref(false);
const form = useForm({
    video: [],
    progress: null
});

const success_flash_message = ref('');

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function submit() {
    form.post('/videos', {
        onProgress: (progress) => {
            form.progress = progress;
        },
        onSuccess: (response) => {
            form.reset();
            success_flash_message.value = response.props.flash.message;
            setTimeout(() => success_flash_message.value = '', 5000);
        },
        preserveScroll: true,
    });
}

function handleDrop(event) {
    event.preventDefault();
    const droppedFiles = Array.from(event.dataTransfer.files);
    updateFileList(droppedFiles);
}

function handleFileChange(event) {
    const selectedFiles = Array.from(event.target.files);
    updateFileList(selectedFiles);
}

function updateFileList(newFiles) {
    const existingFileNames = new Set(form.video.map(file => file.name + file.size));
    const uniqueFiles = newFiles.filter(file => !existingFileNames.has(file.name + file.size));
    form.video = [...form.video, ...uniqueFiles];
}

function handleDragOver(event) {
    event.preventDefault();
}

function handleRemoveFile(index) {
    form.video.splice(index, 1);
}

function triggerFileInput() {
    document.getElementById('dropzone-file').click();
}
</script>