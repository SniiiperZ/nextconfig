<script setup>
import { ref, computed, watch } from "vue";
import { Link } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import RangeSlider from "@/Components/RangeSlider.vue";

// Options pour les usages
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

// Options pour les priorités
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

// Variables réactives
const budget = ref(1500);
const selectedUsage = ref("gaming");
const selectedPriority = ref("balanced");

// Calcul de la répartition du budget par composant
const budgetAllocation = computed(() => {
    // Répartition de base (en pourcentage du budget total)
    let allocation = {
        cpu: 0.2, // Processeur
        gpu: 0.35, // Carte graphique
        ram: 0.1, // Mémoire RAM
        storage: 0.1, // Stockage
        motherboard: 0.1, // Carte mère
        psu: 0.05, // Alimentation
        cooling: 0.05, // Refroidissement
        case: 0.05, // Boîtier
    };

    // Ajustement selon l'usage
    if (selectedUsage.value === "gaming") {
        // Priorité à la carte graphique pour le gaming
        allocation.gpu = 0.4;
        allocation.cpu = 0.2;
    } else if (selectedUsage.value === "streaming") {
        // Streaming: besoin d'un bon CPU et GPU
        allocation.cpu = 0.25;
        allocation.gpu = 0.35;
    } else if (selectedUsage.value === "content_creation") {
        // Création de contenu: priorité au CPU et RAM
        allocation.cpu = 0.3;
        allocation.gpu = 0.25;
        allocation.ram = 0.15;
    } else if (selectedUsage.value === "office") {
        // Bureautique: moins de GPU, plus de stockage et confort
        allocation.gpu = 0.15;
        allocation.storage = 0.15;
        allocation.case = 0.1;
        allocation.cpu = 0.25;
    } else if (selectedUsage.value === "workstation") {
        // Workstation: CPU, RAM et stockage prioritaires
        allocation.cpu = 0.3;
        allocation.ram = 0.15;
        allocation.storage = 0.15;
        allocation.gpu = 0.2;
    }

    // Ajustement selon la priorité
    if (selectedPriority.value === "performance") {
        // Maximiser CPU et GPU aux dépens du boîtier et du refroidissement
        allocation.cpu += 0.02;
        allocation.gpu += 0.03;
        allocation.case -= 0.02;
        allocation.cooling -= 0.01;
    } else if (selectedPriority.value === "quiet") {
        // Plus de budget pour le refroidissement et le boîtier
        allocation.cooling += 0.03;
        allocation.case += 0.02;
        allocation.gpu -= 0.03;
        allocation.psu += 0.01; // Alimentation de meilleure qualité
    } else if (selectedPriority.value === "rgb") {
        // Plus de budget pour le RGB (boîtier et refroidissement)
        allocation.case += 0.03;
        allocation.cooling += 0.02;
        allocation.gpu -= 0.03;
        allocation.motherboard += 0.01; // Carte mère avec RGB
    }

    // Calculer les montants en euros et arrondir
    const result = {};
    for (const [component, percentage] of Object.entries(allocation)) {
        result[component] = Math.round(budget.value * percentage);
    }

    return result;
});

