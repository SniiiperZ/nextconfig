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
            'p-3 rounded-lg border transition-all flex flex-col items-center text-center shadow-sm',
            selected
                ? 'border-led-green bg-led-green/10 text-white shadow-glow-green'
                : 'border-gaming-red/30 text-white/80 hover:border-gaming-red hover:shadow-glow-red',
        ]"
    >
        <div class="mb-1" v-html="icon"></div>
        <span class="text-sm font-medium">{{ name }}</span>
        <span v-if="description" class="text-xs mt-1 block opacity-80">{{
            description
        }}</span>
    </button>
</template>

<style scoped>
button svg {
    transition: transform 0.3s ease;
}

button:hover svg {
    transform: scale(1.2);
}
</style>
