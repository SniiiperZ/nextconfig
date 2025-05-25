<script setup>
// Importation des fonctionnalités Vue pour la réactivité et le cycle de vie
import { ref, computed, watch } from "vue"; // ref pour variables réactives, computed pour calculs dérivés, watch pour observer les changements
import PublicLayout from "@/Layouts/PublicLayout.vue"; // Layout principal pour les pages publiques
import RangeSlider from "@/Components/RangeSlider.vue"; // Slider pour sélectionner le budget
import HeroSection from "@/Components/HeroSection.vue"; // Section d'en-tête avec image de fond
import HeroTitle from "@/Components/HeroTitle.vue"; // Titre stylisé pour les sections
import CTAButton from "@/Components/CTAButton.vue"; // Bouton d'appel à l'action
import BudgetOption from "@/Components/BudgetOption.vue"; // Option de sélection pour les choix de budget
import PerformanceIndicator from "@/Components/PerformanceIndicator.vue"; // Indicateur visuel des performances estimées
import BudgetAllocation from "@/Components/BudgetAllocation.vue"; // Visualisation de la répartition du budget
import ComponentRecommendation from "@/Components/ComponentRecommendation.vue"; // Recommandations de composants

// Options pour le type d'utilisation - détermine les besoins matériels de l'utilisateur
const usageOptions = [
    {
        id: "gaming",
        name: "Gaming",
        icon: "gamepad",
        description: "PC dédié principalement aux jeux vidéo",
    },
    {
        id: "streaming",
        name: "Streaming",
        icon: "video",
        description: "Pour jouer et diffuser en direct",
    },
    {
        id: "content_creation",
        name: "Création de contenu",
        icon: "film",
        description: "Montage vidéo, retouche photo, 3D",
    },
    {
        id: "office",
        name: "Bureautique",
        icon: "document",
        description: "Travail de bureau, navigation web",
    },
    {
        id: "workstation",
        name: "Workstation",
        icon: "desktop",
        description: "Applications professionnelles exigeantes",
    },
];

// Options pour les priorités de configuration - influence la répartition du budget
const priorityOptions = [
    {
        id: "performance",
        name: "Performance pure",
        description: "Maximiser les performances",
    },
    {
        id: "balanced",
        name: "Équilibré",
        description: "Bon rapport performance/esthétique",
    },
    { id: "quiet", name: "Silencieux", description: "Minimiser le bruit" },
    {
        id: "rgb",
        name: "Esthétique RGB",
        description: "Look gaming avec éclairage RGB",
    },
];

// Variables réactives pour les paramètres de configuration
const budget = ref(1500); // Budget initial par défaut en euros
const selectedUsage = ref("gaming"); // Type d'utilisation sélectionné par défaut
const selectedPriority = ref("balanced"); // Priorité sélectionnée par défaut

