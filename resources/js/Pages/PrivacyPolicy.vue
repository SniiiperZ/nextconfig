<script setup>
import { Head } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import HeroSection from "@/Components/HeroSection.vue";
import HeroTitle from "@/Components/HeroTitle.vue";
</script>

<template>
    <PublicLayout
        title="Politique de confidentialité"
        description="Découvrez comment nous protégeons vos données personnelles et respectons votre vie privée conformément au RGPD."
        keywords="politique confidentialité, RGPD, protection données, vie privée, NextConfig"
    >
        <!-- Hero Section avec le composant HeroSection -->
        <HeroSection
            title="Politique de confidentialité"
            subtitle="Nous accordons une grande importance à la protection de vos données"
            backgroundImage="/images/background.jpg"
            height="min-h-40vh sm:min-h-50vh"
        />

        <div class="py-8 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-deep-black border border-gaming-red overflow-hidden shadow-xl rounded-lg"
                >
                    <div class="p-4 sm:p-6 md:p-8 text-white">
                        <div class="mb-6 sm:mb-8 text-center">
                            <HeroTitle
                                title="Protection de vos données"
                                :centered="true"
                                marginBottom="mb-6 sm:mb-8"
                            />
                            <p
                                class="text-white/80 max-w-3xl mx-auto text-sm sm:text-base mb-2"
                            >
                                Conforme au Règlement Général sur la Protection
                                des Données (RGPD)
                            </p>
                        </div>

                        <div class="legal-content animate-fade-in">
                            <h2 class="title-section">1. Introduction</h2>
                            <div class="legal-block">
                                <p>
                                    NextConfig (www.nextconfig.sniperz.be)
                                    accorde une grande importance à la
                                    protection des données personnelles de ses
                                    utilisateurs. Cette politique de
                                    confidentialité décrit comment nous
                                    collectons, utilisons, partageons et
                                    protégeons les données personnelles que vous
                                    nous fournissez via notre site.
                                </p>
                            </div>

                            <h2 class="title-section">
                                2. Responsable du traitement
                            </h2>
                            <div class="legal-block">
                                <p>
                                    Le responsable du traitement des données est
                                    :<br />
                                    <span class="block sm:inline font-medium"
                                        >SCHREURS Dylan</span
                                    ><br class="block sm:hidden" />
                                    <span class="block sm:inline font-medium"
                                        >Adresse :</span
                                    >
                                    Rue du travail de fin d'étude 1, 1000
                                    Bruxelles – Belgique<br
                                        class="hidden sm:block"
                                    />
                                    <span class="block sm:inline font-medium"
                                        >Email :</span
                                    >
                                    <a
                                        href="mailto:dyjoke@nextconfig.sniperz.be"
                                        class="text-led-green hover:underline"
                                        >dyjoke@nextconfig.sniperz.be</a
                                    >
                                </p>
                            </div>

                            <h2 class="title-section">3. Données collectées</h2>
                            <div class="legal-block">
                                <p>
                                    Nous collectons uniquement les données
                                    nécessaires au bon fonctionnement du site et
                                    à la gestion des demandes clients. Ces
                                    données incluent :
                                </p>
                                <ul class="list-items">
                                    <li>
                                        <span class="bullet-point"></span>
                                        Formulaire de contact : nom, prénom,
                                        adresse email, message.
                                    </li>
                                    <li>
                                        <span class="bullet-point"></span>
                                        Formulaire de devis : nom, prénom,
                                        email, budget, besoins spécifiques.
                                    </li>
                                    <li>
                                        <span class="bullet-point"></span>
                                        Commentaires de blog : nom, email,
                                        contenu du commentaire.
                                    </li>
                                </ul>
                            </div>

                            <h2 class="title-section">
                                4. Finalités du traitement
                            </h2>
                            <div class="legal-block">
                                <p>
                                    Les données collectées servent aux finalités
                                    suivantes :
                                </p>
                                <ul class="list-items">
                                    <li>
                                        <span class="bullet-point"></span>
                                        Répondre à vos demandes via les
                                        formulaires de contact et de devis.
                                    </li>
                                    <li>
                                        <span class="bullet-point"></span>
                                        Améliorer nos services et notre
                                        interface grâce à vos retours.
                                    </li>
                                    <li>
                                        <span class="bullet-point"></span>
                                        Gérer les commentaires soumis sur les
                                        articles du blog.
                                    </li>
                                    <li>
                                        <span class="bullet-point"></span>
                                        Réaliser des analyses anonymes du trafic
                                        via Google Analytics 4.
                                    </li>
                                </ul>
                            </div>

                            <h2 class="title-section">
                                5. Base légale du traitement
                            </h2>
                            <div class="legal-block">
                                <p>
                                    Les données sont traitées sur les bases
                                    légales suivantes :
                                </p>
                                <ul class="list-items">
                                    <li>
                                        <span class="bullet-point"></span>
                                        <span class="font-medium"
                                            >Consentement :</span
                                        >
                                        pour la soumission de formulaires.
                                    </li>
                                    <li>
                                        <span class="bullet-point"></span>
                                        <span class="font-medium"
                                            >Intérêt légitime :</span
                                        >
                                        pour garantir la sécurité du site,
                                        prévenir les abus et améliorer notre
                                        service.
                                    </li>
                                </ul>
                            </div>

                            <h2 class="title-section">
                                6. Destinataires des données
                            </h2>
                            <div class="legal-block">
                                <p>
                                    Les données sont uniquement accessibles à
                                    SCHREURS Dylan, responsable du site, et à
                                    SCHREURS Cedric, développeur du projet dans
                                    le cadre de son TFE à l'IFOSUP. Aucune
                                    donnée n'est vendue ni transmise à des tiers
                                    à des fins commerciales.
                                </p>
                            </div>

                            <h2 class="title-section">
                                7. Durée de conservation
                            </h2>
                            <div class="legal-block">
                                <p>Les données sont conservées :</p>
                                <ul class="list-items">
                                    <li>
                                        <span class="bullet-point"></span>
                                        6 mois pour les données soumises via
                                        formulaires (contact, devis,
                                        commentaires).
                                    </li>
                                    <li>
                                        <span class="bullet-point"></span>
                                        2 ans maximum dans le cadre du suivi du
                                        projet et des obligations académiques du
                                        TFE.
                                    </li>
                                </ul>
                            </div>

                            <h2 class="title-section">8. Vos droits</h2>
                            <div class="legal-block">
                                <p>
                                    Conformément au Règlement Général sur la
                                    Protection des Données (RGPD), vous disposez
                                    des droits suivants :
                                </p>
                                <div
                                    class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4 mt-3"
                                >
                                    <div class="right-box">
                                        <div class="right-title">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 sm:h-5 sm:w-5 text-led-green mr-1"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            Droit d'accès
                                        </div>
                                        <p
                                            class="text-white/80 text-xs sm:text-sm"
                                        >
                                            Accédez à vos données personnelles
                                        </p>
                                    </div>
                                    <div class="right-box">
                                        <div class="right-title">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 sm:h-5 sm:w-5 text-led-green mr-1"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                                />
                                            </svg>
                                            Droit de rectification
                                        </div>
                                        <p
                                            class="text-white/80 text-xs sm:text-sm"
                                        >
                                            Modifiez vos données personnelles
                                        </p>
                                    </div>
                                    <div class="right-box">
                                        <div class="right-title">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 sm:h-5 sm:w-5 text-led-green mr-1"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                />
                                            </svg>
                                            Droit à l'effacement
                                        </div>
                                        <p
                                            class="text-white/80 text-xs sm:text-sm"
                                        >
                                            Suppression de toutes vos données
                                        </p>
                                    </div>
                                    <div class="right-box">
                                        <div class="right-title">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 sm:h-5 sm:w-5 text-led-green mr-1"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"
                                                />
                                            </svg>
                                            Droit d'opposition
                                        </div>
                                        <p
                                            class="text-white/80 text-xs sm:text-sm"
                                        >
                                            Limitez l'utilisation de vos données
                                        </p>
                                    </div>
                                    <div class="right-box">
                                        <div class="right-title">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 sm:h-5 sm:w-5 text-led-green mr-1"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                                                />
                                            </svg>
                                            Droit à la portabilité
                                        </div>
                                        <p
                                            class="text-white/80 text-xs sm:text-sm"
                                        >
                                            Récupérez vos données
                                        </p>
                                    </div>
                                </div>
                                <p class="mt-4 text-center">
                                    Vous pouvez exercer ces droits à tout moment
                                    en envoyant un email à :
                                    <a
                                        href="mailto:dyjoke@nextconfig.sniperz.be"
                                        class="text-led-green hover:underline"
                                        >dyjoke@nextconfig.sniperz.be</a
                                    >
                                </p>
                            </div>

                            <h2 class="title-section">
                                9. Sécurité des données
                            </h2>
                            <div class="legal-block">
                                <p>
                                    Le site est sécurisé par un certificat SSL
                                    (HTTPS). Les formulaires sont protégés
                                    contre le spam, les attaques CSRF et les
                                    injections via une validation renforcée côté
                                    client et serveur. Les données sont
                                    hébergées en Europe chez o2switch, conforme
                                    au RGPD.
                                </p>
                            </div>

                            <h2 class="title-section">10. Cookies</h2>
                            <div class="legal-block">
                                <p>
                                    Le site utilise des cookies à des fins
                                    techniques et statistiques :
                                </p>
                                <ul class="list-items">
                                    <li>
                                        <span class="bullet-point"></span>
                                        Cookies nécessaires au bon
                                        fonctionnement (session, langue)
                                    </li>
                                    <li>
                                        <span class="bullet-point"></span>
                                        Cookies analytiques via Google Analytics
                                        (anonymisation IP activée)
                                    </li>
                                </ul>
                                <p>
                                    Un bandeau de consentement s'affiche lors de
                                    votre première visite pour gérer vos
                                    préférences.
                                </p>
                            </div>

                            <h2 class="title-section">
                                11. Modifications de la politique
                            </h2>
                            <div class="legal-block">
                                <p>
                                    Cette politique de confidentialité peut être
                                    mise à jour à tout moment pour refléter les
                                    évolutions légales ou techniques. Toute
                                    modification majeure sera annoncée sur le
                                    site.
                                </p>
                            </div>
                        </div>

                        <div
                            class="mt-8 pt-6 border-t border-gaming-red/30 text-right text-xs sm:text-sm text-white/70 animate-fade-in"
                            style="animation-delay: 0.3s"
                        >
                            <p class="italic">
                                Dernière mise à jour : mai 2025
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
.legal-content {
    line-height: 1.6;
}

