<script setup>
/**
 * Dashboard Administratif NextConfig
 *
 * Cette page présente un tableau de bord complet avec statistiques, graphiques
 * et informations récentes pour l'administrateur du site.
 *
 * Fonctionnalités principales:
 * - Affichage des statistiques clés (projets, avis, articles, commentaires)
 * - Visualisation des revenus avec graphiques interactifs
 * - Distribution des avis clients par note
 * - Liste des activités récentes (avis, projets, articles, commentaires)
 * - Gestion des revenus mensuels
 */

// --------------------------
// IMPORTS
// --------------------------

// Composants Vue et utilitaires
import { ref, onMounted, computed, watch } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue"; // Layout principal d'administration

// Composants pour les graphiques
import { Pie, Doughnut, Bar } from "vue-chartjs";
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
    ArcElement,
} from "chart.js";

// Utilitaires HTTP et Inertia
import axios from "axios";
import { Link, router, useForm, usePage } from "@inertiajs/vue3";

// Icônes Heroicons pour l'interface
import {
    ComputerDesktopIcon, // Icône pour les projets
    ChatBubbleLeftRightIcon, // Icône pour les commentaires
    StarIcon, // Icône pour les avis
    DocumentTextIcon, // Icône pour les articles
    CurrencyEuroIcon, // Icône pour les revenus
    ArrowUpRightIcon, // Icône pour les liens
    WrenchScrewdriverIcon, // Icône pour les projets
    XMarkIcon, // Icône pour fermer/annuler
    CheckCircleIcon, // Icône pour confirmer/approuver
    PencilIcon, // Icône pour modifier
    ViewfinderCircleIcon, // Icône pour les actions rapides
    CalendarIcon, // Icône pour les dates
    QuestionMarkCircleIcon, // Icône pour l'aide
    ChevronLeftIcon, // Icône de navigation gauche
    ChevronRightIcon, // Icône de navigation droite
    ChevronDownIcon, // Icône déroulante bas
    ChevronUpIcon, // Icône déroulante haut
} from "@heroicons/vue/24/outline";

// Enregistrement des composants ChartJS nécessaires
ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
    ArcElement
);

// --------------------------
// PROPS ET ÉTAT INITIAL
// --------------------------

/**
 * Props reçues du contrôleur Laravel
 * - stats: Statistiques globales du site
 * - recent_reviews: Derniers avis clients
 * - recent_projects: Derniers projets réalisés
 * - recent_comments: Derniers commentaires du blog
 * - recent_blog_posts: Derniers articles du blog
 */
const props = defineProps({
    stats: Object,
    recent_reviews: Array,
    recent_projects: Array,
    recent_comments: Array,
    recent_blog_posts: Array,
});

// --------------------------
// GESTION DES NOTIFICATIONS
// --------------------------

/**
 * Système de notification flash
 * Récupère les messages de succès de la session Laravel via Inertia
 * et les affiche temporairement
 */
const page = usePage();
const flashMessage = ref(page.props.flash.success || null);
const showFlashMessage = ref(!!page.props.flash.success);

// Masquer automatiquement le message après 8 secondes
if (flashMessage.value) {
    setTimeout(() => {
        showFlashMessage.value = false;
    }, 8000);
}

// Fonction pour masquer manuellement le message
const hideFlashMessage = () => {
    showFlashMessage.value = false;
};

// --------------------------
// STATISTIQUES CALCULÉES
// --------------------------

/**
 * Données calculées à partir des statistiques reçues
 * Utilisation de computed pour bénéficier de la réactivité
 */
const totalProjectsCount = computed(() => props.stats.projects_count || 0);
const totalReviewsCount = computed(() => props.stats.reviews_count || 0);
const totalBlogPostsCount = computed(() => props.stats.blog_posts_count || 0);
const totalCommentsCount = computed(() => props.stats.comments_count || 0);
const pendingCommentsCount = computed(
    () => props.stats.pending_comments_count || 0
);
const pendingReviewsCount = computed(
    () => props.stats.pending_reviews_count || 0
);
const totalRevenue = computed(() => props.stats.total_revenue || 0);

// --------------------------
// GESTION DU SÉLECTEUR D'ANNÉE
// --------------------------

// État du dropdown de sélection d'année
const yearSelectorOpen = ref(false);

// Indicateur de chargement lors du changement d'année
const isYearChanging = ref(false);

/**
 * Gestion de l'année sélectionnée pour les revenus
 * Permet de filtrer les données financières par année
 */
const selectedYear = ref(props.stats.selected_year || new Date().getFullYear());

/**
 * Liste des années disponibles pour la sélection
 * Utilise les données du serveur ou génère une plage d'années logique
 */
const availableYears = computed(() => {
    // Si des années sont disponibles, les utiliser
    if (props.stats.available_years && props.stats.available_years.length) {
        return props.stats.available_years;
    }

    // Sinon, générer une plage d'années (5 ans en arrière, 2 ans en avant)
    const currentYear = new Date().getFullYear();
    const years = [];
    for (let y = currentYear - 5; y <= currentYear + 2; y++) {
        years.push(y);
    }
    return years;
});

/**
 * Fonction pour changer l'année avec indication visuelle de chargement
 * @param {Number} year - L'année à sélectionner
 */
