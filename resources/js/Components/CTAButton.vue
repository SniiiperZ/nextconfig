<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: {
        type: String,
        required: true,
    },
    primary: {
        type: Boolean,
        default: false,
    },
    icon: {
        type: String,
        default: null,
    },
});
</script>

<template>
    <Link
        :href="href"
        :class="[
            'inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 border text-base sm:text-lg font-medium rounded-md text-white transition duration-300',
            primary
                ? 'main-cta-button border-transparent'
                : 'border-led-green hover:bg-led-green/10',
        ]"
    >
        <svg
            v-if="icon"
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 sm:h-6 sm:w-6 mr-1 sm:mr-2"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            v-html="icon"
        ></svg>
        <slot></slot>
    </Link>
</template>

<style scoped>
/* Effet glow pour le bouton principal */
.main-cta-button {
    position: relative;
    background: linear-gradient(45deg, #ec407a, #d81b60);
    box-shadow: 0 0 15px rgba(236, 64, 122, 0.5);
    transform: translateY(0);
    transition: all 0.3s ease;
}

.main-cta-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 0 25px rgba(236, 64, 122, 0.7);
}

.main-cta-button:active {
    transform: translateY(0);
}

@media (max-width: 640px) {
    .main-cta-button {
        box-shadow: 0 0 10px rgba(236, 64, 122, 0.4);
    }

    .main-cta-button:hover {
        box-shadow: 0 0 15px rgba(236, 64, 122, 0.6);
    }
}
</style>
