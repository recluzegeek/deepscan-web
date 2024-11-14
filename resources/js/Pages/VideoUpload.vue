<template>
    <form @submit.prevent="submit">
        <div class="flex items-center justify-center w-full">
            <div class="w-full p-6 space-y-6">
                <!-- Success Message -->
                <div v-if="success_flash_message" 
                     class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" 
                     role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <span class="font-medium">{{ success_flash_message }}</span>
                </div>

                <!-- Upload Area -->
                <div class="flex flex-col items-center justify-center w-full">
                    <label class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-700 dark:bg-gray-800 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500"
                           @drop.prevent="handleDrop"
                           @dragover.prevent="handleDragOver"
                           @click.stop="triggerFileInput">
                        
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-semibold">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">MP4, MKV, GIF (MAX File Size: 10 MB)</p>
                        </div>
                        <input id="dropzone-file" type="file" class="hidden" @change="handleFileChange" multiple accept="video/mp4,video/x-matroska,image/gif" />
                    </label>
                </div>

                <!-- Error Message -->
                <InputError class="mt-2" :message="form.errors.video"/>

                <!-- File List -->
                <div v-if="form.video.length > 0" class="mt-6 space-y-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Selected Files</h4>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow divide-y divide-gray-200 dark:divide-gray-700">
                        <FileUploadList
                            v-for="(file, index) in form.video"
                            :key="index"
                            :name="file.name"
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
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200 dark:bg-gray-700">
                            <div class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500"
                                 :style="`width: ${form.progress.percentage}%`">
                            </div>
                        </div>
                    </div>

                    <!-- Upload Button -->
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 disabled:opacity-50"
                        >
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white dark:text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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

const form = useForm({
    video: [],
    progress: null
});

const success_flash_message = ref('');

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