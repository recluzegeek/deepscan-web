<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    src: {
        type: String,
        required: true
    },
    alt: {
        type: String,
        default: ''
    },
    label: {
        type: String,
        default: ''
    }
});

const imageRef = ref(null);
const isLoaded = ref(false);
const hasError = ref(false);

onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting && !isLoaded.value) {
                    loadImage();
                    // Unobserve after starting to load
                    observer.unobserve(entry.target);
                }
            });
        },
        {
            rootMargin: '50px 0px', // Start loading when image is 50px from viewport
            threshold: 0.1
        }
    );

    if (imageRef.value) {
        observer.observe(imageRef.value);
    }
});

function loadImage() {
    const img = new Image();
    img.src = props.src;
    
    img.onload = () => {
        isLoaded.value = true;
    };
    
    img.onerror = () => {
        hasError.value = true;
    };
}
</script>

<template>
    <div class="relative rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
        <!-- Placeholder/Loading State -->
        <div 
            v-if="!isLoaded && !hasError"
            ref="imageRef" 
            class="w-full aspect-video animate-pulse bg-gray-200 dark:bg-gray-700"
        >
            <div class="absolute inset-0 flex items-center justify-center">
                <svg 
                    class="w-12 h-12 text-gray-400 dark:text-gray-600" 
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor"
                >
                    <path 
                        strokeLinecap="round" 
                        strokeLinejoin="round" 
                        strokeWidth="1.5" 
                        d="M15 9a3 3 0 11-6 0 3 3 0 016 0zm6 3c0 2.22-3.4 4-7.5 4S6 14.22 6 12s3.4-4 7.5-4 7.5 1.78 7.5 4zM3 12c0-2.66 4.36-4.5 9-4.5s9 1.84 9 4.5m-9 5.25V21m-4.5-3.75L3 21m13.5-3.75L21 21"
                    />
                    <path 
                        strokeLinecap="round" 
                        strokeLinejoin="round" 
                        strokeWidth="1" 
                        stroke-dasharray="2 2"
                        d="M9 3h6M3 9v6m18-6v6M9 21h6"
                    />
                </svg>
                <div class="absolute bottom-3 text-sm text-gray-500 dark:text-gray-400">
                    Loading frame analysis...
                </div>
            </div>
        </div>

        <!-- Actual Image -->
        <img
            v-show="isLoaded"
            :src="src"
            :alt="alt"
            class="w-full h-auto rounded-lg shadow-sm transition duration-300 group-hover:shadow-lg"
        />

        <!-- Label -->
        <span class="absolute top-2 left-2 bg-white/90 dark:bg-gray-800/90 px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200">
            {{ label }}
        </span>
    </div>
</template> 