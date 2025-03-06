<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
</script>

<template>
    <PublicLayout title="Services">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- En-tête -->
                <div class="text-center mb-16">
                    <h1 class="text-4xl font-play text-gaming-red mb-4">
                        Nos Services
                    </h1>
                    <p class="text-xl text-white">
                        Des solutions sur mesure pour tous les gamers
                    </p>
                </div>

                <!-- Services détaillés -->
                <div class="space-y-12">
                    <!-- Montage PC -->
                    <div
                        class="bg-deep-black p-8 rounded-lg border border-gaming-red"
                    >
                        <div class="grid md:grid-cols-2 gap-8 items-center">
                            <div>
                                <h2
                                    class="text-2xl font-play text-led-green mb-4"
                                >
                                    Montage PC Gaming
                                </h2>
                                <ul class="text-white space-y-2">
                                    <li class="flex items-center">
                                        <svg
                                            class="w-5 h-5 mr-2 text-gaming-red"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            />
                                        </svg>
                                        Sélection personnalisée des composants
                                    </li>
                                    <li class="flex items-center">
                                        <svg
                                            class="w-5 h-5 mr-2 text-gaming-red"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            />
                                        </svg>
                                        Assemblage professionnel
                                    </li>
                                    <li class="flex items-center">
                                        <svg
                                            class="w-5 h-5 mr-2 text-gaming-red"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            />
                                        </svg>
                                        Tests et optimisation
                                    </li>
                                </ul>
                            </div>
                            <div class="flex justify-center">
                                <div
                                    class="w-full h-64 bg-deep-black rounded-lg border border-gaming-red flex items-center justify-center"
                                >
                                    <span class="text-white"
                                        >Image Montage PC</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Maintenance -->
                    <div
                        class="bg-deep-black p-8 rounded-lg border border-gaming-red"
                    >
                        <div class="grid md:grid-cols-2 gap-8 items-center">
                            <div class="order-2 md:order-1">
                                <div
                                    class="w-full h-64 bg-deep-black rounded-lg border border-gaming-red flex items-center justify-center"
                                >
                                    <span class="text-white"
                                        >Image Maintenance</span
                                    >
                                </div>
                            </div>
                            <div class="order-1 md:order-2">
                                <h2
                                    class="text-2xl font-play text-led-green mb-4"
                                >
                                    Maintenance et Optimisation
                                </h2>
                                <ul class="text-white space-y-2">
                                    <li class="flex items-center">
                                        <svg
                                            class="w-5 h-5 mr-2 text-gaming-red"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            />
                                        </svg>
                                        Nettoyage complet
                                    </li>
                                    <li class="flex items-center">
                                        <svg
                                            class="w-5 h-5 mr-2 text-gaming-red"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            />
                                        </svg>
                                        Mise à jour des composants
                                    </li>
                                    <li class="flex items-center">
                                        <svg
                                            class="w-5 h-5 mr-2 text-gaming-red"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            />
                                        </svg>
                                        Optimisation des performances
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
