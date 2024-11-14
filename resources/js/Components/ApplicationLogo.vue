<template>
    <svg 
        viewBox="0 0 40 40" 
        fill="none" 
        xmlns="http://www.w3.org/2000/svg" 
        class="w-8 h-8 transition-transform duration-300 ease-in-out hover:scale-110"
    >
        <!-- Animated Circle -->
        <circle 
            cx="20" 
            cy="20" 
            r="16" 
            stroke="currentColor" 
            stroke-width="2"
            class="opacity-80"
        >
            <animate
                attributeName="stroke-dasharray"
                values="0 100;100 100"
                dur="1.5s"
                begin="0s"
                fill="freeze"
            />
        </circle>
        
        <!-- Play Button Triangle with Fade-in -->
        <path 
            d="M17 14L27 20L17 26V14Z" 
            fill="currentColor"
            class="opacity-90"
        >
            <animate
                attributeName="opacity"
                values="0;1"
                dur="0.5s"
                begin="1s"
                fill="freeze"
            />
        </path>
        
        <!-- Shield Arc with Draw Effect -->
        <path 
            d="M14 20C14 24.4183 17.5817 28 22 28C26.4183 28 30 24.4183 30 20" 
            stroke="currentColor" 
            stroke-width="2" 
            stroke-linecap="round"
            class="opacity-80"
            stroke-dasharray="100"
            stroke-dashoffset="100"
        >
            <animate
                attributeName="stroke-dashoffset"
                values="100;0"
                dur="1s"
                begin="0.5s"
                fill="freeze"
            />
        </path>
    </svg>
</template>

<style scoped>
/* Optional: Add hover effect for interactive elements */
svg {
    filter: drop-shadow(0 0 2px rgba(0, 0, 0, 0.1));
}

svg:hover {
    filter: drop-shadow(0 0 4px rgba(0, 0, 0, 0.2));
}
</style>
