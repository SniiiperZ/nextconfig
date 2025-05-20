<script setup>
defineProps({
    modelValue: {
        type: [String, Number],
        default: "",
    },
    label: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        default: "text",
    },
    error: {
        type: String,
        default: "",
    },
    placeholder: {
        type: String,
        default: "",
    },
});

defineEmits(["update:modelValue"]);
</script>

<template>
    <div class="mb-3 sm:mb-4">
        <label
            class="block text-white text-xs sm:text-sm font-medium mb-1 sm:mb-2"
        >
            {{ label }}
        </label>
        <input
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            @input="$emit('update:modelValue', $event.target.value)"
            class="w-full px-2 sm:px-3 py-1.5 sm:py-2 bg-deep-black border border-gaming-red rounded-md text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-led-green"
        />
        <p v-if="error" class="mt-1 text-xs sm:text-sm text-gaming-red">
            {{ error }}
        </p>
    </div>
</template>
