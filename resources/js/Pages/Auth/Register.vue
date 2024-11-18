<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                Create your account
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Join us to start detecting vulnerabilities
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <InputLabel for="name" value="Full Name" class="text-gray-700 dark:text-gray-300" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full bg-white dark:bg-gray-700/50"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="John Doe"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="username" value="Username" class="text-gray-700 dark:text-gray-300" />
                    <TextInput
                        id="username"
                        type="text"
                        class="mt-1 block w-full bg-white dark:bg-gray-700/50"
                        v-model="form.username"
                        required
                        autocomplete="username"
                        placeholder="johndoe"
                    />
                    <InputError class="mt-2" :message="form.errors.username" />
                </div>
            </div>

            <div>
                <InputLabel for="email" value="Email" class="text-gray-700 dark:text-gray-300" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full bg-white dark:bg-gray-700/50"
                    v-model="form.email"
                    required
                    autocomplete="email"
                    placeholder="john@example.com"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <InputLabel for="password" value="Password" class="text-gray-700 dark:text-gray-300" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full bg-white dark:bg-gray-700/50"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div>
                    <InputLabel for="password_confirmation" value="Confirm Password" class="text-gray-700 dark:text-gray-300" />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full bg-white dark:bg-gray-700/50"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>
            </div>

            <div>
                <PrimaryButton 
                    class="w-full justify-center bg-indigo-500 dark:bg-indigo-500/90 hover:bg-indigo-600 dark:hover:bg-indigo-500" 
                    :class="{ 'opacity-25': form.processing }" 
                    :disabled="form.processing"
                >
                    Create Account
                </PrimaryButton>
            </div>

            <div class="text-center">
                <Link
                    :href="route('login')"
                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 transition-colors duration-200"
                >
                    Already have an account?
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
