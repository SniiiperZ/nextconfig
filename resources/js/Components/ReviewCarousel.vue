<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
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
const slideCount = Math.ceil(props.reviews.length / 3);
let timer = null;

const nextSlide = () => {
    currentIndex.value = (currentIndex.value + 1) % slideCount;
};

const prevSlide = () => {
    currentIndex.value = (currentIndex.value - 1 + slideCount) % slideCount;
};

const goToSlide = (index) => {
    currentIndex.value = index;
};

const startAutoplay = () => {
    if (props.autoplay && slideCount > 1) {
        timer = setInterval(() => {
            nextSlide();
        }, props.interval);
    }
};

const stopAutoplay = () => {
    if (timer) {
        clearInterval(timer);
    }
};

onMounted(() => {
    startAutoplay();
});

onBeforeUnmount(() => {
    stopAutoplay();
});

const getReviewsForSlide = (index) => {
    const start = index * 3;
    return props.reviews.slice(start, start + 3);
};
</script>

<template>
    <div
        class="relative"
        @mouseenter="stopAutoplay"
        @mouseleave="startAutoplay"
    >
        <!-- Carousel Container -->
        <div class="overflow-hidden">
            <div
                class="flex transition-transform duration-500 ease-in-out"
                :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
            >
                <div
                    v-for="index in slideCount"
                    :key="index - 1"
                    class="w-full flex-shrink-0 grid grid-cols-1 md:grid-cols-3 gap-6"
                >
                    <div
                        v-for="review in getReviewsForSlide(index - 1)"
                        :key="review.id"
                        class="bg-deep-black border border-gaming-red rounded-lg p-6 transition-all duration-300 hover:shadow-[0_0_15px_rgba(236,64,122,0.5)]"
                        :class="{ 'border-yellow-500': review.is_featured }"
                    >
                        <div class="mb-3 flex justify-center">
                            <StarRating :rating="review.rating" size="3xl" />
                        </div>
                        <p class="text-white/90 mb-4 line-clamp-4">
                            "{{ review.comment }}"
                        </p>
                        <p class="text-led-green font-semibold">
                            - {{ review.name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Arrows - Repositionnées et avec un style amélioré -->
        <div
            v-if="slideCount > 1"
            class="absolute inset-y-0 -left-4 md:-left-12 flex items-center z-10"
        >
            <button
                @click="prevSlide"
                class="bg-deep-black hover:bg-gaming-red text-white p-2 rounded-full shadow-lg border border-gaming-red"
                aria-label="Avis précédent"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
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
            class="absolute inset-y-0 -right-4 md:-right-12 flex items-center z-10"
        >
            <button
                @click="nextSlide"
                class="bg-deep-black hover:bg-gaming-red text-white p-2 rounded-full shadow-lg border border-gaming-red"
                aria-label="Avis suivant"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
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
        <div v-if="slideCount > 1" class="flex justify-center mt-6">
            <button
                v-for="index in slideCount"
                :key="index - 1"
                @click="goToSlide(index - 1)"
                :class="[
                    'mx-2 w-3 h-3 rounded-full transition-colors',
                    currentIndex === index - 1
                        ? 'bg-gaming-red'
                        : 'bg-gray-500 hover:bg-gray-400',
                ]"
                :aria-label="`Aller à la diapositive ${index}`"
            ></button>
        </div>
    </div>
</template>
