<script setup>
// Importation des composants Inertia.js pour la gestion des métadonnées
import { Head } from "@inertiajs/vue3"; // Pour définir les métadonnées de la page (title, description, etc.)

// Importation des layouts et composants réutilisables
import PublicLayout from "@/Layouts/PublicLayout.vue"; // Layout principal pour les pages publiques
import HeroSection from "@/Components/HeroSection.vue"; // Section d'en-tête avec image de fond
import HeroTitle from "@/Components/HeroTitle.vue"; // Titre stylisé pour les sections
</script>

<template>
    <!-- Layout principal avec métadonnées SEO optimisées pour la page de mentions légales -->
    <PublicLayout
        title="Mentions légales"
        description="Consultez les informations légales relatives à NextConfig, l'hébergement du site et vos droits en tant qu'utilisateur."
        keywords="mentions légales, hébergement, droits utilisateur, propriété intellectuelle, NextConfig"
    >
        <!-- En-tête de la page avec titre, sous-titre et image de fond -->
        <HeroSection
            title="Mentions légales"
            subtitle="Informations juridiques concernant l'utilisation du site"
            backgroundImage="/images/background.jpg"
            height="min-h-40vh sm:min-h-50vh"
        />

        <!-- Section principale du contenu avec espacement responsif -->
        <div class="py-8 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Carte principale contenant toutes les informations légales -->
                <div
                    class="bg-deep-black border border-gaming-red overflow-hidden shadow-xl rounded-lg"
                >
                    <div class="p-4 sm:p-6 md:p-8 text-white">
                        <!-- Titre principal centré -->
                        <div class="mb-6 sm:mb-8 text-center">
                            <HeroTitle
                                title="Informations légales"
                                :centered="true"
                                marginBottom="mb-6 sm:mb-8"
                            />
                        </div>

                        <!-- Contenu des mentions légales avec animation d'apparition -->
                        <div class="legal-content animate-fade-in">
                            <!-- Section 1: Informations générales -->
                            <h2 class="title-section">
                                1. Informations générales
                            </h2>
                            <div class="legal-block">
                                <p>
                                    <!-- Utilisation de blocs/inline conditionnels pour l'affichage responsive -->
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

                            <!-- Section 2: Informations sur l'hébergeur -->
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

                            <!-- Section 3: Informations sur le développement -->
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

                            <!-- Section 4: Droits de propriété intellectuelle -->
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

                            <!-- Section 5: Responsabilités et limitations -->
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

                            <!-- Section 6: Politique des liens externes -->
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

                            <!-- Section 7: Traitement des données personnelles (RGPD) -->
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

                            <!-- Section 8: Politique des cookies -->
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

                            <!-- Section 9: Juridiction applicable -->
                            <h2 class="title-section">9. Droit applicable</h2>
                            <div class="legal-block">
                                <p>
                                    Les présentes mentions sont régies par le
                                    droit belge. En cas de litige, les tribunaux
                                    compétents seront ceux du ressort de Wavre.
                                </p>
                            </div>
                        </div>

                        <!-- Pied de page avec contacts et date de mise à jour -->
                        <div
                            class="mt-8 pt-6 border-t border-gaming-red/30 text-center text-xs sm:text-sm text-white/70 animate-fade-in"
                            style="animation-delay: 0.3s" <!-- Animation différée pour un effet de cascade -->
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
/* VARIABLES CSS - Pour centraliser les valeurs de couleurs et autres propriétés */
:root {
    /* Couleurs principales */
    --color-led-green: rgb(0, 255, 85);
    --color-led-green-light: rgba(0, 255, 85, 0.3);
    --color-gaming-red: rgb(236, 64, 122);
    --color-gaming-red-light: rgba(236, 64, 122, 0.3);
    --color-gaming-red-medium: rgba(236, 64, 122, 0.5);
    --color-deep-black: rgb(13, 17, 23);
    
    /* Transitions */
    --transition-standard: all 0.3s ease;
    
    /* Ombres */
    --shadow-standard: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-hover: 0 6px 12px rgba(0, 0, 0, 0.15);
}

/* Structure et base */
.legal-content {
    line-height: 1.6; /* Augmente l'espacement des lignes pour une meilleure lisibilité */
}

