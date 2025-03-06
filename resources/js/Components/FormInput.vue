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
});

defineEmits(["update:modelValue"]);
</script>

<template>
    <div class="mb-4">
        <label class="block text-white text-sm font-medium mb-2">
            {{ label }}
        </label>
        <input
            :type="type"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="w-full px-3 py-2 bg-deep-black border border-gaming-red rounded-md text-white focus:outline-none focus:ring-2 focus:ring-led-green"
        />
        <p v-if="error" class="mt-1 text-sm text-gaming-red">
            {{ error }}
        </p>
    </div>
</template>
