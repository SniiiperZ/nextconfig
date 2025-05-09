<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import FormInput from "@/Components/FormInput.vue";
import Toast from "@/Components/Toast.vue";

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
        value: "contact@nextconfig.be",
        link: "mailto:contact@nextconfig.be",
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
        name: "Discord",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.946 2.4189-2.1568 2.4189Z"/>
               </svg>`,
        link: "https://discord.gg/nextconfig",
    },
    {
        name: "LinkedIn",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
              </svg>`,
        link: "https://linkedin.com/in/nextconfig",
    },
    {
        name: "GitHub",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
               </svg>`,
        link: "https://github.com/nextconfig",
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
    {
        question: "Livrez-vous à l'international ?",
        answer: "Nous livrons principalement en Belgique et dans les pays limitrophes. Contactez-nous pour des demandes spécifiques.",
    },
];
</script>

<template>
    <PublicLayout title="Contact">
        <!-- Hero Section avec image de fond -->
        <div class="contact-hero-section">
            <div class="hero-content z-10 relative text-center">
                <h1
                    class="text-6xl md:text-7xl font-play text-gaming-red mb-6 hero-title"
                >
                    Contactez-nous
                </h1>
                <p
                    class="text-2xl text-white mb-12 max-w-3xl mx-auto font-medium text-shadow-lg"
                >
                    Une question ? Un projet ? Nous sommes là pour vous aider.
                </p>
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
            </div>
        </div>

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
                    <h2
                        class="text-3xl font-play text-gaming-red mb-8 section-title text-center"
                    >
                        Envoyez-nous un message
                    </h2>

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
/* Style pour la Hero Section avec image de fond */
.contact-hero-section {
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

.text-shadow-lg {
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
}

/* Animation d'entrée */
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

/* Hiérarchie visuelle améliorée */
.hero-title {
    text-shadow: 0 0 15px rgba(236, 64, 122, 0.7), 0 3px 10px rgba(0, 0, 0, 0.8);
    letter-spacing: 1px;
    font-weight: bold;
    animation: glow 2s ease-in-out infinite alternate;
}

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

/* Section title avec décoration */
.section-title {
    position: relative;
    display: inline-block;
    padding-bottom: 10px;
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

/* Animation pour les liens sociaux */
.social-link-item {
    transition: all 0.3s ease;
}

.social-link-item:hover {
    transform: translateY(-2px);
}
</style>
