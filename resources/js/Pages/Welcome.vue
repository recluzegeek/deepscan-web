<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { usePage } from '@inertiajs/vue3';

const { auth } = usePage().props;

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Welcome to DeepScan" />

    <div class="relative min-h-screen">
        <!-- Background gradient with animated effect -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-900 dark:to-indigo-950 -z-10">
            <div class="absolute inset-0 bg-[url('/grid.svg')] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))]"></div>
        </div>

        <!-- Navigation -->
        <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link :href="route('welcome')" class="flex items-center">
                            <ApplicationLogo class="w-8 h-8 text-gray-800 dark:text-gray-200" />
                            <span class="ml-2 text-xl font-semibold text-gray-900 dark:text-gray-100">DeepScan</span>
                        </Link>
                    </div>

                    <div class="flex items-center space-x-4">
                        <template v-if="auth.user">
                            <Link
                                :href="route('dashboard')"
                                class="rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                Go to Dashboard
                            </Link>
                        </template>
                        <template v-else>
                            <Link
                                v-if="canLogin"
                                :href="route('login')"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                Start Detecting Now
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section with Comparison -->
        <div class="relative isolate overflow-hidden">
            <div class="mx-auto max-w-7xl px-6 pt-24 sm:pt-32 lg:flex lg:items-center lg:gap-x-10 lg:px-8 lg:pt-40">
                <div class="mx-auto max-w-2xl lg:mx-0 lg:flex-auto">
                    <div class="flex">
                        <div class="relative flex items-center gap-x-4 rounded-full px-4 py-1 text-sm leading-6 text-gray-600 dark:text-gray-400 ring-1 ring-gray-900/10 dark:ring-gray-400/20 hover:ring-gray-900/20 dark:hover:ring-gray-400/30">
                            <span class="font-semibold text-indigo-600 dark:text-indigo-400">New</span>
                            <span class="h-4 w-px bg-gray-900/10 dark:bg-gray-400/20"></span>
                            <span>Advanced AI-powered detection launched</span>
                        </div>
                    </div>
                    <h1 class="mt-10 max-w-lg text-4xl font-bold tracking-tight text-gray-900 dark:text-gray-100 sm:text-6xl">
                        Detect Deepfakes with Unmatched Precision
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-400">
                        In a world where digital deception is becoming increasingly sophisticated, DeepScan empowers you with cutting-edge AI technology to identify manipulated videos with confidence.
                    </p>
                    <div class="mt-10 flex items-center gap-x-6">
                        <template v-if="auth.user">
                            <Link
                                :href="route('dashboard')"
                                class="rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                Go to Dashboard
                            </Link>
                        </template>
                        <template v-else>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                Start Detecting Now
                            </Link>
                        </template>
                        <a href="#features" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">
                            Learn more <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>

                <!-- Comparison Illustration -->
                <div class="mt-16 sm:mt-24 lg:mt-0 lg:flex-shrink-0 lg:flex-grow">
                    <div class="relative mx-auto w-full max-w-lg">
                        <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-white dark:bg-gray-800 shadow-xl ring-1 ring-gray-900/10 dark:ring-white/10">
                            <div class="group relative h-full cursor-pointer">
                                <!-- Original Image -->
                                <div class="absolute inset-0">
                                    <img src="/images/original.jpg" alt="Original" class="h-full w-full object-cover" />
                                    <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="text-white font-medium px-2 py-1 bg-black/50 rounded">Original</span>
                                    </div>
                                </div>
                                <!-- Deepfake Image (revealed on hover) -->
                                <div class="absolute inset-0 translate-x-[50%] group-hover:translate-x-0 transition-transform duration-300">
                                    <img src="/images/deepfake.jpg" alt="Deepfake" class="h-full w-full object-cover" />
                                    <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="text-white font-medium px-2 py-1 bg-black/50 rounded">Deepfake</span>
                                    </div>
                                </div>
                                <!-- Slider Line -->
                                <div class="absolute inset-y-0 left-[50%] group-hover:left-0 w-0.5 bg-white transition-all duration-300">
                                    <div class="absolute top-[50%] -translate-y-1/2 -translate-x-1/2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-lg">
                                        <svg class="w-4 h-4 text-gray-800" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:text-center">
                    <h2 class="text-base font-semibold leading-7 text-indigo-600 dark:text-indigo-400">
                        Powerful Detection
                    </h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl">
                        See What AI Sees
                    </p>
                    <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-400">
                        Our advanced AI doesn't just detect deepfakes - it shows you exactly how it makes its decisions, providing unprecedented transparency in video analysis.
                    </p>
                </div>

                <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                    <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                        <!-- AI Vision Insights -->
                        <div class="flex flex-col">
                            <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">
                                <svg class="h-5 w-5 flex-none text-indigo-600 dark:text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                AI Vision Insights
                            </dt>
                            <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600 dark:text-gray-400">
                                <p class="flex-auto">Watch how our AI spots fakes: see exactly which parts of your video caught its attention.</p>
                            </dd>
                        </div>

                        <!-- Privacy First -->
                        <div class="flex flex-col">
                            <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">
                                <svg class="h-5 w-5 flex-none text-indigo-600 dark:text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.661 2.237a.531.531 0 01.678 0 11.947 11.947 0 007.078 2.749.5.5 0 01.479.425c.069.52.104 1.05.104 1.59 0 5.162-3.26 9.563-7.834 11.256a.48.48 0 01-.332 0C5.26 16.564 2 12.163 2 7c0-.538.035-1.069.104-1.589a.5.5 0 01.48-.425 11.947 11.947 0 007.077-2.75z" clip-rule="evenodd" />
                                </svg>
                                Privacy First
                            </dt>
                            <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600 dark:text-gray-400">
                                <p class="flex-auto">Your content is automatically deleted after analysis. We never store your sensitive data.</p>
                            </dd>
                        </div>

                        <!-- Instant Results -->
                        <div class="flex flex-col">
                            <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">
                                <svg class="h-5 w-5 flex-none text-indigo-600 dark:text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M15.98 1.804a1 1 0 00-1.96 0l-.24 1.192a1 1 0 01-.784.785l-1.192.238a1 1 0 000 1.962l1.192.238a1 1 0 01.785.785l.238 1.192a1 1 0 001.962 0l.238-1.192a1 1 0 01.785-.785l1.192-.238a1 1 0 000-1.962l-1.192-.238a1 1 0 01-.785-.785l-.238-1.192zM6.949 5.684a1 1 0 00-1.898 0l-.683 2.051a1 1 0 01-.633.633l-2.051.683a1 1 0 000 1.898l2.051.684a1 1 0 01.633.632l.683 2.051a1 1 0 001.898 0l.683-2.051a1 1 0 01.633-.633l2.051-.683a1 1 0 000-1.898l-2.051-.683a1 1 0 01-.633-.633L6.95 5.684z" />
                                </svg>
                                Clear Results
                            </dt>
                            <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600 dark:text-gray-400">
                                <p class="flex-auto">Get detailed visual reports with confidence scores and highlighted areas of concern.</p>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Trust Section -->
        <div class="relative py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-y-16 gap-x-8 lg:grid-cols-2">
                    <div class="relative lg:order-2">
                        <div class="relative h-[450px] overflow-hidden rounded-2xl bg-gray-900/5 dark:bg-gray-100/5 p-8">
                            <!-- Add a trust-related illustration or stats here -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="grid grid-cols-2 gap-4 w-full max-w-md">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center shadow-lg">
                                        <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">99%</div>
                                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">Detection Accuracy</div>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center shadow-lg">
                                        <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">24/7</div>
                                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">AI Protection</div>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center shadow-lg">
                                        <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">0</div>
                                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">Data Stored</div>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center shadow-lg">
                                        <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">100K+</div>
                                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">Videos Analyzed</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center lg:order-1">
                        <h2 class="text-base font-semibold leading-7 text-indigo-600 dark:text-indigo-400">
                            Why Trust DeepScan?
                        </h2>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl">
                            Built on Advanced AI Technology
                        </p>
                        <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-400">
                            Our deep learning models are trained on millions of videos to detect even the most sophisticated deepfakes. With continuous learning and updates, we stay ahead of emerging threats.
                        </p>
                        <div class="mt-10 space-y-4">
                            <div class="flex items-center gap-x-3">
                                <svg class="h-5 w-5 flex-none text-indigo-600 dark:text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.403 12.652a3 3 0 000-5.304 3 3 0 00-3.75-3.751 3 3 0 00-5.305 0 3 3 0 00-3.751 3.75 3 3 0 000 5.305 3 3 0 003.75 3.751 3 3 0 005.305 0 3 3 0 003.751-3.75zm-2.546-4.46a.75.75 0 00-1.214-.883l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">State-of-the-art detection algorithms</span>
                            </div>
                            <div class="flex items-center gap-x-3">
                                <svg class="h-5 w-5 flex-none text-indigo-600 dark:text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.403 12.652a3 3 0 000-5.304 3 3 0 00-3.75-3.751 3 3 0 00-5.305 0 3 3 0 00-3.751 3.75 3 3 0 000 5.305 3 3 0 003.75 3.751 3 3 0 005.305 0 3 3 0 003.751-3.75zm-2.546-4.46a.75.75 0 00-1.214-.883l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">Privacy-first approach with zero data retention</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-32 sm:mt-40">
            <div class="mx-auto max-w-7xl px-6 py-12 md:flex md:items-center md:justify-between lg:px-8">
                <div class="flex justify-center space-x-6 md:order-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        Powered by Advanced AI
                    </span>
                </div>
                <div class="mt-8 md:order-1 md:mt-0">
                    <p class="text-center text-xs leading-5 text-gray-500 dark:text-gray-400">
                        &copy; {{ new Date().getFullYear() }} DeepScan. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
