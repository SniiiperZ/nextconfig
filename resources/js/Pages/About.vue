<script setup>
// Importation des fonctionnalités Vue pour la réactivité
import { ref } from "vue"; // Pour créer des variables réactives

// Importation des layouts et composants réutilisables
import PublicLayout from "@/Layouts/PublicLayout.vue"; // Layout principal pour les pages publiques
import HeroSection from "@/Components/HeroSection.vue"; // En-tête de page avec image de fond
import HeroTitle from "@/Components/HeroTitle.vue"; // Titre stylisé pour les sections
import ServiceCard from "@/Components/ServiceCard.vue"; // Carte pour présenter un service
import FeatureBlock from "@/Components/FeatureBlock.vue"; // Bloc de contenu avec image et texte

// Données des expériences professionnelles - variable réactive pour permettre des mises à jour futures
const experiences = ref([
    {
        title: "Assembleur PC Senior", // Poste occupé
        period: "2020 - Présent", // Période d'activité
        description:
            "Création de configurations gaming haut de gamme et sur mesure.", // Description du poste
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

// Définition des icônes SVG pour les cartes de valeurs
// Ces icônes sont injectées directement dans le template via v-html ou props
const expertiseIcon = `
    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
    </svg>
`; // Icône de certification/expertise (symbole bouclier avec coche)

const surMesureIcon = `
    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
    </svg>
`; // Icône de puzzle/personnalisation (symbole de configuration)

const supportIcon = `
    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
`; // Icône de support technique (symbole cible avec cercle central)
</script>

<template>
    <!-- Layout principal avec métadonnées SEO optimisées pour la page À Propos -->
    <PublicLayout
        title="À Propos"
        description="Découvrez NextConfig, expert belge en assemblage PC Gaming sur mesure. Plus de 5 années d'expérience pour créer la machine de vos rêves avec passion et expertise."
        keywords="à propos NextConfig, Dylan Schreurs, expert PC gaming, assembleur PC sur mesure, technicien hardware Belgique"
    >
        <!-- Section d'en-tête avec titre et sous-titre -->
        <HeroSection
            title="À Propos de NextConfig"
            subtitle="Expertise et passion au service de vos configurations gaming"
        />

        <!-- Corps principal de la page avec espacement responsive -->
        <div class="py-8 sm:py-10 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- SECTION 1: Présentation personnelle -->
                <div class="mb-10 sm:mb-12 md:mb-16">
                    <!-- Titre de section stylisé -->
                    <HeroTitle title="Qui suis-je" />

                    <!-- Bloc de présentation avec image à gauche et texte à droite -->
                    <FeatureBlock
                        title="Schreurs Dylan"
                        image="/images/about.jpg"
                        imageAlt="PC Gaming Personnalisé"
                        :imageRight="false"
                    >
                        <!-- Texte de présentation en trois paragraphes avec tailles responsives -->
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

                <!-- SECTION 2: Parcours professionnel avec timeline -->
                <div class="mb-10 sm:mb-12 md:mb-16">
                    <HeroTitle title="Mon Parcours" />

                    <!-- Timeline verticale avec alternance gauche/droite sur desktop -->
                    <div class="timeline-container">
                        <!-- Boucle sur les expériences avec index pour alterner le positionnement -->
                        <div
                            v-for="(exp, index) in experiences"
                            :key="index"
                            class="timeline-item"
                            :class="{
                                left: index % 2 === 0, // Items pairs à gauche
                                right: index % 2 !== 0, // Items impairs à droite
                            }"
                        >
                            <!-- Contenu de chaque étape du parcours avec effet de survol -->
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

                <!-- SECTION 3: Valeurs et services avec cartes -->
                <div class="mb-8 sm:mb-10 md:mb-16">
                    <HeroTitle title="Mes Valeurs" />

                    <!-- Grille responsive : 1 colonne sur mobile, 2 sur tablette, 3 sur desktop -->
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 md:gap-8"
                    >
                        <!-- Carte de service "Expertise" -->
                        <ServiceCard
                            title="Expertise"
                            :iconComponent="expertiseIcon"
                        >
                            Une connaissance approfondie des composants et des
                            dernières technologies gaming pour des performances
                            optimales.
                        </ServiceCard>

                        <!-- Carte de service "Sur Mesure" -->
                        <ServiceCard
                            title="Sur Mesure"
                            :iconComponent="surMesureIcon"
                        >
                            Chaque configuration est unique et adaptée à vos
                            besoins spécifiques, que ce soit pour le gaming, le
                            streaming ou la création.
                        </ServiceCard>

                        <!-- Carte de service "Support" avec positionnement spécial sur tablette -->
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
/* VARIABLES CSS pour une meilleure maintenabilité */
:root {
    /* Couleurs et dégradés */
    --color-gaming-red: rgba(236, 64, 122, 1);
    --color-gaming-red-light: rgba(236, 64, 122, 0.3);
    --color-gaming-red-medium: rgba(236, 64, 122, 0.5);
    --color-gaming-red-strong: rgba(236, 64, 122, 0.7);

    /* Ombres */
    --shadow-standard: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-hover: 0 6px 12px rgba(0, 0, 0, 0.15);
    --shadow-card-hover: 0 10px 25px rgba(0, 0, 0, 0.3);

    /* Transitions */
    --transition-standard: all 0.3s ease;

    /* Dimensions */
    --timeline-width: 4px;
    --timeline-dot-size: 16px;
}

/*---------------------------------------
  SECTION TIMELINE - Style principal
---------------------------------------*/
/* Conteneur principal de la timeline avec une ligne verticale centrale */
.timeline-container {
    position: relative;
    max-width: 1200px;
    margin: 0 auto;
}

/* Ligne verticale centrale créée avec un pseudo-élément */
.timeline-container::after {
    content: "";
    position: absolute;
    width: var(--timeline-width); /* Largeur de la ligne verticale */
    background-color: var(--color-gaming-red-medium); /* Couleur de la ligne */
    top: 0;
    bottom: 0;
    left: 50%; /* Centré horizontalement */
    margin-left: calc(
        var(--timeline-width) / -2
    ); /* Ajustement pour centrer parfaitement */
}

/* Élément individuel de la timeline */
.timeline-item {
    padding: 10px 40px;
    position: relative;
    width: 50%; /* Chaque élément occupe la moitié de la largeur */
    box-sizing: border-box;
    margin-bottom: 20px;
}

/* Positionnement à gauche ou à droite de la ligne centrale */
.left {
    left: 0; /* Éléments à gauche de la ligne */
}

.right {
    left: 50%; /* Éléments à droite de la ligne */
}

/* Carte contenant les informations de l'expérience */
.timeline-content {
    padding: 16px;
    border: 1px solid var(--color-gaming-red);
    border-radius: 8px;
    background-color: rgba(0, 0, 0, 0.2); /* Fond semi-transparent */
    transition: var(--transition-standard);
}

/* Ajustement du padding pour les écrans plus grands */
@media (min-width: 640px) {
    .timeline-content {
        padding: 20px;
    }
}

/* Animation au survol des cartes de timeline */
.timeline-content:hover {
    transform: translateY(-5px); /* Effet de lévitation */
    box-shadow: var(--shadow-card-hover), 0 0 15px var(--color-gaming-red-light);
    border-color: var(--color-gaming-red-strong);
}

/*---------------------------------------
  SECTION TIMELINE - Adaptations responsives
---------------------------------------*/
/* Adaptation pour les tablettes et mobiles */
@media (max-width: 768px) {
    /* Ajustement de la ligne verticale (alignée à gauche plutôt qu'au centre) */
    .timeline-container::after {
        left: 31px;
    }

    /* Tous les éléments prennent la pleine largeur et s'alignent à gauche */
    .timeline-item {
        width: 100%;
        padding-left: 70px;
        padding-right: 25px;
    }

    /* Les éléments "droits" sont également alignés à gauche */
    .right {
        left: 0%;
    }

    /* Animation au survol réduite pour une meilleure expérience tactile */
    .timeline-content:hover {
        transform: translateY(-3px); /* Effet de lévitation réduit */
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2),
            0 0 10px var(--color-gaming-red-light);
    }
}

/* Adaptation spécifique pour les très petits écrans */
@media (max-width: 480px) {
    .timeline-container::after {
        left: 20px; /* Ligne verticale plus proche du bord */
    }

    .timeline-item {
        padding-left: 50px; /* Moins d'espace à gauche */
        padding-right: 15px;
        margin-bottom: 15px;
    }
}

/*---------------------------------------
  EFFETS DE SURVOL - Cartes interactives
---------------------------------------*/
/* Style de base pour les cartes avec effet de survol */
.hover-card {
    transition: var(--transition-standard);
}

/* Animation au survol des cartes */
.hover-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3),
        0 0 15px var(--color-gaming-red-light);
    transform: translateY(-5px); /* Effet de lévitation */
}

/* Adaptation responsive pour l'effet de survol */
@media (max-width: 768px) {
    .hover-card:hover {
        transform: translateY(-3px); /* Effet réduit sur mobile */
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2),
            0 0 10px var(--color-gaming-red-light);
    }
}
</style>
