<script setup>
import { computed } from "vue";

const props = defineProps({
    allocations: {
        type: Object,
        required: true,
    },
    totalBudget: {
        type: Number,
        required: true,
    },
    componentIcons: {
        type: Object,
        required: true,
    },
});

const componentLabels = {
    cpu: "Processeur",
    gpu: "Carte graphique",
    ram: "Mémoire RAM",
    storage: "Stockage",
    motherboard: "Carte mère",
    psu: "Alimentation",
    cooling: "Refroidissement",
    case: "Boîtier",
};

// Valeurs minimales et maximales attendues pour chaque composant (en euros)
// Ces valeurs sont basées sur les allocations possibles dans BudgetCalculator.vue
const componentsRange = {
    cpu: { min: 100, max: 1500 }, // 20-30% du budget
    gpu: { min: 75, max: 2000 }, // 15-40% du budget
    ram: { min: 50, max: 750 }, // 10-15% du budget
    storage: { min: 50, max: 750 }, // 10-15% du budget
    motherboard: { min: 50, max: 500 }, // 10% du budget
    psu: { min: 25, max: 300 }, // 5-6% du budget
    cooling: { min: 25, max: 350 }, // 5-8% du budget
    case: { min: 25, max: 400 }, // 5-8% du budget
};

// Fonction pour calculer la largeur de la barre en fonction des min/max du composant
const getComponentWidth = (amount, component) => {
    const range = componentsRange[component];

    // Si le composant n'est pas dans notre liste, utiliser une échelle standard
    if (!range) {
        return Math.min(100, (amount / 500) * 100);
    }

    // Calculer le pourcentage basé sur la plage de valeurs pour ce composant spécifique
    const percentage = ((amount - range.min) / (range.max - range.min)) * 100;

    // S'assurer que la valeur est entre 5% (pour toujours avoir une barre visible) et 100%
    return Math.min(100, Math.max(5, percentage));
};

// Trier les composants par montant décroissant pour une meilleure visualisation
const sortedComponents = computed(() => {
    return Object.entries(props.allocations)
        .sort((a, b) => b[1] - a[1])
        .reduce((acc, [key, value]) => {
            acc[key] = value;
            return acc;
        }, {});
});
</script>

<template>
    <div class="space-y-2 sm:space-y-3">
        <div
            v-for="(amount, component) in sortedComponents"
            :key="component"
            class="flex items-center component-row p-1.5 sm:p-2 hover:bg-gaming-red/5 transition-colors duration-300 rounded"
        >
            <span
                class="text-white/90 w-24 sm:w-32 flex items-center text-xs sm:text-sm font-medium"
            >
                <span
                    class="mr-1 sm:mr-2"
                    v-html="componentIcons[component]"
                ></span>
                <span class="truncate">{{
                    componentLabels[component] || component
                }}</span>
            </span>

            <div class="flex-grow mx-2 sm:mx-4 relative">
                <!-- Barre de fond grisée représentant la plage totale possible -->
                <div
                    class="w-full h-1.5 sm:h-2 bg-deep-black border border-gaming-red/20 rounded-full overflow-hidden"
                >
                    <!-- Barre de progression colorée représentant la valeur actuelle -->
                    <div
                        class="h-full bg-gradient-to-r from-gaming-red to-led-green rounded-full transition-all duration-700"
                        :style="`width: ${getComponentWidth(
                            amount,
                            component
                        )}%`"
                    ></div>
                </div>
            </div>

            <!-- Utiliser une largeur fixe pour éviter les décalages -->
            <span
                class="font-medium text-led-green w-16 sm:w-20 text-right text-xs sm:text-sm"
                >{{ amount }}€</span
            >
        </div>
    </div>
</template>

<style scoped>
.component-row {
    transition: all 0.2s ease;
}

.component-row:hover {
    background: rgba(236, 64, 122, 0.05);
    transform: translateX(5px);
}

/* Ajustement des icônes SVG */
:deep(svg) {
    width: 16px;
    height: 16px;
}

@media (min-width: 640px) {
    :deep(svg) {
        width: 20px;
        height: 20px;
    }
}

/* Ajustements responsive pour mobile */
@media (max-width: 640px) {
    .component-row:hover {
        transform: translateX(3px);
    }
}
</style>
