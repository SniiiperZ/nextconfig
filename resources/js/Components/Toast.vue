<script setup>
import { computed, onMounted, ref, watch } from "vue";

const props = defineProps({
    show: Boolean,
    message: String,
    type: {
        type: String,
        default: "success", // success, error, warning, info
    },
    duration: {
        type: Number,
        default: 5000,
    },
    position: {
        type: String,
        default: "bottom-right", // top-right, top-left, bottom-right, bottom-left, top-center, bottom-center
    },
});

const emit = defineEmits(["close"]);

const isVisible = ref(props.show);
const progress = ref(100);
const interval = ref(null);

const positionClasses = computed(() => {
    switch (props.position) {
        case "top-right":
            return "top-4 right-4";
        case "top-left":
            return "top-4 left-4";
        case "bottom-left":
            return "bottom-4 left-4";
        case "top-center":
            return "top-4 left-1/2 transform -translate-x-1/2";
        case "bottom-center":
            return "bottom-4 left-1/2 transform -translate-x-1/2";
        case "bottom-right":
        default:
            return "bottom-4 right-4";
    }
});

const typeClasses = computed(() => {
    switch (props.type) {
        case "error":
            return "bg-deep-black border-gaming-red text-gaming-red";
        case "warning":
            return "bg-deep-black border-amber-500 text-amber-500";
        case "info":
            return "bg-deep-black border-blue-500 text-blue-500";
        case "success":
        default:
            return "bg-deep-black border-led-green text-led-green";
    }
});

const progressBarColor = computed(() => {
    switch (props.type) {
        case "error":
            return "bg-gaming-red";
        case "warning":
            return "bg-amber-500";
        case "info":
            return "bg-blue-500";
        case "success":
        default:
            return "bg-led-green";
    }
});

const startTimer = () => {
    const stepDuration = 10; // mise à jour toutes les 10ms
    const steps = props.duration / stepDuration;
    const step = 100 / steps;

    clearInterval(interval.value);

    interval.value = setInterval(() => {
        if (progress.value > 0) {
            progress.value -= step;
        } else {
            closeToast();
        }
    }, stepDuration);
};

const closeToast = () => {
    clearInterval(interval.value);
    isVisible.value = false;
    emit("close");
};

onMounted(() => {
    if (props.show) {
        startTimer();
    }
});

watch(
    () => props.show,
    (newVal) => {
        isVisible.value = newVal;
        if (newVal) {
            progress.value = 100;
            startTimer();
        } else {
            clearInterval(interval.value);
        }
    }
);
</script>

<template>
    <transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="isVisible"
            class="fixed z-50 max-w-sm w-full shadow-lg rounded-lg pointer-events-auto border"
            :class="[positionClasses, typeClasses]"
        >
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <!-- Icônes pour chaque type de toast -->
                        <svg
                            v-if="type === 'success'"
                            class="h-6 w-6"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                        <svg
                            v-else-if="type === 'error'"
                            class="h-6 w-6"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                        <svg
                            v-else-if="type === 'warning'"
                            class="h-6 w-6"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                            />
                        </svg>
                        <svg
                            v-else-if="type === 'info'"
                            class="h-6 w-6"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium">
                            {{ message }}
                        </p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button
                            @click="closeToast"
                            class="inline-flex hover:opacity-70 transition-opacity"
                        >
                            <span class="sr-only">Fermer</span>
                            <svg
                                class="h-5 w-5"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Barre de progression -->
                <div class="mt-2 w-full bg-white/10 rounded-full h-1">
                    <div
                        class="h-1 rounded-full"
                        :class="progressBarColor"
                        :style="{ width: `${progress}%` }"
                    ></div>
                </div>
            </div>
        </div>
    </transition>
</template>