// Calcul de l'allocation du budget entre les différents composants en fonction des choix utilisateur
const budgetAllocation = computed(() => {
    // Allocation de base (répartition par défaut du budget)
    let allocation = {
        cpu: 0.2, // Processeur - 20% du budget par défaut
        gpu: 0.35, // Carte graphique - 35% du budget par défaut
        ram: 0.1, // Mémoire RAM - 10% du budget par défaut
        storage: 0.1, // Stockage - 10% du budget par défaut
        motherboard: 0.1, // Carte mère - 10% du budget par défaut
        psu: 0.05, // Alimentation - 5% du budget par défaut
        cooling: 0.05, // Refroidissement - 5% du budget par défaut
        case: 0.05, // Boîtier - 5% du budget par défaut
    };

    // Ajustement de l'allocation selon le type d'utilisation sélectionné
    if (selectedUsage.value === "gaming") {
        // Pour le gaming: priorité à la carte graphique
        allocation.gpu = 0.4;
        allocation.cpu = 0.2;
    } else if (selectedUsage.value === "streaming") {
        // Pour le streaming: équilibre entre CPU et GPU
        allocation.cpu = 0.25;
        allocation.gpu = 0.35;
    } else if (selectedUsage.value === "content_creation") {
        // Pour la création de contenu: priorité au CPU et à la RAM
        allocation.cpu = 0.3;
        allocation.gpu = 0.25;
        allocation.ram = 0.15;
    } else if (selectedUsage.value === "office") {
        // Pour la bureautique: moins de GPU, plus de stockage et d'esthétique
        allocation.gpu = 0.15;
        allocation.storage = 0.15;
        allocation.case = 0.1;
        allocation.cpu = 0.25;
    } else if (selectedUsage.value === "workstation") {
        // Pour une workstation: CPU puissant, beaucoup de RAM et de stockage
        allocation.cpu = 0.3;
        allocation.ram = 0.15;
        allocation.storage = 0.15;
        allocation.gpu = 0.2;
    }

    // Ajustements supplémentaires selon la priorité sélectionnée
    if (selectedPriority.value === "performance") {
        // Priorité performance: plus de CPU et GPU, moins d'esthétique
        allocation.cpu += 0.02;
        allocation.gpu += 0.03;
        allocation.case -= 0.02;
        allocation.cooling -= 0.01;
    } else if (selectedPriority.value === "quiet") {
        // Priorité silence: meilleur refroidissement et boîtier, GPU moins puissant
        allocation.cooling += 0.03;
        allocation.case += 0.02;
        allocation.gpu -= 0.03;
        allocation.psu += 0.01;
    } else if (selectedPriority.value === "rgb") {
        // Priorité RGB: plus de budget pour le boîtier et refroidissement
        allocation.case += 0.03;
        allocation.cooling += 0.02;
        allocation.gpu -= 0.03;
        allocation.motherboard += 0.01;
    }

    // Conversion des pourcentages en montants réels
    const result = {};
    for (const [component, percentage] of Object.entries(allocation)) {
        result[component] = Math.round(budget.value * percentage);
    }

    return result;
});

// Génération des recommandations de composants en fonction du budget alloué
const recommendations = computed(() => {
    const allocation = budgetAllocation.value;

    // Fonction utilitaire pour déterminer la gamme d'un composant selon son budget
    const getRange = (budget) => {
        if (budget < 100) return "entrée de gamme";
        if (budget < 200) return "milieu de gamme";
        if (budget < 350) return "haut de gamme";
        return "premium";
    };

    // Retourne des recommandations spécifiques pour chaque composant
    return {
        cpu: getCpuRecommendation(allocation.cpu, selectedUsage.value),
        gpu: getGpuRecommendation(allocation.gpu, selectedUsage.value),
        ram: `${getRamRecommendation(allocation.ram, selectedUsage.value)}`,
        storage: getStorageRecommendation(allocation.storage),
        motherboard: `Carte mère ${getRange(allocation.motherboard)}`,
        psu: `Alimentation ${getPsuRecommendation(
            allocation.psu,
            budgetTotal()
        )}`,
        cooling: `Système de refroidissement ${getRange(allocation.cooling)}`,
        case: `Boîtier ${getRange(allocation.case)}`,
    };
});

// Calcul du budget total à partir de l'allocation
function budgetTotal() {
    return Object.values(budgetAllocation.value).reduce((a, b) => a + b, 0);
}

// Recommandation de CPU en fonction du budget et de l'utilisation
function getCpuRecommendation(budget, usage) {
    if (budget < 120) {
        // Budget très limité: recommandation de base ou d'entrée de gamme
        return usage === "office"
            ? "Intel Core i3 / AMD Ryzen 3"
            : "AMD Ryzen 5 / Intel Core i5 (entrée de gamme)";
    } else if (budget < 200) {
        // Budget moyen: milieu de gamme
        return "AMD Ryzen 5 / Intel Core i5 (milieu de gamme)";
    } else if (budget < 300) {
        // Budget élevé: haut de gamme
        return "AMD Ryzen 7 / Intel Core i7";
    } else {
        // Budget premium: très haut de gamme
        return "AMD Ryzen 9 / Intel Core i9";
    }
}

