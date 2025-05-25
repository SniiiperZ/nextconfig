<script setup>
// Importation des composants Inertia.js pour la gestion des métadonnées
import { Head } from "@inertiajs/vue3"; // Pour définir les métadonnées de la page (title, description, etc.)

// Importation des layouts et composants réutilisables
import PublicLayout from "@/Layouts/PublicLayout.vue"; // Layout principal pour les pages publiques
import HeroSection from "@/Components/HeroSection.vue"; // En-tête de page avec image de fond
import HeroTitle from "@/Components/HeroTitle.vue"; // Titre stylisé pour les sections
</script>

<template>
    <!-- Layout principal avec métadonnées SEO optimisées pour la page de politique de confidentialité -->
    <PublicLayout
        title="Politique de confidentialité"
        description="Découvrez comment nous protégeons vos données personnelles et respectons votre vie privée conformément au RGPD."
        keywords="politique confidentialité, RGPD, protection données, vie privée, NextConfig"
    >
        <!-- Hero Section avec image de fond, titre et sous-titre explicatifs -->
        <HeroSection
            title="Politique de confidentialité"
            subtitle="Nous
        accordons une grande importance à la protection de vos données"
            backgroundImage="/images/background.jpg"
            height="min-h-40vh
        sm:min-h-50vh"
        />

        <!-- Section principale du contenu avec espacement responsif -->
        <div class="py-8 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Carte principale contenant toutes les informations légales -->
                <div
                    class="bg-deep-black border border-gaming-red overflow-hidden shadow-xl rounded-lg"
                >
                    <div class="p-4 sm:p-6 md:p-8 text-white">
                        <!-- En-tête de la page avec titre centré et mention RGPD -->
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

                        <!-- Contenu des mentions légales avec animation d'apparition -->
                        <div class="legal-content animate-fade-in">
                            <!-- SECTION 1: Introduction -->
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

                            <!-- SECTION 2: Identité du responsable de traitement -->
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
                                    <!-- Saut de ligne sur mobile uniquement -->
                                    <span class="block sm:inline font-medium"
                                        >Adresse :</span
                                    >
                                    Rue du travail de fin d'étude 1, 1000
                                    Bruxelles – Belgique<br
                                        class="hidden sm:block"
                                    />
                                    <!-- Saut de ligne sur desktop uniquement -->
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

                            <!-- SECTION 3: Données collectées et leur source -->
                            <h2 class="title-section">3. Données collectées</h2>
                            <div class="legal-block">
                                <p>
                                    Nous collectons uniquement les données
                                    nécessaires au bon fonctionnement du site et
                                    à la gestion des demandes clients. Ces
                                    données incluent :
                                </p>
                                <!-- Liste à puces stylisée avec points personnalisés -->
                                <ul class="list-items">
                                    <li>
                                        <span class="bullet-point"></span>
                                        <!-- Point décoratif personnalisé -->
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

                            <!-- SECTION 4: Objectifs du traitement des données -->
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

                            <!-- SECTION 5: Base juridique du traitement -->
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

                            <!-- SECTION 6: Partage des données -->
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

                            <!-- SECTION 7: Durée de conservation des données -->
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

                            <!-- SECTION 8: Droits des utilisateurs (RGPD) -->
                            <h2 class="title-section">8. Vos droits</h2>
                            <div class="legal-block">
                                <p>
                                    Conformément au Règlement Général sur la
                                    Protection des Données (RGPD), vous disposez
                                    des droits suivants :
                                </p>
                                <!-- Grille responsive pour afficher les droits RGPD avec icônes -->
                                <div
                                    class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4 mt-3"
                                >
                                    <!-- Droit d'accès -->
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
                                    <!-- Droit de rectification -->
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
                                    <!-- Droit à l'effacement (droit à l'oubli) -->
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
                                    <!-- Droit d'opposition -->
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
                                    <!-- Droit à la portabilité -->
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
                                <!-- Texte d'information sur la manière d'exercer ses droits -->
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

                            <!-- SECTION 9: Mesures de sécurité techniques -->
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

                            <!-- SECTION 10: Politique des cookies -->
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

                            <!-- SECTION 11: Mise à jour de la politique -->
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

                        <!-- Pied de page avec date de mise à jour -->
                        <div
                            class="mt-8 pt-6 border-t border-gaming-red/30 text-right text-xs sm:text-sm text-white/70 animate-fade-in"
                            style="animation-delay: 0.3s"
                            <!--
                            Animation
                            différée
                            pour
                            un
                            effet
                            de
                            cascade
                            --
                        >
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
/* VARIABLES CSS POUR UNE MEILLEURE MAINTENABILITÉ */
/* Ces variables sont définies dans app.css mais redéfinies ici pour une portée locale */
:root {
    --color-led-green: #00ff55; /* Vert LED */
    --color-gaming-red: #ff0055; /* Rouge Gaming */
    --color-gaming-red-light: rgba(255, 0, 85, 0.2); /* Rouge Gaming clair */
    --color-led-green-glow: rgba(0, 255, 85, 0.3); /* Lueur verte LED */
    --color-deep-black: #121212; /* Noir profond pour le fond */
    --transition-standard: all 0.3s ease-in-out; /* Transition standard pour les effets */
}

