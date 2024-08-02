<template>
    <form @submit.prevent="submit">
        <div class="flex items-center justify-center w-full">
            <label class="flex flex-col items-center justify-center w-3/4 h-[32rem] border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-600"
                   @click.stop.prevent="() => {}"
            >

                <div v-if="success_flash_message" class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <div class="font-semibold"> {{ success_flash_message }} </div>
                </div>

                <div class="flex flex-col items-center justify-center pt-5 pb-6 bg-gray-200 w-3/4 my-4 rounded-lg cursor-pointer"
                     @drop.prevent="handleDrop"
                     @dragover.prevent="handleDragOver"
                     @click.stop="triggerFileInput">
                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">MP4, MKV, GIF (MAX File Size: 10 MB)</p>
                    <input id="dropzone-file" type="file" class="hidden" @change="handleFileChange" multiple />
                </div>

                <InputError class="mt-2" :message="form.errors.video"/>

                <div class="mt-2 flex flex-col divide-y divide-gray-200 dark:divide-gray-800 w-3/4 overflow-y-auto">

                    <FileUploadList
                        v-for="(file, index) in form.video"
                        :key="index"
                        :name="file.name"
                        :index=index
                        :is_form_processing="form.processing"
                        @remove-file="handleRemoveFile(index)"
                    />

                    <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700 my-2" v-if="form.progress">
                        <div class="bg-black p-0.5 leading-none rounded-full text-white font-semibold text-center" :style="`width: ${form.progress.percentage}%`">
                            {{ form.progress.percentage }}%
                        </div>
                    </div>

                </div>

                <button
                    v-if="form.video.length > 0"
                    class="inline-flex items-center justify-center p-4 mt-4 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 w-3/4"
                    :disabled="form.processing"
                    @click="submit"
                >
                    Upload Video
                </button>
            </label>
        </div>

    </form>
</template>

<script setup>
import {ref} from "vue";
import {useForm} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import FileUploadList from "@/Components/FileUploadList.vue";

const form = useForm({
    video: []
});

const success_flash_message = ref('');

function submit() {
    form.post('/videos',{
        onProgress: (progress) => {
            form.progress = progress;
        },
        onSuccess: (response) => {
            form.reset();
            success_flash_message.value = response.props.flash.message
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
    updateFileList(selectedFiles)
}

function updateFileList(newFiles) {
    const existingFileNames = new Set(form.video.map(file => file.name + file.size));

    // Filter out duplicates
    const uniqueFiles = newFiles.filter(file => !existingFileNames.has(file.name + file.size));

    // Update the list with unique files
    form.video = [...form.video, ...uniqueFiles];
}

function handleDragOver(event) {
    event.preventDefault();
}

function handleRemoveFile(index) {
    form.video.splice(index, 1)
}

function triggerFileInput() {
    const fileInput = document.getElementById('dropzone-file');
    if (fileInput) {
        fileInput.click();
    }
}
</script>
