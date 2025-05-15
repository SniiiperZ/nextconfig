<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <PublicLayout title="Services">
        <!-- Hero Section pour la page Services -->
        <div class="services-hero-section">
            <div class="hero-content z-10 relative text-center">
                <h1
                    class="text-6xl md:text-7xl font-play text-gaming-red mb-6 hero-title"
                >
                    Nos Services
                </h1>
                <p
                    class="text-2xl text-white mb-12 max-w-3xl mx-auto font-medium text-shadow-lg"
                >
                    Des solutions sur mesure pour tous vos besoins gaming
                </p>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Services détaillés -->
                <div class="space-y-16">
                    <!-- Montage PC -->
                    <div class="service-card-large">
                        <div class="flex justify-center mb-10">
                            <h2
                                class="text-3xl font-play text-gaming-red section-title"
                            >
                                Montage PC Gaming
                            </h2>
                        </div>

                        <div
                            class="bg-deep-black p-8 rounded-lg border border-gaming-red hover-card"
                        >
                            <div class="grid md:grid-cols-2 gap-8 items-center">
                                <div class="animate-fade-in-left">
                                    <h3
                                        class="text-2xl font-play text-led-green mb-6"
                                    >
                                        Configuration sur mesure
                                    </h3>
                                    <ul class="text-white space-y-4">
                                        <li class="flex items-start">
                                            <svg
                                                class="w-6 h-6 mr-3 text-gaming-red flex-shrink-0 mt-0.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <span
                                                >Sélection personnalisée des
                                                composants selon votre budget et
                                                vos besoins spécifiques (gaming,
                                                streaming, création)</span
                                            >
                                        </li>
                                        <li class="flex items-start">
                                            <svg
                                                class="w-6 h-6 mr-3 text-gaming-red flex-shrink-0 mt-0.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <span
                                                >Assemblage professionnel avec
                                                gestion optimisée des câbles
                                                pour un flux d'air maximal</span
                                            >
                                        </li>
                                        <li class="flex items-start">
                                            <svg
                                                class="w-6 h-6 mr-3 text-gaming-red flex-shrink-0 mt-0.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <span
                                                >Tests de stabilité, benchmarks
                                                et optimisation des performances
                                                avant livraison</span
                                            >
                                        </li>
                                        <li class="flex items-start">
                                            <svg
                                                class="w-6 h-6 mr-3 text-gaming-red flex-shrink-0 mt-0.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <span
                                                >Installation des pilotes et du
                                                système d'exploitation de votre
                                                choix</span
                                            >
                                        </li>
                                    </ul>

                                    <div class="mt-8">
                                        <Link
                                            :href="route('devis')"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-white bg-gaming-red hover:bg-opacity-90 transition duration-300 shadow-glow"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 mr-2"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                                />
                                            </svg>
                                            Demander un devis
                                        </Link>
                                    </div>
                                </div>
                                <div
                                    class="flex justify-center animate-fade-in-right"
                                >
                                    <div class="service-image">
                                        <img
                                            src="/images/montage.jpg"
                                            alt="Montage PC Gaming"
                                            class="rounded-lg shadow-glow"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Maintenance -->
                    <div class="service-card-large">
                        <div class="flex justify-center mb-10">
                            <h2
                                class="text-3xl font-play text-gaming-red section-title"
                            >
                                Maintenance et Optimisation
                            </h2>
                        </div>

                        <div
                            class="bg-deep-black p-8 rounded-lg border border-gaming-red hover-card"
                        >
                            <div class="grid md:grid-cols-2 gap-8 items-center">
                                <div
                                    class="order-2 md:order-1 flex justify-center animate-fade-in-left"
                                >
                                    <div class="service-image">
                                        <img
                                            src="/images/maintenance.jpg"
                                            alt="Maintenance PC Gaming"
                                            class="rounded-lg shadow-glow"
                                        />
                                    </div>
                                </div>
                                <div
                                    class="order-1 md:order-2 animate-fade-in-right"
                                >
                                    <h3
                                        class="text-2xl font-play text-led-green mb-6"
                                    >
                                        Performances optimales
                                    </h3>
                                    <ul class="text-white space-y-4">
                                        <li class="flex items-start">
                                            <svg
                                                class="w-6 h-6 mr-3 text-gaming-red flex-shrink-0 mt-0.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <span
                                                >Nettoyage complet du système
                                                (dépoussiérage des ventilateurs,
                                                radiateurs, composants)</span
                                            >
                                        </li>
                                        <li class="flex items-start">
                                            <svg
                                                class="w-6 h-6 mr-3 text-gaming-red flex-shrink-0 mt-0.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <span
                                                >Remplacement de la pâte
                                                thermique pour des températures
                                                optimales</span
                                            >
                                        </li>
                                        <li class="flex items-start">
                                            <svg
                                                class="w-6 h-6 mr-3 text-gaming-red flex-shrink-0 mt-0.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <span
                                                >Mise à jour des pilotes et du
                                                BIOS pour des performances
                                                améliorées</span
                                            >
                                        </li>
                                        <li class="flex items-start">
                                            <svg
                                                class="w-6 h-6 mr-3 text-gaming-red flex-shrink-0 mt-0.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <span
                                                >Diagnostics complets et
                                                recommandations pour les mises à
                                                niveau potentielles</span
                                            >
                                        </li>
                                    </ul>

                                    <div class="mt-8">
                                        <Link
                                            :href="route('contact')"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-white bg-gaming-red hover:bg-opacity-90 transition duration-300 shadow-glow"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 mr-2"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                                />
                                            </svg>
                                            Prendre rendez-vous
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Optimisation -->
                    <div class="service-card-large">
                        <div class="flex justify-center mb-10">
                            <h2
                                class="text-3xl font-play text-gaming-red section-title"
                            >
                                Upgrade et Personnalisation
                            </h2>
                        </div>

                        <div
                            class="bg-deep-black p-8 rounded-lg border border-gaming-red hover-card"
                        >
                            <div class="grid md:grid-cols-2 gap-8 items-center">
                                <div class="animate-fade-in-left">
                                    <h3
                                        class="text-2xl font-play text-led-green mb-6"
                                    >
                                        Améliorez votre expérience
                                    </h3>
                                    <ul class="text-white space-y-4">
                                        <li class="flex items-start">
                                            <svg
                                                class="w-6 h-6 mr-3 text-gaming-red flex-shrink-0 mt-0.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <span
                                                >Upgrade ciblé des composants
                                                pour améliorer les performances
                                                (GPU, CPU, RAM)</span
                                            >
                                        </li>
                                        <li class="flex items-start">
                                            <svg
                                                class="w-6 h-6 mr-3 text-gaming-red flex-shrink-0 mt-0.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <span
                                                >Installation de systèmes de
                                                refroidissement avancés (AIO,
                                                watercooling)</span
                                            >
                                        </li>
                                        <li class="flex items-start">
                                            <svg
                                                class="w-6 h-6 mr-3 text-gaming-red flex-shrink-0 mt-0.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <span
                                                >Personnalisation RGB
                                                synchronisée pour un setup
                                                gaming unique</span
                                            >
                                        </li>
                                        <li class="flex items-start">
                                            <svg
                                                class="w-6 h-6 mr-3 text-gaming-red flex-shrink-0 mt-0.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <span
                                                >Optimisation des performances
                                                via overclocking sécurisé et
                                                stable</span
                                            >
                                        </li>
                                    </ul>

                                    <div class="mt-8">
                                        <Link
                                            :href="route('budget.calculator')"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-white bg-gaming-red hover:bg-opacity-90 transition duration-300 shadow-glow"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 mr-2"
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
                                            Calculer mon budget upgrade
                                        </Link>
                                    </div>
                                </div>
                                <div
                                    class="flex justify-center animate-fade-in-right"
                                >
                                    <div class="service-image custom-rgb-bg">
                                        <img
                                            src="/images/background.jpg"
                                            alt="Upgrade PC Gaming"
                                            class="rounded-lg shadow-glow"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="mt-20 text-center">
                    <h2 class="text-3xl font-play text-gaming-red mb-6">
                        Prêt à booster votre expérience gaming?
                    </h2>
                    <p class="text-white text-xl mb-8 max-w-3xl mx-auto">
                        Contactez-nous dès aujourd'hui pour discuter de votre
                        projet ou commencer à configurer votre PC gaming sur
                        mesure.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <Link
                            :href="route('devis')"
                            class="main-cta-button inline-flex items-center justify-center px-6 py-3 border border-transparent text-lg font-medium rounded-md text-white"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 mr-2"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                />
                            </svg>
                            Demander un devis
                        </Link>
                        <Link
                            :href="route('contact')"
                            class="inline-flex items-center justify-center px-6 py-3 border border-gaming-red text-lg font-medium rounded-md text-white hover:bg-gaming-red/10 transition duration-300"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 mr-2"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>
                            Nous contacter
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
/* Style pour la hero section */
.services-hero-section {
    position: relative;
    width: 100%;
    min-height: 50vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.85)),
        url("/images/background.jpg");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    padding: 2rem;
    margin-bottom: 2rem;
}

