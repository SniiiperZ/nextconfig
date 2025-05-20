<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from "vue";
import StarRating from "@/Components/StarRating.vue";

const props = defineProps({
    reviews: {
        type: Array,
        required: true,
    },
    autoplay: {
        type: Boolean,
        default: true,
    },
    interval: {
        type: Number,
        default: 8000,
    },
});

const currentIndex = ref(0);
const isMobile = ref(false);

// Calculer le nombre d'avis par slide en fonction de la taille de l'écran
const reviewsPerSlide = computed(() => {
    return isMobile.value ? 1 : 3;
});

// Recalculer le nombre de slides
const slideCount = computed(() => {
    return Math.max(1, Math.ceil(props.reviews.length / reviewsPerSlide.value));
});

let timer = null;

const nextSlide = () => {
    if (slideCount.value <= 1) return;
    currentIndex.value = (currentIndex.value + 1) % slideCount.value;
};

const prevSlide = () => {
    if (slideCount.value <= 1) return;
    currentIndex.value =
        (currentIndex.value - 1 + slideCount.value) % slideCount.value;
};

const goToSlide = (index) => {
    currentIndex.value = index;
};

const startAutoplay = () => {
    if (props.autoplay && slideCount.value > 1) {
        stopAutoplay(); // Arrête tout timer existant avant d'en créer un nouveau
        timer = setInterval(() => {
            nextSlide();
        }, props.interval);
    }
};

const stopAutoplay = () => {
    if (timer) {
        clearInterval(timer);
        timer = null;
    }
};

// Surveiller le redimensionnement de la fenêtre
const handleResize = () => {
    const wasMobile = isMobile.value;
    isMobile.value = window.innerWidth < 768;

    // Si l'état mobile/desktop a changé, réinitialiser l'index et redémarrer l'autoplay
    if (wasMobile !== isMobile.value) {
        currentIndex.value = 0;
        startAutoplay();
    }
};

onMounted(() => {
    // Définir l'état initial
    isMobile.value = window.innerWidth < 768;
    window.addEventListener("resize", handleResize);
    startAutoplay();
});

onBeforeUnmount(() => {
    window.removeEventListener("resize", handleResize);
    stopAutoplay();
});

const getReviewsForSlide = (slideIndex) => {
    const start = slideIndex * reviewsPerSlide.value;
    const end = Math.min(start + reviewsPerSlide.value, props.reviews.length);
    return props.reviews.slice(start, end);
};
</script>

<template>
    <div
        class="relative px-4 md:px-0"
        @mouseenter="stopAutoplay"
        @mouseleave="startAutoplay"
    >
        <!-- Carousel Container -->
        <div class="overflow-hidden">
            <div
                class="flex transition-transform duration-500 ease-in-out"
                :style="{
                    transform: `translateX(-${currentIndex * 100}%)`,
                }"
            >
                <div
                    v-for="slideIndex in slideCount"
                    :key="`slide-${slideIndex - 1}`"
                    class="w-full flex-shrink-0"
                >
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                        <div
                            v-for="(review, reviewIndex) in getReviewsForSlide(
                                slideIndex - 1
                            )"
                            :key="`review-${review.id || reviewIndex}`"
                            class="bg-deep-black border border-gaming-red rounded-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_0_15px_rgba(236,64,122,0.5)]"
                            :class="{ 'border-yellow-500': review.is_featured }"
                        >
                            <div class="mb-3 flex justify-center">
                                <StarRating
                                    :rating="review.rating"
                                    size="text-xl sm:text-3xl"
                                />
                            </div>
                            <p
                                class="text-white/90 mb-4 line-clamp-4 text-sm sm:text-base"
                            >
                                "{{ review.comment }}"
                            </p>
                            <p
                                class="text-led-green font-semibold text-sm sm:text-base"
                            >
                                - {{ review.name }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Arrows -->
        <div
            v-if="slideCount > 1"
            class="absolute inset-y-0 left-0 sm:-left-4 md:-left-8 flex items-center z-10"
        >
            <button
                @click="prevSlide"
                class="bg-deep-black hover:bg-gaming-red text-white p-1 sm:p-2 rounded-full shadow-lg border border-gaming-red"
                aria-label="Avis précédent"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 sm:h-6 sm:w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
            </button>
        </div>

        <div
            v-if="slideCount > 1"
            class="absolute inset-y-0 right-0 sm:-right-4 md:-right-8 flex items-center z-10"
        >
            <button
                @click="nextSlide"
                class="bg-deep-black hover:bg-gaming-red text-white p-1 sm:p-2 rounded-full shadow-lg border border-gaming-red"
                aria-label="Avis suivant"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 sm:h-6 sm:w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>
            </button>
        </div>

        <!-- Indicators -->
        <div v-if="slideCount > 1" class="flex justify-center mt-4 sm:mt-6">
            <button
                v-for="index in slideCount"
                :key="`indicator-${index - 1}`"
                @click="goToSlide(index - 1)"
                :class="[
                    'mx-1 sm:mx-2 w-2 h-2 sm:w-3 sm:h-3 rounded-full transition-colors',
                    currentIndex === index - 1
                        ? 'bg-gaming-red'
                        : 'bg-gray-500 hover:bg-gray-400',
                ]"
                :aria-label="`Aller à la diapositive ${index}`"
            ></button>
        </div>
    </div>
</template>
