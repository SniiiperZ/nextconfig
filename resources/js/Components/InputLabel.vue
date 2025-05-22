<script setup>
defineProps({
    value: String,
    required: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <label class="block font-medium text-sm text-white">
        <span v-if="value">
            {{ value }}
            <span v-if="required" class="text-gaming-red">*</span>
        </span>
        <span v-else>
            <slot />
            <span v-if="required" class="text-gaming-red">*</span>
        </span>
    </label>
</template>
