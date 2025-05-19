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
            height="min-h-50vh"
        />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-deep-black border border-gaming-red overflow-hidden shadow-xl sm:rounded-lg"
                >
                    <div class="p-6 text-white">
                        <div class="mb-8 text-center">
                            <HeroTitle
                                title="Protection de vos données"
                                :centered="true"
                                marginBottom="mb-8"
                            />
                        </div>

                        <div class="legal-content">
                            <h2>1. Introduction</h2>
                            <p>
                                NextConfig (www.nextconfig.sniperz.be) accorde
                                une grande importance à la protection des
                                données personnelles de ses utilisateurs. Cette
                                politique de confidentialité décrit comment nous
                                collectons, utilisons, partageons et protégeons
                                les données personnelles que vous nous
                                fournissez via notre site.
                            </p>

                            <h2>2. Responsable du traitement</h2>
                            <p>
                                Le responsable du traitement des données est
                                :<br />
                                SCHREURS Dylan<br />
                                Rue du travail de fin d'étude 1, 1000 Bruxelles
                                – Belgique<br />
                                Email : dyjoke@nextconfig.sniperz.be
                            </p>

                            <h2>3. Données collectées</h2>
                            <p>
                                Nous collectons uniquement les données
                                nécessaires au bon fonctionnement du site et à
                                la gestion des demandes clients. Ces données
                                incluent :
                            </p>
                            <ul>
                                <li>
                                    Formulaire de contact : nom, prénom, adresse
                                    email, message.
                                </li>
                                <li>
                                    Formulaire de devis : nom, prénom, email,
                                    budget, besoins spécifiques.
                                </li>
                                <li>
                                    Commentaires de blog : nom, email, contenu
                                    du commentaire.
                                </li>
                            </ul>

                            <h2>4. Finalités du traitement</h2>
                            <p>
                                Les données collectées servent aux finalités
                                suivantes :
                            </p>
                            <ul>
                                <li>
                                    Répondre à vos demandes via les formulaires
                                    de contact et de devis.
                                </li>
                                <li>
                                    Améliorer nos services et notre interface
                                    grâce à vos retours.
                                </li>
                                <li>
                                    Gérer les commentaires soumis sur les
                                    articles du blog.
                                </li>
                                <li>
                                    Réaliser des analyses anonymes du trafic via
                                    Google Analytics 4.
                                </li>
                            </ul>

                            <h2>5. Base légale du traitement</h2>
                            <p>
                                Les données sont traitées sur les bases légales
                                suivantes :
                            </p>
                            <ul>
                                <li>
                                    Consentement : pour la soumission de
                                    formulaires.
                                </li>
                                <li>
                                    Intérêt légitime : pour garantir la sécurité
                                    du site, prévenir les abus et améliorer
                                    notre service.
                                </li>
                            </ul>

                            <h2>6. Destinataires des données</h2>
                            <p>
                                Les données sont uniquement accessibles à
                                SCHREURS Dylan, responsable du site, et à
                                SCHREURS Cedric, développeur du projet dans le
                                cadre de son TFE à l'IFOSUP. Aucune donnée n'est
                                vendue ni transmise à des tiers à des fins
                                commerciales.
                            </p>

                            <h2>7. Durée de conservation</h2>
                            <p>Les données sont conservées :</p>
                            <ul>
                                <li>
                                    6 mois pour les données soumises via
                                    formulaires (contact, devis, commentaires).
                                </li>
                                <li>
                                    2 ans maximum dans le cadre du suivi du
                                    projet et des obligations académiques du
                                    TFE.
                                </li>
                            </ul>

                            <h2>8. Vos droits</h2>
                            <p>
                                Conformément au Règlement Général sur la
                                Protection des Données (RGPD), vous disposez des
                                droits suivants :
                            </p>
                            <ul>
                                <li>Droit d'accès à vos données</li>
                                <li>Droit de rectification</li>
                                <li>
                                    Droit à l'effacement ("droit à l'oubli")
                                </li>
                                <li>
                                    Droit d'opposition et de limitation du
                                    traitement
                                </li>
                                <li>Droit à la portabilité</li>
                            </ul>
                            <p>
                                Vous pouvez exercer ces droits à tout moment en
                                envoyant un email à :
                                dyjoke@nextconfig.sniperz.be
                            </p>

                            <h2>9. Sécurité des données</h2>
                            <p>
                                Le site est sécurisé par un certificat SSL
                                (HTTPS). Les formulaires sont protégés contre le
                                spam, les attaques CSRF et les injections via
                                une validation renforcée côté client et serveur.
                                Les données sont hébergées en Europe chez
                                o2switch, conforme au RGPD.
                            </p>

                            <h2>10. Cookies</h2>
                            <p>
                                Le site utilise des cookies à des fins
                                techniques et statistiques :
                            </p>
                            <ul>
                                <li>
                                    Cookies nécessaires au bon fonctionnement
                                    (session, langue)
                                </li>
                                <li>
                                    Cookies analytiques via Google Analytics
                                    (anonymisation IP activée)
                                </li>
                            </ul>
                            <p>
                                Un bandeau de consentement s'affiche lors de
                                votre première visite pour gérer vos
                                préférences.
                            </p>

                            <h2>11. Modifications de la politique</h2>
                            <p>
                                Cette politique de confidentialité peut être
                                mise à jour à tout moment pour refléter les
                                évolutions légales ou techniques. Toute
                                modification majeure sera annoncée sur le site.
                            </p>
                            <p class="text-right mt-4 italic">
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

.legal-content h2 {
    font-size: 1.5rem;
    color: #4ade80; /* Couleur led-green */
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-family: "Play", sans-serif;
}

.legal-content p {
    margin-bottom: 1rem;
}

.legal-content ul {
    list-style-type: disc;
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}
</style>
