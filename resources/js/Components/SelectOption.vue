<script setup>
defineProps({
    modelValue: {
        type: [String, Number, Boolean, Array],
        default: "",
    },
    label: {
        type: String,
        required: true,
    },
    options: {
        type: Array,
        default: () => [],
    },
    multiple: {
        type: Boolean,
        default: false,
    },
    error: {
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
        <select
            v-if="!multiple"
            :value="modelValue"
            @change="$emit('update:modelValue', $event.target.value)"
            class="w-full px-2 sm:px-3 py-1.5 sm:py-2 bg-deep-black border border-gaming-red rounded-md text-white text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-led-green"
        >
            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>
        <div
            v-else
            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3"
        >
            <div v-for="option in options" :key="option.value" class="relative">
                <input
                    type="checkbox"
                    :id="option.value"
                    :value="option.value"
                    :checked="
                        Array.isArray(modelValue) &&
                        modelValue.includes(option.value)
                    "
                    @change="
                        (e) => {
                            const val = [...(modelValue || [])];
                            if (e.target.checked) {
                                if (!val.includes(option.value)) {
                                    val.push(option.value);
                                }
                            } else {
                                const idx = val.indexOf(option.value);
                                if (idx !== -1) {
                                    val.splice(idx, 1);
                                }
                            }
                            $emit('update:modelValue', val);
                        }
                    "
                    class="hidden"
                />
                <label
                    :for="option.value"
                    class="flex items-center justify-center p-1.5 sm:p-3 border rounded-md cursor-pointer transition-all text-xs sm:text-sm"
                    :class="
                        Array.isArray(modelValue) &&
                        modelValue.includes(option.value)
                            ? 'border-led-green bg-led-green bg-opacity-10 text-led-green'
                            : 'border-gaming-red text-white hover:border-led-green'
                    "
                >
                    {{ option.label }}
                </label>
            </div>
        </div>
        <p v-if="error" class="mt-1 text-2xs sm:text-xs text-gaming-red">
            {{ error }}
        </p>
    </div>
</template>

<style scoped>
.text-2xs {
    font-size: 0.65rem;
}
</style>