// Recommandation de GPU en fonction du budget et de l'utilisation
function getGpuRecommendation(budget, usage) {
    if (usage === "office") {
        // Pour bureautique: GPU intégré ou entrée de gamme suffisant
        return budget < 100
            ? "Graphiques intégrés"
            : "NVIDIA GTX 1650 / AMD RX 6500";
    }

    // Pour autres utilisations: recommandations selon budget
    if (budget < 150) {
        return "NVIDIA GTX 1650 / AMD RX 6500";
    } else if (budget < 300) {
        return "NVIDIA RTX 3050 / AMD RX 6600";
    } else if (budget < 500) {
        return "NVIDIA RTX 3060 / AMD RX 6700";
    } else {
        return "NVIDIA RTX 4070 / AMD RX 7800";
    }
}

// Recommandation de RAM en fonction du budget et de l'utilisation
function getRamRecommendation(budget, usage) {
    if (budget < 80) {
        // Budget limité: DDR4 standard
        return "16 GB DDR4";
    } else if (budget < 150) {
        // Budget moyen: DDR4 haute fréquence ou DDR5 d'entrée
        return "16-32 GB DDR4/DDR5";
    } else {
        // Budget élevé: DDR5 avec plus de capacité pour workstation
        return usage === "workstation" || usage === "content_creation"
            ? "64 GB DDR5"
            : "32 GB DDR5";
    }
}

// Recommandation de stockage en fonction du budget
function getStorageRecommendation(budget) {
    if (budget < 80) {
        // Budget limité: SSD de base
        return "500GB SSD NVMe";
    } else if (budget < 150) {
        // Budget moyen: SSD plus grand
        return "1TB SSD NVMe";
    } else {
        // Budget élevé: SSD rapide + HDD supplémentaire
        return "1TB SSD NVMe + 2TB HDD";
    }
}

// Recommandation d'alimentation en fonction du budget et du budget total
function getPsuRecommendation(budget, totalBudget) {
    // Puissance recommandée selon le budget total
    const wattage =
        totalBudget < 1000 ? "500W" : totalBudget < 2000 ? "650W" : "850W";
    // Certification selon le budget alloué à l'alimentation
    const certification =
        budget < 60 ? "80+ Bronze" : budget < 100 ? "80+ Gold" : "80+ Platinum";
    return `${wattage} ${certification}`;
}

// Calcul du score de performance estimé (de 1 à 10) selon le budget et les choix
const performanceScore = computed(() => {
    let baseScore = budget.value / 500; // Score de base proportionnel au budget

    // Ajustements selon l'utilisation et la priorité
    if (
        selectedUsage.value === "gaming" &&
        selectedPriority.value === "performance"
    ) {
        baseScore += 0.5; // Bonus pour configuration gaming orientée performance
    } else if (selectedUsage.value === "office") {
        baseScore += 1; // Bonus pour bureautique (besoins moins exigeants)
    }

    // Limiter le score entre 1 et 10
    return Math.max(1, Math.min(10, Math.round(baseScore)));
});

// Texte explicatif correspondant au score de performance
const performanceText = computed(() => {
    const score = performanceScore.value;
    if (score <= 3)
        return "Performances basiques, adaptées à une utilisation simple.";
    if (score <= 5)
        return "Performances moyennes pour une utilisation quotidienne.";
    if (score <= 7) return "Bonnes performances pour la plupart des usages.";
    if (score <= 9)
        return "Performances haut de gamme, même pour les usages exigeants.";
    return "Performances exceptionnelles sans compromis.";
});

// Mise à jour automatique de la priorité lors du changement d'utilisation
watch(selectedUsage, (newValue) => {
    // Sélection de la priorité la plus adaptée à chaque type d'utilisation
    if (newValue === "gaming") selectedPriority.value = "performance";
    else if (newValue === "streaming") selectedPriority.value = "balanced";
    else if (newValue === "content_creation")
        selectedPriority.value = "balanced";
    else if (newValue === "office") selectedPriority.value = "quiet";
    else selectedPriority.value = "balanced";
});

// Définition des icônes SVG pour les différents types d'utilisation
const usageIcons = {
    gaming: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>`,
    streaming: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>`,
    content_creation: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" /></svg>`,
    office: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>`,
    workstation: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>`,
};

