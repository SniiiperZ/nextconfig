<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import ReviewCarousel from "@/Components/ReviewCarousel.vue";
import ReviewForm from "@/Components/ReviewForm.vue";
import HeroSection from "@/Components/HeroSection.vue";
import HeroTitle from "@/Components/HeroTitle.vue";
import ServiceCard from "@/Components/ServiceCard.vue";
import CTAButton from "@/Components/CTAButton.vue";

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

// Icônes SVG pour les services
const montageIcon = `
  <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
  </svg>
`;

const optimisationIcon = `
  <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
  </svg>
`;

const maintenanceIcon = `
  <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
  </svg>
`;

// Icônes pour les boutons CTA
const devisIcon = `
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
`;
</script>

<template>
    <PublicLayout
        title="Accueil"
        description="Expert en configuration et assemblage de PC Gaming sur mesure en Belgique. Des machines performantes adaptées à vos besoins et votre budget."
        keywords="PC gaming Belgique, configuration PC sur mesure, assemblage PC gaming, ordinateur gaming personnalisé"
    >
        <!-- Hero Section avec nouveau composant -->
        <HeroSection
            title="NextConfig"
            subtitle="Configurations personnalisées pour des performances optimales"
            height="min-h-91vh"
        >
            <template #actions>
                <CTAButton
                    :href="route('devis')"
                    :primary="true"
                    :icon="devisIcon"
                >
                    Demander un devis
                </CTAButton>
            </template>
        </HeroSection>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Services Section avec les nouveaux composants -->
                <HeroTitle title="Nos services" />

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-24">
                    <ServiceCard
                        title="Montage PC Sur Mesure"
                        :iconComponent="montageIcon"
                    >
                        Configuration et assemblage personnalisés selon vos
                        besoins et votre budget.
                    </ServiceCard>

                    <ServiceCard
                        title="Optimisation"
                        :iconComponent="optimisationIcon"
                    >
                        Amélioration des performances de votre configuration
                        existante.
                    </ServiceCard>

                    <ServiceCard
                        title="Maintenance"
                        :iconComponent="maintenanceIcon"
                    >
                        Entretien et réparation de votre matériel gaming.
                    </ServiceCard>
                </div>

                <!-- Reviews Section -->
                <div class="mt-20">
                    <HeroTitle title="Ce que nos clients disent" />

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
