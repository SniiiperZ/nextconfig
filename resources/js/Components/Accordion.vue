<script setup>
import { ref } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    defaultOpen: {
        type: Boolean,
        default: false,
    },
});

const isOpen = ref(props.defaultOpen);

const toggle = () => {
    isOpen.value = !isOpen.value;
};
</script>

<template>
    <div class="border border-gaming-red rounded-lg overflow-hidden">
        <button
            @click="toggle"
            class="w-full px-6 py-4 flex justify-between items-center bg-deep-black hover:bg-gray-900 transition-colors duration-200"
        >
            <h3
                class="text-xl font-play font-semibold text-led-green text-left"
            >
                {{ title }}
            </h3>
            <svg
                class="w-5 h-5 text-led-green transform transition-transform duration-200"
                :class="{ 'rotate-180': isOpen }"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
            >
                <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                />
            </svg>
        </button>

        <TransitionRoot
            :show="isOpen"
            enter="transition-all duration-200"
            enter-from="opacity-0 -translate-y-2"
            enter-to="opacity-100 translate-y-0"
            leave="transition-all duration-200"
            leave-from="opacity-100 translate-y-0"
            leave-to="opacity-0 -translate-y-2"
        >
            <div class="px-6 py-4 bg-deep-black">
                <slot></slot>
            </div>
        </TransitionRoot>
    </div>
</template>
