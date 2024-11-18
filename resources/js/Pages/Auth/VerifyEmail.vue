<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-indigo-900 dark:text-indigo-100">
                Verify Your Email
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.
            </p>
        </div>

        <div v-if="status === 'verification-link-sent'" class="mb-4 font-medium text-sm text-green-600">
            A new verification link has been sent to your email address.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-6 flex items-center justify-between">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Resend Verification Email
                </PrimaryButton>

                <form @submit.prevent="$inertia.post(route('logout'))">
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300"
                    >
                        Log Out
                    </Link>
                </form>
            </div>
        </form>
    </GuestLayout>
</template>
