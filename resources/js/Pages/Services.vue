<script setup>
// Import du layout principal pour les pages publiques
import PublicLayout from "@/Layouts/PublicLayout.vue";
// Import du composant Link d'Inertia pour la navigation
import { Link } from "@inertiajs/vue3";
// Imports des composants réutilisables pour la structure de la page
import HeroSection from "@/Components/HeroSection.vue";
import HeroTitle from "@/Components/HeroTitle.vue";
import FeatureBlock from "@/Components/FeatureBlock.vue";
import CheckListItem from "@/Components/CheckListItem.vue";
import CTAButton from "@/Components/CTAButton.vue";

// Définition des icônes SVG pour les différents boutons CTA
// Icône pour les demandes de devis
const devisIcon = `
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
`;

// Icône pour le contact/prise de rendez-vous
const contactIcon = `
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
`;

// Icône pour le calculateur de budget
const calculatorIcon = `
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
`;
</script>

<template>
    <!-- Layout principal avec SEO optimisé pour la page Services -->
    <PublicLayout
        title="Nos Services"
        description="Services professionnels de montage PC Gaming, maintenance et optimisation en Belgique. Configuration sur mesure, personnalisation RGB et overclocking pour des performances maximales."
        keywords="services assemblage PC, montage PC sur mesure, maintenance PC gaming, upgrade ordinateur gaming, overclocking, personnalisation RGB"
    >
        <!-- En-tête de la page avec titre principal -->
        <HeroSection
            title="Nos Services"
            subtitle="Des solutions sur mesure pour tous vos besoins gaming"
        />

        <!-- Section principale du contenu avec espacement responsif -->
        <div class="py-8 sm:py-10 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Conteneur pour les cartes de services avec espacement vertical -->
                <div class="space-y-12 sm:space-y-14 md:space-y-16">
                    <!-- SERVICE 1: Montage PC Gaming -->
                    <div class="service-card-large">
                        <HeroTitle title="Montage PC Gaming" />

                        <!-- Bloc de fonctionnalités avec image à gauche -->
                        <FeatureBlock
                            title="Configuration sur mesure"
                            image="/images/montage.jpg"
                            imageAlt="Montage PC Gaming"
                            :imageRight="false"
                        >
                            <!-- Liste des avantages du service avec puces -->
                            <ul class="text-white space-y-3 sm:space-y-4">
                                <CheckListItem
                                    text="Sélection personnalisée des composants selon votre budget et vos besoins spécifiques (gaming, streaming, création)"
                                />
                                <CheckListItem
                                    text="Assemblage professionnel avec gestion optimisée des câbles pour un flux d'air maximal"
                                />
                                <CheckListItem
                                    text="Tests de stabilité, benchmarks et optimisation des performances avant livraison"
                                />
                                <CheckListItem
                                    text="Installation des pilotes et du système d'exploitation de votre choix"
                                />
                            </ul>

                            <!-- Bouton d'appel à l'action principal -->
                            <div class="mt-6 sm:mt-8">
                                <CTAButton
                                    :href="route('devis')"
                                    :primary="true"
                                    :icon="devisIcon"
                                >
                                    Demander un devis
                                </CTAButton>
                            </div>
                        </FeatureBlock>
                    </div>

                    <!-- SERVICE 2: Maintenance et Optimisation -->
                    <div class="service-card-large">
                        <HeroTitle title="Maintenance et Optimisation" />

                        <!-- Bloc de fonctionnalités avec image à droite -->
                        <FeatureBlock
                            title="Performances optimales"
                            image="/images/maintenance.jpg"
                            imageAlt="Maintenance PC Gaming"
                            :imageRight="true"
                        >
                            <!-- Liste des avantages du service avec puces -->
                            <ul class="text-white space-y-3 sm:space-y-4">
                                <CheckListItem
                                    text="Nettoyage complet du système (dépoussiérage des ventilateurs, radiateurs, composants)"
                                />
                                <CheckListItem
                                    text="Remplacement de la pâte thermique pour des températures optimales"
                                />
                                <CheckListItem
                                    text="Mise à jour des pilotes et du BIOS pour des performances améliorées"
                                />
                                <CheckListItem
                                    text="Diagnostics complets et recommandations pour les mises à niveau potentielles"
                                />
                            </ul>

                            <!-- Bouton d'appel à l'action secondaire -->
                            <div class="mt-6 sm:mt-8">
                                <CTAButton
                                    :href="route('contact')"
                                    :primary="false"
                                    :icon="contactIcon"
                                >
                                    Prendre rendez-vous
                                </CTAButton>
                            </div>
                        </FeatureBlock>
                    </div>

                    <!-- SERVICE 3: Upgrade et Personnalisation -->
                    <div class="service-card-large">
                        <HeroTitle title="Upgrade et Personnalisation" />

                        <!-- Bloc de fonctionnalités avec image à gauche -->
                        <FeatureBlock
                            title="Améliorez votre expérience"
                            image="/images/background.jpg"
                            imageAlt="Upgrade PC Gaming"
                            :imageRight="false"
                        >
                            <!-- Liste des avantages du service avec puces -->
                            <ul class="text-white space-y-3 sm:space-y-4">
                                <CheckListItem
                                    text="Upgrade ciblé des composants pour améliorer les performances (GPU, CPU, RAM)"
                                />
                                <CheckListItem
                                    text="Installation de systèmes de refroidissement avancés (AIO, watercooling)"
                                />
                                <CheckListItem
                                    text="Personnalisation RGB pour un style gaming unique"
                                />
                                <CheckListItem
                                    text="Overclocking sécurisé pour des performances maximales et stable"
                                />
                            </ul>

                            <!-- Bouton d'appel à l'action tertiaire -->
                            <div class="mt-6 sm:mt-8">
                                <CTAButton
                                    :href="route('budget.calculator')"
                                    :primary="false"
                                    :icon="calculatorIcon"
                                >
                                    Calculer mon budget upgrade
                                </CTAButton>
                            </div>
                        </FeatureBlock>
                    </div>
                </div>

                <!-- Section de conclusion avec CTA finaux -->
                <div class="mt-12 sm:mt-16 md:mt-20 text-center px-4 sm:px-0">
                    <h2
                        class="text-2xl sm:text-3xl font-play text-gaming-red mb-4 sm:mb-6"
                    >
                        Prêt à booster votre expérience gaming?
                    </h2>
                    <p
                        class="text-white text-base sm:text-lg md:text-xl mb-6 sm:mb-8 max-w-3xl mx-auto"
                    >
                        Contactez-nous dès aujourd'hui pour discuter de votre
                        projet ou commencer à configurer votre PC gaming sur
                        mesure.
                    </p>
                    <!-- Boutons d'action finaux côte à côte (responsive) -->
                    <div
                        class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6"
                    >
                        <CTAButton
                            :href="route('devis')"
                            :primary="true"
                            :icon="devisIcon"
                        >
                            Demander un devis
                        </CTAButton>
                        <CTAButton
                            :href="route('contact')"
                            :primary="false"
                            :icon="contactIcon"
                        >
                            Nous contacter
                        </CTAButton>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
