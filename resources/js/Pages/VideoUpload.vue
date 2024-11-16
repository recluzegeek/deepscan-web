<script setup>
import {ref, computed} from "vue";
import {useForm} from '@inertiajs/vue3';
import FileUploadList from "@/Components/FileUploadList.vue";

const isDragging = ref(false);
const MAX_FILE_SIZE = 20 * 1024 * 1024; // 20MB in bytes
const ACCEPTED_TYPES = ['video/mp4', 'video/x-matroska', 'video/x-msvideo', 'video/x-flv'];

const form = useForm({
    video: [],
    progress: null
});

const success_flash_message = ref('');
const server_errors = ref({}); // Store server validation errors
const clientErrors = ref({}); // Store client validation errors

// Helper function to validate a single file
function validateFile(file) {
    const errors = [];

    if (!ACCEPTED_TYPES.includes(file.type)) {
        errors.push("File must be a valid video format (MP4, MKV, AVI, FLV)");
    }

    if (file.size > MAX_FILE_SIZE) {
        errors.push("Video exceeds 20MB file size limit");
    }

    return errors;
}

function updateFileList(newFiles) {
    const existingFileNames = new Set(form.video.map(file => file.name + file.size));
    const uniqueFiles = newFiles.filter(file => !existingFileNames.has(file.name + file.size));

    // Validate each new file before adding
    uniqueFiles.forEach(file => {
        const fileErrors = validateFile(file);
        if (fileErrors.length > 0) {
            clientErrors.value[file.name] = fileErrors;
        }
    });

    form.video = [...form.video, ...uniqueFiles];
}

function handleRemoveFile(index) {
    const fileName = form.video[index].name;
    form.video.splice(index, 1);
    // Remove client errors for this file if they exist
    if (clientErrors.value[fileName]) {
        delete clientErrors.value[fileName];
    }
}

function submit() {
    server_errors.value = {}; // Clear previous server errors
    form.post('/videos', {
        onProgress: (progress) => {
            form.progress = progress;
        },
        onSuccess: (response) => {
            form.reset();
            clientErrors.value = {};
            server_errors.value = {};
            success_flash_message.value = response.props.flash.message;
            setTimeout(() => success_flash_message.value = '', 5000);
        },
        onError: (errors) => {
            // Parse server validation errors
            server_errors.value = errors;
            // Auto-clear server errors after 5 seconds
            setTimeout(() => server_errors.value = {}, 5000);
        },
        preserveScroll: true,
    });
}

// Computed property to check if form can be submitted
const canSubmit = computed(() => {
    return form.video.length > 0 &&
           Object.keys(clientErrors.value).length === 0 &&
           !form.processing;
});

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
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

function handleDragOver(event) {
    event.preventDefault();
}

function triggerFileInput() {
    document.getElementById('dropzone-file').click();
}

// Add this new function to handle clearing all files
function clearAllFiles() {
    form.video = [];
    clientErrors.value = {}
}
</script>

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

                <!-- Server Error Messages -->
                <div v-if="Object.keys(server_errors).length > 0"
                     class="flex items-center p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-700/50 dark:text-red-400"
                     role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <div class="ml-3">
                        <div class="font-medium">Server validation errors:</div>
                        <ul class="mt-1.5 ml-4 list-disc list-inside">
                            <template v-for="(errors, key) in server_errors" :key="key">
                                <li v-for="(error, index) in errors" :key="`${key}-${index}`">
                                    {{ error }}
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                <!-- Client Error Messages -->
                <div v-if="Object.keys(clientErrors).length > 0"
                     class="flex items-center p-4 text-sm text-orange-800 rounded-lg bg-orange-50 dark:bg-gray-700/50 dark:text-orange-400"
                     role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <span class="font-medium">Please fix the issues before uploading</span>
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
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                        Upload your videos
                                    </h3>
                                    <p class="mt-1 text-base text-gray-600 dark:text-gray-400">
                                        Drag and drop or <span class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">browse</span>
                                    </p>
                                </div>

                                <!-- Video Guidelines -->
                                <div class="max-w-sm mx-auto space-y-3 text-sm">
                                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Face centered in frame for best results</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <span>Multiple faces may reduce accuracy</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        <span>Use high-quality, uncompressed video</span>
                                    </div>
                                </div>

                                <!-- File Type & Size Info -->
                                <div class="space-y-3">
                                    <div class="flex flex-wrap justify-center gap-2">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 rounded-full border border-indigo-100 dark:border-indigo-800">
                                            MP4
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 rounded-full border border-indigo-100 dark:border-indigo-800">
                                            MKV
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 rounded-full border border-indigo-100 dark:border-indigo-800">
                                            GIF
                                        </span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        Maximum file size: <span class="text-indigo-600 dark:text-indigo-400">20MB</span>
                                    </p>
                                </div>
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

                <!-- File List with individual client errors -->
                <div v-if="form.video.length > 0" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            Selected Files ({{ form.video.length }})
                        </h4>
                        <button
                            @click="clearAllFiles"
                            type="button"
                            class="p-2 text-gray-500 hover:text-red-500 dark:text-gray-400 dark:hover:text-red-400 transition-colors duration-200"
                            title="Clear all files"
                        >
                            <svg 
                                xmlns="http://www.w3.org/2000/svg" 
                                class="h-6 w-6" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor"
                            >
                                <path 
                                    stroke-linecap="round" 
                                    stroke-linejoin="round" 
                                    stroke-width="2" 
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm divide-y divide-gray-200 dark:divide-gray-700">
                        <FileUploadList
                            v-for="(file, index) in form.video"
                            :key="index"
                            :name="file.name"
                            :size="formatFileSize(file.size)"
                            :index="index"
                            :errors="clientErrors[file.name]"
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

                    <!-- Update Upload Button -->
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            :disabled="!canSubmit"
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
