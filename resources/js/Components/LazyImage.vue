<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    src: {
        type: String,
        required: true
    },
    alt: {
        type: String,
        required: true
    },
    label: {
        type: String,
        required: true
    }
});

const isLoading = ref(true);
const isIntersecting = ref(false);
const imageRef = ref(null);

const handleIntersection = (entries) => {
    const [entry] = entries;
    isIntersecting.value = entry.isIntersecting;
};

onMounted(() => {
    const observer = new IntersectionObserver(handleIntersection, {
        root: null,
        rootMargin: '50px', // Start loading images 50px before they enter viewport
        threshold: 0
    });

    if (imageRef.value) {
        observer.observe(imageRef.value);
    }

    // Cleanup
    return () => {
        if (imageRef.value) {
            observer.unobserve(imageRef.value);
        }
    };
});
</script>

<template>
    <div ref="imageRef" class="relative group">
        <!-- Loading Skeleton -->
        <div v-if="isLoading" class="animate-pulse bg-gray-200 dark:bg-gray-700 rounded-lg w-full" style="padding-top: 56.25%">
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-8-6a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
            </div>
        </div>

        <!-- Actual Image -->
        <img
            v-show="!isLoading"
            :src="isIntersecting ? src : ''"
            :alt="alt"
            class="w-full h-auto rounded-lg shadow-sm transition duration-300 group-hover:shadow-lg"
            @load="isLoading = false"
        />

        <!-- Label -->
        <span class="absolute top-2 left-2 bg-white/90 dark:bg-gray-800/90 px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200">
            {{ label }}
        </span>
    </div>
</template> 