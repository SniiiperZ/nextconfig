<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import FormInput from "@/Components/FormInput.vue";
import Toast from "@/Components/Toast.vue";
import HeroSection from "@/Components/HeroSection.vue";
import HeroTitle from "@/Components/HeroTitle.vue";

const form = useForm({
    name: "",
    email: "",
    phone: "",
    subject: "",
    message: "",
});

// Toast pour les notifications
const toast = ref({
    show: false,
    message: "",
    type: "success",
});

const submit = () => {
    form.post(route("contact.send"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.value = {
                show: true,
                message:
                    "Votre message a été envoyé avec succès ! Nous vous contacterons bientôt.",
                type: "success",
            };

            setTimeout(() => {
                toast.value.show = false;
            }, 6000);
        },
    });
};

const closeToast = () => {
    toast.value.show = false;
};

// Coordonnées avec icônes et liens
const contactInfo = [
    {
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>`,
        label: "Téléphone",
        value: "+32 (0) 123 456 789",
        link: "tel:+32123456789",
    },
    {
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>`,
        label: "Email",
        value: "dyjoke@nextconfig.sniperz.be",
        link: "mailto:dyjoke@nextconfig.sniperz.be",
    },
    {
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>`,
        label: "Adresse",
        value: "Bruxelles, Belgique",
        link: "https://maps.google.com/?q=Bruxelles,Belgique",
    },
];

// Réseaux sociaux
const socialLinks = [
    {
        name: "#NextConfig",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.946 2.4189-2.1568 2.4189Z"/>
               </svg>`,
        link: "#",
    },
    {
        name: "NextConfig",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.571 4.714h1.715v5.143H11.57zm4.715 0H18v5.143h-1.714zM6 0L1.714 4.286v15.428h5.143V24l4.286-4.286h3.428L22.286 12V0zm14.571 11.143l-3.428 3.428h-3.429l-3 3v-3H6.857V1.714h13.714Z"/>
              </svg>`,
        link: "#",
    },
    {
        name: "Next_Config",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
               </svg>`,
        link: "#",
    },
];

// FAQ courtes
const faqItems = [
    {
        question: "Combien de temps faut-il pour assembler un PC ?",
        answer: "Le délai moyen d'assemblage est de 3 à 5 jours ouvrables après réception et validation de tous les composants.",
    },
    {
        question: "Proposez-vous un service après-vente ?",
        answer: "Oui, tous nos PC bénéficient d'un service après-vente de qualité avec un support technique dédié pendant 2 ans.",
    },
];
</script>