// Définition des icônes SVG pour les différentes priorités
const priorityIcons = {
    performance: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>`,
    balanced: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>`,
    quiet: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" /></svg>`,
    rgb: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>`,
};

// Définition des icônes SVG pour les différents composants
const componentIcons = {
    cpu: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>`,
    gpu: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" /></svg>`,
    ram: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" /></svg>`,
    storage: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>`,
    motherboard: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>`,
    psu: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>`,
    cooling: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
    case: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>`,
};
</script>

<template>
    <!-- Layout principal avec métadonnées SEO optimisées pour la page de calculateur de budget -->
    <PublicLayout
        title="Calculateur de Budget"
        description="Estimez le budget optimal pour votre PC gaming et découvrez les composants recommandés selon vos besoins. Outil gratuit d'aide à la configuration PC."
        keywords="calculateur budget PC gaming, estimation coût PC sur mesure, prix composants informatiques, outil configuration PC, simulateur budget ordinateur"
    >
        <!-- En-tête de la page avec image de fond et titre -->
        <HeroSection
            title="Calculateur de Budget"
            subtitle="Estimez ce que vous pouvez obtenir avec votre budget selon vos besoins"
            backgroundImage="/images/background.jpg"
            height="min-h-50vh"
        />

        <!-- Section principale du contenu avec espacement responsif -->
        <div class="py-6 sm:py-8 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Introduction explicative de l'outil -->
                <div class="mb-6 sm:mb-10">
                    <HeroTitle
                        title="Configurez votre budget idéal"
                        :centered="true"
                        marginBottom="mb-4 sm:mb-6"
                    />
                    <p
                        class="text-white text-center mb-6 sm:mb-10 max-w-3xl mx-auto text-sm sm:text-base"
                    >
                        Cet outil vous donne une première idée de la répartition
                        optimale de votre budget et des composants recommandés
                        selon votre utilisation.
                    </p>
                </div>

                <!-- Conteneur principal avec les deux colonnes principales -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 mb-8 sm:mb-12 md:mb-16"
                >
                    <!-- COLONNE 1: Formulaire des paramètres du budget -->
                    <div
                        class="bg-deep-black p-4 sm:p-6 border border-gaming-red rounded-lg shadow-glow-sm hover-card animate-fade-in"
                    >
                        <!-- Titre de la section -->
                        <h2
                            class="text-xl sm:text-2xl font-play text-led-green mb-4 sm:mb-6 flex items-center"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 sm:h-6 sm:w-6 mr-1.5 sm:mr-2"
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
                            Personnalisez votre budget
                        </h2>

                        <!-- Sélecteur de budget avec effet de survol -->
                        <div
                            class="mb-6 sm:mb-8 transform transition-all duration-300 hover:scale-102"
                        >
                            <RangeSlider
                                v-model="budget"
                                :min="500"
                                :max="5000"
                                :step="100"
                                label="Votre budget"
                            />
                        </div>

                        <!-- Sélection du type d'utilisation -->
                        <div class="mb-6 sm:mb-8">
                            <h3
                                class="text-base sm:text-lg text-white mb-2 sm:mb-3 flex items-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 sm:h-5 sm:w-5 mr-1.5 sm:mr-2"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                    />
                                </svg>
                                Utilisation principale
                            </h3>

                            <!-- Grille d'options d'utilisation - responsive -->
                            <div
                                class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3"
                            >
                                <BudgetOption
                                    v-for="usage in usageOptions"
                                    :key="usage.id"
                                    :icon="usageIcons[usage.id]"
                                    :name="usage.name"
                                    :description="usage.description"
                                    :selected="selectedUsage === usage.id"
                                    @click="selectedUsage = usage.id"
                                />
                            </div>
                        </div>

                        <!-- Sélection de la priorité -->
                        <div class="mb-6 sm:mb-8">
                            <h3
                                class="text-base sm:text-lg text-white mb-2 sm:mb-3 flex items-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 sm:h-5 sm:w-5 mr-1.5 sm:mr-2"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                                    />
                                </svg>
                                Votre priorité
                            </h3>

                            <!-- Grille d'options de priorité - 2 colonnes -->
                            <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                <BudgetOption
                                    v-for="priority in priorityOptions"
                                    :key="priority.id"
                                    :icon="priorityIcons[priority.id]"
                                    :name="priority.name"
                                    :description="priority.description"
                                    :selected="selectedPriority === priority.id"
                                    @click="selectedPriority = priority.id"
                                />
                            </div>
                        </div>

                        <!-- Bouton d'appel à l'action pour le devis complet -->
                        <div class="mt-6 sm:mt-10">
                            <CTAButton
                                :href="route('devis')"
                                primary
                                class="w-full"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 sm:h-5 sm:w-5 mr-1.5 sm:mr-2"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                    />
                                </svg>
                                Passer au devis complet
                            </CTAButton>
                        </div>
                    </div>

                    <!-- COLONNE 2: Affichage des résultats et recommandations -->
                    <div
                        class="bg-deep-black p-4 sm:p-6 border border-gaming-red rounded-lg shadow-glow-sm hover-card animate-fade-in"
                        style="animation-delay: 0.2s"
                    >
                        <!-- Titre de la section -->
                        <h2
                            class="text-xl sm:text-2xl font-play text-led-green mb-4 sm:mb-6 flex items-center"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 sm:h-6 sm:w-6 mr-1.5 sm:mr-2"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                            Résultats estimés
                        </h2>

                        <!-- Indicateur visuel de performance avec score et texte explicatif -->
                        <div class="mb-6 sm:mb-8">
                            <PerformanceIndicator
                                :score="performanceScore"
                                :text="performanceText"
                            />
                        </div>

                        <!-- Visualisation de la répartition du budget -->
                        <div class="mb-6 sm:mb-8">
                            <h3
                                class="text-base sm:text-lg text-white mb-2 sm:mb-3 flex items-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 sm:h-5 sm:w-5 mr-1.5 sm:mr-2 text-led-green"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                Répartition du budget
                            </h3>

                            <!-- Composant qui affiche les montants alloués à chaque composant -->
                            <BudgetAllocation
                                :allocations="budgetAllocation"
                                :totalBudget="budget"
                                :componentIcons="componentIcons"
                            />
                        </div>

                        <!-- Liste des composants recommandés -->
                        <div>
                            <h3
                                class="text-base sm:text-lg text-white mb-2 sm:mb-3 flex items-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 sm:h-5 sm:w-5 mr-1.5 sm:mr-2 text-led-green"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                    />
                                </svg>
                                Composants recommandés
                            </h3>

                            <!-- Composant qui affiche les recommandations détaillées -->
                            <ComponentRecommendation
                                :recommendations="recommendations"
                                :componentIcons="componentIcons"
                            />
                        </div>

                        <!-- Avertissement et disclaimer sur les résultats -->
                        <div
                            class="mt-5 sm:mt-6 p-2 sm:p-3 bg-gaming-red/10 rounded-lg border border-gaming-red/20 text-xs sm:text-sm text-white/80"
                        >
                            <div class="flex items-start">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 sm:h-5 sm:w-5 mr-1.5 sm:mr-2 text-gaming-red flex-shrink-0 mt-0.5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                <p>
                                    Ces résultats sont donnés à titre indicatif.
                                    Les prix réels peuvent varier selon les
                                    disponibilités et les promotions en cours.
                                    Pour une estimation précise, demandez un
                                    devis personnalisé.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section promotionnelle pour le devis personnalisé -->
                <div
                    class="bg-deep-black border border-gaming-red rounded-lg p-4 sm:p-6 md:p-8 shadow-glow service-card animate-fade-in"
                    style="animation-delay: 0.4s"
                >
                    <div class="flex flex-col md:flex-row items-center">
                        <!-- Partie texte et description (à gauche) -->
                        <div class="mb-4 sm:mb-6 md:mb-0 md:mr-6 md:w-3/4">
                            <h3
                                class="text-xl sm:text-2xl font-play text-led-green mb-2 sm:mb-3"
                            >
                                Besoin d'une configuration personnalisée?
                            </h3>
                            <p
                                class="text-white mb-3 sm:mb-4 text-sm sm:text-base"
                            >
                                Ce calculateur donne une estimation rapide. Pour
                                une configuration précise et adaptée à vos
                                besoins spécifiques, contactez-moi directement.
                                Je vous proposerai une solution optimale qui
                                correspond exactement à votre utilisation et à
                                votre budget.
                            </p>
                            <!-- Boutons d'appel à l'action -->
                            <div
                                class="flex flex-col sm:flex-row gap-2 sm:gap-4"
                            >
                                <CTAButton
                                    :href="route('devis')"
                                    primary
                                    class="w-full sm:w-auto"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 sm:h-5 sm:w-5 mr-1.5 sm:mr-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                        />
                                    </svg>
                                    Devis détaillé
                                </CTAButton>
                                <CTAButton
                                    :href="route('contact')"
                                    class="w-full sm:w-auto"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 sm:h-5 sm:w-5 mr-1.5 sm:mr-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        />
                                    </svg>
                                    Me contacter
                                </CTAButton>
                            </div>
                        </div>
                        <!-- Icône circulaire (à droite) -->
                        <div class="md:w-1/4 flex justify-center">
                            <div
                                class="p-3 sm:p-4 bg-led-green/10 border border-led-green rounded-full shadow-glow-green flex items-center justify-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-12 w-12 sm:h-16 sm:w-16 text-led-green"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