/* Titres et sections - Style pour les en-têtes de chaque section légale */
.title-section {
    font-size: 1.25rem; /* Taille de base pour mobile */
    font-weight: 600; /* Gras mais pas trop lourd */
    color: var(--color-led-green); /* Couleur d'accent verte */
    margin-top: 1.5rem; /* Espacement supérieur */
    margin-bottom: 0.75rem; /* Espacement inférieur */
    font-family: "Play", sans-serif; /* Police de caractère spéciale pour les titres */
    display: flex; /* Pour aligner l'élément décoratif et le texte */
    align-items: center;
    border-bottom: 1px solid var(--color-gaming-red-light); /* Ligne de séparation subtile */
    padding-bottom: 0.5rem; /* Espace sous le titre avant la ligne */
}

/* Barre verticale décorative à gauche des titres de section */
.title-section::before {
    content: ""; /* Pseudo-élément sans contenu */
    display: inline-block;
    width: 4px; /* Largeur de la barre */
    height: 1rem; /* Hauteur adaptée au texte */
    background-color: var(--color-led-green); /* Couleur d'accent verte */
    margin-right: 0.75rem; /* Espacement entre la barre et le texte */
    border-radius: 2px; /* Coins légèrement arrondis */
}

/* Blocs et conteneurs - Style pour chaque bloc d'information légale */
.legal-block {
    background-color: rgba(0, 0, 0, 0.2); /* Fond légèrement transparent */
    border: 1px solid var(--color-gaming-red-light); /* Bordure subtile */
    border-radius: 0.5rem; /* Coins arrondis */
    padding: 1rem; /* Espacement interne */
    margin-bottom: 1.5rem; /* Espacement entre les blocs */
}

/* Éléments textuels - Style pour les paragraphes de texte */
.legal-content p {
    margin-bottom: 0.75rem; /* Espacement entre paragraphes */
    font-size: 0.875rem; /* Taille de texte légèrement réduite pour une meilleure lisibilité */
    line-height: 1.6; /* Hauteur de ligne augmentée */
}

/* Style pour les listes à puces (non utilisées actuellement mais prêtes si besoin) */
.legal-content ul {
    list-style-type: disc; /* Style de puce standard */
    margin-left: 1.25rem; /* Indentation des puces */
    margin-bottom: 1rem; /* Espacement après la liste */
    font-size: 0.875rem; /* Taille de texte cohérente avec les paragraphes */
}

.legal-content ul li {
    margin-bottom: 0.5rem; /* Espacement entre les éléments de liste */
}

/* Animations - Effet d'apparition progressive des éléments */
.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards; /* Animation de fondu avec délai progressif */
    opacity: 0; /* Commence invisible */
}

/* Définition de l'animation de fondu entrant avec léger déplacement */
@keyframes fadeIn {
    from {
        opacity: 0; /* Invisible au départ */
        transform: translateY(10px); /* Légèrement décalé vers le bas */
    }
    to {
        opacity: 1; /* Complètement visible à la fin */
        transform: translateY(0); /* Position finale normale */
    }
}

/* Media Queries - Adaptations pour les écrans plus grands */
@media (min-width: 640px) {
    /* Titres - Taille et espacement augmentés sur grands écrans */
    .title-section {
        font-size: 1.5rem; /* Plus grand sur desktop */
        margin-top: 2rem; /* Plus d'espace au-dessus */
        margin-bottom: 1rem; /* Plus d'espace en-dessous */
    }

    /* Barre décorative plus grande sur desktop */
    .title-section::before {
        width: 6px; /* Plus large */
        height: 1.5rem; /* Plus haute */
        margin-right: 1rem; /* Plus d'espace avec le texte */
    }

    /* Blocs et conteneurs - Plus d'espace sur grands écrans */
    .legal-block {
        padding: 1.25rem; /* Plus de padding interne */
        margin-bottom: 2rem; /* Plus d'espace entre les blocs */
    }

    /* Éléments textuels - Taille et espacement augmentés */
    .legal-content p {
        font-size: 1rem; /* Taille standard sur desktop */
        line-height: 1.7; /* Hauteur de ligne légèrement augmentée */
    }

    .legal-content ul {
        margin-left: 1.5rem; /* Indentation plus importante */
        margin-bottom: 1.25rem; /* Plus d'espace après la liste */
        font-size: 1rem; /* Taille standard sur desktop */
    }
}
</style>