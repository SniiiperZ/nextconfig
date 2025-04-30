<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import FormInput from "@/Components/FormInput.vue";
import SelectOption from "@/Components/SelectOption.vue";
import RangeSlider from "@/Components/RangeSlider.vue";
import Toast from "@/Components/Toast.vue";

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
    { value: "no_rush", label: "Pas pressé" },
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
    form.post(route("configurator.submit"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.value = {
                show: true,
                message:
                    "Votre demande de configuration a été envoyée avec succès ! Nous vous contacterons bientôt pour discuter des détails.",
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
    <PublicLayout title="Configurateur PC">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-deep-black p-8 rounded-lg border border-gaming-red"
                >
                    <h1 class="text-3xl font-play text-gaming-red mb-2">
                        Configurateur PC
                    </h1>
                    <p class="text-white mb-8">
                        Dites-nous ce dont vous avez besoin, et nous vous
                        proposerons une configuration sur mesure adaptée à votre
                        utilisation et votre budget.
                    </p>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Information personnelle -->
                        <div
                            class="bg-gaming-red/5 p-4 rounded-lg border border-gaming-red/30 mb-6"
                        >
                            <h2 class="text-xl font-play text-led-green mb-4">
                                Informations personnelles
                            </h2>

                            <FormInput
                                v-model="form.name"
                                label="Nom complet"
                                :error="form.errors.name"
                            />

                            <FormInput
                                v-model="form.email"
                                type="email"
                                label="Email"
                                :error="form.errors.email"
                            />

                            <FormInput
                                v-model="form.phone"
                                label="Téléphone"
                                :error="form.errors.phone"
                            />
                        </div>

                        <!-- Type d'utilisation -->
                        <div
                            class="bg-gaming-red/5 p-4 rounded-lg border border-gaming-red/30 mb-6"
                        >
                            <h2 class="text-xl font-play text-led-green mb-4">
                                Votre utilisation
                            </h2>

                            <SelectOption
                                v-model="form.usage_type"
                                label="Utilisation principale"
                                :options="usageTypeOptions"
                                :error="form.errors.usage_type"
                                @update:model-value="updateUsagePlaceholder"
                            />

                            <div class="mb-4">
                                <label
                                    class="block text-white text-sm font-medium mb-2"
                                >
                                    Détails de votre utilisation
                                </label>
                                <textarea
                                    v-model="form.usage_details"
                                    rows="4"
                                    class="w-full px-3 py-2 bg-deep-black border border-gaming-red rounded-md text-white focus:outline-none focus:ring-2 focus:ring-led-green"
                                    :placeholder="usagePlaceholder"
                                ></textarea>
                                <p
                                    v-if="form.errors.usage_details"
                                    class="mt-1 text-sm text-gaming-red"
                                >
                                    {{ form.errors.usage_details }}
                                </p>
                            </div>
                        </div>

                        <!-- Préférences -->
                        <div
                            class="bg-gaming-red/5 p-4 rounded-lg border border-gaming-red/30 mb-6"
                        >
                            <h2 class="text-xl font-play text-led-green mb-4">
                                Vos préférences
                            </h2>

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

                            <div class="mb-4">
                                <label
                                    class="flex items-center space-x-3 cursor-pointer"
                                >
                                    <input
                                        type="checkbox"
                                        v-model="form.rgb_lighting"
                                        class="form-checkbox h-5 w-5 text-led-green rounded border-gaming-red bg-deep-black"
                                    />
                                    <span
                                        class="text-white text-sm font-medium"
                                    >
                                        Je souhaite un éclairage RGB
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
                        <div class="mb-4">
                            <label
                                class="block text-white text-sm font-medium mb-2"
                            >
                                Notes supplémentaires (optionnel)
                            </label>
                            <textarea
                                v-model="form.additional_notes"
                                rows="3"
                                class="w-full px-3 py-2 bg-deep-black border border-gaming-red rounded-md text-white focus:outline-none focus:ring-2 focus:ring-led-green"
                                placeholder="Y a-t-il d'autres informations que vous aimeriez nous transmettre ?"
                            ></textarea>
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full px-4 py-3 bg-gaming-red text-white rounded-md hover:bg-opacity-90 transition duration-150 disabled:opacity-50 font-play"
                        >
                            Envoyer ma demande de configuration
                        </button>
                    </form>
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