// Recommandations de composants selon le budget
const recommendations = computed(() => {
    const allocation = budgetAllocation.value;

    // Fonction pour déterminer la gamme selon le budget
    const getRange = (budget) => {
        if (budget < 100) return "entrée de gamme";
        if (budget < 200) return "milieu de gamme";
        if (budget < 350) return "haut de gamme";
        return "premium";
    };

    // Recommandations spécifiques basées sur l'allocation budgétaire
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

// Fonction pour calculer le total (permet de vérifier que tout est bien alloué)
function budgetTotal() {
    return Object.values(budgetAllocation.value).reduce((a, b) => a + b, 0);
}

// Fonctions pour obtenir des recommandations spécifiques par composant
function getCpuRecommendation(budget, usage) {
    if (budget < 120) {
        return usage === "office"
            ? "Intel Core i3 / AMD Ryzen 3"
            : "AMD Ryzen 5 / Intel Core i5 (entrée de gamme)";
    } else if (budget < 200) {
        return "AMD Ryzen 5 / Intel Core i5 (milieu de gamme)";
    } else if (budget < 300) {
        return "AMD Ryzen 7 / Intel Core i7";
    } else {
        return "AMD Ryzen 9 / Intel Core i9";
    }
}

function getGpuRecommendation(budget, usage) {
    if (usage === "office") {
        return budget < 100
            ? "Graphiques intégrés"
            : "NVIDIA GTX 1650 / AMD RX 6500";
    }

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

function getRamRecommendation(budget, usage) {
    if (budget < 80) {
        return "16 GB DDR4";
    } else if (budget < 150) {
        return "16-32 GB DDR4/DDR5";
    } else {
        return usage === "workstation" || usage === "content_creation"
            ? "64 GB DDR5"
            : "32 GB DDR5";
    }
}

function getStorageRecommendation(budget) {
    if (budget < 80) {
        return "500GB SSD NVMe";
    } else if (budget < 150) {
        return "1TB SSD NVMe";
    } else {
        return "1TB SSD NVMe + 2TB HDD";
    }
}

function getPsuRecommendation(budget, totalBudget) {
    const wattage =
        totalBudget < 1000 ? "500W" : totalBudget < 2000 ? "650W" : "850W";
    const certification =
        budget < 60 ? "80+ Bronze" : budget < 100 ? "80+ Gold" : "80+ Platinum";
    return `${wattage} ${certification}`;
}

// Indicateur de performance global
const performanceScore = computed(() => {
    // Calculer un score global basé sur le budget et l'usage
    let baseScore = Math.min(Math.round(budget.value / 300), 10); // Max 10 points

    // Ajuster en fonction de l'usage et de la priorité
    if (
        selectedUsage.value === "gaming" &&
        selectedPriority.value === "performance"
    ) {
        baseScore += 1;
    } else if (selectedUsage.value === "office") {
        baseScore = Math.min(baseScore + 2, 10); // Office a besoin de moins de ressources
    }

    return baseScore;
});

// Texte d'évaluation basé sur le score
const performanceText = computed(() => {
    const score = performanceScore.value;
    if (score <= 3)
        return "Performances basiques, adaptées à une utilisation simple.";
    if (score <= 5)
        return "Bonnes performances pour une utilisation quotidienne.";
    if (score <= 7)
        return "Excellentes performances pour la plupart des usages.";
    if (score <= 9)
        return "Performances haut de gamme, même pour les usages exigeants.";
    return "Performances exceptionnelles sans compromis.";
});

// Réinitialiser la priorité en fonction de l'usage choisi
watch(selectedUsage, (newValue) => {
    // Définir une priorité par défaut selon l'usage
    if (newValue === "gaming") selectedPriority.value = "performance";
    else if (newValue === "streaming") selectedPriority.value = "balanced";
    else if (newValue === "content_creation")
        selectedPriority.value = "balanced";
    else if (newValue === "office") selectedPriority.value = "quiet";
    else selectedPriority.value = "balanced";
});

// Icônes pour chaque usage
const usageIcons = {
    gaming: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>`,
    streaming: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>`,
    content_creation: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" /></svg>`,
    office: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>`,
    workstation: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>`,
};

// Icônes pour chaque priorité
const priorityIcons = {
    performance: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>`,
    balanced: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>`,
    quiet: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" /></svg>`,
    rgb: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>`,
};

// Liste des composants avec icônes
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
    <PublicLayout title="Calculateur de Budget PC">
        <!-- Hero Section avec image de fond -->
        <div class="calculator-hero-section">
            <div class="hero-content z-10 relative text-center">
                <h1
                    class="text-6xl md:text-7xl font-play text-gaming-red mb-6 hero-title"
                >
                    Calculateur de Budget
                </h1>
                <p
                    class="text-2xl text-white mb-12 max-w-3xl mx-auto font-medium text-shadow-lg"
                >
                    Estimez ce que vous pouvez obtenir avec votre budget selon
                    vos besoins
                </p>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Introduction -->
                <div class="flex justify-center mb-10">
                    <h2
                        class="text-4xl font-play text-gaming-red section-title"
                    >
                        Configurez votre budget idéal
                    </h2>
                </div>

                <p class="text-white text-center mb-10 max-w-3xl mx-auto">
                    Cet outil vous donne une première idée de la répartition
                    optimale de votre budget et des composants recommandés selon
                    votre utilisation.
                </p>

                <!-- Contenu principal en deux colonnes -->
                <div class="grid md:grid-cols-2 gap-8 mb-16">
                    <!-- Panneau de configuration -->
                    <div
                        class="bg-deep-black p-6 border border-gaming-red rounded-lg shadow-glow-sm hover-card animate-fade-in"
                    >
                        <h2
                            class="text-2xl font-play text-led-green mb-6 flex items-center"
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

                        <!-- Slider de budget avec effet glow -->
                        <div
                            class="mb-8 transform transition-all duration-300 hover:scale-102"
                        >
                            <RangeSlider
                                v-model="budget"
                                :min="500"
                                :max="5000"
                                :step="100"
                                label="Votre budget"
                            />
                        </div>

                        <!-- Sélection d'usage avec icônes -->
                        <div class="mb-8">
                            <h3
                                class="text-white text-lg mb-3 flex items-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2"
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

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <button
                                    v-for="usage in usageOptions"
                                    :key="usage.id"
                                    @click="selectedUsage = usage.id"
                                    :class="[
                                        'p-3 rounded-lg border transition-all flex flex-col items-center text-center shadow-sm',
                                        selectedUsage === usage.id
                                            ? 'border-led-green bg-led-green/10 text-white shadow-glow-green'
                                            : 'border-gaming-red/30 text-white/80 hover:border-gaming-red hover:shadow-glow-red',
                                    ]"
                                >
                                    <!-- Insérer l'icône selon le type -->
                                    <div
                                        v-html="usageIcons[usage.id]"
                                        class="mb-1"
                                    ></div>
                                    <span class="text-sm font-medium">{{
                                        usage.name
                                    }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Priorités avec icônes -->
                        <div class="mb-8">
                            <h3
                                class="text-white text-lg mb-3 flex items-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2"
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

                            <div class="grid grid-cols-2 gap-3">
                                <button
                                    v-for="priority in priorityOptions"
                                    :key="priority.id"
                                    @click="selectedPriority = priority.id"
                                    :class="[
                                        'p-3 rounded-lg border transition-all text-center flex flex-col items-center justify-center',
                                        selectedPriority === priority.id
                                            ? 'border-led-green bg-led-green/10 text-white shadow-glow-green'
                                            : 'border-gaming-red/30 text-white/80 hover:border-gaming-red hover:shadow-glow-red',
                                    ]"
                                >
                                    <!-- Insérer l'icône selon la priorité -->
                                    <div
                                        v-html="priorityIcons[priority.id]"
                                        class="mb-1"
                                    ></div>
                                    <span class="block font-semibold text-sm">{{
                                        priority.name
                                    }}</span>
                                    <span
                                        class="text-xs mt-1 block opacity-80"
                                        >{{ priority.description }}</span
                                    >
                                </button>
                            </div>
                        </div>

                        <!-- Bouton vers le devis complet avec effet glow -->
                        <div class="mt-10">
                            <Link
                                :href="route('devis')"
                                class="main-cta-button w-full text-white py-3 px-4 rounded-md text-center flex items-center justify-center font-medium"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2"
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
                            </Link>
                        </div>
                    </div>

                    <!-- Panneau des résultats -->
                    <div
                        class="bg-deep-black p-6 border border-gaming-red rounded-lg shadow-glow-sm hover-card animate-fade-in"
                        style="animation-delay: 0.2s"
                    >
                        <h2
                            class="text-2xl font-play text-led-green mb-6 flex items-center"
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
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                            Résultats estimés
                        </h2>

                        <!-- Score de performance avec effet glow -->
                        <div
                            class="mb-8 bg-deep-black/70 p-5 rounded-lg border border-gaming-red/40 shadow-inner"
                        >
                            <h3
                                class="text-white text-lg mb-3 flex items-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-led-green"
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
                                Performance estimée
                            </h3>

                            <div
                                class="w-full h-5 bg-deep-black border border-gaming-red/30 rounded-full overflow-hidden mb-2"
                            >
                                <div
                                    class="h-full rounded-full transition-all duration-700 shadow-glow-inner"
                                    :class="[
                                        performanceScore <= 3
                                            ? 'bg-red-500'
                                            : performanceScore <= 6
                                            ? 'bg-yellow-500'
                                            : performanceScore <= 8
                                            ? 'bg-green-500'
                                            : 'bg-led-green',
                                    ]"
                                    :style="`width: ${performanceScore * 10}%`"
                                ></div>
                            </div>

                            <div class="flex justify-between items-center">
                                <div
                                    class="performance-rating flex items-center"
                                >
                                    <span class="text-white text-xl font-play"
                                        >{{ performanceScore }} / 10</span
                                    >
                                </div>
                                <p class="text-white/80 text-sm">
                                    {{ performanceText }}
                                </p>
                            </div>
                        </div>

                        <!-- Répartition du budget avec animations -->
                        <div class="mb-8">
                            <h3
                                class="text-white text-lg mb-3 flex items-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-led-green"
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

                            <div class="space-y-3">
                                <div
                                    v-for="(
                                        amount, component
                                    ) in budgetAllocation"
                                    :key="component"
                                    class="flex items-center component-row p-2 hover:bg-gaming-red/5 transition-colors duration-300 rounded"
                                >
                                    <span
                                        class="text-white/90 w-32 flex items-center text-sm font-medium"
                                    >
                                        <span
                                            v-html="componentIcons[component]"
                                        ></span>
                                        {{
                                            component === "cpu"
                                                ? "Processeur"
                                                : component === "gpu"
                                                ? "Carte graphique"
                                                : component === "ram"
                                                ? "Mémoire RAM"
                                                : component === "storage"
                                                ? "Stockage"
                                                : component === "motherboard"
                                                ? "Carte mère"
                                                : component === "psu"
                                                ? "Alimentation"
                                                : component === "cooling"
                                                ? "Refroidissement"
                                                : component === "case"
                                                ? "Boîtier"
                                                : component
                                        }}
                                    </span>

                                    <div class="flex-grow mx-4">
                                        <div
                                            class="w-full h-2 bg-deep-black border border-gaming-red/20 rounded-full overflow-hidden"
                                        >
                                            <div
                                                class="h-full bg-gradient-to-r from-gaming-red to-led-green rounded-full transition-all duration-700"
                                                :style="`width: ${
                                                    (amount / budget) * 100
                                                }%`"
                                            ></div>
                                        </div>
                                    </div>

                                    <span class="font-medium text-led-green"
                                        >{{ amount }}€</span
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Recommandations de composants avec cartes -->
                        <div>
                            <h3
                                class="text-white text-lg mb-3 flex items-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-led-green"
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

                            <div class="space-y-2">
                                <div
                                    v-for="(
                                        recommendation, component
                                    ) in recommendations"
                                    :key="component"
                                    class="bg-deep-black/50 p-3 rounded border border-gaming-red/20 hover:border-gaming-red/60 hover:shadow-glow-sm transform hover:-translate-y-1 hover:translate-x-1 transition-transform duration-300"
                                >
                                    <h4
                                        class="text-led-green text-sm capitalize font-medium flex items-center"
                                    >
                                        <span
                                            v-html="componentIcons[component]"
                                        ></span>
                                        {{
                                            component === "cpu"
                                                ? "Processeur"
                                                : component === "gpu"
                                                ? "Carte graphique"
                                                : component === "ram"
                                                ? "Mémoire RAM"
                                                : component === "storage"
                                                ? "Stockage"
                                                : component === "motherboard"
                                                ? "Carte mère"
                                                : component === "psu"
                                                ? "Alimentation"
                                                : component === "cooling"
                                                ? "Refroidissement"
                                                : component === "case"
                                                ? "Boîtier"
                                                : component
                                        }}
                                    </h4>
                                    <p class="text-white ml-7">
                                        {{ recommendation }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Note d'avertissement avec style amélioré -->
                        <div
                            class="mt-6 p-3 bg-gaming-red/10 rounded-lg border border-gaming-red/20 text-sm text-white/80"
                        >
                            <div class="flex items-start">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-gaming-red flex-shrink-0 mt-0.5"
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

                <!-- Appel à l'action - Contact personnel -->
                <div
                    class="bg-deep-black border border-gaming-red rounded-lg p-8 shadow-glow service-card animate-fade-in"
                    style="animation-delay: 0.4s"
                >
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="mb-6 md:mb-0 md:mr-8 md:w-3/4">
                            <h3 class="text-2xl font-play text-led-green mb-3">
                                Besoin d'une configuration personnalisée?
                            </h3>
                            <p class="text-white mb-4">
                                Ce calculateur donne une estimation rapide. Pour
                                une configuration précise et adaptée à vos
                                besoins spécifiques, contactez-moi directement.
                                Je vous proposerai une solution optimale qui
                                correspond exactement à votre utilisation et à
                                votre budget.
                            </p>
                            <div class="flex space-x-4">
                                <Link
                                    :href="route('devis')"
                                    class="main-cta-button inline-flex items-center px-6 py-2 text-white rounded-md transition"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-2"
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
                                </Link>
                                <Link
                                    :href="route('contact')"
                                    class="inline-flex items-center px-6 py-2 border border-led-green text-led-green rounded-md hover:bg-led-green/10 transition shadow-sm hover:shadow-glow-green"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-2"
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
                                </Link>
                            </div>
                        </div>
                        <div class="md:w-1/4 flex justify-center">
                            <div
                                class="p-4 bg-led-green/10 border border-led-green rounded-full shadow-glow-green flex items-center justify-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-16 w-16 text-led-green"
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
/* Style pour la Hero Section avec image de fond */
.calculator-hero-section {
    position: relative;
    width: 100%;
    min-height: 50vh;
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

/* Animation pour les cartes */
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

/* Animation pour les rangées de composants */
.component-row {
    transition: all 0.2s ease;
}

.component-row:hover {
    background: rgba(236, 64, 122, 0.05);
    transform: translateX(5px);
}

/* Effets pour les boutons et cartes */
.shadow-glow-sm {
    box-shadow: 0 0 10px rgba(236, 64, 122, 0.2);
    transition: box-shadow 0.3s ease;
}

.shadow-glow-green {
    box-shadow: 0 0 10px rgba(0, 255, 85, 0.3);
    transition: box-shadow 0.3s ease;
}

.shadow-glow-red {
    box-shadow: 0 0 10px rgba(236, 64, 122, 0.3);
    transition: box-shadow 0.3s ease;
}

.shadow-glow-inner {
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.5);
}

.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 0 15px rgba(236, 64, 122, 0.3);
    transform: translateY(-5px);
}

/* Animation pour les éléments qui apparaissent */
.animate-fade-in {
    animation: fadeIn 0.6s ease-out forwards;
    opacity: 0;
}

.scale-102:hover {
    transform: scale(1.02);
}

/* Ajustement pour les cartes de priorité et d'usage */
button svg {
    transition: transform 0.3s ease;
}

button:hover svg {
    transform: scale(1.2);
}

/* Effet pulsation pour la performance */
.performance-rating {
    position: relative;
}

.performance-rating::after {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    border-radius: 50%;
    animation: pulse 2s infinite;
    opacity: 0;
}

@keyframes pulse {
    0% {
        transform: scale(0.95);
        opacity: 0;
    }
    70% {
        transform: scale(1.05);
        opacity: 0.3;
    }
    100% {
        transform: scale(0.95);
        opacity: 0;
    }
}
</style>
