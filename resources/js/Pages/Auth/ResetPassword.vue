<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    input_type: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    input_type: props.input_type,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-indigo-900 dark:text-indigo-100">
                Set New Password
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Please create a strong password for your account
            </p>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="input_type" value="Email / Username" />
                <TextInput
                    id="input_type"
                    type="text"
                    class="mt-1 block w-full bg-gray-50"
                    v-model="form.input_type"
                    required
                    readonly
                    disabled
                />
                <InputError class="mt-2" :message="form.errors.email" />
                <InputError class="mt-2" :message="form.errors.username" />
            </div>

            <div class="mt-6 space-y-6">
                <div>
                    <InputLabel for="password" value="New Password" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autofocus
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div>
                    <InputLabel for="password_confirmation" value="Confirm Password" />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>
            </div>

            <div class="mt-6">
                <PrimaryButton class="w-full justify-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Reset Password
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
