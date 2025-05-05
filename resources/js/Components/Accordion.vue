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
    <div
        class="accordion-container border border-gaming-red rounded-lg overflow-hidden shadow-glow-sm transition-all duration-300"
    >
        <button
            @click="toggle"
            class="w-full px-6 py-4 flex justify-between items-center bg-deep-black hover:bg-gray-900/50 transition-all duration-300"
            :class="{ 'active-accordion': isOpen }"
        >
            <h3
                class="text-xl font-play font-semibold text-left transition-colors duration-300"
                :class="isOpen ? 'text-gaming-red' : 'text-led-green'"
            >
                {{ title }}
            </h3>
            <div class="accordion-icon-wrapper">
                <svg
                    class="w-5 h-5 transform transition-all duration-300"
                    :class="{
                        'rotate-180 text-gaming-red': isOpen,
                        'text-led-green': !isOpen,
                    }"
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
            </div>
        </button>

        <TransitionRoot
            :show="isOpen"
            enter="transition-all duration-300 ease-out"
            enter-from="opacity-0 max-h-0"
            enter-to="opacity-100 max-h-96"
            leave="transition-all duration-300 ease-in"
            leave-from="opacity-100 max-h-96"
            leave-to="opacity-0 max-h-0"
        >
            <div
                class="px-6 py-5 bg-deep-black/50 border-t border-gaming-red/30 accordion-content"
            >
                <slot></slot>
            </div>
        </TransitionRoot>
    </div>
</template>

<style scoped>
.accordion-container {
    position: relative;
    margin-bottom: 0.5rem;
}

.accordion-container:hover {
    transform: translateY(-2px);
}

.active-accordion {
    background: linear-gradient(
        90deg,
        rgba(0, 0, 0, 0.7),
        rgba(236, 64, 122, 0.1),
        rgba(0, 0, 0, 0.7)
    );
    border-bottom: none;
}

.accordion-icon-wrapper {
    position: relative;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.shadow-glow-sm {
    box-shadow: 0 0 8px rgba(236, 64, 122, 0.3);
    transition: box-shadow 0.3s ease;
}

.accordion-container:hover .shadow-glow-sm {
    box-shadow: 0 0 15px rgba(236, 64, 122, 0.5);
}

.accordion-content {
    position: relative;
    overflow: hidden;
}

/* Animation d'un léger rayonnement pour l'accordéon actif */
.active-accordion::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(236, 64, 122, 0.6),
        transparent
    );
}
</style>
