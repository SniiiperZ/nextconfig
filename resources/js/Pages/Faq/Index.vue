<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import Accordion from "@/Components/Accordion.vue";
import HeroSection from "@/Components/HeroSection.vue";
import HeroTitle from "@/Components/HeroTitle.vue";

defineProps({
    faqs: Array,
});
</script>

<template>
    <PublicLayout
        title="FAQ"
        description="Trouvez des réponses aux questions fréquentes concernant l'assemblage de PC gaming sur mesure, les services de maintenance et l'optimisation de performances."
        keywords="FAQ PC gaming, questions fréquentes assemblage PC, maintenance ordinateur gaming, assistance NextConfig, support technique PC"
    >
        <!-- Hero Section pour la page FAQ avec le composant HeroSection -->
        <HeroSection
            title="Foire aux questions"
            subtitle="Tout ce que vous devez savoir sur nos services gaming"
            backgroundImage="/images/background.jpg"
            height="min-h-50vh"
        />

        <div class="py-12">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Explication et introduction -->
                <div class="mb-12 text-center animate-fade-in">
                    <HeroTitle
                        title="Vos questions, nos réponses"
                        :centered="true"
                        marginBottom="mb-10"
                    />
                    <p class="text-white/80 max-w-3xl mx-auto">
                        Retrouvez ci-dessous les réponses aux questions les plus
                        courantes concernant nos services de montage PC gaming,
                        d'optimisation et de maintenance. Si vous ne trouvez pas
                        la réponse à votre question, n'hésitez pas à nous
                        contacter directement.
                    </p>
                </div>

                <!-- Accordéons améliorés -->
                <div class="space-y-5">
                    <div
                        v-for="(faq, index) in faqs"
                        :key="faq.id"
                        class="faq-accordion animate-fade-in"
                        :style="{ animationDelay: `${index * 0.1}s` }"
                    >
                        <Accordion
                            :title="faq.question"
                            :default-open="index === 0"
                        >
                            <div class="text-white/90 leading-relaxed">
                                <p>{{ faq.answer }}</p>
                            </div>
                        </Accordion>
                    </div>
                </div>

                <!-- Section d'appel à l'action -->
                <div
                    class="mt-16 bg-deep-black p-8 rounded-lg border border-gaming-red hover-card text-center animate-fade-in"
                >
                    <h3 class="text-2xl font-play text-led-green mb-4">
                        Vous avez d'autres questions ?
                    </h3>
                    <p class="text-white mb-6">
                        N'hésitez pas à nous contacter directement, nous serons
                        ravis de vous aider.
                    </p>
                    <a
                        :href="route('contact')"
                        class="main-cta-button inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gaming-red hover:bg-opacity-90 transition duration-300"
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
                        Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
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

/* Animations d'entrée pour les blocs */
.animate-fade-in {
    animation: fadeIn 0.6s ease-out forwards;
    opacity: 0;
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

/* Animation spéciale pour les accordéons */
.faq-accordion {
    transition: all 0.3s ease;
}

.faq-accordion:hover {
    transform: translateX(5px);
}

/* Cards avec hover effect */
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2), 0 0 15px rgba(236, 64, 122, 0.3);
    border-color: rgba(236, 64, 122, 0.8);
    transform: translateY(-5px);
}
</style>
