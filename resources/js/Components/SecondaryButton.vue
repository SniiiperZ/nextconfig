<script setup>
defineProps({
    type: {
        type: String,
        default: "button",
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-4 py-2 bg-deep-black border border-gaming-red rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gaming-red/10 focus:outline-none focus:ring-2 focus:ring-led-green focus:ring-offset-2 focus:ring-offset-deep-black disabled:opacity-50 transition-all duration-200"
    >
        <slot />
    </button>
</template>
