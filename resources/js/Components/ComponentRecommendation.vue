<script setup>
defineProps({
    recommendations: {
        type: Object,
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
</script>

<template>
    <div class="space-y-1.5 sm:space-y-2">
        <div
            v-for="(recommendation, component) in recommendations"
            :key="component"
            class="bg-deep-black/50 p-2 sm:p-3 rounded border border-gaming-red/20 hover:border-gaming-red/60 hover:shadow-glow-sm transform hover:-translate-y-1 hover:translate-x-1 transition-transform duration-300"
        >
            <h4
                class="text-led-green text-xs sm:text-sm capitalize font-medium flex items-center mb-0.5 sm:mb-1"
            >
                <span
                    class="mr-1 sm:mr-2"
                    v-html="componentIcons[component]"
                ></span>
                {{ componentLabels[component] || component }}
            </h4>
            <p class="text-white ml-6 sm:ml-7 text-xs sm:text-sm">
                {{ recommendation }}
            </p>
        </div>
    </div>
</template>

<style scoped>
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
    div[v-for] {
        transform: translateY(0) translateX(0);
    }

    div[v-for]:hover {
        transform: translateY(-0.5px) translateX(0.5px);
    }
}
</style>