/* VARIABLES CSS POUR UNE MEILLEURE MAINTENABILITÉ */
:root {
    /* Couleurs */
    --color-gaming-red-light: rgba(236, 64, 122, 0.3);
    --color-gaming-red-medium: rgba(236, 64, 122, 0.5);
    --color-gaming-red-strong: rgba(236, 64, 122, 0.7);
    --color-led-green-glow: rgba(0, 255, 85, 0.3);

    /* Ombres */
    --shadow-card-hover: 0 10px 25px rgba(0, 0, 0, 0.3);

    /* Transitions */
    --transition-standard: all 0.3s ease;
}

/* Cartes et conteneurs - effets visuels */
.service-card {
    position: relative;
    overflow: hidden;
    background: rgba(0, 0, 0, 0.2);
    transform: translateY(0);
    transition: var(--transition-standard);
}

/* Effet de dégradé radial qui apparaît au survol */
.service-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(
        circle at center,
        var(--color-gaming-red-light),
        transparent
    );
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1;
}

/* Animation au survol des cartes de service */
.service-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-card-hover), 0 0 15px var(--color-gaming-red-light);
    border-color: var(--color-gaming-red-strong);
}

/* Activation de l'effet de fond au survol */
.service-card:hover::before {
    opacity: 1;
}

