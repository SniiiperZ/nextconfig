<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <Head :title="title" />

        <div class="min-h-screen bg-deep-black">
            <nav class="border-b border-gaming-red">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Logo à gauche -->
                        <div class="flex-shrink-0 flex items-center">
                            <Link :href="route('home')">
                                <img
                                    src="logo.png"
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
                                :href="route('configurator')"
                                class="text-white hover:text-led-green transition"
                                :class="{
                                    'text-led-green font-bold border-b-2 border-led-green pb-1':
                                        route().current('configurator'),
                                }"
                            >
                                Configurateur PC
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
                            :href="route('configurator')"
                            class="block pl-3 pr-4 py-2 text-white hover:text-led-green transition"
                            :class="{
                                'bg-gaming-red/10 border-l-4 border-led-green font-bold text-led-green':
                                    route().current('configurator'),
                            }"
                        >
                            Configurateur PC
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
                    <div class="text-center text-white">
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
