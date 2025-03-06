<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
</script>

<template>
    <PublicLayout title="À Propos">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Section Présentation -->
                <div class="mb-16">
                    <h1
                        class="text-4xl font-play text-gaming-red mb-8 text-center"
                    >
                        À Propos de NextConfig
                    </h1>
                    <div
                        class="bg-deep-black p-8 rounded-lg border border-gaming-red"
                    >
                        <div class="grid md:grid-cols-2 gap-8 items-center">
                            <div>
                                <h2
                                    class="text-2xl font-play text-led-green mb-4"
                                >
                                    Schreurs Dylan
                                </h2>
                                <p class="text-white mb-4">
                                    Passionné d'informatique et expert en
                                    assemblage PC, je me suis spécialisé dans la
                                    création de configurations gaming sur
                                    mesure.
                                </p>
                                <p class="text-white">
                                    Avec plus de X années d'expérience dans le
                                    domaine, je vous accompagne dans le choix
                                    des meilleurs composants pour créer la
                                    machine de vos rêves.
                                </p>
                            </div>
                            <div class="flex justify-center">
                                <!-- Placeholder pour photo -->
                                <div
                                    class="w-64 h-64 bg-gaming-red rounded-full flex items-center justify-center"
                                >
                                    <span class="text-white">Photo</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Valeurs -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div class="p-6 border border-gaming-red rounded-lg">
                        <h3 class="text-xl font-play text-led-green mb-4">
                            Expertise
                        </h3>
                        <p class="text-white">
                            Une connaissance approfondie des composants et des
                            dernières technologies gaming.
                        </p>
                    </div>
                    <div class="p-6 border border-gaming-red rounded-lg">
                        <h3 class="text-xl font-play text-led-green mb-4">
                            Sur Mesure
                        </h3>
                        <p class="text-white">
                            Chaque configuration est unique et adaptée à vos
                            besoins spécifiques.
                        </p>
                    </div>
                    <div class="p-6 border border-gaming-red rounded-lg">
                        <h3 class="text-xl font-play text-led-green mb-4">
                            Support
                        </h3>
                        <p class="text-white">
                            Un accompagnement personnalisé et un service
                            après-vente réactif.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
