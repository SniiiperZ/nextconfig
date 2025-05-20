<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import CookieConsent from "@/Components/CookieConsent.vue"; // Ajout de l'import

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default:
            "Expert en configuration et assemblage de PC Gaming sur mesure en Belgique. Des machines performantes adaptées à vos besoins et votre budget.",
    },
    keywords: {
        type: String,
        default:
            "PC gaming Belgique, configuration PC sur mesure, assemblage PC gaming, NextConfig",
    },
});

const page = usePage();

const showingNavigationDropdown = ref(false);
// Pour gérer la transparence/opacité de la barre de navigation lors du défilement
const isScrolled = ref(false);
// Pour contrôler si nous sommes en mode mobile
const isMobile = ref(window.innerWidth < 640);

// Observer le défilement pour des effets visuels sur la barre de navigation
const handleScroll = () => {
    isScrolled.value = window.scrollY > 10;
};

// Fermer le menu mobile lorsqu'on clique en dehors
const handleOutsideClick = (event) => {
    const nav = document.getElementById("mobile-menu");
    const hamburger = document.getElementById("hamburger-button");

    if (
        showingNavigationDropdown.value &&
        nav &&
        !nav.contains(event.target) &&
        hamburger &&
        !hamburger.contains(event.target)
    ) {
        showingNavigationDropdown.value = false;
    }
};

// Gérer le redimensionnement de la fenêtre
const handleResize = () => {
    isMobile.value = window.innerWidth < 640;
    // Fermer le menu mobile si on passe en mode desktop
    if (!isMobile.value) {
        showingNavigationDropdown.value = false;
    }
};

// Fermer le menu lors d'une navigation
watch(
    () => page.url,
    () => {
        showingNavigationDropdown.value = false;
    },
    { deep: true }
);

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
    window.addEventListener("click", handleOutsideClick);
    window.addEventListener("resize", handleResize);
    handleScroll(); // Vérifier l'état initial
    handleResize(); // Vérifier la taille initiale
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
    window.removeEventListener("click", handleOutsideClick);
    window.removeEventListener("resize", handleResize);
});

const fullTitle = computed(() => {
    return props.title
        ? `${props.title}`
        : "NextConfig - Assemblage de PC Gaming sur mesure en Belgique";
});

const canonicalUrl = computed(() => {
    // Obtenir l'URL actuelle
    return window.location.origin + page.url;
});
</script>

