<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    input_type: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-indigo-900 dark:text-indigo-100">
                Reset Password
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Enter your email or username and we'll send you a password reset link
            </p>
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="input_type" value="Email / Username" />
                <TextInput
                    id="input_type"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.input_type"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
                <InputError class="mt-2" :message="form.errors.username" />
            </div>

            <div class="mt-6 flex items-center justify-between">
                <Link
                    :href="route('login')"
                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300"
                >
                    Back to login
                </Link>

                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Email Reset Link
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
