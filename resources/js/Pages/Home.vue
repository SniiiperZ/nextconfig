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
        <!-- Hero Section avec image de fond -->
        <div class="hero-section">
            <!-- Contenu de la Hero Section -->
            <div class="hero-content z-10 relative text-center">
                <h1
                    class="text-6xl md:text-7xl font-play text-gaming-red mb-6 hero-title"
                >
                    NextConfig
                </h1>
                <p
                    class="text-2xl text-white mb-12 max-w-3xl mx-auto font-medium text-shadow-lg"
                >
                    Configurations personnalisées pour des performances
                    optimales
                </p>
                <Link
                    :href="route('configurator')"
                    class="main-cta-button inline-flex items-center px-8 py-4 border border-transparent text-lg font-medium rounded-md text-white bg-gaming-red hover:bg-opacity-90 transition duration-300"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"
                        />
                    </svg>
                    Configurer mon PC
                </Link>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Services Section avec icônes et effets -->
                <div class="flex justify-center mb-10">
                    <h2
                        class="text-4xl font-play text-gaming-red section-title"
                    >
                        Nos services
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-24">
                    <div
                        class="p-8 border border-gaming-red rounded-lg service-card group transition-all duration-300"
                    >
                        <div class="service-icon mb-4 text-led-green">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-12 w-12 mx-auto"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"
                                />
                            </svg>
                        </div>
                        <h3
                            class="text-2xl font-play text-led-green mb-4 text-center group-hover:text-gaming-red transition-colors duration-300"
                        >
                            Montage PC Sur Mesure
                        </h3>
                        <p class="text-white text-center">
                            Configuration et assemblage personnalisés selon vos
                            besoins et votre budget.
                        </p>
                    </div>

                    <div
                        class="p-8 border border-gaming-red rounded-lg service-card group transition-all duration-300"
                    >
                        <div class="service-icon mb-4 text-led-green">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-12 w-12 mx-auto"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                />
                            </svg>
                        </div>
                        <h3
                            class="text-2xl font-play text-led-green mb-4 text-center group-hover:text-gaming-red transition-colors duration-300"
                        >
                            Optimisation
                        </h3>
                        <p class="text-white text-center">
                            Amélioration des performances de votre configuration
                            existante.
                        </p>
                    </div>

                    <div
                        class="p-8 border border-gaming-red rounded-lg service-card group transition-all duration-300"
                    >
                        <div class="service-icon mb-4 text-led-green">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-12 w-12 mx-auto"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                        </div>
                        <h3
                            class="text-2xl font-play text-led-green mb-4 text-center group-hover:text-gaming-red transition-colors duration-300"
                        >
                            Maintenance
                        </h3>
                        <p class="text-white text-center">
                            Entretien et réparation de votre matériel gaming.
                        </p>
                    </div>

                    <div
                        class="p-8 border border-gaming-red rounded-lg service-card group transition-all duration-300"
                    >
                        <div class="service-icon mb-4 text-led-green">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-12 w-12 mx-auto"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                        <h3
                            class="text-2xl font-play text-led-green mb-4 text-center group-hover:text-gaming-red transition-colors duration-300"
                        >
                            Calculateur de budget
                        </h3>
                        <p class="text-white text-center">
                            Estimez ce que vous pouvez obtenir avec votre budget
                            selon vos besoins.
                        </p>
                        <div class="flex justify-center mt-4">
                            <Link
                                :href="route('budget.calculator')"
                                class="text-gaming-red hover:text-white transition"
                            >
                                Essayer maintenant
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="mt-20">
                    <div class="flex justify-center mb-10">
                        <h2
                            class="text-4xl font-play text-gaming-red section-title"
                        >
                            Ce que nos clients disent
                        </h2>
                    </div>

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

<style scoped>
/* Style pour la Hero Section avec image de fond */
.hero-section {
    position: relative;
    width: 100%;
    min-height: 91vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.85)),
        url("/images/background.jpg");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    padding: 2rem;
    margin-bottom: 2rem;
}

.hero-content {
    width: 100%;
    max-width: 1200px;
    padding: 3rem 1.5rem;
    animation: fadeIn 1.2s ease-out;
}

.text-shadow-lg {
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
}

/* Animation d'entrée */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Hiérarchie visuelle améliorée */
.hero-title {
    text-shadow: 0 0 15px rgba(236, 64, 122, 0.7), 0 3px 10px rgba(0, 0, 0, 0.8);
    letter-spacing: 1px;
    font-weight: bold;
    animation: glow 2s ease-in-out infinite alternate;
}

@keyframes glow {
    from {
        text-shadow: 0 0 15px rgba(236, 64, 122, 0.7),
            0 3px 10px rgba(0, 0, 0, 0.8);
    }
    to {
        text-shadow: 0 0 25px rgba(236, 64, 122, 0.9),
            0 3px 10px rgba(0, 0, 0, 0.8);
    }
}

/* Les styles existants */
.section-title {
    position: relative;
    display: inline-block;
    padding-bottom: 10px;
    margin-left: auto;
    margin-right: auto;
}

.section-title::after {
    content: "";
    position: absolute;
    width: 60%;
    height: 3px;
    bottom: 0;
    left: 20%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(236, 64, 122, 0.8),
        transparent
    );
}

/* Effet glow pour le bouton principal */
.main-cta-button {
    position: relative;
    background: linear-gradient(45deg, #ec407a, #d81b60);
    box-shadow: 0 0 15px rgba(236, 64, 122, 0.5);
    transform: translateY(0);
    transition: all 0.3s ease;
}

.main-cta-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 0 25px rgba(236, 64, 122, 0.7);
}

.main-cta-button:active {
    transform: translateY(0);
}

/* Animation pour les cartes de service */
.service-card {
    position: relative;
    overflow: hidden;
    background: rgba(0, 0, 0, 0.2);
    transform: translateY(0);
    transition: all 0.3s ease;
}

.service-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(
        circle at center,
        rgba(236, 64, 122, 0.1),
        transparent
    );
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 0 0 15px rgba(236, 64, 122, 0.3);
    border-color: rgba(236, 64, 122, 0.8);
}

.service-card:hover::before {
    opacity: 1;
}

.service-icon {
    transition: transform 0.3s ease;
}

.service-card:hover .service-icon {
    transform: scale(1.1);
}
</style>