<template>
    <div>
        <Head>
            <title>{{ fullTitle }}</title>
            <meta
                name="description"
                :content="description"
                head-key="description"
            />
            <meta name="keywords" :content="keywords" head-key="keywords" />
            <link rel="canonical" :href="canonicalUrl" head-key="canonical" />

            <!-- OpenGraph tags pour améliorer le partage sur les réseaux sociaux -->
            <meta
                property="og:title"
                :content="fullTitle"
                head-key="og:title"
            />
            <meta
                property="og:description"
                :content="description"
                head-key="og:description"
            />
            <meta property="og:url" :content="canonicalUrl" head-key="og:url" />
            <meta property="og:type" content="website" head-key="og:type" />

            <!-- Twitter Card data -->
            <meta
                name="twitter:title"
                :content="fullTitle"
                head-key="twitter:title"
            />
            <meta
                name="twitter:description"
                :content="description"
                head-key="twitter:description"
            />
            <meta
                name="twitter:card"
                content="summary"
                head-key="twitter:card"
            />
        </Head>

        <div class="min-h-screen bg-deep-black">
            <!-- Barre de navigation avec effet de scroll -->
            <nav
                :class="[
                    'border-b border-gaming-red transition-all duration-300 sticky top-0 z-50',
                    isScrolled ? 'bg-deep-black/95 shadow-lg' : 'bg-deep-black',
                ]"
            >
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 sm:h-20">
                        <!-- Logo à gauche -->
                        <div class="flex-shrink-0 flex items-center">
                            <Link
                                :href="route('home')"
                                class="focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gaming-red rounded-md"
                            >
                                <img
                                    src="/logo.png"
                                    alt="NextConfig Logo"
                                    class="h-12 sm:h-16 w-auto transition-all duration-300"
                                />
                            </Link>
                        </div>

                        <!-- Navigation Links à droite - Version desktop -->
                        <div
                            class="hidden sm:flex sm:items-center space-x-4 md:space-x-8"
                        >
                            <Link
                                v-for="(item, index) in [
                                    { name: 'Accueil', route: 'home' },
                                    { name: 'À propos', route: 'about' },
                                    { name: 'Nos services', route: 'services' },
                                    {
                                        name: 'Réalisations',
                                        route: 'portfolio',
                                    },
                                    { name: 'Blog', route: 'blog' },
                                    { name: 'FAQ', route: 'faq' },
                                    { name: 'Devis', route: 'devis' },
                                    {
                                        name: 'Calculateur',
                                        route: 'budget.calculator',
                                    },
                                    { name: 'Contact', route: 'contact' },
                                ]"
                                :key="index"
                                :href="route(item.route)"
                                class="text-white hover:text-led-green transition-all duration-200 text-sm md:text-base py-2 px-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gaming-red rounded-md"
                                :class="{
                                    'text-led-green font-bold border-b-2 border-led-green pb-1':
                                        route().current(item.route),
                                }"
                            >
                                {{ item.name }}
                            </Link>
                        </div>

                        <!-- Hamburger Menu -->
                        <div class="flex items-center sm:hidden">
                            <button
                                id="hamburger-button"
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-led-green transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gaming-red"
                                aria-label="Menu principal"
                            >
                                <span class="sr-only">Menu principal</span>
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Menu Mobile avec animation -->
                <transition
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-150"
                    leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95"
                >
                    <div
                        v-show="showingNavigationDropdown"
                        id="mobile-menu"
                        class="sm:hidden absolute top-16 left-0 right-0 bg-deep-black/95 backdrop-blur-md border-b border-gaming-red/50 shadow-lg z-40"
                    >
                        <div
                            class="pt-2 pb-3 space-y-1 max-h-[70vh] overflow-y-auto scrollbar-thin"
                        >
                            <Link
                                v-for="(item, index) in [
                                    { name: 'Accueil', route: 'home' },
                                    { name: 'À propos', route: 'about' },
                                    { name: 'Nos services', route: 'services' },
                                    {
                                        name: 'Réalisations',
                                        route: 'portfolio',
                                    },
                                    { name: 'Blog', route: 'blog' },
                                    { name: 'FAQ', route: 'faq' },
                                    { name: 'Devis', route: 'devis' },
                                    {
                                        name: 'Calculateur',
                                        route: 'budget.calculator',
                                    },
                                    { name: 'Contact', route: 'contact' },
                                ]"
                                :key="index"
                                :href="route(item.route)"
                                class="block pl-3 pr-4 py-2 text-white hover:text-led-green transition-all hover:bg-gaming-red/5 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-gaming-red"
                                :class="{
                                    'bg-gaming-red/10 border-l-4 border-led-green font-bold text-led-green':
                                        route().current(item.route),
                                }"
                                @click="showingNavigationDropdown = false"
                            >
                                {{ item.name }}
                            </Link>
                        </div>
                    </div>
                </transition>
            </nav>

            <!-- Contenu Principal -->
            <main>
                <slot />
            </main>

            <!-- Footer -->
            <footer class="bg-deep-black border-t border-gaming-red">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                        <!-- Colonne 1: Logo et texte -->
                        <div class="text-center md:text-left">
                            <Link
                                :href="route('home')"
                                class="inline-block focus:outline-none focus:ring-2 focus:ring-gaming-red rounded-md"
                            >
                                <img
                                    src="/logo.png"
                                    alt="NextConfig Logo"
                                    class="h-16 sm:h-20 w-auto mx-auto md:mx-0 mb-2"
                                />
                            </Link>
                            <p class="text-white/70 text-sm mt-2">
                                Votre expert en PC Gaming sur mesure en
                                Belgique. Qualité et performance garanties.
                            </p>
                        </div>

                        <!-- Colonne 2: Liens rapides -->
                        <div class="text-center">
                            <h3
                                class="text-led-green text-lg font-semibold mb-3"
                            >
                                Liens rapides
                            </h3>
                            <div
                                class="grid grid-cols-2 sm:flex sm:flex-col sm:space-y-2 gap-2"
                            >
                                <Link
                                    v-for="(item, index) in [
                                        { name: 'Accueil', route: 'home' },
                                        {
                                            name: 'Nos services',
                                            route: 'services',
                                        },
                                        {
                                            name: 'Réalisations',
                                            route: 'portfolio',
                                        },
                                        { name: 'Contact', route: 'contact' },
                                    ]"
                                    :key="index"
                                    :href="route(item.route)"
                                    class="text-white hover:text-led-green transition-all focus:outline-none focus:ring-1 focus:ring-gaming-red rounded px-2 py-1"
                                >
                                    {{ item.name }}
                                </Link>
                            </div>
                        </div>

                        <!-- Colonne 3: Informations légales -->
                        <div class="text-center md:text-right">
                            <h3
                                class="text-led-green text-lg font-semibold mb-3"
                            >
                                Informations légales
                            </h3>
                            <div
                                class="grid grid-cols-2 sm:flex sm:flex-col sm:space-y-2 sm:items-end gap-2"
                            >
                                <Link
                                    :href="route('privacy.policy')"
                                    class="text-white hover:text-led-green transition-all focus:outline-none focus:ring-1 focus:ring-gaming-red rounded px-2 py-1"
                                >
                                    Politique de confidentialité
                                </Link>
                                <Link
                                    :href="route('legal.notices')"
                                    class="text-white hover:text-led-green transition-all focus:outline-none focus:ring-1 focus:ring-gaming-red rounded px-2 py-1"
                                >
                                    Mentions légales
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div
                        class="text-center text-white/70 pt-4 border-t border-gaming-red/30"
                    >
                        <p>
                            &copy; {{ new Date().getFullYear() }} NextConfig.
                            Tous droits réservés.
                        </p>
                    </div>
                </div>
            </footer>

            <!-- Bandeau de consentement aux cookies -->
            <CookieConsent />
        </div>
    </div>
</template>

<style scoped>
/* Style pour la barre de navigation */
nav {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Style personnalisé pour la barre de défilement dans le menu mobile */
.scrollbar-thin {
    scrollbar-width: thin;
    scrollbar-color: rgba(236, 64, 122, 0.5) rgba(0, 0, 0, 0.1);
}

.scrollbar-thin::-webkit-scrollbar {
    width: 4px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background-color: rgba(236, 64, 122, 0.5);
    border-radius: 6px;
}

/* Animation pour le focus des boutons */
@keyframes pulse-focus {
    0%,
    100% {
        box-shadow: 0 0 0 2px rgba(236, 64, 122, 0.5);
    }
    50% {
        box-shadow: 0 0 0 4px rgba(236, 64, 122, 0.3);
    }
}

/* Support de la navigation au clavier */
a:focus-visible,
button:focus-visible {
    outline: none;
    box-shadow: 0 0 0 3px rgba(236, 64, 122, 0.6);
    animation: pulse-focus 1.5s infinite;
}
</style>
