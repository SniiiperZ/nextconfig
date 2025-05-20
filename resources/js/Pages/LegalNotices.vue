<script setup>
import { Head } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import HeroSection from "@/Components/HeroSection.vue";
import HeroTitle from "@/Components/HeroTitle.vue";
</script>

<template>
    <PublicLayout
        title="Mentions légales"
        description="Consultez les informations légales relatives à NextConfig, l'hébergement du site et vos droits en tant qu'utilisateur."
        keywords="mentions légales, hébergement, droits utilisateur, propriété intellectuelle, NextConfig"
    >
        <!-- Hero Section avec le composant HeroSection -->
        <HeroSection
            title="Mentions légales"
            subtitle="Informations juridiques concernant l'utilisation du site"
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
                                title="Informations légales"
                                :centered="true"
                                marginBottom="mb-6 sm:mb-8"
                            />
                        </div>

                        <div class="legal-content animate-fade-in">
                            <h2 class="title-section">
                                1. Informations générales
                            </h2>
                            <div class="legal-block">
                                <p>
                                    <span class="block sm:inline font-medium"
                                        >Nom du site :</span
                                    >
                                    NextConfig<br class="hidden sm:block" />
                                    <span class="block sm:inline font-medium"
                                        >Adresse du site :</span
                                    >
                                    https://www.nextconfig.sniperz.be<br
                                        class="hidden sm:block"
                                    />
                                    <span class="block sm:inline font-medium"
                                        >Propriétaire :</span
                                    >
                                    SCHREURS Dylan<br class="hidden sm:block" />
                                    <span class="block sm:inline font-medium"
                                        >Statut :</span
                                    >
                                    Travail réalisé dans le cadre d'un TFE à
                                    IFOSUP<br class="hidden sm:block" />
                                    <span class="block sm:inline font-medium"
                                        >Responsable de la publication :</span
                                    >
                                    SCHREURS Dylan<br class="hidden sm:block" />
                                    <span class="block sm:inline font-medium"
                                        >Email de contact :</span
                                    >
                                    dyjoke@nextconfig.sniperz.be
                                </p>
                            </div>

                            <h2 class="title-section">2. Hébergement</h2>
                            <div class="legal-block">
                                <p>
                                    <span class="block sm:inline font-medium"
                                        >Hébergeur :</span
                                    >
                                    o2switch<br class="hidden sm:block" />
                                    <span class="block sm:inline font-medium"
                                        >Adresse :</span
                                    >
                                    Chemin des Pardiaux, 63000 Clermont-Ferrand,
                                    France<br class="hidden sm:block" />
                                    <span class="block sm:inline font-medium"
                                        >Société :</span
                                    >
                                    SAS au capital de 100 000 €<br
                                        class="hidden sm:block"
                                    />
                                    <span class="block sm:inline font-medium"
                                        >RCS :</span
                                    >
                                    Clermont-Ferrand<br
                                        class="hidden sm:block"
                                    />
                                    <span class="block sm:inline font-medium"
                                        >SIRET :</span
                                    >
                                    510 909 80700024<br
                                        class="hidden sm:block"
                                    />
                                    <span class="block sm:inline font-medium"
                                        >Support :</span
                                    >
                                    support@o2switch.fr<br
                                        class="hidden sm:block"
                                    />
                                    <span class="block sm:inline font-medium"
                                        >Site :</span
                                    >
                                    https://www.o2switch.fr
                                </p>
                            </div>

                            <h2 class="title-section">3. Développement</h2>
                            <div class="legal-block">
                                <p>
                                    Ce site a été développé par SCHREURS Cedric
                                    dans le cadre de son Travail de Fin d'Études
                                    (TFE) au sein de l'IFOSUP, encadré par ces
                                    superviseurs Martin Bruno et Six
                                    Thibault.<br class="hidden sm:block" />
                                    <span class="block sm:inline font-medium"
                                        >Email :</span
                                    >
                                    cedric.schreurs@ifosup.wavre.be
                                </p>
                            </div>

                            <h2 class="title-section">
                                4. Propriété intellectuelle
                            </h2>
                            <div class="legal-block">
                                <p>
                                    L'ensemble des contenus (textes, images,
                                    logos, vidéos, fichiers, codes sources)
                                    présents sur ce site sont la propriété
                                    exclusive de SCHREURS Dylan ou de ses
                                    partenaires, sauf mention contraire. Toute
                                    reproduction, distribution ou réutilisation,
                                    même partielle, est interdite sans
                                    autorisation écrite préalable.
                                </p>
                            </div>

                            <h2 class="title-section">5. Responsabilité</h2>
                            <div class="legal-block">
                                <p>
                                    L'utilisateur est seul responsable de
                                    l'utilisation qu'il fait du site et de ses
                                    contenus. Malgré toute l'attention portée au
                                    bon fonctionnement du site, des erreurs ou
                                    des interruptions de service peuvent
                                    survenir. SCHREURS Dylan ne pourra être tenu
                                    responsable des dommages directs ou
                                    indirects liés à l'utilisation du site.
                                </p>
                            </div>

                            <h2 class="title-section">6. Liens externes</h2>
                            <div class="legal-block">
                                <p>
                                    Le site peut contenir des liens vers des
                                    sites tiers. Ces liens sont proposés à titre
                                    informatif. SCHREURS Dylan n'a aucun
                                    contrôle sur leur contenu et décline toute
                                    responsabilité quant aux informations qui y
                                    sont présentées.
                                </p>
                            </div>

                            <h2 class="title-section">
                                7. Données personnelles
                            </h2>
                            <div class="legal-block">
                                <p>
                                    La gestion des données personnelles est
                                    détaillée dans notre Politique de
                                    confidentialité. L'utilisateur dispose de
                                    droits (accès, rectification,
                                    suppression...) qu'il peut exercer à tout
                                    moment en contactant :
                                    <span class="text-led-green"
                                        >dyjoke@nextconfig.sniperz.be</span
                                    >
                                </p>
                            </div>

                            <h2 class="title-section">8. Cookies</h2>
                            <div class="legal-block">
                                <p>
                                    Le site utilise des cookies nécessaires à
                                    son bon fonctionnement, ainsi que des
                                    cookies de mesure d'audience anonymes. Un
                                    bandeau d'information permet à l'utilisateur
                                    d'accepter ou refuser ces cookies lors de sa
                                    première visite.
                                </p>
                            </div>

                            <h2 class="title-section">9. Droit applicable</h2>
                            <div class="legal-block">
                                <p>
                                    Les présentes mentions sont régies par le
                                    droit belge. En cas de litige, les tribunaux
                                    compétents seront ceux du ressort de Wavre.
                                </p>
                            </div>
                        </div>

                        <div
                            class="mt-8 pt-6 border-t border-gaming-red/30 text-center text-xs sm:text-sm text-white/70 animate-fade-in"
                            style="animation-delay: 0.3s"
                        >
                            <p>
                                Pour toute question relative à ces mentions
                                légales, vous pouvez nous contacter à l'adresse
                                email :
                                <a
                                    href="mailto:dyjoke@nextconfig.sniperz.be"
                                    class="text-led-green hover:underline"
                                    >dyjoke@nextconfig.sniperz.be</a
                                >
                            </p>
                            <p class="mt-2">Dernière mise à jour : mai 2025</p>
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

.legal-content ul {
    list-style-type: disc;
    margin-left: 1.25rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
}

@media (min-width: 640px) {
    .legal-content ul {
        margin-left: 1.5rem;
        margin-bottom: 1.25rem;
        font-size: 1rem;
    }
}

.legal-content ul li {
    margin-bottom: 0.5rem;
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
