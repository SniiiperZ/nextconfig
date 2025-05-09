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
</script>

<template>
    <PublicLayout title="Calculateur de Budget PC">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h1 class="text-4xl font-play text-gaming-red mb-4 text-center">
                    Calculateur de Budget PC
                </h1>
                <p class="text-white text-center mb-10 max-w-3xl mx-auto">
                    Estimez ce que vous pouvez obtenir avec votre budget selon
                    vos besoins. Cet outil vous donne une première idée de la
                    répartition optimale de votre budget.
                </p>

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Panneau de configuration -->
                    <div
                        class="bg-deep-black p-6 border border-gaming-red rounded-lg"
                    >
                        <h2 class="text-2xl font-play text-led-green mb-6">
                            Personnalisez votre budget
                        </h2>

                        <!-- Slider de budget -->
                        <div class="mb-8">
                            <RangeSlider
                                v-model="budget"
                                :min="500"
                                :max="5000"
                                :step="100"
                                label="Votre budget (€)"
                            />
                        </div>

                        <!-- Sélection d'usage -->
                        <div class="mb-8">
                            <h3 class="text-white text-lg mb-3">
                                Utilisation principale
                            </h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <button
                                    v-for="usage in usageOptions"
                                    :key="usage.id"
                                    @click="selectedUsage = usage.id"
                                    :class="[
                                        'p-3 rounded-lg border transition-all flex flex-col items-center text-center',
                                        selectedUsage === usage.id
                                            ? 'border-led-green bg-led-green/10 text-white'
                                            : 'border-gaming-red/30 text-white/80 hover:border-gaming-red',
                                    ]"
                                >
                                    <span class="text-sm">{{
                                        usage.name
                                    }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Priorités -->
                        <div class="mb-8">
                            <h3 class="text-white text-lg mb-3">
                                Votre priorité
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                <button
                                    v-for="priority in priorityOptions"
                                    :key="priority.id"
                                    @click="selectedPriority = priority.id"
                                    :class="[
                                        'p-3 rounded-lg border transition-all',
                                        selectedPriority === priority.id
                                            ? 'border-led-green bg-led-green/10 text-white'
                                            : 'border-gaming-red/30 text-white/80 hover:border-gaming-red',
                                    ]"
                                >
                                    <span class="block font-semibold">{{
                                        priority.name
                                    }}</span>
                                    <span class="text-xs mt-1 block">{{
                                        priority.description
                                    }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Bouton vers le devis complet -->
                        <div class="mt-10">
                            <Link
                                :href="route('devis')"
                                class="w-full bg-gaming-red text-white py-3 px-4 rounded-md text-center inline-block hover:bg-opacity-90 transition-colors"
                            >
                                Passer au devis complet
                            </Link>
                        </div>
                    </div>

                    <!-- Panneau des résultats -->
                    <div
                        class="bg-deep-black p-6 border border-gaming-red rounded-lg"
                    >
                        <h2 class="text-2xl font-play text-led-green mb-6">
                            Résultats estimés
                        </h2>

                        <!-- Score de performance -->
                        <div class="mb-6">
                            <h3 class="text-white text-lg mb-2">
                                Performance estimée
                            </h3>
                            <div
                                class="w-full h-4 bg-gray-700 rounded-full overflow-hidden"
                            >
                                <div
                                    class="h-full rounded-full"
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
                            <p class="text-white mt-2 text-sm">
                                {{ performanceText }}
                            </p>
                        </div>

                        <!-- Répartition du budget -->
                        <div class="mb-6">
                            <h3 class="text-white text-lg mb-3">
                                Répartition du budget
                            </h3>
                            <div class="space-y-3">
                                <div
                                    v-for="(
                                        amount, component
                                    ) in budgetAllocation"
                                    :key="component"
                                    class="flex items-center"
                                >
                                    <span
                                        class="text-white/80 w-32 text-sm capitalize"
                                    >
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
                                            class="w-full h-2 bg-gray-700 rounded-full overflow-hidden"
                                        >
                                            <div
                                                class="h-full bg-gaming-red rounded-full"
                                                :style="`width: ${
                                                    (amount / budget) * 100
                                                }%`"
                                            ></div>
                                        </div>
                                    </div>
                                    <span class="text-white font-medium"
                                        >{{ amount }}€</span
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Recommandations de composants -->
                        <div>
                            <h3 class="text-white text-lg mb-3">
                                Composants recommandés
                            </h3>
                            <div class="space-y-2">
                                <div
                                    v-for="(
                                        recommendation, component
                                    ) in recommendations"
                                    :key="component"
                                    class="bg-deep-black/50 p-3 rounded border border-gaming-red/20"
                                >
                                    <h4
                                        class="text-led-green text-sm capitalize"
                                    >
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
                                    <p class="text-white">
                                        {{ recommendation }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Note d'avertissement -->
                        <div class="mt-6 text-xs text-white/60 italic">
                            <p>
                                Note: Ces résultats sont donnés à titre
                                indicatif. Les prix réels peuvent varier selon
                                les disponibilités et les promotions en cours.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Appel à l'action - Contact personnel -->
                <div
                    class="bg-gaming-red/10 border border-gaming-red rounded-lg p-6 mt-10 text-center"
                >
                    <h3 class="text-xl font-play text-led-green mb-3">
                        Besoin d'une configuration personnalisée?
                    </h3>
                    <p class="text-white mb-4">
                        Ce calculateur donne une estimation rapide. Pour une
                        configuration précise et adaptée à vos besoins
                        spécifiques, contactez-moi directement.
                    </p>
                    <div class="flex justify-center space-x-4">
                        <Link
                            :href="route('devis')"
                            class="px-6 py-2 bg-gaming-red text-white rounded-md hover:bg-opacity-90 transition"
                        >
                            Devis détaillé
                        </Link>
                        <Link
                            :href="route('contact')"
                            class="px-6 py-2 border border-led-green text-led-green rounded-md hover:bg-led-green/10 transition"
                        >
                            Me contacter
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
/* Animation pour les transitions d'état */
button {
    transition: all 0.2s ease-in-out;
}
</style>
