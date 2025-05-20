<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import FormInput from "@/Components/FormInput.vue";
import SelectOption from "@/Components/SelectOption.vue";
import RangeSlider from "@/Components/RangeSlider.vue";
import Toast from "@/Components/Toast.vue";
import HeroSection from "@/Components/HeroSection.vue";
import HeroTitle from "@/Components/HeroTitle.vue";

const usageTypeOptions = [
    { value: "gaming", label: "Gaming" },
    { value: "streaming", label: "Streaming" },
    { value: "content_creation", label: "Création de contenu" },
    { value: "office", label: "Bureautique" },
    { value: "other", label: "Autre" },
];

const brandOptions = [
    { value: "amd", label: "AMD" },
    { value: "intel", label: "Intel" },
    { value: "nvidia", label: "NVIDIA" },
    { value: "asus", label: "ASUS" },
    { value: "msi", label: "MSI" },
    { value: "corsair", label: "Corsair" },
    { value: "gigabyte", label: "Gigabyte" },
    { value: "thermaltake", label: "Thermaltake" },
    { value: "nzxt", label: "NZXT" },
];

const timeframeOptions = [
    { value: "asap", label: "Dès que possible" },
    { value: "1month", label: "D'ici 1 mois" },
    { value: "3months", label: "D'ici 3 mois" },
    { value: "6months", label: "D'ici 6 mois" },
];

const usageDetailsPlaceholders = {
    gaming: "Quels jeux jouez-vous principalement ? Quelle résolution et fréquence d'affichage souhaitez-vous ?",
    streaming:
        "Sur quelle plateforme diffusez-vous ? Quels jeux/contenus souhaitez-vous streamer ?",
    content_creation:
        "Quels logiciels utilisez-vous ? Travaillez-vous avec de la vidéo 4K, du rendu 3D, etc. ?",
    office: "Quelles applications utilisez-vous au quotidien ? Avez-vous besoin de multitâche intensif ?",
    other: "Décrivez votre utilisation spécifique pour que nous puissions vous proposer la configuration idéale.",
};

const form = useForm({
    name: "",
    email: "",
    phone: "",
    usage_type: "gaming",
    usage_details: "",
    budget: 1500,
    preferred_brands: [],
    rgb_lighting: false,
    timeframe: "1month",
    additional_notes: "",
});

const success = ref(false);
const usagePlaceholder = ref(usageDetailsPlaceholders.gaming);

const toast = ref({
    show: false,
    message: "",
    type: "success",
});

// Mettre à jour le placeholder en fonction du type d'usage sélectionné
const updateUsagePlaceholder = (newType) => {
    usagePlaceholder.value =
        usageDetailsPlaceholders[newType] || usageDetailsPlaceholders.other;
};

const submit = () => {
    form.post(route("devis.submit"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.value = {
                show: true,
                message:
                    "Votre demande de devis a été envoyée avec succès ! Nous vous contacterons bientôt pour discuter des détails.",
                type: "success",
            };

            setTimeout(() => {
                toast.value.show = false;
            }, 6000);
        },
    });
};

const closeToast = () => {
    toast.value.show = false;
};
</script>

