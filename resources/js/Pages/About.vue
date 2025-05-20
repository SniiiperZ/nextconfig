// filepath: c:\laragon\www\nextconfig\resources\js\Pages\About.vue
<script setup>
import { ref } from "vue";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import HeroSection from "@/Components/HeroSection.vue";
import HeroTitle from "@/Components/HeroTitle.vue";
import ServiceCard from "@/Components/ServiceCard.vue";
import FeatureBlock from "@/Components/FeatureBlock.vue";

// Données sur l'expérience professionnelle
const experiences = ref([
    {
        title: "Assembleur PC Senior",
        period: "2020 - Présent",
        description:
            "Création de configurations gaming haut de gamme et sur mesure.",
    },
    {
        title: "Technicien Hardware",
        period: "2018 - 2020",
        description: "Diagnostic et réparation de matériel informatique.",
    },
    {
        title: "Conseiller Tech",
        period: "2016 - 2018",
        description: "Conseil client et vente de matériel gaming.",
    },
]);

// Icônes SVG pour les valeurs
const expertiseIcon = `
    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
    </svg>
`;

const surMesureIcon = `
    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
    </svg>
`;

const supportIcon = `
    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
`;
</script>

<template>
    <PublicLayout
        title="À Propos"
        description="Découvrez NextConfig, expert belge en assemblage PC Gaming sur mesure. Plus de 5 années d'expérience pour créer la machine de vos rêves avec passion et expertise."
        keywords="à propos NextConfig, Dylan Schreurs, expert PC gaming, assembleur PC sur mesure, technicien hardware Belgique"
    >
        <!-- Hero Section pour la page À Propos -->
        <HeroSection
            title="À Propos de NextConfig"
            subtitle="Expertise et passion au service de vos configurations gaming"
        />

        <div class="py-8 sm:py-10 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Présentation avec style amélioré -->
                <div class="mb-10 sm:mb-12 md:mb-16">
                    <HeroTitle title="Qui suis-je" />

                    <FeatureBlock
                        title="Schreurs Dylan"
                        image="/images/about.jpg"
                        imageAlt="PC Gaming Personnalisé"
                        :imageRight="false"
                    >
                        <p class="text-white mb-4 sm:mb-6 text-base sm:text-lg">
                            Passionné d'informatique et expert en assemblage PC,
                            je me suis spécialisé dans la création de
                            configurations gaming sur mesure.
                        </p>
                        <p class="text-white mb-4 sm:mb-6 text-sm sm:text-base">
                            Avec plus de 5 années d'expérience dans le domaine,
                            je vous accompagne dans le choix des meilleurs
                            composants pour créer la machine de vos rêves.
                        </p>
                        <p class="text-white text-sm sm:text-base">
                            Chaque PC est assemblé avec un souci du détail et
                            une attention particulière portée aux performances,
                            à l'esthétique et à la fiabilité.
                        </p>
                    </FeatureBlock>
                </div>

                <!-- Section Expérience -->
                <div class="mb-10 sm:mb-12 md:mb-16">
                    <HeroTitle title="Mon Parcours" />

                    <div class="timeline-container">
                        <div
                            v-for="(exp, index) in experiences"
                            :key="index"
                            class="timeline-item"
                            :class="{
                                left: index % 2 === 0,
                                right: index % 2 !== 0,
                            }"
                        >
                            <div class="timeline-content hover-card">
                                <h3
                                    class="text-lg sm:text-xl font-play text-led-green mb-2"
                                >
                                    {{ exp.title }}
                                </h3>
                                <p
                                    class="text-gaming-red font-semibold mb-2 sm:mb-3 text-sm sm:text-base"
                                >
                                    {{ exp.period }}
                                </p>
                                <p class="text-white text-sm sm:text-base">
                                    {{ exp.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Valeurs avec cards améliorées -->
                <div class="mb-8 sm:mb-10 md:mb-16">
                    <HeroTitle title="Mes Valeurs" />

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 md:gap-8"
                    >
                        <ServiceCard
                            title="Expertise"
                            :iconComponent="expertiseIcon"
                        >
                            Une connaissance approfondie des composants et des
                            dernières technologies gaming pour des performances
                            optimales.
                        </ServiceCard>

                        <ServiceCard
                            title="Sur Mesure"
                            :iconComponent="surMesureIcon"
                        >
                            Chaque configuration est unique et adaptée à vos
                            besoins spécifiques, que ce soit pour le gaming, le
                            streaming ou la création.
                        </ServiceCard>

                        <ServiceCard
                            title="Support"
                            :iconComponent="supportIcon"
                            class="sm:col-span-2 md:col-span-1 sm:max-w-md sm:mx-auto md:max-w-none"
                        >
                            Un accompagnement personnalisé et un service
                            après-vente réactif pour vous garantir une
                            expérience optimale.
                        </ServiceCard>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
/* Timeline styling */
.timeline-container {
    position: relative;
    max-width: 1200px;
    margin: 0 auto;
}

.timeline-container::after {
    content: "";
    position: absolute;
    width: 4px;
    background-color: rgba(236, 64, 122, 0.5);
    top: 0;
    bottom: 0;
    left: 50%;
    margin-left: -2px;
}

.timeline-item {
    padding: 10px 40px;
    position: relative;
    width: 50%;
    box-sizing: border-box;
    margin-bottom: 20px;
}

.left {
    left: 0;
}

.right {
    left: 50%;
}

.timeline-content {
    padding: 16px;
    border: 1px solid #ec407a;
    border-radius: 8px;
    background-color: rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

@media (min-width: 640px) {
    .timeline-content {
        padding: 20px;
    }
}

.timeline-content:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 0 0 15px rgba(236, 64, 122, 0.3);
    border-color: rgba(236, 64, 122, 0.8);
}

/* Responsive */
@media (max-width: 768px) {
    .timeline-container::after {
        left: 31px;
    }

    .timeline-item {
        width: 100%;
        padding-left: 70px;
        padding-right: 25px;
    }

    .right {
        left: 0%;
    }

    .timeline-content:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2),
            0 0 10px rgba(236, 64, 122, 0.3);
    }
}

/* Plus de spécificité pour les appareils très petits */
@media (max-width: 480px) {
    .timeline-container::after {
        left: 20px;
    }

    .timeline-item {
        padding-left: 50px;
        padding-right: 15px;
        margin-bottom: 15px;
    }
}

.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 0 15px rgba(236, 64, 122, 0.3);
    transform: translateY(-5px);
}

@media (max-width: 768px) {
    .hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2),
            0 0 10px rgba(236, 64, 122, 0.3);
    }
}
</style>