<template>
    <PublicLayout
        title="Contact"
        description="Contactez notre équipe d'experts en PC gaming sur mesure. Posez vos questions, demandez un conseil personnalisé ou discutez de votre projet d'assemblage PC."
        keywords="contact NextConfig, assistance PC gaming, support technique ordinateur, conseil configuration PC, expert assemblage Belgique"
    >
        <!-- Hero Section avec le composant HeroSection -->
        <HeroSection
            title="Contactez-nous"
            subtitle="Une question ? Un projet ? Nous sommes là pour vous aider."
            backgroundImage="/images/background.jpg"
            height="min-h-50vh"
        >
            <template #actions>
                <div class="flex flex-wrap justify-center gap-4">
                    <a
                        :href="`tel:+32123456789`"
                        class="contact-button bg-deep-black border border-led-green text-led-green"
                    >
                        <div class="flex items-center">
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
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                />
                            </svg>
                            <span>Appelez-nous</span>
                        </div>
                    </a>
                    <a
                        href="#contact-form"
                        class="contact-button main-cta-button text-white"
                    >
                        <div class="flex items-center">
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
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>
                            <span>Envoyez un message</span>
                        </div>
                    </a>
                </div>
            </template>
        </HeroSection>

        <div class="py-16">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Section d'informations de contact améliorée -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <!-- Coordonnées avec animation au survol -->
                    <div
                        class="bg-deep-black p-8 rounded-lg border border-gaming-red hover-card animate-fade-in"
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
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                        <h2
                            class="text-2xl font-play text-led-green mb-6 text-center"
                        >
                            Coordonnées
                        </h2>

                        <div class="space-y-6">
                            <div
                                v-for="(info, index) in contactInfo"
                                :key="index"
                                class="contact-info-item group"
                            >
                                <a
                                    :href="info.link"
                                    target="_blank"
                                    rel="noopener"
                                    class="flex items-center p-3 border border-gaming-red/30 rounded-lg hover:border-gaming-red transition-all duration-300"
                                >
                                    <div
                                        class="text-led-green group-hover:text-gaming-red transition-colors duration-300"
                                        v-html="info.icon"
                                    ></div>
                                    <div class="ml-4">
                                        <p class="text-white/60 text-sm">
                                            {{ info.label }}
                                        </p>
                                        <p class="text-white font-medium">
                                            {{ info.value }}
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Liens sociaux avec animation au survol -->
                    <div
                        class="bg-deep-black p-8 rounded-lg border border-gaming-red hover-card animate-fade-in"
                        style="animation-delay: 0.1s"
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
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                />
                            </svg>
                        </div>
                        <h2
                            class="text-2xl font-play text-led-green mb-6 text-center"
                        >
                            Réseaux sociaux
                        </h2>

                        <div class="grid grid-cols-1 gap-4">
                            <a
                                v-for="(social, index) in socialLinks"
                                :key="index"
                                :href="social.link"
                                target="_blank"
                                rel="noopener"
                                class="social-link-item flex items-center p-3 border border-gaming-red/30 rounded-lg hover:border-gaming-red hover:shadow-glow-sm transition-all duration-300 group"
                            >
                                <div
                                    class="w-10 h-10 rounded-full bg-gaming-red/10 flex items-center justify-center mr-4 text-led-green group-hover:text-gaming-red group-hover:bg-gaming-red/20 transition-all duration-300"
                                    v-html="social.icon"
                                ></div>
                                <span class="text-white">{{
                                    social.name
                                }}</span>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 ml-auto text-gaming-red opacity-0 group-hover:opacity-100 transform translate-x-0 group-hover:translate-x-1 transition-all duration-300"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"
                                    />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- FAQ courtes -->
                    <div
                        class="bg-deep-black p-8 rounded-lg border border-gaming-red hover-card animate-fade-in"
                        style="animation-delay: 0.2s"
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
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <h2
                            class="text-2xl font-play text-led-green mb-6 text-center"
                        >
                            FAQ
                        </h2>

                        <div class="space-y-4">
                            <div
                                v-for="(faq, index) in faqItems"
                                :key="index"
                                class="bg-deep-black/50 p-4 rounded-lg border border-gaming-red/30 hover:border-gaming-red/50 transition-all duration-300"
                            >
                                <h3
                                    class="text-gaming-red font-medium mb-2 flex items-center"
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
                                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    {{ faq.question }}
                                </h3>
                                <p class="text-white/80 ml-7">
                                    {{ faq.answer }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <Link
                                :href="route('faq')"
                                class="inline-flex items-center text-led-green hover:text-gaming-red transition group"
                            >
                                <span>Voir toutes les questions</span>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 ml-1 transform group-hover:translate-x-1 transition-transform duration-200"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"
                                    />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Bannière Devis animée -->
                <div
                    class="bg-deep-black p-8 rounded-lg border border-led-green mb-16 shadow-glow-green service-card animate-fade-in"
                    style="animation-delay: 0.3s"
                >
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="mb-6 md:mb-0 md:mr-8 md:w-3/4">
                            <h2 class="text-2xl font-play text-gaming-red mb-3">
                                Besoin d'un PC sur mesure ?
                            </h2>
                            <p class="text-white mb-4">
                                Utilisez notre devis d'orientation pour nous
                                indiquer vos besoins spécifiques et obtenir une
                                proposition personnalisée qui correspond
                                exactement à votre utilisation.
                            </p>
                            <div class="flex space-x-4">
                                <Link
                                    :href="route('devis')"
                                    class="main-cta-button inline-flex items-center px-6 py-2 text-white rounded-md transition"
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
                                <Link
                                    :href="route('budget.calculator')"
                                    class="inline-flex items-center px-6 py-2 border border-led-green text-led-green rounded-md hover:bg-led-green/10 transition shadow-sm hover:shadow-glow-green"
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
                                    Calculateur de budget
                                </Link>
                            </div>
                        </div>
                        <div class="md:w-1/4 flex justify-center">
                            <div
                                class="p-4 bg-led-green/10 border border-led-green rounded-full shadow-glow-green flex items-center justify-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-16 w-16 text-led-green"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de contact amélioré -->
                <div
                    id="contact-form"
                    class="bg-deep-black p-8 rounded-lg border border-gaming-red shadow-glow-sm hover-card animate-fade-in"
                    style="animation-delay: 0.4s"
                >
                    <HeroTitle
                        title="Envoyez-nous un message"
                        :centered="true"
                        marginBottom="mb-8"
                    />

                    <form @submit.prevent="submit" class="space-y-8">
                        <!-- Informations personnelles -->
                        <div
                            class="bg-gaming-red/5 p-6 rounded-lg border border-gaming-red/30 mb-6"
                        >
                            <div class="flex items-center mb-4">
                                <div
                                    class="bg-led-green w-10 h-10 rounded-full flex items-center justify-center mr-4 shadow-glow-sm"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-deep-black"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-play text-led-green">
                                    Vos coordonnées
                                </h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <FormInput
                                    v-model="form.name"
                                    label="Nom complet"
                                    :error="form.errors.name"
                                />

                                <FormInput
                                    v-model="form.email"
                                    type="email"
                                    label="Email"
                                    :error="form.errors.email"
                                />

                                <FormInput
                                    v-model="form.phone"
                                    label="Téléphone"
                                    :error="form.errors.phone"
                                />
                            </div>
                        </div>

                        <!-- Message avec effet spécial -->
                        <div
                            class="bg-gaming-red/5 p-6 rounded-lg border border-gaming-red/30 mb-6"
                        >
                            <div class="flex items-center mb-4">
                                <div
                                    class="bg-led-green w-10 h-10 rounded-full flex items-center justify-center mr-4 shadow-glow-sm"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-deep-black"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                                        />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-play text-led-green">
                                    Votre message
                                </h2>
                            </div>

                            <FormInput
                                v-model="form.subject"
                                label="Sujet"
                                :error="form.errors.subject"
                            />

                            <div class="mb-4">
                                <label
                                    class="block text-white text-sm font-medium mb-2"
                                >
                                    Message
                                </label>
                                <textarea
                                    v-model="form.message"
                                    rows="5"
                                    class="w-full px-3 py-2 bg-deep-black border border-gaming-red rounded-md text-white focus:outline-none focus:ring-2 focus:ring-led-green transition duration-200"
                                    placeholder="Décrivez votre demande ou votre projet..."
                                ></textarea>
                                <p
                                    v-if="form.errors.message"
                                    class="mt-1 text-sm text-gaming-red"
                                >
                                    {{ form.errors.message }}
                                </p>
                            </div>
                        </div>

                        <!-- Politique de confidentialité -->
                        <div class="text-white/60 text-sm mb-6">
                            <div class="flex items-start">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-gaming-red flex-shrink-0 mt-0.5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                <p>
                                    En envoyant ce formulaire, vous acceptez que
                                    les informations saisies soient utilisées
                                    pour vous recontacter. Aucune information ne
                                    sera partagée avec des tiers. Pour plus
                                    d'informations, consultez notre
                                    <Link
                                        :href="route('privacy.policy')"
                                        class="text-led-green hover:text-gaming-red transition-colors duration-300"
                                        >politique de confidentialité</Link
                                    >.
                                </p>
                            </div>
                        </div>

                        <!-- Bouton de soumission amélioré -->
                        <div class="flex justify-center">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="main-cta-button inline-flex items-center px-8 py-4 border border-transparent text-lg font-medium rounded-md text-white transition duration-300 disabled:opacity-50 font-play min-w-[250px]"
                            >
                                <svg
                                    v-if="!form.processing"
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
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="animate-spin h-5 w-5 mr-2"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                {{
                                    form.processing
                                        ? "Envoi en cours..."
                                        : "Envoyer mon message"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notification toast flottante -->
        <Toast
            :show="toast.show"
            :message="toast.message"
            :type="toast.type"
            position="bottom-right"
            @close="closeToast"
        />
    </PublicLayout>
</template>

<style scoped>
/* Boutons de contact dans le hero */
.contact-button {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.contact-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
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

/* Animation pour les cartes */
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

/* Effets pour les icônes */
.service-icon {
    transition: transform 0.3s ease;
}

.hover-card:hover .service-icon {
    transform: scale(1.1);
}

/* Effets spéciaux pour les cartes */
.shadow-glow-sm {
    box-shadow: 0 0 10px rgba(236, 64, 122, 0.2);
    transition: box-shadow 0.3s ease;
}

.shadow-glow-green {
    box-shadow: 0 0 10px rgba(0, 255, 85, 0.3);
    transition: box-shadow 0.3s ease;
}

/* Cartes avec hover effect */
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 0 15px rgba(236, 64, 122, 0.3);
    transform: translateY(-5px);
}

/* Animation pour l'apparition des éléments */
.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
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

/* Animation pour les liens sociaux */
.social-link-item {
    transition: all 0.3s ease;
}

.social-link-item:hover {
    transform: translateY(-2px);
}
</style>
