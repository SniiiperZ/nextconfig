<script setup>
const props = defineProps({
    modelValue: {
        type: [Number, String],
        required: true,
    },
    min: {
        type: Number,
        default: 0,
    },
    max: {
        type: Number,
        default: 100,
    },
    step: {
        type: Number,
        default: 1,
    },
    label: {
        type: String,
        required: true,
    },
    error: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["update:modelValue"]);

const updateValue = (event) => {
    emit("update:modelValue", Number(event.target.value));
};
</script>

<template>
    <div class="mb-6">
        <div class="flex justify-between items-center mb-2">
            <label class="block text-white text-sm font-medium">
                {{ label }}
            </label>
            <span class="text-led-green font-medium">{{ modelValue }} €</span>
        </div>

        <input
            type="range"
            :min="min"
            :max="max"
            :step="step"
            :value="modelValue"
            @input="updateValue"
            class="w-full appearance-none h-2 bg-deep-black border border-gaming-red rounded-lg slider-thumb"
        />

        <div class="flex justify-between mt-1 text-xs text-white/60">
            <span>{{ min }}€</span>
            <span>{{ max }}€</span>
        </div>

        <p v-if="error" class="mt-1 text-sm text-gaming-red">{{ error }}</p>
    </div>
</template>

<style scoped>
.slider-thumb::-webkit-slider-thumb {
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ff0033;
    cursor: pointer;
}

.slider-thumb::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ff0033;
    cursor: pointer;
    border: none;
}

.slider-thumb:focus {
    outline: none;
}

.slider-thumb:focus::-webkit-slider-thumb {
    box-shadow: 0 0 0 3px rgba(0, 255, 85, 0.3);
}
</style>