.hero-content {
    width: 100%;
    max-width: 1200px;
    padding: 3rem 1.5rem;
    animation: fadeIn 1.2s ease-out;
}

.hero-title {
    text-shadow: 0 0 15px rgba(236, 64, 122, 0.7), 0 3px 10px rgba(0, 0, 0, 0.8);
    letter-spacing: 1px;
    font-weight: bold;
    animation: glow 2s ease-in-out infinite alternate;
}

.text-shadow-lg {
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
}

/* Image avec effet de glow */
.service-image {
    position: relative;
    display: inline-block;
    border-radius: 8px;
    overflow: hidden;
    width: 100%;
    max-width: 500px;
    transition: all 0.3s ease;
}

.shadow-glow {
    box-shadow: 0 0 20px rgba(236, 64, 122, 0.5);
    transition: all 0.3s ease;
}

.service-image:hover .shadow-glow {
    box-shadow: 0 0 30px rgba(236, 64, 122, 0.8);
}

/* Card de service avec hover effect */
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 0 15px rgba(236, 64, 122, 0.3);
    transform: translateY(-5px);
}

/* Animation pour le titre */
@keyframes glow {
    from {
        text-shadow: 0 0 15px rgba(236, 64, 122, 0.7),
            0 3px 10px rgba(0, 0, 0, 0.8);
    }
    to {
        text-shadow: 0 0 25px rgba(236, 64, 122, 0.9),
            0 3px 10px rgba(0, 0, 0, 0.8);
    }
}

