<script setup>
import { ref, computed } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";

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

const fullTitle = computed(() => {
    return props.title
        ? `${props.title}`
        : "NextConfig - Assemblage de PC Gaming sur mesure en Belgique";
});

const canonicalUrl = computed(() => {
    // Obtenir l'URL actuelle
    return window.location.origin + page.url.value;
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
            <nav class="border-b border-gaming-red">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Logo à gauche -->
                        <div class="flex-shrink-0 flex items-center">
                            <Link :href="route('home')">
                                <img
                                    src="/logo.png"
                                    alt="NextConfig Logo"
                                    class="h-16 w-auto"
                                />
                            </Link>
                        </div>

                        <!-- Navigation Links à droite -->
                        <div class="hidden sm:flex sm:items-center space-x-8">
                            <Link
                                :href="route('home')"
                                class="text-white hover:text-led-green transition"
                                :class="{
                                    'text-led-green font-bold border-b-2 border-led-green pb-1':
                                        route().current('home'),
                                }"
                            >
                                Accueil
                            </Link>
                            <Link
                                :href="route('about')"
                                class="text-white hover:text-led-green transition"
                                :class="{
                                    'text-led-green font-bold border-b-2 border-led-green pb-1':
                                        route().current('about'),
                                }"
                            >
                                À propos
                            </Link>
                            <Link
                                :href="route('services')"
                                class="text-white hover:text-led-green transition"
                                :class="{
                                    'text-led-green font-bold border-b-2 border-led-green pb-1':
                                        route().current('services'),
                                }"
                            >
                                Nos services
                            </Link>
                            <Link
                                :href="route('portfolio')"
                                class="text-white hover:text-led-green transition"
                                :class="{
                                    'text-led-green font-bold border-b-2 border-led-green pb-1':
                                        route().current('portfolio'),
                                }"
                            >
                                Réalisations
                            </Link>
                            <Link
                                :href="route('blog')"
                                class="text-white hover:text-led-green transition"
                                :class="{
                                    'text-led-green font-bold border-b-2 border-led-green pb-1':
                                        route().current('blog'),
                                }"
                            >
                                Blog
                            </Link>
                            <Link
                                :href="route('faq')"
                                class="text-white hover:text-led-green transition"
                                :class="{
                                    'text-led-green font-bold border-b-2 border-led-green pb-1':
                                        route().current('faq'),
                                }"
                            >
                                FAQ
                            </Link>
                            <Link
                                :href="route('devis')"
                                class="text-white hover:text-led-green transition"
                                :class="{
                                    'text-led-green font-bold border-b-2 border-led-green pb-1':
                                        route().current('devis'),
                                }"
                            >
                                Devis
                            </Link>
                            <Link
                                :href="route('budget.calculator')"
                                class="text-white hover:text-led-green transition"
                                :class="{
                                    'text-led-green font-bold border-b-2 border-led-green pb-1':
                                        route().current('budget.calculator'),
                                }"
                            >
                                Calculateur de budget
                            </Link>
                            <Link
                                :href="route('contact')"
                                class="text-white hover:text-led-green transition"
                                :class="{
                                    'text-led-green font-bold border-b-2 border-led-green pb-1':
                                        route().current('contact'),
                                }"
                            >
                                Contact
                            </Link>
                        </div>

                        <!-- Hamburger Menu -->
                        <div class="flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-led-green"
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

                <!-- Menu Mobile -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <Link
                            :href="route('home')"
                            class="block pl-3 pr-4 py-2 text-white hover:text-led-green transition"
                            :class="{
                                'bg-gaming-red/10 border-l-4 border-led-green font-bold text-led-green':
                                    route().current('home'),
                            }"
                        >
                            Accueil
                        </Link>
                        <Link
                            :href="route('about')"
                            class="block pl-3 pr-4 py-2 text-white hover:text-led-green transition"
                            :class="{
                                'bg-gaming-red/10 border-l-4 border-led-green font-bold text-led-green':
                                    route().current('about'),
                            }"
                        >
                            À propos
                        </Link>
                        <Link
                            :href="route('services')"
                            class="block pl-3 pr-4 py-2 text-white hover:text-led-green transition"
                            :class="{
                                'bg-gaming-red/10 border-l-4 border-led-green font-bold text-led-green':
                                    route().current('services'),
                            }"
                        >
                            Nos services
                        </Link>
                        <Link
                            :href="route('portfolio')"
                            class="block pl-3 pr-4 py-2 text-white hover:text-led-green transition"
                            :class="{
                                'bg-gaming-red/10 border-l-4 border-led-green font-bold text-led-green':
                                    route().current('portfolio'),
                            }"
                        >
                            Réalisations
                        </Link>
                        <Link
                            :href="route('blog')"
                            class="block pl-3 pr-4 py-2 text-white hover:text-led-green transition"
                            :class="{
                                'bg-gaming-red/10 border-l-4 border-led-green font-bold text-led-green':
                                    route().current('blog'),
                            }"
                        >
                            Blog
                        </Link>
                        <Link
                            :href="route('faq')"
                            class="block pl-3 pr-4 py-2 text-white hover:text-led-green transition"
                            :class="{
                                'bg-gaming-red/10 border-l-4 border-led-green font-bold text-led-green':
                                    route().current('faq'),
                            }"
                        >
                            FAQ
                        </Link>
                        <Link
                            :href="route('devis')"
                            class="block pl-3 pr-4 py-2 text-white hover:text-led-green transition"
                            :class="{
                                'bg-gaming-red/10 border-l-4 border-led-green font-bold text-led-green':
                                    route().current('devis'),
                            }"
                        >
                            Devis
                        </Link>
                        <Link
                            :href="route('budget.calculator')"
                            class="block pl-3 pr-4 py-2 text-white hover:text-led-green transition"
                            :class="{
                                'bg-gaming-red/10 border-l-4 border-led-green font-bold text-led-green':
                                    route().current('budget.calculator'),
                            }"
                        >
                            Calculateur de budget
                        </Link>
                        <Link
                            :href="route('contact')"
                            class="block pl-3 pr-4 py-2 text-white hover:text-led-green transition"
                            :class="{
                                'bg-gaming-red/10 border-l-4 border-led-green font-bold text-led-green':
                                    route().current('contact'),
                            }"
                        >
                            Contact
                        </Link>
                    </div>
                </div>
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
                            <Link :href="route('home')">
                                <img
                                    src="/logo.png"
                                    alt="NextConfig Logo"
                                    class="h-20 w-auto mx-auto md:mx-0 mb-2"
                                />
                            </Link>
                        </div>

                        <!-- Colonne 2: Liens rapides -->
                        <div class="text-center">
                            <h3
                                class="text-led-green text-lg font-semibold mb-3"
                            >
                                Liens rapides
                            </h3>
                            <div class="flex flex-col space-y-2">
                                <Link
                                    :href="route('home')"
                                    class="text-white hover:text-led-green transition"
                                    >Accueil</Link
                                >
                                <Link
                                    :href="route('services')"
                                    class="text-white hover:text-led-green transition"
                                    >Nos services</Link
                                >
                                <Link
                                    :href="route('portfolio')"
                                    class="text-white hover:text-led-green transition"
                                    >Réalisations</Link
                                >
                                <Link
                                    :href="route('contact')"
                                    class="text-white hover:text-led-green transition"
                                    >Contact</Link
                                >
                            </div>
                        </div>

                        <!-- Colonne 3: Informations légales -->
                        <div class="text-center md:text-right">
                            <h3
                                class="text-led-green text-lg font-semibold mb-3"
                            >
                                Informations légales
                            </h3>
                            <div class="flex flex-col space-y-2">
                                <Link
                                    :href="route('privacy.policy')"
                                    class="text-white hover:text-led-green transition"
                                    >Politique de confidentialité</Link
                                >
                                <Link
                                    :href="route('legal.notices')"
                                    class="text-white hover:text-led-green transition"
                                    >Mentions légales</Link
                                >
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
        </div>
    </div>
</template>
