<script setup>
defineProps({
    rating: {
        type: Number,
        required: true,
        validator: (value) => value >= 0 && value <= 5,
    },
    size: {
        type: String,
        default: "md",
    },
});
</script>

<template>
    <div class="flex">
        <template v-for="index in 5" :key="index">
            <span
                :class="{
                    'text-yellow-400': index <= rating,
                    'text-gray-500': index > rating,
                    'text-xs': size === 'sm',
                    'text-base': size === 'md',
                    'text-lg': size === 'lg',
                    'text-xl': size === 'xl',
                    'text-2xl': size === '2xl',
                    'text-3xl': size === '3xl',
                }"
            >
                ★
            </span>
        </template>
    </div>
</template>
