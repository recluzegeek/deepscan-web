<template>
    <form @submit.prevent="submit">
        <div class="flex items-center justify-center w-full">
            <label for="dropzone-file"
                   class="flex flex-col items-center justify-center w-3/4 h-96 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                   @drop="handleDrop"
                   @dragover="handleDragOver">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                        class="font-semibold">Click to upload</span> or drag and drop</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">MP4, MKV, GIF (MAX File Size: 10 MB)</p>
                    <input id="dropzone-file" type="file" class="hidden" @change="handleFileChange" multiple/>
                </div>

                <div class="mt-2 flex flex-col divide-y divide-gray-200 dark:divide-gray-800 w-3/4 overflow-y-auto">
                    <div v-for="(file, index) in files" :key="index" class="py-2 mx-12">
                        <div class="flex items-center justify-between w-full text-sm my-1">
                            <div class="flex items-center gap-2 flex-grow">
                                <img
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAoElEQVR4nO2V0QnDIBCGvzxkATNG5khW6AadQGgKPviYXeIMHaRrdACLcHkptD0iWgr+cHjID5934glNmRoAC1wOhAWMBnIVYw/cgUn2A7BIntZN8kl8vRxw93yUF/M3SJB8foF4LSRmhG+QWLNdI3ArDUnqgDPwKAnZdfrrSsYadxLbY/xZu0zGqPfa/yRn1C8aSKrCAeuBcNqfsYl3egKac+n/ekuWvQAAAABJRU5ErkJggg=="
                                    alt="File Icon"
                                    class="w-8 h-8"
                                />
                                <span class="font-mono font-medium">{{ file.name }}</span>
                            </div>
                            <span @click="removeFile(index)" class="cursor-pointer text-red-500 hover:fill-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="26" height="26"
                                     viewBox="0,0,300,150"><g fill="#ff0000" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(3.55556,3.55556)"><path d="M32,13c-1.105,0 -2,0.895 -2,2v1h-13c-2.209,0 -4,1.791 -4,4c0,1.97365 1.43236,3.60263 3.3125,3.92969l2.39453,28.73437c0.346,4.147 3.81361,7.33594 7.97461,7.33594h18.63672c4.161,0 7.62761,-3.18894 7.97461,-7.33594l2.39453,-28.73437c1.88014,-0.32705 3.3125,-1.95604 3.3125,-3.92969c0,-2.209 -1.791,-4 -4,-4h-13v-1c0,-1.105 -0.895,-2 -2,-2zM24.34766,24h23.30469l-2.25586,27.08203c-0.044,0.518 -0.47805,0.91797 -0.99805,0.91797h-16.79688c-0.52,0 -0.95409,-0.39997 -0.99609,-0.91797z"></path></g></g></svg>
                            </span>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                            <div
                                class="bg-black text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                style="width: 90%"
                            >
                            </div>
                        </div>

                    </div>

                </div>

                <PrimaryButton>Upload Button</PrimaryButton>
            </label>

        </div>

        <!--        <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700" v-if="form.progress">-->
        <!--            <div-->
        <!--                class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"-->
        <!--                :style="getProgressBarWidth()"-->
        <!--            >-->
        <!--                {{ form.progress.percentage }}%-->
        <!--            </div>-->
        <!--        </div>-->

        <InputError class="mt-2" :message="form.errors.video"/>
    </form>
</template>


<script setup>
import {ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const form = useForm({
    video: null,
});

const files = ref([]);
const uploadProgress = ref({});

// Function to submit the form
function submit() {
    form.post('/videos', {
        onFinish: () => {
            form.reset('video');
            files.value = []; // Clear the files array
        },
        onProgress: (progress) => {
            form.progress = progress; // Capture the progress
        }
    });
}

// Handle file selection
function handleFileChange(event) {
    const selectedFiles = Array.from(event.target.files);
    updateFileList(selectedFiles);
}

// Handle drag and drop
function handleDrop(event) {
    event.preventDefault();
    const droppedFiles = Array.from(event.dataTransfer.files);
    updateFileList(droppedFiles);
}

// Prevent default behavior when dragging over the drop zone
function handleDragOver(event) {
    event.preventDefault();
}

// Update the files array with new files
function updateFileList(newFiles) {
    const validFiles = newFiles.filter(file => file.size <= 10 * 1024 * 1024);
    files.value = [...new Set([...files.value, ...validFiles])]; // Ensure no duplicates
}

// Remove a file from the list
function removeFile(index) {
    files.value.splice(index, 1);
}

// Trigger the hidden file input
function triggerFileInput() {
    const fileInput = document.getElementById('dropzone-file');
    if (fileInput) {
        fileInput.click();
    }
}

// Get the progress bar width
function getProgressBarWidth() {
    return `width: ${form.progress.percentage}%;`
}
</script>

<style scoped>
/* Add any additional styles here */
</style>
