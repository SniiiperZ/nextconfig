<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import ReviewCarousel from "@/Components/ReviewCarousel.vue";
import ReviewForm from "@/Components/ReviewForm.vue";

const props = defineProps({
    reviews: Array,
    canLogin: Boolean,
    canRegister: Boolean,
});

const successMessage = ref("");

const handleReviewSubmitted = () => {
    successMessage.value =
        "Merci ! Votre avis a été soumis avec succès et sera affiché après modération.";

    // Effacer le message après 5 secondes
    setTimeout(() => {
        successMessage.value = "";
    }, 5000);
};
</script>

<template>
    <PublicLayout title="Accueil">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Hero Section -->
                <div class="text-center mb-16">
                    <h1 class="text-5xl font-play text-gaming-red mb-4">
                        NextConfig
                    </h1>
                    <p class="text-xl text-white mb-8">
                        Configurations personnalisées pour des performances
                        optimales
                    </p>
                    <Link
                        :href="route('contact')"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gaming-red hover:bg-opacity-90 transition duration-150"
                    >
                        Configurer mon PC
                    </Link>
                </div>

                <!-- Services Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div class="p-6 border border-gaming-red rounded-lg">
                        <h3 class="text-xl font-play text-led-green mb-4">
                            Montage PC Sur Mesure
                        </h3>
                        <p class="text-white">
                            Configuration et assemblage personnalisés selon vos
                            besoins et votre budget.
                        </p>
                    </div>
                    <div class="p-6 border border-gaming-red rounded-lg">
                        <h3 class="text-xl font-play text-led-green mb-4">
                            Optimisation
                        </h3>
                        <p class="text-white">
                            Amélioration des performances de votre configuration
                            existante.
                        </p>
                    </div>
                    <div class="p-6 border border-gaming-red rounded-lg">
                        <h3 class="text-xl font-play text-led-green mb-4">
                            Maintenance
                        </h3>
                        <p class="text-white">
                            Entretien et réparation de votre matériel gaming.
                        </p>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="mt-16">
                    <h2
                        class="text-3xl font-play text-gaming-red mb-8 text-center"
                    >
                        Ce que nos clients disent
                    </h2>

                    <!-- Alert de succès -->
                    <div
                        v-if="successMessage"
                        class="mb-6 bg-green-600/80 text-white p-4 rounded-lg text-center"
                    >
                        {{ successMessage }}
                    </div>

                    <div v-if="reviews && reviews.length > 0">
                        <ReviewCarousel :reviews="reviews" />
                        <ReviewForm @submitted="handleReviewSubmitted" />
                    </div>

                    <div v-else class="text-center mb-8">
                        <p class="text-white/70 mb-4">
                            Aucun avis pour le moment. Soyez le premier à
                            partager votre expérience !
                        </p>
                        <ReviewForm @submitted="handleReviewSubmitted" />
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
