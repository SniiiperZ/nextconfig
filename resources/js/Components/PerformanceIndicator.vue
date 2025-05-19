<script setup>
defineProps({
    score: {
        type: Number,
        required: true,
        validator: (value) => value >= 0 && value <= 10,
    },
    text: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div
        class="bg-deep-black/70 p-5 rounded-lg border border-gaming-red/40 shadow-inner"
    >
        <h3 class="text-white text-lg mb-3 flex items-center">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 mr-2 text-led-green"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 10V3L4 14h7v7l9-11h-7z"
                />
            </svg>
            Performance estimée
        </h3>

        <div
            class="w-full h-5 bg-deep-black border border-gaming-red/30 rounded-full overflow-hidden mb-2"
        >
            <div
                class="h-full rounded-full transition-all duration-700 shadow-glow-inner"
                :class="[
                    score <= 3
                        ? 'bg-red-500'
                        : score <= 6
                        ? 'bg-yellow-500'
                        : score <= 8
                        ? 'bg-green-500'
                        : 'bg-led-green',
                ]"
                :style="`width: ${score * 10}%`"
            ></div>
        </div>

        <div class="flex justify-between items-center">
            <div class="performance-rating flex items-center">
                <span class="text-white text-xl font-play animate-pulse-score">
                    {{ score }} / 10
                </span>
            </div>
            <p class="text-white/80 text-sm">{{ text }}</p>
        </div>

        <!-- Ajout d'indicateurs numériques -->
        <div class="flex justify-between text-xs text-white/50 mt-1">
            <span>Faible</span>
            <span>Moyen</span>
            <span>Bon</span>
            <span>Excellent</span>
        </div>
    </div>
</template>

<style scoped>
.performance-rating {
    position: relative;
}

.performance-rating::after {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    border-radius: 50%;
    animation: pulse 2s infinite;
    opacity: 0;
}

@keyframes pulse {
    0% {
        transform: scale(0.95);
        opacity: 0;
    }
    70% {
        transform: scale(1.05);
        opacity: 0.3;
    }
    100% {
        transform: scale(0.95);
        opacity: 0;
    }
}

.animate-pulse-score {
    transition: all 0.5s ease;
}

.animate-pulse-score:hover {
    text-shadow: 0 0 10px rgba(0, 255, 85, 0.7);
}
</style>
