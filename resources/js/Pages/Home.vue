<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
</script>

<template>
    <PublicLayout title="Accueil">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Hero Section -->
                <div class="text-center mb-16">
                    <h1 class="text-5xl font-play text-gaming-red mb-4">
                        Votre PC Gaming Sur Mesure
                    </h1>
                    <p class="text-xl text-white mb-8">
                        Configurations personnalisées pour des performances
                        optimales
                    </p>
                    <Link
                        :href="route('contact')"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gaming-red hover:bg-opacity-90 transition duration-150"
                    >
                        Configurer mon PC
                    </Link>
                </div>

                <!-- Services Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div class="p-6 border border-gaming-red rounded-lg">
                        <h3 class="text-xl font-play text-led-green mb-4">
                            Montage PC Sur Mesure
                        </h3>
                        <p class="text-white">
                            Configuration et assemblage personnalisés selon vos
                            besoins et votre budget.
                        </p>
                    </div>
                    <div class="p-6 border border-gaming-red rounded-lg">
                        <h3 class="text-xl font-play text-led-green mb-4">
                            Optimisation
                        </h3>
                        <p class="text-white">
                            Amélioration des performances de votre configuration
                            existante.
                        </p>
                    </div>
                    <div class="p-6 border border-gaming-red rounded-lg">
                        <h3 class="text-xl font-play text-led-green mb-4">
                            Maintenance
                        </h3>
                        <p class="text-white">
                            Entretien et réparation de votre matériel gaming.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
