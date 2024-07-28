<script setup>
import { useForm } from '@inertiajs/vue3'
import InputError from "@/Components/InputError.vue";

const form = useForm({
    video: null,
})

function submit() {
    form.post('/videos')
}
</script>

<template>
    <form @submit.prevent="submit">
        <input type="file" @input="form.video = $event.target.files[0]" />
        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
            {{ form.progress.percentage }}%
        </progress>
        <button type="submit">Submit</button>
    </form>

    <InputError class="mt-2" :message="form.errors.video" />

</template>
