<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default:
            "Panneau d'administration NextConfig - Gestion de votre site d'assemblage PC gaming sur mesure",
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
        ? `Admin | ${props.title}`
        : "NextConfig - Administration";
});

const switchToTeam = (team) => {
    router.put(
        route("current-team.update"),
        {
            team_id: team.id,
        },
        {
            preserveState: false,
        }
    );
};

const logout = () => {
    router.post(route("logout"));
};

// Navigation items pour éviter la duplication de code
const navItems = [
    { name: "Dashboard", route: "dashboard" },
    { name: "FAQ", route: "admin.faq" },
    { name: "Portfolio", route: "admin.portfolio" },
    { name: "Blog", route: "admin.blog" },
    { name: "Avis Clients", route: "admin.reviews" },
    { name: "Commentaires", route: "admin.comments" },
];
</script>

<template>
    <div>
        <Head :title="fullTitle">
            <meta
                name="description"
                :content="description"
                head-key="description"
            />
        </Head>

        <Banner />

        <div class="min-h-screen bg-deep-black">
            <nav
                :class="[
                    'border-b border-gaming-red transition-all duration-300 sticky top-0 z-50',
                    isScrolled
                        ? 'bg-deep-black/95 shadow-lg backdrop-blur-md'
                        : 'bg-deep-black',
                ]"
            >
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 sm:h-20">
                        <div class="flex items-center">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link
                                    :href="route('dashboard')"
                                    class="focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gaming-red rounded-md"
                                >
                                    <ApplicationMark
                                        class="block h-12 sm:h-14 w-auto transition-all duration-300"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-4 md:space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    v-for="item in navItems"
                                    :key="item.route"
                                    :href="route(item.route)"
                                    :active="route().current(item.route)"
                                    class="font-medium text-sm sm:text-base"
                                >
                                    {{ item.name }}
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center">
                            <div class="ms-3 relative">
                                <!-- Teams Dropdown -->
                                <Dropdown
                                    v-if="$page.props.jetstream.hasTeamFeatures"
                                    align="right"
                                    width="60"
                                >
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-gaming-red/30 bg-deep-black/30 text-sm leading-4 font-medium rounded-md text-white hover:text-led-green focus:outline-none focus:ring-2 focus:ring-led-green focus:ring-offset-2 focus:ring-offset-deep-black transition ease-in-out duration-150"
                                            >
                                                {{
                                                    $page.props.auth.user
                                                        .current_team.name
                                                }}

                                                <svg
                                                    class="ms-2 -me-0.5 size-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="w-60">
                                            <!-- Team Management -->
                                            <div
                                                class="block px-4 py-2 text-xs text-gray-400"
                                            >
                                                Gérer l'équipe
                                            </div>

                                            <!-- Team Settings -->
                                            <DropdownLink
                                                :href="
                                                    route(
                                                        'teams.show',
                                                        $page.props.auth.user
                                                            .current_team
                                                    )
                                                "
                                            >
                                                Paramètres de l'équipe
                                            </DropdownLink>

                                            <DropdownLink
                                                v-if="
                                                    $page.props.jetstream
                                                        .canCreateTeams
                                                "
                                                :href="route('teams.create')"
                                            >
                                                Créer une nouvelle équipe
                                            </DropdownLink>

                                            <!-- Team Switcher -->
                                            <template
                                                v-if="
                                                    $page.props.auth.user
                                                        .all_teams.length > 1
                                                "
                                            >
                                                <div
                                                    class="border-t border-gaming-red/30"
                                                ></div>

                                                <div
                                                    class="block px-4 py-2 text-xs text-gray-400"
                                                >
                                                    Changer d'équipe
                                                </div>

                                                <template
                                                    v-for="team in $page.props
                                                        .auth.user.all_teams"
                                                    :key="team.id"
                                                >
                                                    <form
                                                        @submit.prevent="
                                                            switchToTeam(team)
                                                        "
                                                    >
                                                        <DropdownLink
                                                            as="button"
                                                        >
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <svg
                                                                    v-if="
                                                                        team.id ==
                                                                        $page
                                                                            .props
                                                                            .auth
                                                                            .user
                                                                            .current_team_id
                                                                    "
                                                                    class="me-2 size-5 text-led-green"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    fill="none"
                                                                    viewBox="0 0 24 24"
                                                                    stroke-width="1.5"
                                                                    stroke="currentColor"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                                    />
                                                                </svg>

                                                                <div>
                                                                    {{
                                                                        team.name
                                                                    }}
                                                                </div>
                                                            </div>
                                                        </DropdownLink>
                                                    </form>
                                                </template>
                                            </template>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            v-if="
                                                $page.props.jetstream
                                                    .managesProfilePhotos
                                            "
                                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-led-green transition"
                                        >
                                            <img
                                                class="size-8 rounded-full object-cover border border-gaming-red/30"
                                                :src="
                                                    $page.props.auth.user
                                                        .profile_photo_url
                                                "
                                                :alt="
                                                    $page.props.auth.user.name
                                                "
                                            />
                                        </button>

                                        <span
                                            v-else
                                            class="inline-flex rounded-md"
                                        >
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-gaming-red/30 bg-deep-black/30 text-sm leading-4 font-medium rounded-md text-white hover:text-led-green focus:outline-none focus:ring-2 focus:ring-led-green focus:ring-offset-2 focus:ring-offset-deep-black transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ms-2 -me-0.5 size-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div
                                            class="block px-4 py-2 text-xs text-gray-400"
                                        >
                                            Gérer le compte
                                        </div>

                                        <DropdownLink
                                            :href="route('profile.show')"
                                        >
                                            Profil
                                        </DropdownLink>

                                        <DropdownLink
                                            v-if="
                                                $page.props.jetstream
                                                    .hasApiFeatures
                                            "
                                            :href="route('api-tokens.index')"
                                        >
                                            API Tokens
                                        </DropdownLink>

                                        <div
                                            class="border-t border-gaming-red/30"
                                        ></div>

                                        <!-- Site Public -->
                                        <DropdownLink :href="route('home')">
                                            Voir le site public
                                        </DropdownLink>

                                        <div
                                            class="border-t border-gaming-red/30"
                                        ></div>

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                Déconnexion
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger Button -->
                        <div class="flex items-center sm:hidden">
                            <button
                                id="hamburger-button"
                                class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-led-green transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gaming-red"
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
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

                <!-- Responsive Navigation Menu with animation -->
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
                            <ResponsiveNavLink
                                v-for="item in navItems"
                                :key="item.route"
                                :href="route(item.route)"
                                :active="route().current(item.route)"
                            >
                                {{ item.name }}
                            </ResponsiveNavLink>
                        </div>

                        <!-- Responsive Settings Options -->
                        <div class="pt-4 pb-1 border-t border-gaming-red/30">
                            <div class="flex items-center px-4">
                                <div
                                    v-if="
                                        $page.props.jetstream
                                            .managesProfilePhotos
                                    "
                                    class="shrink-0 me-3"
                                >
                                    <img
                                        class="size-10 rounded-full object-cover border border-gaming-red/30"
                                        :src="
                                            $page.props.auth.user
                                                .profile_photo_url
                                        "
                                        :alt="$page.props.auth.user.name"
                                    />
                                </div>

                                <div>
                                    <div
                                        class="font-medium text-base text-white"
                                    >
                                        {{ $page.props.auth.user.name }}
                                    </div>
                                    <div
                                        class="font-medium text-sm text-gray-400"
                                    >
                                        {{ $page.props.auth.user.email }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink
                                    :href="route('profile.show')"
                                    :active="route().current('profile.show')"
                                >
                                    Profil
                                </ResponsiveNavLink>

                                <ResponsiveNavLink
                                    v-if="$page.props.jetstream.hasApiFeatures"
                                    :href="route('api-tokens.index')"
                                    :active="
                                        route().current('api-tokens.index')
                                    "
                                >
                                    API Tokens
                                </ResponsiveNavLink>

                                <!-- Voir le site public -->
                                <ResponsiveNavLink :href="route('home')">
                                    Voir le site public
                                </ResponsiveNavLink>

                                <!-- Authentication -->
                                <form method="POST" @submit.prevent="logout">
                                    <ResponsiveNavLink as="button">
                                        Déconnexion
                                    </ResponsiveNavLink>
                                </form>

                                <!-- Team Management -->
                                <template
                                    v-if="$page.props.jetstream.hasTeamFeatures"
                                >
                                    <div
                                        class="border-t border-gaming-red/30"
                                    ></div>

                                    <div
                                        class="block px-4 py-2 text-xs text-gray-400"
                                    >
                                        Gérer l'équipe
                                    </div>

                                    <!-- Team Settings -->
                                    <ResponsiveNavLink
                                        :href="
                                            route(
                                                'teams.show',
                                                $page.props.auth.user
                                                    .current_team
                                            )
                                        "
                                        :active="route().current('teams.show')"
                                    >
                                        Paramètres de l'équipe
                                    </ResponsiveNavLink>

                                    <ResponsiveNavLink
                                        v-if="
                                            $page.props.jetstream.canCreateTeams
                                        "
                                        :href="route('teams.create')"
                                        :active="
                                            route().current('teams.create')
                                        "
                                    >
                                        Créer une nouvelle équipe
                                    </ResponsiveNavLink>

                                    <!-- Team Switcher -->
                                    <template
                                        v-if="
                                            $page.props.auth.user.all_teams
                                                .length > 1
                                        "
                                    >
                                        <div
                                            class="border-t border-gaming-red/30"
                                        ></div>

                                        <div
                                            class="block px-4 py-2 text-xs text-gray-400"
                                        >
                                            Changer d'équipe
                                        </div>

                                        <template
                                            v-for="team in $page.props.auth.user
                                                .all_teams"
                                            :key="team.id"
                                        >
                                            <form
                                                @submit.prevent="
                                                    switchToTeam(team)
                                                "
                                            >
                                                <ResponsiveNavLink as="button">
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <svg
                                                            v-if="
                                                                team.id ==
                                                                $page.props.auth
                                                                    .user
                                                                    .current_team_id
                                                            "
                                                            class="me-2 size-5 text-led-green"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke-width="1.5"
                                                            stroke="currentColor"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                            />
                                                        </svg>
                                                        <div>
                                                            {{ team.name }}
                                                        </div>
                                                    </div>
                                                </ResponsiveNavLink>
                                            </form>
                                        </template>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </div>
                </transition>
            </nav>

            <!-- Page Heading -->
            <header
                v-if="$slots.header"
                class="bg-deep-black shadow border-b border-gaming-red/30"
            >
                <div
                    class="max-w-7xl mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8"
                >
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>

            <!-- Footer / Admin Footer Light -->
            <footer class="bg-deep-black border-t border-gaming-red/30 py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center text-white/60 text-sm">
                        <p>
                            &copy; {{ new Date().getFullYear() }} NextConfig -
                            Administration
                        </p>
                        <p class="mt-1">
                            <Link
                                :href="route('home')"
                                class="text-led-green hover:text-led-green/80 transition-colors"
                            >
                                Voir le site public
                            </Link>
                        </p>
                    </div>
                </div>
            </footer>
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