const changeYear = (year) => {
    // Éviter de recharger si on clique sur l'année déjà sélectionnée
    if (year === selectedYear.value) {
        yearSelectorOpen.value = false;
        return;
    }

    // Afficher l'indicateur de chargement
    isYearChanging.value = true;

    // Redirection après un court délai pour que l'animation soit visible
    setTimeout(() => {
        window.location.href = route("dashboard", { year: year });
    }, 300);
};

// --------------------------
// DONNÉES DE LA PÉRIODE ACTUELLE
// --------------------------

/**
 * Informations sur la période actuelle
 * Indépendantes de l'année sélectionnée pour le graphique
 */
const today = new Date();
const currentMonthName = today.toLocaleString("fr-FR", { month: "long" });
const currentYearNumber = today.getFullYear();
const currentMonthRevenue = computed(
    () => props.stats.current_month_revenue || 0
);

// --------------------------
// DONNÉES POUR LES GRAPHIQUES
// --------------------------

/**
 * Configuration du graphique de distribution des avis
 * Affiche la répartition des notes (1 à 5 étoiles)
 */
const reviewsChartData = ref({
    labels: ["5★", "4★", "3★", "2★", "1★"],
    datasets: [
        {
            backgroundColor: [
                "#10b981", // Vert pour 5 étoiles
                "#a3e635", // Vert-jaune pour 4 étoiles
                "#fbbf24", // Jaune pour 3 étoiles
                "#f97316", // Orange pour 2 étoiles
                "#ef4444", // Rouge pour 1 étoile
            ],
            data: props.stats.reviews_rating_distribution || [45, 30, 15, 7, 3], // Valeurs par défaut si non fournies
            borderWidth: 0,
        },
    ],
});

/**
 * Configuration du graphique des revenus mensuels
 * Affiche les revenus pour chaque mois de l'année sélectionnée
 */
const monthlyRevenueData = ref({
    labels: [
        "Jan",
        "Fév",
        "Mars",
        "Avr",
        "Mai",
        "Juin",
        "Juil",
        "Août",
        "Sep",
        "Oct",
        "Nov",
        "Déc",
    ],
    datasets: [
        {
            label: "Revenus (€)",
            backgroundColor: "rgba(16, 185, 129, 0.6)",
            borderColor: "#10b981",
            borderWidth: 1,
            data: props.stats.monthly_revenue || [
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
            ],
            borderRadius: 5,
        },
    ],
});

// --------------------------
// GESTION DU FORMULAIRE DE REVENUS
// --------------------------

// État d'édition des revenus
const editingRevenue = ref(false);

// Noms des mois pour le sélecteur
const monthNames = [
    "Janvier",
    "Février",
    "Mars",
    "Avril",
    "Mai",
    "Juin",
    "Juillet",
    "Août",
    "Septembre",
    "Octobre",
    "Novembre",
    "Décembre",
];

/**
 * Formulaire pour la mise à jour des revenus mensuels
 * Utilise useForm d'Inertia pour gérer la soumission
 */
const revenueForm = useForm({
    month: new Date().getMonth() + 1, // Mois courant (1-12)
    year: selectedYear.value, // Année sélectionnée actuellement
    amount: 0, // Montant à définir
});

// Mettre à jour le formulaire quand l'année sélectionnée change
watch(selectedYear, (newValue) => {
    revenueForm.year = newValue;
});

// --------------------------
// OPTIONS DES GRAPHIQUES
// --------------------------

/**
 * Options pour le graphique circulaire (avis)
 * Configuration responsive avec adaptation mobile/desktop
 */
const pieChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "bottom",
            labels: {
                color: "rgba(255, 255, 255, 0.7)",
                padding: 20,
                boxWidth: 15,
                // Adapter la taille de la police sur mobile
                font: {
                    size: window.innerWidth < 768 ? 10 : 12,
                },
            },
        },
    },
};

/**
 * Options pour le graphique en barres (revenus)
 * Configuration responsive avec adaptation mobile/desktop
 */
const barChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: "rgba(255, 255, 255, 0.1)",
            },
            ticks: {
                color: "rgba(255, 255, 255, 0.7)",
                // Adapter le nombre de ticks sur mobile
                maxTicksLimit: window.innerWidth < 768 ? 4 : 8,
            },
        },
        x: {
            grid: {
                display: false,
            },
            ticks: {
                color: "rgba(255, 255, 255, 0.7)",
                // Adapter la taille de la police sur mobile
                font: {
                    size: window.innerWidth < 768 ? 9 : 12,
                },
            },
        },
    },
    plugins: {
        legend: {
            labels: {
                color: "rgba(255, 255, 255, 0.7)",
                font: {
                    size: window.innerWidth < 768 ? 10 : 12,
                },
            },
        },
        tooltip: {
            callbacks: {
                label: function (context) {
                    return context.parsed.y.toLocaleString("fr-FR") + " €";
                },
            },
        },
    },
};

// --------------------------
// FONCTIONS DU FORMULAIRE DE REVENUS
// --------------------------

/**
 * Initialise le formulaire d'édition des revenus
 * Prérempli avec les données du mois et de l'année sélectionnés
 */
const startEditingRevenue = () => {
    editingRevenue.value = true;
    // Utiliser l'année sélectionnée pour le formulaire
    revenueForm.year = selectedYear.value;
    revenueForm.month = new Date().getMonth() + 1;

    // Précharger le montant du mois sélectionné pour l'année choisie
    if (props.stats.monthly_revenue) {
        revenueForm.amount =
            props.stats.monthly_revenue[revenueForm.month - 1] || 0;
    }
};