/* Styles pour les titres de section */
.section-title {
    position: relative;
    display: inline-block;
    padding-bottom: 10px;
    margin-left: auto;
    margin-right: auto;
}

.section-title::after {
    content: "";
    position: absolute;
    width: 60%;
    height: 3px;
    bottom: 0;
    left: 20%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(236, 64, 122, 0.8),
        transparent
    );
}

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

.shadow-glow {
    box-shadow: 0 0 15px rgba(236, 64, 122, 0.4);
}

/* Fond RGB personnalisé pour l'élément */
.custom-rgb-bg {
    position: relative;
}

.custom-rgb-bg::before {
    content: "";
    position: absolute;
    top: -3px;
    left: -3px;
    right: -3px;
    bottom: -3px;
    background: linear-gradient(
        45deg,
        #ff0000,
        #ff7300,
        #fffb00,
        #48ff00,
        #00ffd5,
        #002bff,
        #7a00ff,
        #ff00c8,
        #ff0000
    );
    background-size: 400%;
    z-index: -1;
    filter: blur(5px);
    animation: rgb-animation 20s linear infinite;
    border-radius: 10px;
    opacity: 0.7;
}

@keyframes rgb-animation {
    0% {
        background-position: 0 0;
    }
    50% {
        background-position: 400% 0;
    }
    100% {
        background-position: 0 0;
    }
}

/* Animations d'entrée */
.animate-fade-in-left {
    animation: fadeInLeft 1s ease-out;
}

.animate-fade-in-right {
    animation: fadeInRight 1s ease-out;
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

@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>