<template>
    <PublicLayout
        title="Demande de Devis"
        description="Obtenez un devis personnalisé pour votre PC gaming sur mesure. Configuration adaptée à vos besoins et à votre budget par un expert en assemblage PC en Belgique."
        keywords="devis PC gaming, configuration PC sur mesure, assemblage ordinateur personnalisé, demande de devis informatique, prix PC gaming Belgique"
    >
        <!-- Hero Section avec image de fond -->
        <HeroSection
            title="Demande de Devis"
            subtitle="Décrivez votre projet et obtenez une offre personnalisée"
            backgroundImage="/images/background.jpg"
            height="min-h-50vh"
        />

        <div class="py-6 sm:py-8 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Introduction au formulaire -->
                <HeroTitle
                    title="Votre futur PC gaming"
                    :centered="true"
                    marginBottom="mb-6 sm:mb-8 md:mb-10"
                />

                <p
                    class="text-white text-center max-w-3xl mx-auto mb-8 sm:mb-10 md:mb-12 text-sm sm:text-base"
                >
                    Remplissez ce formulaire en nous donnant un maximum de
                    détails sur vos besoins. Notre équipe vous recontactera
                    rapidement avec une proposition adaptée à votre utilisation
                    et à votre budget.
                </p>

                <!-- Formulaire de devis amélioré -->
                <div
                    class="bg-deep-black p-4 sm:p-6 md:p-8 rounded-lg border border-gaming-red shadow-glow-sm hover-card animate-fade-in"
                >
                    <form
                        @submit.prevent="submit"
                        class="space-y-6 sm:space-y-8"
                    >
                        <!-- Information personnelle -->
                        <div
                            class="bg-gaming-red/5 p-3 sm:p-5 md:p-6 rounded-lg border border-gaming-red/30 mb-4 sm:mb-6 animate-fade-in"
                            style="animation-delay: 0.1s"
                        >
                            <div class="flex items-center mb-3 sm:mb-4">
                                <div
                                    class="bg-led-green w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center mr-3 sm:mr-4 shadow-glow-sm"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 sm:h-6 sm:w-6 text-deep-black"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        />
                                    </svg>
                                </div>
                                <h2
                                    class="text-lg sm:text-xl font-play text-led-green"
                                >
                                    Vos coordonnées
                                </h2>
                            </div>

                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4"
                            >
                                <FormInput
                                    v-model="form.name"
                                    label="Nom complet"
                                    :error="form.errors.name"
                                    placeholder="Votre nom et prénom"
                                />

                                <FormInput
                                    v-model="form.email"
                                    type="email"
                                    label="Email"
                                    :error="form.errors.email"
                                    placeholder="votre.email@exemple.com"
                                />

                                <FormInput
                                    v-model="form.phone"
                                    label="Téléphone"
                                    :error="form.errors.phone"
                                    placeholder="Ex: +32 xxx xxx xxx"
                                />
                            </div>
                        </div>

                        <!-- Type d'utilisation -->
                        <div
                            class="bg-gaming-red/5 p-3 sm:p-5 md:p-6 rounded-lg border border-gaming-red/30 mb-4 sm:mb-6 animate-fade-in"
                            style="animation-delay: 0.2s"
                        >
                            <div class="flex items-center mb-3 sm:mb-4">
                                <div
                                    class="bg-led-green w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center mr-3 sm:mr-4 shadow-glow-sm"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 sm:h-6 sm:w-6 text-deep-black"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"
                                        />
                                    </svg>
                                </div>
                                <h2
                                    class="text-lg sm:text-xl font-play text-led-green"
                                >
                                    Utilisation prévue
                                </h2>
                            </div>

                            <SelectOption
                                v-model="form.usage_type"
                                label="Utilisation principale"
                                :options="usageTypeOptions"
                                :error="form.errors.usage_type"
                                @update:model-value="updateUsagePlaceholder"
                            />

                            <div class="mb-3 sm:mb-4">
                                <label
                                    class="block text-white text-xs sm:text-sm font-medium mb-1 sm:mb-2"
                                >
                                    Détails de votre utilisation
                                </label>
                                <textarea
                                    v-model="form.usage_details"
                                    rows="3"
                                    class="w-full px-2 sm:px-3 py-1.5 sm:py-2 bg-deep-black border border-gaming-red rounded-md text-white text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-led-green transition duration-200"
                                    :placeholder="usagePlaceholder"
                                ></textarea>
                                <p
                                    v-if="form.errors.usage_details"
                                    class="mt-1 text-xs sm:text-sm text-gaming-red"
                                >
                                    {{ form.errors.usage_details }}
                                </p>
                            </div>
                        </div>

                        <!-- Préférences -->
                        <div
                            class="bg-gaming-red/5 p-3 sm:p-5 md:p-6 rounded-lg border border-gaming-red/30 mb-4 sm:mb-6 animate-fade-in"
                            style="animation-delay: 0.3s"
                        >
                            <div class="flex items-center mb-3 sm:mb-4">
                                <div
                                    class="bg-led-green w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center mr-3 sm:mr-4 shadow-glow-sm"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 sm:h-6 sm:w-6 text-deep-black"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"
                                        />
                                    </svg>
                                </div>
                                <h2
                                    class="text-lg sm:text-xl font-play text-led-green"
                                >
                                    Vos préférences et budget
                                </h2>
                            </div>

                            <RangeSlider
                                v-model="form.budget"
                                :min="500"
                                :max="10000"
                                :step="100"
                                label="Budget"
                                :error="form.errors.budget"
                            />

                            <SelectOption
                                v-model="form.preferred_brands"
                                label="Marques préférées (optionnel)"
                                :options="brandOptions"
                                :multiple="true"
                                :error="form.errors.preferred_brands"
                            />

                            <div class="mb-4 sm:mb-6">
                                <label
                                    class="flex items-center space-x-2 sm:space-x-3 cursor-pointer p-2 sm:p-3 border border-gaming-red/30 rounded-md hover:border-led-green/50 transition-colors duration-300"
                                >
                                    <input
                                        type="checkbox"
                                        v-model="form.rgb_lighting"
                                        class="form-checkbox h-4 w-4 sm:h-5 sm:w-5 text-led-green rounded border-gaming-red bg-deep-black"
                                    />
                                    <span
                                        class="text-white text-xs sm:text-sm font-medium flex items-center"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 sm:h-5 sm:w-5 text-gaming-red mr-1.5 sm:mr-2"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                            />
                                        </svg>
                                        Je souhaite un éclairage RGB
                                        personnalisé
                                    </span>
                                </label>
                            </div>

                            <SelectOption
                                v-model="form.timeframe"
                                label="Délai souhaité"
                                :options="timeframeOptions"
                                :error="form.errors.timeframe"
                            />
                        </div>

                        <!-- Notes supplémentaires -->
                        <div
                            class="animate-fade-in"
                            style="animation-delay: 0.4s"
                        >
                            <label
                                class="block text-white text-xs sm:text-sm font-medium mb-1 sm:mb-2"
                            >
                                Notes supplémentaires (optionnel)
                            </label>
                            <textarea
                                v-model="form.additional_notes"
                                rows="3"
                                class="w-full px-2 sm:px-3 py-1.5 sm:py-2 bg-deep-black border border-gaming-red rounded-md text-white text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-led-green transition duration-200"
                                placeholder="Y a-t-il d'autres informations que vous aimeriez nous transmettre ?"
                            ></textarea>
                        </div>

                        <!-- Bouton de soumission -->
                        <div
                            class="pt-3 sm:pt-4 flex justify-center animate-fade-in"
                            style="animation-delay: 0.5s"
                        >
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="main-cta-button inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 border border-transparent text-base sm:text-lg font-medium rounded-md text-white bg-gaming-red hover:bg-opacity-90 transition duration-300 disabled:opacity-50 font-play min-w-[200px] sm:min-w-[250px]"
                            >
                                <svg
                                    v-if="!form.processing"
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
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="animate-spin h-4 w-4 sm:h-5 sm:w-5 mr-1.5 sm:mr-2"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                {{
                                    form.processing
                                        ? "Envoi en cours..."
                                        : "Envoyer ma demande de devis"
                                }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Section calculateur de budget -->
                <div
                    class="mt-8 sm:mt-12 md:mt-16 p-4 sm:p-6 md:p-8 border border-gaming-red rounded-lg bg-deep-black/50 shadow-glow-sm hover-card text-center animate-fade-in"
                    style="animation-delay: 0.6s"
                >
                    <h2
                        class="text-xl sm:text-2xl font-play text-led-green mb-3 sm:mb-4"
                    >
                        Besoin d'aide pour estimer votre budget ?
                    </h2>
                    <p
                        class="text-white mb-4 sm:mb-6 max-w-3xl mx-auto text-sm sm:text-base"
                    >
                        Utilisez notre calculateur de budget pour obtenir une
                        estimation et découvrir les différentes options
                        disponibles selon votre budget.
                    </p>
                    <Link
                        :href="route('budget.calculator')"
                        class="main-cta-button inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 border border-transparent text-sm sm:text-base font-medium rounded-md text-white bg-gaming-red hover:bg-opacity-90 transition duration-300"
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
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                            />
                        </svg>
                        Calculateur de budget
                    </Link>
                </div>
            </div>
        </div>

        <!-- Notification toast flottante -->
        <Toast
            :show="toast.show"
            :message="toast.message"
            :type="toast.type"
            position="bottom-right"
            @close="closeToast"
        />
    </PublicLayout>
</template>

<style scoped>
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

/* Effet de brillance pour les cartes */
.shadow-glow-sm {
    box-shadow: 0 0 10px rgba(236, 64, 122, 0.3);
    transition: box-shadow 0.3s ease;
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

/* Responsive styles */
@media (max-width: 640px) {
    .main-cta-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 15px rgba(236, 64, 122, 0.5);
    }

    .hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2),
            0 0 10px rgba(236, 64, 122, 0.3);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
}
</style>
