<script setup>
import { onMounted, ref } from "vue";

defineProps({
    modelValue: String,
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="w-full px-3 py-2 bg-deep-black border border-gaming-red rounded-md text-white focus:outline-none focus:ring-2 focus:ring-led-green"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
