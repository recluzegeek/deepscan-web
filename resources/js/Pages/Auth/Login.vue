<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    input_type: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                Welcome back
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Please sign in to your account
            </p>
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="input_type" value="Email/Username" class="text-gray-700 dark:text-gray-300" />
                <TextInput
                    id="input_type"
                    type="text"
                    class="mt-1 block w-full bg-white dark:bg-gray-700/50"
                    v-model="form.input_type"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
                <InputError class="mt-2" :message="form.errors.username" />
            </div>

            <div>
                <InputLabel for="password" value="Password" class="text-gray-700 dark:text-gray-300" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full bg-white dark:bg-gray-700/50"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <Checkbox 
                        name="remember" 
                        v-model:checked="form.remember" 
                        class="rounded border-gray-300 dark:border-gray-600 text-indigo-500 dark:text-indigo-400 shadow-sm focus:ring-indigo-400 dark:focus:ring-indigo-400/50 dark:focus:ring-offset-gray-800" 
                    />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-indigo-500 dark:text-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition-colors duration-200"
                >
                    Forgot your password?
                </Link>
            </div>

            <div>
                <PrimaryButton 
                    class="w-full justify-center bg-indigo-500 dark:bg-indigo-500/90 hover:bg-indigo-600 dark:hover:bg-indigo-500" 
                    :class="{ 'opacity-25': form.processing }" 
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>

            <div class="text-center">
                <Link
                    :href="route('register')"
                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 transition-colors duration-200"
                >
                    Don't have an account? 
                    <span class="text-indigo-500 dark:text-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-300">Register</span>
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
