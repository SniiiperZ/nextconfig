<script setup>
// Importation des fonctionnalités de Vue et Inertia.js
import { ref } from "vue"; // Pour créer des variables réactives
import { Link } from "@inertiajs/vue3"; // Composant de navigation Inertia

// Importation des layouts et composants réutilisables
import PublicLayout from "@/Layouts/PublicLayout.vue"; // Layout principal pour les pages publiques
import ReviewCarousel from "@/Components/ReviewCarousel.vue"; // Carousel pour afficher les avis clients
import ReviewForm from "@/Components/ReviewForm.vue"; // Formulaire pour soumettre de nouveaux avis
import HeroSection from "@/Components/HeroSection.vue"; // Section d'en-tête avec image de fond
import HeroTitle from "@/Components/HeroTitle.vue"; // Titre stylisé pour les sections
import ServiceCard from "@/Components/ServiceCard.vue"; // Carte pour présenter un service
import CTAButton from "@/Components/CTAButton.vue"; // Bouton d'appel à l'action

// Définition des propriétés reçues du contrôleur Laravel
const props = defineProps({
    reviews: Array, // Liste des avis clients approuvés
    canLogin: Boolean, // Indique si l'utilisateur peut se connecter
    canRegister: Boolean, // Indique si l'inscription est ouverte
});

// Variable réactive pour stocker les messages de confirmation après soumission d'un avis
const successMessage = ref("");

// Gestionnaire d'événement appelé lorsqu'un avis est soumis avec succès
const handleReviewSubmitted = () => {
    // Affichage du message de confirmation
    successMessage.value =
        "Merci ! Votre avis a été soumis avec succès et sera affiché après modération.";

    // Effacement automatique du message après 5 secondes
    setTimeout(() => {
        successMessage.value = "";
    }, 5000);
};

// Définition des icônes SVG pour les différentes cartes de services
// Icône pour le service de montage PC
const montageIcon = `
  <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
  </svg>
`;

// Icône pour le service d'optimisation
const optimisationIcon = `
  <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
  </svg>
`;

// Icône pour le service de maintenance
const maintenanceIcon = `
  <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
  </svg>
`;

// Icône pour le bouton de demande de devis
const devisIcon = `
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
`;
</script>

<template>
    <!-- Layout principal avec métadonnées SEO optimisées pour la page d'accueil -->
    <PublicLayout
        title="Accueil"
        description="Expert en configuration et assemblage de PC Gaming sur mesure en Belgique. Des machines performantes adaptées à vos besoins et votre budget."
        keywords="PC gaming Belgique, configuration PC sur mesure, assemblage PC gaming, ordinateur gaming personnalisé"
    >
        <!-- Section héro avec image de fond, titre principal et appel à l'action -->
        <HeroSection
            title="NextConfig"
            subtitle="Configurations personnalisées pour des performances optimales"
            height="min-h-91vh"
        >
            <!-- Bouton d'appel à l'action dans la section héro -->
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

        <!-- Section principale de contenu avec espacement responsif -->
        <div class="py-8 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Titre de la section services -->
                <HeroTitle title="Nos services" />

                <!-- Grille de services - 1 colonne sur mobile, 3 colonnes sur écrans moyens et grands -->
                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-8 mb-16 sm:mb-24"
                >
                    <!-- Carte service 1: Montage PC Sur Mesure -->
                    <ServiceCard
                        title="Montage PC Sur Mesure"
                        :iconComponent="montageIcon"
                    >
                        Configuration et assemblage personnalisés selon vos
                        besoins et votre budget.
                    </ServiceCard>

                    <!-- Carte service 2: Optimisation -->
                    <ServiceCard
                        title="Optimisation"
                        :iconComponent="optimisationIcon"
                    >
                        Amélioration des performances de votre configuration
                        existante.
                    </ServiceCard>

                    <!-- Carte service 3: Maintenance -->
                    <ServiceCard
                        title="Maintenance"
                        :iconComponent="maintenanceIcon"
                    >
                        Entretien et réparation de votre matériel gaming.
                    </ServiceCard>
                </div>

                <!-- Section des avis clients avec espacement vertical -->
                <div class="mt-12 sm:mt-20">
                    <HeroTitle title="Ce que nos clients disent" />

                    <!-- Message de confirmation après soumission d'un avis (conditionnel) -->
                    <div
                        v-if="successMessage"
                        class="mb-4 sm:mb-6 bg-green-600/80 text-white p-3 sm:p-4 rounded-lg text-center mx-4 sm:mx-0"
                    >
                        {{ successMessage }}
                    </div>

                    <!-- Affichage du carousel d'avis et du formulaire si des avis existent -->
                    <div v-if="reviews && reviews.length > 0">
                        <ReviewCarousel :reviews="reviews" />
                        <ReviewForm @submitted="handleReviewSubmitted" />
                    </div>

                    <!-- Message alternatif et formulaire si aucun avis n'existe encore -->
                    <div v-else class="text-center mb-6 sm:mb-8 px-4 sm:px-0">
                        <p
                            class="text-white/70 mb-3 sm:mb-4 text-sm sm:text-base"
                        >
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
