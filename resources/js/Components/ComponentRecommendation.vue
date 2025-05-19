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
    <div class="space-y-2">
        <div
            v-for="(recommendation, component) in recommendations"
            :key="component"
            class="bg-deep-black/50 p-3 rounded border border-gaming-red/20 hover:border-gaming-red/60 hover:shadow-glow-sm transform hover:-translate-y-1 hover:translate-x-1 transition-transform duration-300"
        >
            <h4
                class="text-led-green text-sm capitalize font-medium flex items-center"
            >
                <span v-html="componentIcons[component]"></span>
                {{ componentLabels[component] || component }}
            </h4>
            <p class="text-white ml-7">{{ recommendation }}</p>
        </div>
    </div>
</template>