/* Effet de survol pour les cartes interactives */
.hover-card {
    transition: var(--transition-standard);
}

/* Animation au survol des cartes génériques */
.hover-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3),
        0 0 15px var(--color-gaming-red-light);
    transform: translateY(-5px);
}

/* Effets d'ombre pour différents éléments */
.shadow-glow-sm {
    box-shadow: 0 0 10px var(--color-gaming-red-light);
    transition: box-shadow 0.3s ease;
}

.shadow-glow-green {
    box-shadow: 0 0 10px var(--color-led-green-glow);
    transition: box-shadow 0.3s ease;
}

.shadow-glow-red {
    box-shadow: 0 0 10px var(--color-gaming-red-light);
    transition: box-shadow 0.3s ease;
}

/* Animation d'apparition progressive des éléments */
.animate-fade-in {
    animation: fadeIn 0.6s ease-out forwards;
    opacity: 0;
}

/* Définition de l'animation de fondu entrant */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px); /* Commence légèrement plus bas */
    }
    to {
        opacity: 1;
        transform: translateY(0); /* Position finale normale */
    }
}

/* Effet de légère mise à l'échelle au survol */
.scale-102:hover {
    transform: scale(1.02);
}

/* Adaptations pour les écrans mobiles */
@media (max-width: 640px) {
    /* Effet de survol réduit sur mobile pour une meilleure expérience tactile */
    .service-card:hover,
    .hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2),
            0 0 10px var(--color-gaming-red-light);
    }

    /* Animation de fade-in avec déplacement réduit pour mobile */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(
                10px
            ); /* Déplacement initial réduit sur mobile */
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Mise à l'échelle réduite sur mobile */
    .scale-102:hover {
        transform: scale(1.01);
    }
}
</style>