/**
 * Soumet le formulaire de revenus au serveur
 * Utilise la méthode post de useForm pour envoyer les données
 */
const submitRevenueForm = () => {
    revenueForm.post(route("admin.update-monthly-revenue"), {
        onSuccess: () => {
            editingRevenue.value = false;
            // Rafraîchir la page complètement pour obtenir les nouvelles données du serveur
            window.location.reload();
        },
    });
};

/**
 * Annule l'édition des revenus et réinitialise le formulaire
 */
const cancelEditRevenue = () => {
    editingRevenue.value = false;
    revenueForm.reset();
};

/**
 * Met à jour le montant affiché dans le formulaire
 * quand l'utilisateur change de mois
 */
const updateSelectedMonthRevenue = () => {
    if (props.stats.monthly_revenue) {
        revenueForm.amount =
            props.stats.monthly_revenue[revenueForm.month - 1] || 0;
    }
};

// --------------------------
// FONCTIONS UTILITAIRES
// --------------------------

/**
 * Formate une date au format français
 * @param {String} dateString - Date au format ISO
 * @returns {String} - Date formatée (ex: "15 mars 2025")
 */
const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};

/**
 * Tronque un texte s'il dépasse la longueur spécifiée
 * @param {String} text - Texte à tronquer
 * @param {Number} length - Longueur maximale (défaut: 60)
 * @returns {String} - Texte tronqué avec "..." si nécessaire
 */
const truncateText = (text, length = 60) => {
    if (!text) return "";
    return text.length > length ? text.substring(0, length) + "..." : text;
};

/**
 * Génère un tableau d'étoiles remplies/vides selon la note
 * @param {Number} rating - Note (1-5)
 * @returns {Array} - Tableau de booléens (true = étoile remplie)
 */
const renderStars = (rating) => {
    return Array.from({ length: 5 }, (_, i) => i < rating);
};

// --------------------------
// DÉTECTION MOBILE ET ADAPTATIONS RESPONSIVES
// --------------------------

/**
 * Détecte si l'appareil est en mode mobile
 * Permet d'adapter l'affichage en conséquence
 */
const isMobile = ref(window.innerWidth < 768);

// Mise à jour de la détection mobile lors du redimensionnement
onMounted(() => {
    const handleResize = () => {
        isMobile.value = window.innerWidth < 768;
    };

    window.addEventListener("resize", handleResize);

    return () => {
        window.removeEventListener("resize", handleResize);
    };
});
</script>

