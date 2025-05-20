<script setup>
import { ref, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";

const showBanner = ref(false);
const cookieConsentKey = "nextconfig_cookie_consent";
const cookieChoices = ref({
    necessary: true, // Toujours obligatoire
    analytics: false,
    marketing: false,
});

// Récupérer les préférences existantes ou afficher la bannière
onMounted(() => {
    const savedConsent = localStorage.getItem(cookieConsentKey);

    if (savedConsent) {
        try {
            const parsedConsent = JSON.parse(savedConsent);
            cookieChoices.value = {
                ...cookieChoices.value,
                ...parsedConsent,
            };
        } catch (e) {
            console.error(
                "Erreur lors de la lecture des préférences de cookies",
                e
            );
            showBanner.value = true;
        }
    } else {
        showBanner.value = true;
    }
});

const acceptAll = () => {
    cookieChoices.value = {
        necessary: true,
        analytics: true,
        marketing: true,
    };
    saveConsent();
};

const acceptNecessary = () => {
    cookieChoices.value = {
        necessary: true,
        analytics: false,
        marketing: false,
    };
    saveConsent();
};

const saveCustomPreferences = () => {
    saveConsent();
};

const saveConsent = () => {
    localStorage.setItem(cookieConsentKey, JSON.stringify(cookieChoices.value));
    showBanner.value = false;

    // Activer/désactiver Google Analytics en fonction du choix
    if (cookieChoices.value.analytics) {
        enableGoogleAnalytics();
    } else {
        disableGoogleAnalytics();
    }
};

const enableGoogleAnalytics = () => {
    // Activer Google Analytics
    window["ga-disable-G-5GLJQE505D"] = false;
};

const disableGoogleAnalytics = () => {
    // Désactiver Google Analytics
    window["ga-disable-G-5GLJQE505D"] = true;
};

const openPreferences = ref(false);
</script>

<template>
    <transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="transform translate-y-full opacity-0"
        enter-to-class="transform translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="transform translate-y-0 opacity-100"
        leave-to-class="transform translate-y-full opacity-0"
    >
        <div
            v-if="showBanner"
            class="fixed bottom-0 left-0 right-0 bg-deep-black border-t border-gaming-red shadow-lg shadow-gaming-red/20 z-50 p-4 md:p-6"
        >
            <div class="max-w-7xl mx-auto">
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
                >
                    <div class="flex-grow">
                        <h3 class="text-led-green text-lg font-bold mb-2">
                            Paramètres de confidentialité
                        </h3>
                        <p class="text-white/80 text-sm mb-4">
                            Ce site utilise des cookies pour améliorer votre
                            expérience. Veuillez choisir les types de cookies
                            que vous acceptez.
                        </p>

                        <div
                            v-if="openPreferences"
                            class="bg-gray-900/50 p-4 rounded-md mb-4"
                        >
                            <div class="mb-3">
                                <div
                                    class="flex items-center justify-between mb-2"
                                >
                                    <label class="font-medium text-white"
                                        >Cookies nécessaires</label
                                    >
                                    <input
                                        type="checkbox"
                                        v-model="cookieChoices.necessary"
                                        disabled
                                        class="rounded cursor-not-allowed"
                                    />
                                </div>
                                <p class="text-xs text-white/70">
                                    Ces cookies sont essentiels au
                                    fonctionnement du site et ne peuvent pas
                                    être désactivés.
                                </p>
                            </div>

                            <div class="mb-3">
                                <div
                                    class="flex items-center justify-between mb-2"
                                >
                                    <label
                                        class="font-medium text-white cursor-pointer"
                                        for="analytics"
                                        >Cookies analytiques</label
                                    >
                                    <input
                                        type="checkbox"
                                        id="analytics"
                                        v-model="cookieChoices.analytics"
                                        class="rounded border-gaming-red/50 text-led-green focus:ring-led-green cursor-pointer"
                                    />
                                </div>
                                <p class="text-xs text-white/70">
                                    Nous utilisons ces cookies pour analyser le
                                    trafic et améliorer notre site (Google
                                    Analytics).
                                </p>
                            </div>

                            <div class="mb-3">
                                <div
                                    class="flex items-center justify-between mb-2"
                                >
                                    <label
                                        class="font-medium text-white cursor-pointer"
                                        for="marketing"
                                        >Cookies marketing</label
                                    >
                                    <input
                                        type="checkbox"
                                        id="marketing"
                                        v-model="cookieChoices.marketing"
                                        class="rounded border-gaming-red/50 text-led-green focus:ring-led-green cursor-pointer"
                                    />
                                </div>
                                <p class="text-xs text-white/70">
                                    Ces cookies sont utilisés pour vous proposer
                                    des publicités personnalisées.
                                </p>
                            </div>

                            <button
                                @click="saveCustomPreferences"
                                class="w-full bg-led-green hover:bg-led-green/90 text-black font-bold py-2 px-4 rounded-md transition-all mt-2"
                            >
                                Enregistrer mes préférences
                            </button>
                        </div>
                    </div>

                    <div
                        class="flex flex-col sm:flex-row gap-2 w-full md:w-auto"
                    >
                        <button
                            @click="openPreferences = !openPreferences"
                            class="bg-transparent border border-led-green hover:bg-led-green/10 text-led-green font-medium py-2 px-4 rounded-md transition-all flex-grow"
                        >
                            {{
                                openPreferences
                                    ? "Masquer les options"
                                    : "Personnaliser"
                            }}
                        </button>
                        <button
                            @click="acceptNecessary"
                            class="bg-gray-700 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition-all flex-grow"
                        >
                            Refuser
                        </button>
                        <button
                            @click="acceptAll"
                            class="bg-gaming-red hover:bg-gaming-red/90 text-white font-bold py-2 px-4 rounded-md transition-all flex-grow"
                        >
                            Tout accepter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<style scoped>
/* Animation pulse subtile pour attirer l'attention */
@keyframes subtle-pulse {
    0%,
    100% {
        box-shadow: 0 0 0 0 rgba(236, 64, 122, 0.2);
    }
    50% {
        box-shadow: 0 0 15px 0 rgba(236, 64, 122, 0.4);
    }
}

.animate-pulse {
    animation: subtle-pulse 3s infinite;
}

input[type="checkbox"] {
    accent-color: #07b6a4;
}
</style>
