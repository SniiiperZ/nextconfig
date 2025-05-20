<script setup>
defineProps({
    icon: {
        type: String,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: "",
    },
    selected: {
        type: Boolean,
        default: false,
    },
});

defineEmits(["click"]);
</script>

<template>
    <button
        @click="$emit('click')"
        :class="[
            'p-2 sm:p-3 rounded-lg border transition-all flex flex-col items-center text-center shadow-sm',
            selected
                ? 'border-led-green bg-led-green/10 text-white shadow-glow-green'
                : 'border-gaming-red/30 text-white/80 hover:border-gaming-red hover:shadow-glow-red',
        ]"
    >
        <div class="mb-1" v-html="icon"></div>
        <span class="text-xs sm:text-sm font-medium">{{ name }}</span>
        <span
            v-if="description"
            class="text-2xs sm:text-xs mt-0.5 sm:mt-1 block opacity-80 hidden sm:block"
            >{{ description }}</span
        >
    </button>
</template>

<style scoped>
.text-2xs {
    font-size: 0.65rem;
}

button svg {
    transition: transform 0.3s ease;
    width: 20px;
    height: 20px;
}

@media (min-width: 640px) {
    button svg {
        width: 24px;
        height: 24px;
    }
}

button:hover svg {
    transform: scale(1.2);
}

@media (max-width: 640px) {
    button:hover svg {
        transform: scale(1.1);
    }

    .shadow-glow-green {
        box-shadow: 0 0 6px rgba(0, 255, 85, 0.2);
    }

    .shadow-glow-red {
        box-shadow: 0 0 6px rgba(236, 64, 122, 0.2);
    }
}
</style>