<template>
    <AdminLayout title="Dashboard">
        <!-- En-tête du Dashboard avec titre et message de notification -->
        <template #header>
            <div
                class="flex flex-col sm:flex-row justify-between items-center gap-3"
            >
                <!-- Titre principal du dashboard -->
                <h2
                    class="font-play font-semibold text-xl text-white leading-tight"
                >
                    Dashboard Admin - NextConfig
                </h2>

                <!-- Notification de succès avec animation d'entrée/sortie -->
                <transition
                    enter-active-class="transition ease-out duration-300"
                    enter-from-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-200"
                    leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95"
                >
                    <div
                        v-if="showFlashMessage"
                        class="bg-led-green/20 border border-led-green text-led-green px-4 py-2 rounded-md flex items-center max-w-full sm:max-w-md text-sm sm:text-base"
                    >
                        <CheckCircleIcon class="w-5 h-5 mr-2 flex-shrink-0" />
                        <span class="line-clamp-2">{{ flashMessage }}</span>
                        <button
                            @click="hideFlashMessage"
                            class="ml-3 text-led-green/80 hover:text-led-green flex-shrink-0"
                        >
                            <XMarkIcon class="w-4 h-4" />
                        </button>
                    </div>
                </transition>
            </div>
        </template>

        <!-- Corps principal du Dashboard avec espacement responsif -->
        <div class="py-6 md:py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- SECTION 1: STATISTIQUES PRINCIPALES -->
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8"
                >
                    <!-- 1.1 Carte: Projets réalisés -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red transition-all duration-300 rounded-lg shadow-lg overflow-hidden"
                    >
                        <div class="p-4 md:p-5">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p
                                        class="text-white/70 text-sm font-medium"
                                    >
                                        Projets réalisés
                                    </p>
                                    <h3
                                        class="text-2xl md:text-3xl font-play font-bold text-white mt-1"
                                    >
                                        {{ totalProjectsCount }}
                                    </h3>
                                </div>
                                <div
                                    class="bg-gaming-red/20 rounded-full p-3 md:p-4"
                                >
                                    <ComputerDesktopIcon
                                        class="h-6 w-6 md:h-8 md:w-8 text-gaming-red"
                                    />
                                </div>
                            </div>
                            <div class="mt-3 md:mt-4 text-sm">
                                <Link
                                    :href="route('admin.portfolio')"
                                    class="text-white/70 hover:text-led-green flex items-center"
                                >
                                    Voir les projets
                                    <ArrowUpRightIcon class="ml-1 h-3 w-3" />
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- 1.2 Carte: Avis clients -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red transition-all duration-300 rounded-lg shadow-lg overflow-hidden"
                    >
                        <div class="p-4 md:p-5">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p
                                        class="text-white/70 text-sm font-medium"
                                    >
                                        Avis clients
                                    </p>
                                    <h3
                                        class="text-2xl md:text-3xl font-play font-bold text-white mt-1 flex items-center flex-wrap"
                                    >
                                        {{ totalReviewsCount }}
                                        <!-- Badge pour les avis en attente -->
                                        <span
                                            v-if="pendingReviewsCount > 0"
                                            class="ml-2 text-xs md:text-sm text-gaming-red"
                                            >({{ pendingReviewsCount }} en
                                            attente)</span
                                        >
                                    </h3>
                                </div>
                                <div
                                    class="bg-gaming-red/20 rounded-full p-3 md:p-4"
                                >
                                    <StarIcon
                                        class="h-6 w-6 md:h-8 md:w-8 text-gaming-red"
                                    />
                                </div>
                            </div>
                            <div class="mt-3 md:mt-4 text-sm">
                                <Link
                                    :href="route('admin.reviews')"
                                    class="text-white/70 hover:text-led-green flex items-center"
                                >
                                    Gérer les avis
                                    <ArrowUpRightIcon class="ml-1 h-3 w-3" />
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- 1.3 Carte: Articles de blog -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red transition-all duration-300 rounded-lg shadow-lg overflow-hidden"
                    >
                        <div class="p-4 md:p-5">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p
                                        class="text-white/70 text-sm font-medium"
                                    >
                                        Articles de blog
                                    </p>
                                    <h3
                                        class="text-2xl md:text-3xl font-play font-bold text-white mt-1"
                                    >
                                        {{ totalBlogPostsCount }}
                                    </h3>
                                </div>
                                <div
                                    class="bg-gaming-red/20 rounded-full p-3 md:p-4"
                                >
                                    <DocumentTextIcon
                                        class="h-6 w-6 md:h-8 md:w-8 text-gaming-red"
                                    />
                                </div>
                            </div>
                            <div class="mt-3 md:mt-4 text-sm">
                                <Link
                                    :href="route('admin.blog')"
                                    class="text-white/70 hover:text-led-green flex items-center"
                                >
                                    Gérer le blog
                                    <ArrowUpRightIcon class="ml-1 h-3 w-3" />
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- 1.4 Carte: Commentaires -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red transition-all duration-300 rounded-lg shadow-lg overflow-hidden"
                    >
                        <div class="p-4 md:p-5">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p
                                        class="text-white/70 text-sm font-medium"
                                    >
                                        Commentaires
                                    </p>
                                    <h3
                                        class="text-2xl md:text-3xl font-play font-bold text-white mt-1 flex items-center flex-wrap"
                                    >
                                        {{ totalCommentsCount }}
                                        <!-- Badge pour les commentaires en attente -->
                                        <span
                                            v-if="pendingCommentsCount > 0"
                                            class="ml-2 text-xs md:text-sm text-gaming-red"
                                            >({{ pendingCommentsCount }} en
                                            attente)</span
                                        >
                                    </h3>
                                </div>
                                <div
                                    class="bg-gaming-red/20 rounded-full p-3 md:p-4"
                                >
                                    <ChatBubbleLeftRightIcon
                                        class="h-6 w-6 md:h-8 md:w-8 text-gaming-red"
                                    />
                                </div>
                            </div>
                            <div class="mt-3 md:mt-4 text-sm">
                                <Link
                                    :href="route('admin.comments')"
                                    class="text-white/70 hover:text-led-green flex items-center"
                                >
                                    Gérer les commentaires
                                    <ArrowUpRightIcon class="ml-1 h-3 w-3" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: INFORMATIONS DÉTAILLÉES ET GRAPHIQUES -->
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8"
                >
                    <!-- 2.1 Carte: Informations du site et actions rapides -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red/60 transition-all duration-300 rounded-lg shadow-lg p-4 md:p-6"
                    >
                        <h3
                            class="text-base md:text-lg font-play font-semibold text-white mb-4"
                        >
                            Informations NextConfig
                        </h3>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6"
                        >
                            <!-- 2.1.1 Stats de la période courante -->
                            <div
                                class="bg-deep-black/50 border border-gaming-red/20 rounded-lg p-3 md:p-4"
                            >
                                <div class="flex items-center mb-2 md:mb-3">
                                    <CalendarIcon
                                        class="h-5 w-5 text-gaming-red mr-2"
                                    />
                                    <h4 class="text-white font-medium">
                                        Période actuelle
                                    </h4>
                                </div>

                                <div
                                    class="space-y-2 md:space-y-3 text-sm md:text-base"
                                >
                                    <div class="flex justify-between">
                                        <span class="text-white/70">Mois</span>
                                        <span class="text-white font-medium">{{
                                            currentMonthName
                                        }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/70">Année</span>
                                        <span class="text-white font-medium">{{
                                            currentYearNumber
                                        }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/70"
                                            >Revenus ce mois</span
                                        >
                                        <span
                                            class="text-led-green font-medium"
                                        >
                                            {{
                                                currentMonthRevenue.toLocaleString(
                                                    "fr-FR"
                                                )
                                            }}
                                            €
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- 2.1.2 Actions rapides -->
                            <div
                                class="bg-deep-black/50 border border-gaming-red/20 rounded-lg p-3 md:p-4"
                            >
                                <div class="flex items-center mb-2 md:mb-3">
                                    <ViewfinderCircleIcon
                                        class="h-5 w-5 text-gaming-red mr-2"
                                    />
                                    <h4 class="text-white font-medium">
                                        Actions rapides
                                    </h4>
                                </div>

                                <!-- Liste des actions rapides avec liens -->
                                <div class="space-y-2 text-sm">
                                    <Link
                                        :href="route('admin.portfolio')"
                                        class="w-full flex items-center justify-between bg-deep-black/70 hover:bg-gaming-red/20 border border-gaming-red/30 rounded p-2 transition-all duration-200"
                                    >
                                        <span class="text-white/80"
                                            >Ajouter un projet</span
                                        >
                                        <ArrowUpRightIcon
                                            class="h-4 w-4 text-white/60"
                                        />
                                    </Link>

                                    <Link
                                        :href="route('admin.blog')"
                                        class="w-full flex items-center justify-between bg-deep-black/70 hover:bg-gaming-red/20 border border-gaming-red/30 rounded p-2 transition-all duration-200"
                                    >
                                        <span class="text-white/80"
                                            >Rédiger un article</span
                                        >
                                        <ArrowUpRightIcon
                                            class="h-4 w-4 text-white/60"
                                        />
                                    </Link>

                                    <Link
                                        :href="route('admin.comments')"
                                        class="w-full flex items-center justify-between bg-deep-black/70 hover:bg-gaming-red/20 border border-gaming-red/30 rounded p-2 transition-all duration-200"
                                    >
                                        <span class="text-white/80"
                                            >Modérer les commentaires</span
                                        >
                                        <ArrowUpRightIcon
                                            class="h-4 w-4 text-white/60"
                                        />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2.2 Carte: Graphique des revenus mensuels avec sélecteur d'année -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red/60 transition-all duration-300 rounded-lg shadow-lg p-4 md:p-6 relative"
                    >
                        <!-- Overlay de chargement pour le changement d'année -->
                        <div
                            v-if="isYearChanging"
                            class="absolute inset-0 bg-deep-black/80 flex items-center justify-center rounded-lg z-20"
                        >
                            <div
                                class="animate-spin rounded-full h-10 w-10 md:h-12 md:w-12 border-t-2 border-b-2 border-led-green"
                            ></div>
                        </div>

                        <!-- En-tête avec titre, sélecteur d'année et total -->
                        <div
                            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-3 md:mb-4"
                        >
                            <div
                                class="flex flex-wrap items-center gap-2 md:gap-4"
                            >
                                <h3
                                    class="text-base md:text-lg font-play font-semibold text-white"
                                >
                                    Revenus mensuels {{ selectedYear }}
                                </h3>

                                <!-- Sélecteur d'année avec dropdown -->
                                <div class="relative">
                                    <button
                                        @click="
                                            yearSelectorOpen = !yearSelectorOpen
                                        "
                                        class="flex items-center bg-deep-black/70 border border-gaming-red/30 rounded-md px-2 py-1 md:px-3 md:py-1.5 text-sm text-white font-medium hover:bg-gaming-red/20 transition-colors"
                                    >
                                        <span>{{ selectedYear }}</span>
                                        <ChevronDownIcon
                                            v-if="!yearSelectorOpen"
                                            class="ml-1 h-3 w-3 md:h-4 md:w-4"
                                        />
                                        <ChevronUpIcon
                                            v-else
                                            class="ml-1 h-3 w-3 md:h-4 md:w-4"
                                        />
                                    </button>

                                    <!-- Liste déroulante des années disponibles -->
                                    <div
                                        v-if="yearSelectorOpen"
                                        class="absolute top-full left-0 mt-1 w-32 md:w-40 bg-deep-black border border-gaming-red/30 rounded-md shadow-lg py-1 z-10"
                                    >
                                        <button
                                            v-for="year in availableYears"
                                            :key="year"
                                            @click="
                                                changeYear(year);
                                                yearSelectorOpen = false;
                                            "
                                            class="w-full text-left px-3 py-1.5 text-sm text-white hover:bg-gaming-red/20 transition-colors"
                                            :class="{
                                                'bg-gaming-red/10':
                                                    year === selectedYear,
                                            }"
                                        >
                                            {{ year }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Affichage du total et bouton d'édition -->
                            <div class="flex items-center">
                                <span
                                    class="text-base md:text-lg text-led-green font-bold mr-2 md:mr-4"
                                >
                                    Total:
                                    {{ totalRevenue.toLocaleString("fr-FR") }}
                                    €
                                </span>
                                <button
                                    v-if="!editingRevenue"
                                    @click="startEditingRevenue"
                                    class="bg-led-green text-white p-1.5 rounded-full hover:bg-led-green/80 transition-all"
                                    aria-label="Modifier les revenus"
                                >
                                    <PencilIcon class="h-3 w-3 md:h-4 md:w-4" />
                                </button>
                            </div>
                        </div>

                        <!-- Graphique des revenus mensuels -->
                        <div class="h-40 md:h-60 relative">
                            <Bar
                                :data="monthlyRevenueData"
                                :options="barChartOptions"
                            />

                            <!-- Formulaire d'édition des revenus (affiché en overlay) -->
                            <div
                                v-if="editingRevenue"
                                class="absolute inset-0 bg-deep-black/90 flex items-center justify-center rounded-lg p-4"
                            >
                                <div
                                    class="bg-deep-black border border-gaming-red p-4 md:p-6 rounded-lg shadow-lg w-full max-w-xs md:max-w-md"
                                >
                                    <h4
                                        class="text-white font-play font-semibold text-base md:text-lg mb-3 md:mb-4"
                                    >
                                        Éditer les revenus
                                    </h4>

                                    <form @submit.prevent="submitRevenueForm">
                                        <!-- Sélecteur de mois -->
                                        <div class="mb-3 md:mb-4">
                                            <label
                                                class="block text-white/80 text-xs md:text-sm font-medium mb-1 md:mb-2"
                                            >
                                                Mois
                                            </label>
                                            <select
                                                v-model="revenueForm.month"
                                                @change="
                                                    updateSelectedMonthRevenue
                                                "
                                                class="w-full bg-deep-black border border-gaming-red/50 rounded-md text-white p-2 text-sm focus:border-gaming-red focus:ring-1 focus:ring-gaming-red"
                                            >
                                                <option
                                                    v-for="(
                                                        month, index
                                                    ) in monthNames"
                                                    :key="index"
                                                    :value="index + 1"
                                                >
                                                    {{ month }}
                                                </option>
                                            </select>
                                        </div>

                                        <!-- Champ montant -->
                                        <div class="mb-5">
                                            <label
                                                class="block text-white/80 text-xs md:text-sm font-medium mb-1 md:mb-2"
                                            >
                                                Montant (€)
                                            </label>
                                            <div class="relative">
                                                <input
                                                    v-model="revenueForm.amount"
                                                    type="number"
                                                    min="0"
                                                    step="0.01"
                                                    class="w-full bg-deep-black border border-gaming-red/50 rounded-md text-white p-2 text-sm focus:border-gaming-red focus:ring-1 focus:ring-gaming-red"
                                                    placeholder="0.00"
                                                />
                                                <span
                                                    class="absolute right-3 top-2 text-white/60"
                                                    >€</span
                                                >
                                            </div>
                                            <!-- Champs cachés pour le mois et l'année -->
                                            <input
                                                type="hidden"
                                                v-model="revenueForm.month"
                                            />
                                            <input
                                                type="hidden"
                                                v-model="revenueForm.year"
                                            />
                                        </div>

                                        <!-- Boutons d'action -->
                                        <div class="flex justify-end space-x-3">
                                            <button
                                                type="button"
                                                @click="cancelEditRevenue"
                                                class="px-3 py-1.5 text-xs md:text-sm bg-gray-700 text-white rounded-md hover:bg-gray-600 transition-all"
                                            >
                                                Annuler
                                            </button>
                                            <button
                                                type="submit"
                                                class="px-3 py-1.5 text-xs md:text-sm bg-led-green text-white rounded-md hover:bg-led-green/80 transition-all"
                                            >
                                                Enregistrer
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: GRAPHIQUE DE DISTRIBUTION DES AVIS -->
                <div class="mb-6 md:mb-8">
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red/60 transition-all duration-300 rounded-lg shadow-lg p-4 md:p-6"
                    >
                        <h3
                            class="text-base md:text-lg font-play font-semibold text-white mb-3 md:mb-4"
                        >
                            Distribution des avis par note
                        </h3>
                        <!-- Graphique circulaire des avis -->
                        <div
                            class="h-48 md:h-64 flex items-center justify-center"
                        >
                            <Doughnut
                                :data="reviewsChartData"
                                :options="pieChartOptions"
                            />
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: ACTIVITÉ RÉCENTE (AVIS, PROJETS, COMMENTAIRES) -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6"
                >
                    <!-- 4.1 Derniers avis clients -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red/60 transition-all duration-300 rounded-lg shadow-lg p-4 md:p-6"
                    >
                        <div
                            class="flex justify-between items-center mb-3 md:mb-4"
                        >
                            <h3
                                class="text-base md:text-lg font-play font-semibold text-white"
                            >
                                Derniers avis clients
                            </h3>
                            <Link
                                :href="route('admin.reviews')"
                                class="text-xs md:text-sm text-led-green hover:text-led-green/80"
                            >
                                Voir tout
                            </Link>
                        </div>

                        <!-- Liste des avis récents -->
                        <div
                            v-if="recent_reviews && recent_reviews.length"
                            class="space-y-3 md:space-y-4"
                        >
                            <div
                                v-for="review in recent_reviews"
                                :key="review.id"
                                class="border-b border-gaming-red/20 pb-3 last:border-0"
                            >
                                <div class="flex items-start gap-2">
                                    <!-- Icône d'avis -->
                                    <div
                                        class="bg-gaming-red/20 rounded-full p-1.5 md:p-2 mt-1"
                                    >
                                        <StarIcon
                                            class="h-4 w-4 md:h-5 md:w-5 text-gaming-red"
                                        />
                                    </div>
                                    <!-- Détails de l'avis -->
                                    <div class="min-w-0">
                                        <div
                                            class="flex flex-wrap items-center gap-1 md:gap-2"
                                        >
                                            <span
                                                class="font-medium text-white text-sm md:text-base"
                                                >{{ review.name }}</span
                                            >
                                            <!-- Affichage des étoiles -->
                                            <span class="flex">
                                                <span
                                                    v-for="(
                                                        isFilled, index
                                                    ) in renderStars(
                                                        review.rating
                                                    )"
                                                    :key="index"
                                                >
                                                    <StarIcon
                                                        class="h-3 w-3 md:h-4 md:w-4"
                                                        :class="
                                                            isFilled
                                                                ? 'text-gaming-red'
                                                                : 'text-white/20'
                                                        "
                                                    />
                                                </span>
                                            </span>
                                        </div>
                                        <!-- Commentaire tronqué -->
                                        <p
                                            class="text-xs md:text-sm text-white/70 mt-1 break-words"
                                        >
                                            {{
                                                truncateText(
                                                    review.comment,
                                                    isMobile ? 40 : 60
                                                )
                                            }}
                                        </p>
                                        <!-- Date et statut d'approbation -->
                                        <div class="flex justify-between mt-2">
                                            <span
                                                class="text-xs text-white/50"
                                                >{{
                                                    formatDate(
                                                        review.created_at
                                                    )
                                                }}</span
                                            >
                                            <span
                                                v-if="!review.is_approved"
                                                class="text-xs text-gaming-red flex items-center gap-1"
                                            >
                                                <XMarkIcon class="h-3 w-3" /> En
                                                attente
                                            </span>
                                            <span
                                                v-else
                                                class="text-xs text-led-green flex items-center gap-1"
                                            >
                                                <CheckCircleIcon
                                                    class="h-3 w-3"
                                                />
                                                Approuvé
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Message affiché si aucun avis -->
                        <div v-else class="text-center text-white/50 py-4">
                            Aucun avis récent
                        </div>
                    </div>

                    <!-- 4.2 Derniers projets -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red/60 transition-all duration-300 rounded-lg shadow-lg p-4 md:p-6"
                    >
                        <div
                            class="flex justify-between items-center mb-3 md:mb-4"
                        >
                            <h3
                                class="text-base md:text-lg font-play font-semibold text-white"
                            >
                                Derniers projets
                            </h3>
                            <Link
                                :href="route('admin.portfolio')"
                                class="text-xs md:text-sm text-led-green hover:text-led-green/80"
                            >
                                Voir tout
                            </Link>
                        </div>

                        <!-- Liste des projets récents -->
                        <div
                            v-if="recent_projects && recent_projects.length"
                            class="space-y-3 md:space-y-4"
                        >
                            <div
                                v-for="project in recent_projects"
                                :key="project.id"
                                class="border-b border-gaming-red/20 pb-3 last:border-0"
                            >
                                <div class="flex items-start gap-2">
                                    <!-- Icône de projet -->
                                    <div
                                        class="bg-gaming-red/20 rounded-full p-1.5 md:p-2 mt-1"
                                    >
                                        <WrenchScrewdriverIcon
                                            class="h-4 w-4 md:h-5 md:w-5 text-gaming-red"
                                        />
                                    </div>
                                    <!-- Détails du projet -->
                                    <div class="min-w-0">
                                        <div
                                            class="flex flex-wrap items-center gap-1 md:gap-2"
                                        >
                                            <span
                                                class="font-medium text-white text-sm md:text-base"
                                                >{{ project.title }}</span
                                            >
                                            <!-- Badge si le projet est mis en avant -->
                                            <span
                                                v-if="project.is_featured"
                                                class="text-xs text-led-green bg-led-green/10 px-1.5 py-0.5 rounded"
                                            >
                                                Mis en avant
                                            </span>
                                        </div>
                                        <!-- Description tronquée -->
                                        <p
                                            class="text-xs md:text-sm text-white/70 mt-1 break-words"
                                        >
                                            {{
                                                truncateText(
                                                    project.description,
                                                    isMobile ? 40 : 80
                                                )
                                            }}
                                        </p>
                                        <!-- Date et statut de visibilité -->
                                        <div class="flex justify-between mt-2">
                                            <span
                                                class="text-xs text-white/50"
                                                >{{
                                                    formatDate(
                                                        project.created_at
                                                    )
                                                }}</span
                                            >
                                            <span
                                                v-if="!project.is_visible"
                                                class="text-xs text-gaming-red flex items-center gap-1"
                                            >
                                                <XMarkIcon class="h-3 w-3" />
                                                Masqué
                                            </span>
                                            <span
                                                v-else
                                                class="text-xs text-led-green flex items-center gap-1"
                                            >
                                                <CheckCircleIcon
                                                    class="h-3 w-3"
                                                />
                                                Visible
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Message affiché si aucun projet -->
                        <div v-else class="text-center text-white/50 py-4">
                            Aucun projet récent
                        </div>
                    </div>

                    <!-- 4.3 Dernières activités blog/commentaires -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red/60 transition-all duration-300 rounded-lg shadow-lg p-4 md:p-6"
                    >
                        <div
                            class="flex justify-between items-center mb-3 md:mb-4"
                        >
                            <h3
                                class="text-base md:text-lg font-play font-semibold text-white"
                            >
                                Activité récente
                            </h3>
                            <Link
                                :href="route('admin.blog')"
                                class="text-xs md:text-sm text-led-green hover:text-led-green/80"
                            >
                                Voir le blog
                            </Link>
                        </div>

                        <div class="space-y-3 md:space-y-4">
                            <!-- Articles de blog récents -->
                            <div
                                v-if="
                                    recent_blog_posts &&
                                    recent_blog_posts.length
                                "
                            >
                                <div
                                    v-for="post in recent_blog_posts"
                                    :key="post.id"
                                    class="border-b border-gaming-red/20 pb-3 last:border-0"
                                >
                                    <div class="flex items-start gap-2">
                                        <!-- Icône d'article -->
                                        <div
                                            class="bg-gaming-red/20 rounded-full p-1.5 md:p-2 mt-1"
                                        >
                                            <DocumentTextIcon
                                                class="h-4 w-4 md:h-5 md:w-5 text-gaming-red"
                                            />
                                        </div>
                                        <!-- Détails de l'article -->
                                        <div class="min-w-0">
                                            <div class="flex items-center">
                                                <span
                                                    class="font-medium text-white text-sm md:text-base"
                                                    >{{ post.title }}</span
                                                >
                                            </div>
                                            <!-- Extrait tronqué -->
                                            <p
                                                class="text-xs md:text-sm text-white/70 mt-1 break-words"
                                            >
                                                {{
                                                    truncateText(
                                                        post.excerpt ||
                                                            post.content,
                                                        isMobile ? 40 : 80
                                                    )
                                                }}
                                            </p>
                                            <!-- Date et statut de publication -->
                                            <div
                                                class="flex justify-between mt-2"
                                            >
                                                <span
                                                    class="text-xs text-white/50"
                                                    >{{
                                                        formatDate(
                                                            post.created_at
                                                        )
                                                    }}</span
                                                >
                                                <span
                                                    v-if="!post.is_published"
                                                    class="text-xs text-gaming-red flex items-center gap-1"
                                                >
                                                    <XMarkIcon
                                                        class="h-3 w-3"
                                                    />
                                                    Brouillon
                                                </span>
                                                <span
                                                    v-else
                                                    class="text-xs text-led-green flex items-center gap-1"
                                                >
                                                    <CheckCircleIcon
                                                        class="h-3 w-3"
                                                    />
                                                    Publié
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Commentaires récents -->
                            <div
                                v-if="recent_comments && recent_comments.length"
                            >
                                <div
                                    v-for="comment in recent_comments"
                                    :key="comment.id"
                                    class="border-b border-gaming-red/20 pb-3 last:border-0"
                                >
                                    <div class="flex items-start gap-2">
                                        <!-- Icône de commentaire -->
                                        <div
                                            class="bg-gaming-red/20 rounded-full p-1.5 md:p-2 mt-1"
                                        >
                                            <ChatBubbleLeftRightIcon
                                                class="h-4 w-4 md:h-5 md:w-5 text-gaming-red"
                                            />
                                        </div>
                                        <!-- Détails du commentaire -->
                                        <div class="min-w-0">
                                            <div
                                                class="flex flex-wrap items-center gap-1"
                                            >
                                                <span
                                                    class="font-medium text-white text-sm md:text-base"
                                                    >{{ comment.name }}</span
                                                >
                                                <span
                                                    class="text-xs text-white/50"
                                                    >sur "{{
                                                        truncateText(
                                                            comment.blog_post
                                                                .title,
                                                            20
                                                        )
                                                    }}"</span
                                                >
                                            </div>
                                            <!-- Contenu tronqué -->
                                            <p
                                                class="text-xs md:text-sm text-white/70 mt-1 break-words"
                                            >
                                                {{
                                                    truncateText(
                                                        comment.content,
                                                        isMobile ? 40 : 80
                                                    )
                                                }}
                                            </p>
                                            <!-- Date et statut d'approbation -->
                                            <div
                                                class="flex justify-between mt-2"
                                            >
                                                <span
                                                    class="text-xs text-white/50"
                                                    >{{
                                                        formatDate(
                                                            comment.created_at
                                                        )
                                                    }}</span
                                                >
                                                <span
                                                    v-if="!comment.is_approved"
                                                    class="text-xs text-gaming-red flex items-center gap-1"
                                                >
                                                    <XMarkIcon
                                                        class="h-3 w-3"
                                                    />
                                                    En attente
                                                </span>
                                                <span
                                                    v-else
                                                    class="text-xs text-led-green flex items-center gap-1"
                                                >
                                                    <CheckCircleIcon
                                                        class="h-3 w-3"
                                                    />
                                                    Approuvé
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Message affiché si aucune activité récente -->
                            <div
                                v-if="
                                    (!recent_blog_posts ||
                                        !recent_blog_posts.length) &&
                                    (!recent_comments ||
                                        !recent_comments.length)
                                "
                                class="text-center text-white/50 py-4"
                            >
                                Aucune activité récente
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
/**
 * Styles du Dashboard
 * Utilisant les variables CSS définies dans app.css
 */

/* Couleurs de fond */
.bg-deep-black {
    background-color: var(--color-deep-black);
}

.bg-led-green {
    background-color: var(--color-led-green);
}

/* Couleurs de bordure */
.border-gaming-red {
    border-color: var(--color-gaming-red);
}

/* Couleurs de texte */
.text-gaming-red {
    color: var(--color-gaming-red);
}

.text-led-green {
    color: var(--color-led-green);
}

/* Typographie */
.font-play {
    font-family: "Play", sans-serif;
}

/* Utilitaires de texte */
.break-words {
    overflow-wrap: break-word;
    word-wrap: break-word;
    -ms-word-break: break-all;
    word-break: break-all;
    word-break: break-word;
}

/* Media Queries pour l'adaptation responsive */
@media (max-width: 768px) {
    /* Ajustement de la hauteur des graphiques en responsive */
    .chartjs-render-monitor {
        height: auto !important;
    }
}
</style>
