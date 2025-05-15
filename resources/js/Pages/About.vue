<script setup>
import { ref } from "vue";
import PublicLayout from "@/Layouts/PublicLayout.vue";

// Données sur l'expérience professionnelle
const experiences = ref([
    {
        title: "Assembleur PC Senior",
        period: "2020 - Présent",
        description:
            "Création de configurations gaming haut de gamme et sur mesure.",
    },
    {
        title: "Technicien Hardware",
        period: "2018 - 2020",
        description: "Diagnostic et réparation de matériel informatique.",
    },
    {
        title: "Conseiller Tech",
        period: "2016 - 2018",
        description: "Conseil client et vente de matériel gaming.",
    },
]);
</script>

<template>
    <PublicLayout title="À Propos">
        <!-- Hero Section pour la page À Propos -->
        <div class="about-hero-section">
            <div class="hero-content z-10 relative text-center">
                <h1
                    class="text-6xl md:text-7xl font-play text-gaming-red mb-6 hero-title"
                >
                    À Propos de NextConfig
                </h1>
                <p
                    class="text-2xl text-white mb-12 max-w-3xl mx-auto font-medium text-shadow-lg"
                >
                    Expertise et passion au service de vos configurations gaming
                </p>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Section Présentation avec style amélioré -->
                <div class="mb-16">
                    <div class="flex justify-center mb-10">
                        <h2
                            class="text-4xl font-play text-gaming-red section-title"
                        >
                            Qui suis-je
                        </h2>
                    </div>

                    <div
                        class="bg-deep-black p-8 rounded-lg border border-gaming-red hover-card"
                    >
                        <div class="grid md:grid-cols-2 gap-8 items-center">
                            <div class="animate-fade-in-left">
                                <h2
                                    class="text-3xl font-play text-led-green mb-6"
                                >
                                    Schreurs Dylan
                                </h2>
                                <p class="text-white mb-6 text-lg">
                                    Passionné d'informatique et expert en
                                    assemblage PC, je me suis spécialisé dans la
                                    création de configurations gaming sur
                                    mesure.
                                </p>
                                <p class="text-white mb-6">
                                    Avec plus de 5 années d'expérience dans le
                                    domaine, je vous accompagne dans le choix
                                    des meilleurs composants pour créer la
                                    machine de vos rêves.
                                </p>
                                <p class="text-white">
                                    Chaque PC est assemblé avec un souci du
                                    détail et une attention particulière portée
                                    aux performances, à l'esthétique et à la
                                    fiabilité.
                                </p>
                            </div>
                            <div
                                class="flex justify-center animate-fade-in-right"
                            >
                                <!-- Photo avec effet de style -->
                                <div class="profile-image">
                                    <img
                                        src="/images/about.jpg"
                                        alt="PC Gaming Personnalisé"
                                        class="rounded-lg shadow-glow"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Expérience -->
                <div class="mb-16">
                    <div class="flex justify-center mb-10">
                        <h2
                            class="text-4xl font-play text-gaming-red section-title"
                        >
                            Mon Parcours
                        </h2>
                    </div>

                    <div class="timeline-container">
                        <div
                            v-for="(exp, index) in experiences"
                            :key="index"
                            class="timeline-item"
                            :class="{
                                left: index % 2 === 0,
                                right: index % 2 !== 0,
                            }"
                        >
                            <div class="timeline-content hover-card">
                                <h3
                                    class="text-xl font-play text-led-green mb-2"
                                >
                                    {{ exp.title }}
                                </h3>
                                <p class="text-gaming-red font-semibold mb-3">
                                    {{ exp.period }}
                                </p>
                                <p class="text-white">{{ exp.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Valeurs avec cards améliorées -->
                <div class="mb-16">
                    <div class="flex justify-center mb-10">
                        <h2
                            class="text-4xl font-play text-gaming-red section-title"
                        >
                            Mes Valeurs
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div
                            class="p-8 border border-gaming-red rounded-lg service-card group transition-all duration-300"
                        >
                            <div class="service-icon mb-6 text-led-green">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-12 w-12 mx-auto"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                    />
                                </svg>
                            </div>
                            <h3
                                class="text-2xl font-play text-led-green mb-4 text-center group-hover:text-gaming-red transition-colors duration-300"
                            >
                                Expertise
                            </h3>
                            <p class="text-white text-center">
                                Une connaissance approfondie des composants et
                                des dernières technologies gaming pour des
                                performances optimales.
                            </p>
                        </div>

                        <div
                            class="p-8 border border-gaming-red rounded-lg service-card group transition-all duration-300"
                        >
                            <div class="service-icon mb-6 text-led-green">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-12 w-12 mx-auto"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"
                                    />
                                </svg>
                            </div>
                            <h3
                                class="text-2xl font-play text-led-green mb-4 text-center group-hover:text-gaming-red transition-colors duration-300"
                            >
                                Sur Mesure
                            </h3>
                            <p class="text-white text-center">
                                Chaque configuration est unique et adaptée à vos
                                besoins spécifiques, que ce soit pour le gaming,
                                le streaming ou la création.
                            </p>
                        </div>

                        <div
                            class="p-8 border border-gaming-red rounded-lg service-card group transition-all duration-300"
                        >
                            <div class="service-icon mb-6 text-led-green">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-12 w-12 mx-auto"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"
                                    />
                                </svg>
                            </div>
                            <h3
                                class="text-2xl font-play text-led-green mb-4 text-center group-hover:text-gaming-red transition-colors duration-300"
                            >
                                Support
                            </h3>
                            <p class="text-white text-center">
                                Un accompagnement personnalisé et un service
                                après-vente réactif pour vous garantir une
                                expérience optimale.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
/* Style pour la hero section */
.about-hero-section {
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

/* Animation pour les cards */
.service-card {
    position: relative;
    overflow: hidden;
    background: rgba(0, 0, 0, 0.2);
    transform: translateY(0);
    transition: all 0.3s ease;
}

.service-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(
        circle at center,
        rgba(236, 64, 122, 0.1),
        transparent
    );
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 0 0 15px rgba(236, 64, 122, 0.3);
    border-color: rgba(236, 64, 122, 0.8);
}

.service-card:hover::before {
    opacity: 1;
}

.service-icon {
    transition: transform 0.3s ease;
}

.service-card:hover .service-icon {
    transform: scale(1.1);
}

/* Photo avec effet de glow */
.profile-image {
    position: relative;
    display: inline-block;
    border-radius: 8px;
    overflow: hidden;
}

.shadow-glow {
    box-shadow: 0 0 20px rgba(236, 64, 122, 0.5);
    transition: all 0.3s ease;
}

.profile-image:hover .shadow-glow {
    box-shadow: 0 0 30px rgba(236, 64, 122, 0.8);
}

/* Timeline styling */
.timeline-container {
    position: relative;
    max-width: 1200px;
    margin: 0 auto;
}

.timeline-container::after {
    content: "";
    position: absolute;
    width: 4px;
    background-color: rgba(236, 64, 122, 0.5);
    top: 0;
    bottom: 0;
    left: 50%;
    margin-left: -2px;
}

.timeline-item {
    padding: 10px 40px;
    position: relative;
    width: 50%;
    box-sizing: border-box;
    margin-bottom: 20px;
}

.left {
    left: 0;
}

.right {
    left: 50%;
}

.timeline-content {
    padding: 20px;
    border: 1px solid #ec407a;
    border-radius: 8px;
    background-color: rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.timeline-content:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 0 0 15px rgba(236, 64, 122, 0.3);
    border-color: rgba(236, 64, 122, 0.8);
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

/* Responsive */
@media (max-width: 768px) {
    .timeline-container::after {
        left: 31px;
    }

    .timeline-item {
        width: 100%;
        padding-left: 70px;
        padding-right: 25px;
    }

    .right {
        left: 0%;
    }
}
</style>
