<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Link } from "@inertiajs/vue3";
import HeroSection from "@/Components/HeroSection.vue";
import HeroTitle from "@/Components/HeroTitle.vue";
import FeatureBlock from "@/Components/FeatureBlock.vue";
import CheckListItem from "@/Components/CheckListItem.vue";
import CTAButton from "@/Components/CTAButton.vue";

// Icônes pour les boutons CTA
const devisIcon = `
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
`;

const contactIcon = `
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
`;

const calculatorIcon = `
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
`;
</script>

<template>
    <PublicLayout
        title="Nos Services"
        description="Services professionnels de montage PC Gaming, maintenance et optimisation en Belgique. Configuration sur mesure, personnalisation RGB et overclocking pour des performances maximales."
        keywords="services assemblage PC, montage PC sur mesure, maintenance PC gaming, upgrade ordinateur gaming, overclocking, personnalisation RGB"
    >
        <!-- Hero Section pour la page Services -->
        <HeroSection
            title="Nos Services"
            subtitle="Des solutions sur mesure pour tous vos besoins gaming"
        />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Services détaillés -->
                <div class="space-y-16">
                    <!-- Montage PC -->
                    <div class="service-card-large">
                        <HeroTitle title="Montage PC Gaming" />

                        <FeatureBlock
                            title="Configuration sur mesure"
                            image="/images/montage.jpg"
                            imageAlt="Montage PC Gaming"
                            :imageRight="false"
                        >
                            <ul class="text-white space-y-4">
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

                            <div class="mt-8">
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

                    <!-- Maintenance -->
                    <div class="service-card-large">
                        <HeroTitle title="Maintenance et Optimisation" />

                        <FeatureBlock
                            title="Performances optimales"
                            image="/images/maintenance.jpg"
                            imageAlt="Maintenance PC Gaming"
                            :imageRight="true"
                        >
                            <ul class="text-white space-y-4">
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

                            <div class="mt-8">
                                <CTAButton
                                    :href="route('contact')"
                                    :primary="true"
                                    :icon="contactIcon"
                                >
                                    Prendre rendez-vous
                                </CTAButton>
                            </div>
                        </FeatureBlock>
                    </div>

                    <!-- Optimisation -->
                    <div class="service-card-large">
                        <HeroTitle title="Upgrade et Personnalisation" />

                        <FeatureBlock
                            title="Améliorez votre expérience"
                            image="/images/background.jpg"
                            imageAlt="Upgrade PC Gaming"
                            :imageRight="false"
                        >
                            <ul class="text-white space-y-4">
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

                            <div class="mt-8">
                                <CTAButton
                                    :href="route('budget.calculator')"
                                    :primary="true"
                                    :icon="calculatorIcon"
                                >
                                    Calculer mon budget upgrade
                                </CTAButton>
                            </div>
                        </FeatureBlock>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="mt-20 text-center">
                    <h2 class="text-3xl font-play text-gaming-red mb-6">
                        Prêt à booster votre expérience gaming?
                    </h2>
                    <p class="text-white text-xl mb-8 max-w-3xl mx-auto">
                        Contactez-nous dès aujourd'hui pour discuter de votre
                        projet ou commencer à configurer votre PC gaming sur
                        mesure.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
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

<style scoped>
.custom-rgb-bg {
    position: relative;
}

.custom-rgb-bg::before {
    content: "";
    position: absolute;
    top: -3px;
    left: -3px;
    right: -3px;
    bottom: -3px;
    background: linear-gradient(
        45deg,
        #ff0000,
        #ff7300,
        #fffb00,
        #48ff00,
        #00ffd5,
        #002bff,
        #7a00ff,
        #ff00c8,
        #ff0000
    );
    background-size: 400%;
    z-index: -1;
    filter: blur(5px);
    animation: rgb-animation 20s linear infinite;
    border-radius: 10px;
    opacity: 0.7;
}

@keyframes rgb-animation {
    0% {
        background-position: 0 0;
    }
    50% {
        background-position: 400% 0;
    }
    100% {
        background-position: 0 0;
    }
}
</style>
