<script setup>
defineProps({
    text: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <li class="flex items-start">
        <svg
            class="w-5 h-5 sm:w-6 sm:h-6 mr-2 sm:mr-3 text-gaming-red flex-shrink-0 mt-0.5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
        </svg>
        <span class="text-sm sm:text-base">{{ text }}</span>
    </li>
</template>