.title-section {
    font-size: 1.25rem;
    font-weight: 600;
    color: #4ade80; /* Couleur led-green */
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    font-family: "Play", sans-serif;
    display: flex;
    align-items: center;
    border-bottom: 1px solid rgba(236, 64, 122, 0.2);
    padding-bottom: 0.5rem;
}

@media (min-width: 640px) {
    .title-section {
        font-size: 1.5rem;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
}

.title-section::before {
    content: "";
    display: inline-block;
    width: 4px;
    height: 1rem;
    background-color: #4ade80;
    margin-right: 0.75rem;
    border-radius: 2px;
}

@media (min-width: 640px) {
    .title-section::before {
        width: 6px;
        height: 1.5rem;
        margin-right: 1rem;
    }
}

.legal-block {
    background-color: rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(236, 64, 122, 0.1);
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

@media (min-width: 640px) {
    .legal-block {
        padding: 1.25rem;
        margin-bottom: 2rem;
    }
}

.legal-content p {
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
    line-height: 1.6;
}

@media (min-width: 640px) {
    .legal-content p {
        font-size: 1rem;
        line-height: 1.7;
    }
}

.list-items {
    list-style-type: none;
    margin-left: 0.5rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
}

@media (min-width: 640px) {
    .list-items {
        margin-left: 0.75rem;
        margin-bottom: 1.25rem;
        font-size: 1rem;
    }
}

.list-items li {
    margin-bottom: 0.5rem;
    position: relative;
    padding-left: 1.25rem;
}

.bullet-point {
    position: absolute;
    left: 0;
    top: 0.5rem;
    width: 6px;
    height: 6px;
    background-color: #ec407a; /* gaming-red */
    border-radius: 50%;
    display: inline-block;
}

@media (min-width: 640px) {
    .bullet-point {
        width: 8px;
        height: 8px;
        top: 0.55rem;
    }

    .list-items li {
        padding-left: 1.5rem;
    }
}

.right-box {
    background-color: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(0, 255, 85, 0.1);
    border-radius: 0.375rem;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.right-box:hover {
    border-color: rgba(0, 255, 85, 0.3);
    box-shadow: 0 0 8px rgba(0, 255, 85, 0.2);
}

.right-title {
    display: flex;
    align-items: center;
    font-weight: 500;
    color: #4ade80;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

@media (min-width: 640px) {
    .right-title {
        font-size: 1rem;
    }

    .right-box {
        padding: 1rem;
    }
}

/* Animation pour les éléments qui apparaissent */
.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
    opacity: 0;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