/* Structure et base - Définit la mise en page générale du contenu légal */
.legal-content {
    line-height: 1.6; /* Augmente l'espacement des lignes pour une meilleure lisibilité */
}

/* Titres et sections - Style pour les en-têtes de chaque section de confidentialité */
.title-section {
    font-size: 1.25rem; /* Taille de base pour mobile */
    font-weight: 600; /* Gras mais pas trop lourd */
    color: var(--color-led-green); /* Couleur d'accent verte */
    margin-top: 1.5rem; /* Espacement supérieur */
    margin-bottom: 0.75rem; /* Espacement inférieur */
    font-family: "Play", sans-serif; /* Police de caractère spéciale pour les titres */
    display: flex; /* Pour aligner l'élément décoratif et le texte */
    align-items: center; /* Centrage vertical des éléments */
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

/* Blocs et conteneurs - Style pour chaque bloc d'information de confidentialité */
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

/* Style pour les listes à puces personnalisées */
.list-items {
    list-style-type: none; /* Supprime les puces par défaut */
    margin-left: 0.5rem; /* Léger retrait à gauche */
    margin-bottom: 1rem; /* Espace après la liste */
    font-size: 0.875rem; /* Taille de texte cohérente avec les paragraphes */
}

.list-items li {
    margin-bottom: 0.5rem; /* Espace entre les éléments de liste */
    position: relative; /* Pour positionner la puce personnalisée */
    padding-left: 1.25rem; /* Espace pour la puce */
}

/* Point de puce personnalisé avec la couleur d'accent */
.bullet-point {
    position: absolute; /* Positionnement absolu par rapport à l'élément li */
    left: 0; /* Aligné à gauche */
    top: 0.5rem; /* Ajustement vertical pour aligner avec le texte */
    width: 6px; /* Taille du point */
    height: 6px; /* Taille du point */
    background-color: var(--color-gaming-red); /* Couleur d'accent rouge */
    border-radius: 50%; /* Forme circulaire */
    display: inline-block;
}

/* Style pour les boîtes présentant les droits RGPD */
.right-box {
    background-color: rgba(0, 0, 0, 0.3); /* Fond légèrement plus foncé */
    border: 1px solid var(--color-led-green-glow); /* Bordure verte subtile */
    border-radius: 0.375rem; /* Coins arrondis */
    padding: 0.75rem; /* Espacement interne */
    transition: var(--transition-standard); /* Animation fluide au survol */
}

/* Effet de survol pour les boîtes de droits */
.right-box:hover {
    border-color: rgba(0, 255, 85, 0.3); /* Bordure plus visible au survol */
    box-shadow: 0 0 8px var(--color-led-green-glow); /* Effet de lueur verte */
}

/* Titre de chaque droit RGPD avec icône */
.right-title {
    display: flex; /* Pour aligner l'icône et le texte */
    align-items: center; /* Centrage vertical */
    font-weight: 500; /* Semi-gras */
    color: var(--color-led-green); /* Couleur d'accent verte */
    font-size: 0.875rem; /* Taille de texte adaptée */
    margin-bottom: 0.25rem; /* Petit espace avant la description */
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

    /* Paragraphes - Taille augmentée sur desktop */
    .legal-content p {
        font-size: 1rem; /* Taille standard sur desktop */
        line-height: 1.7; /* Hauteur de ligne légèrement augmentée */
    }

    /* Listes - Adaptations pour desktop */
    .list-items {
        margin-left: 0.75rem; /* Indentation plus importante */
        margin-bottom: 1.25rem; /* Plus d'espace après la liste */
        font-size: 1rem; /* Taille standard sur desktop */
    }

    /* Points de puce plus grands sur desktop */
    .bullet-point {
        width: 8px; /* Point plus grand */
        height: 8px; /* Point plus grand */
        top: 0.55rem; /* Ajustement de la position verticale */
    }

    /* Plus d'espace pour le texte après la puce */
    .list-items li {
        padding-left: 1.5rem; /* Plus d'espace pour la puce */
    }

    /* Titres des droits RGPD plus grands sur desktop */
    .right-title {
        font-size: 1rem; /* Taille augmentée */
    }

    /* Boîtes de droits plus spacieuses sur desktop */
    .right-box {
        padding: 1rem; /* Plus d'espace interne */
    }
}
</style>
