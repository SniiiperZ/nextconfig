<script setup>
import { ref, onMounted, computed, watch } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
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
import axios from "axios";
import { Link, router, useForm, usePage } from "@inertiajs/vue3";
import {
    ComputerDesktopIcon,
    ChatBubbleLeftRightIcon,
    StarIcon,
    DocumentTextIcon,
    CurrencyEuroIcon,
    ArrowUpRightIcon,
    WrenchScrewdriverIcon,
    XMarkIcon,
    CheckCircleIcon,
    PencilIcon,
    ViewfinderCircleIcon,
    CalendarIcon,
    QuestionMarkCircleIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    ChevronDownIcon,
    ChevronUpIcon,
} from "@heroicons/vue/24/outline";

// Enregistrer les composants ChartJS nécessaires
ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
    ArcElement
);

const props = defineProps({
    stats: Object,
    recent_reviews: Array,
    recent_projects: Array,
    recent_comments: Array,
    recent_blog_posts: Array,
});

// Notification de succès avec durée personnalisée
const page = usePage();
const flashMessage = ref(page.props.flash.success || null);
const showFlashMessage = ref(!!page.props.flash.success);

// Masquer le message après un délai (8 secondes)
if (flashMessage.value) {
    setTimeout(() => {
        showFlashMessage.value = false;
    }, 8000); // 8 secondes
}

// Masquer manuellement le message
const hideFlashMessage = () => {
    showFlashMessage.value = false;
};

// Stats calculées
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

// Sélecteur d'année amélioré
const yearSelectorOpen = ref(false);

// État de chargement pour le changement d'année
const isYearChanging = ref(false);

// Année sélectionnée pour les revenus - modification pour permettre toute année
const selectedYear = ref(props.stats.selected_year || new Date().getFullYear());
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

// Données sur la période actuelle - indépendantes de l'année sélectionnée
const today = new Date();
const currentMonthName = today.toLocaleString("fr-FR", { month: "long" });
const currentYearNumber = today.getFullYear();
const currentMonthRevenue = computed(
    () => props.stats.current_month_revenue || 0
);

// Fonction pour changer d'année avec indicateur de chargement
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

// Données pour les graphiques
const reviewsChartData = ref({
    labels: ["5★", "4★", "3★", "2★", "1★"],
    datasets: [
        {
            backgroundColor: [
                "#10b981",
                "#a3e635",
                "#fbbf24",
                "#f97316",
                "#ef4444",
            ],
            data: props.stats.reviews_rating_distribution || [45, 30, 15, 7, 3],
            borderWidth: 0,
        },
    ],
});

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

// Formulaire pour mettre à jour les revenus
const editingRevenue = ref(false);
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

const revenueForm = useForm({
    month: new Date().getMonth() + 1, // Mois courant (1-12)
    year: selectedYear.value, // Utiliser l'année sélectionnée
    amount: 0,
});

// Mettre à jour le formulaire quand l'année sélectionnée change
watch(selectedYear, (newValue) => {
    revenueForm.year = newValue;
});

// Options des graphiques
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
            },
        },
    },
};

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
            },
        },
        x: {
            grid: {
                display: false,
            },
            ticks: {
                color: "rgba(255, 255, 255, 0.7)",
            },
        },
    },
    plugins: {
        legend: {
            labels: {
                color: "rgba(255, 255, 255, 0.7)",
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

// Gestion du formulaire de revenus
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

const submitRevenueForm = () => {
    revenueForm.post(route("admin.update-monthly-revenue"), {
        onSuccess: () => {
            editingRevenue.value = false;
            // Rafraîchir la page complètement pour obtenir les nouvelles données du serveur
            window.location.reload();
        },
    });
};

const cancelEditRevenue = () => {
    editingRevenue.value = false;
    revenueForm.reset();
};

// Mise à jour du montant quand le mois change
const updateSelectedMonthRevenue = () => {
    if (props.stats.monthly_revenue) {
        revenueForm.amount =
            props.stats.monthly_revenue[revenueForm.month - 1] || 0;
    }
};

// Formatage des dates
const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};

// Tronquer le texte
const truncateText = (text, length = 60) => {
    if (!text) return "";
    return text.length > length ? text.substring(0, length) + "..." : text;
};

// Rendu des étoiles pour les avis
const renderStars = (rating) => {
    return Array.from({ length: 5 }, (_, i) => i < rating);
};
</script>

<template>
    <AdminLayout title="Dashboard">
        <template #header>
            <div class="flex justify-between items-center">
                <h2
                    class="font-play font-semibold text-xl text-white leading-tight"
                >
                    Dashboard Admin - NextConfig
                </h2>

                <!-- Notification de succès améliorée -->
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
                        class="bg-led-green/20 border border-led-green text-led-green px-4 py-2 rounded-md flex items-center"
                    >
                        <CheckCircleIcon class="w-5 h-5 mr-2" />
                        <span>{{ flashMessage }}</span>
                        <button
                            @click="hideFlashMessage"
                            class="ml-3 text-led-green/80 hover:text-led-green"
                        >
                            <XMarkIcon class="w-4 h-4" />
                        </button>
                    </div>
                </transition>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Statistiques principales -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
                >
                    <!-- Projets réalisés -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red transition-all duration-300 rounded-lg shadow-lg overflow-hidden"
                    >
                        <div class="p-5">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p
                                        class="text-white/70 text-sm font-medium"
                                    >
                                        Projets réalisés
                                    </p>
                                    <h3
                                        class="text-3xl font-play font-bold text-white mt-1"
                                    >
                                        {{ totalProjectsCount }}
                                    </h3>
                                </div>
                                <div class="bg-gaming-red/20 rounded-full p-4">
                                    <ComputerDesktopIcon
                                        class="h-8 w-8 text-gaming-red"
                                    />
                                </div>
                            </div>
                            <div class="mt-4 text-sm">
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

                    <!-- Avis clients -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red transition-all duration-300 rounded-lg shadow-lg overflow-hidden"
                    >
                        <div class="p-5">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p
                                        class="text-white/70 text-sm font-medium"
                                    >
                                        Avis clients
                                    </p>
                                    <h3
                                        class="text-3xl font-play font-bold text-white mt-1"
                                    >
                                        {{ totalReviewsCount }}
                                    </h3>
                                    <span
                                        v-if="pendingReviewsCount > 0"
                                        class="ml-2 text-sm text-gaming-red"
                                        >({{ pendingReviewsCount }} en
                                        attente)</span
                                    >
                                </div>
                                <div class="bg-gaming-red/20 rounded-full p-4">
                                    <StarIcon class="h-8 w-8 text-gaming-red" />
                                </div>
                            </div>
                            <div class="mt-4 text-sm">
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

                    <!-- Articles de blog -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red transition-all duration-300 rounded-lg shadow-lg overflow-hidden"
                    >
                        <div class="p-5">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p
                                        class="text-white/70 text-sm font-medium"
                                    >
                                        Articles de blog
                                    </p>
                                    <h3
                                        class="text-3xl font-play font-bold text-white mt-1"
                                    >
                                        {{ totalBlogPostsCount }}
                                    </h3>
                                </div>
                                <div class="bg-gaming-red/20 rounded-full p-4">
                                    <DocumentTextIcon
                                        class="h-8 w-8 text-gaming-red"
                                    />
                                </div>
                            </div>
                            <div class="mt-4 text-sm">
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

                    <!-- Commentaires -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red transition-all duration-300 rounded-lg shadow-lg overflow-hidden"
                    >
                        <div class="p-5">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p
                                        class="text-white/70 text-sm font-medium"
                                    >
                                        Commentaires
                                    </p>
                                    <h3
                                        class="text-3xl font-play font-bold text-white mt-1"
                                    >
                                        {{ totalCommentsCount }}
                                    </h3>
                                    <span
                                        v-if="pendingCommentsCount > 0"
                                        class="ml-2 text-sm text-gaming-red"
                                        >({{ pendingCommentsCount }} en
                                        attente)</span
                                    >
                                </div>
                                <div class="bg-gaming-red/20 rounded-full p-4">
                                    <ChatBubbleLeftRightIcon
                                        class="h-8 w-8 text-gaming-red"
                                    />
                                </div>
                            </div>
                            <div class="mt-4 text-sm">
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

                <!-- Statistiques et données du site -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Informations du site -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red/60 transition-all duration-300 rounded-lg shadow-lg p-6"
                    >
                        <h3
                            class="text-lg font-play font-semibold text-white mb-4"
                        >
                            Informations NextConfig
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Stats de la période -->
                            <div
                                class="bg-deep-black/50 border border-gaming-red/20 rounded-lg p-4"
                            >
                                <div class="flex items-center mb-3">
                                    <CalendarIcon
                                        class="h-5 w-5 text-gaming-red mr-2"
                                    />
                                    <h4 class="text-white font-medium">
                                        Période actuelle
                                    </h4>
                                </div>

                                <div class="space-y-3">
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

                            <!-- Actions rapides -->
                            <div
                                class="bg-deep-black/50 border border-gaming-red/20 rounded-lg p-4"
                            >
                                <div class="flex items-center mb-3">
                                    <ViewfinderCircleIcon
                                        class="h-5 w-5 text-gaming-red mr-2"
                                    />
                                    <h4 class="text-white font-medium">
                                        Actions rapides
                                    </h4>
                                </div>

                                <div class="space-y-2">
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

                    <!-- Revenus mensuels (éditable) avec sélecteur d'année -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red/60 transition-all duration-300 rounded-lg shadow-lg p-6 relative"
                    >
                        <!-- Overlay de chargement -->
                        <div
                            v-if="isYearChanging"
                            class="absolute inset-0 bg-deep-black/80 flex items-center justify-center rounded-lg z-20"
                        >
                            <div
                                class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-led-green"
                            ></div>
                        </div>

                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center space-x-4">
                                <h3
                                    class="text-lg font-play font-semibold text-white"
                                >
                                    Revenus mensuels {{ selectedYear }}
                                </h3>

                                <!-- Sélecteur d'année avec dropdown plutôt que simple navigation -->
                                <div class="relative">
                                    <button
                                        @click="
                                            yearSelectorOpen = !yearSelectorOpen
                                        "
                                        class="flex items-center bg-deep-black/70 border border-gaming-red/30 rounded-md px-3 py-1.5 text-white font-medium hover:bg-gaming-red/20 transition-colors"
                                    >
                                        <span>{{ selectedYear }}</span>
                                        <ChevronDownIcon
                                            v-if="!yearSelectorOpen"
                                            class="ml-2 h-4 w-4"
                                        />
                                        <ChevronUpIcon
                                            v-else
                                            class="ml-2 h-4 w-4"
                                        />
                                    </button>

                                    <div
                                        v-if="yearSelectorOpen"
                                        class="absolute top-full left-0 mt-1 w-40 bg-deep-black border border-gaming-red/30 rounded-md shadow-lg py-1 z-10"
                                    >
                                        <button
                                            v-for="year in availableYears"
                                            :key="year"
                                            @click="
                                                changeYear(year);
                                                yearSelectorOpen = false;
                                            "
                                            class="w-full text-left px-4 py-2 text-white hover:bg-gaming-red/20 transition-colors"
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

                            <div class="flex items-center">
                                <span
                                    class="text-lg text-led-green font-bold mr-4"
                                >
                                    Total:
                                    {{ totalRevenue.toLocaleString("fr-FR") }}
                                    €
                                </span>
                                <button
                                    v-if="!editingRevenue"
                                    @click="startEditingRevenue"
                                    class="bg-led-green text-white p-1.5 rounded-full hover:bg-led-green/80 transition-all"
                                >
                                    <PencilIcon class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                        <div class="h-60 relative">
                            <Bar
                                :data="monthlyRevenueData"
                                :options="barChartOptions"
                            />

                            <!-- Formulaire d'édition des revenus -->
                            <div
                                v-if="editingRevenue"
                                class="absolute inset-0 bg-deep-black/90 flex items-center justify-center rounded-lg"
                            >
                                <div
                                    class="bg-deep-black border border-gaming-red p-6 rounded-lg shadow-lg w-full max-w-md"
                                >
                                    <h4
                                        class="text-white font-play font-semibold text-lg mb-4"
                                    >
                                        Éditer les revenus
                                    </h4>

                                    <form @submit.prevent="submitRevenueForm">
                                        <div class="mb-4">
                                            <label
                                                class="block text-white/80 text-sm font-medium mb-2"
                                            >
                                                Mois
                                            </label>
                                            <select
                                                v-model="revenueForm.month"
                                                @change="
                                                    updateSelectedMonthRevenue
                                                "
                                                class="w-full bg-deep-black border border-gaming-red/50 rounded-md text-white p-2 focus:border-gaming-red focus:ring-1 focus:ring-gaming-red"
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

                                        <div class="mb-6">
                                            <label
                                                class="block text-white/80 text-sm font-medium mb-2"
                                            >
                                                Montant (€)
                                            </label>
                                            <div class="relative">
                                                <input
                                                    v-model="revenueForm.amount"
                                                    type="number"
                                                    min="0"
                                                    step="0.01"
                                                    class="w-full bg-deep-black border border-gaming-red/50 rounded-md text-white p-2 focus:border-gaming-red focus:ring-1 focus:ring-gaming-red"
                                                    placeholder="0.00"
                                                />
                                                <span
                                                    class="absolute right-3 top-2 text-white/60"
                                                    >€</span
                                                >
                                            </div>
                                            <input
                                                type="hidden"
                                                v-model="revenueForm.month"
                                            />
                                            <input
                                                type="hidden"
                                                v-model="revenueForm.year"
                                            />
                                        </div>

                                        <div class="flex justify-end space-x-3">
                                            <button
                                                type="button"
                                                @click="cancelEditRevenue"
                                                class="px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600 transition-all"
                                            >
                                                Annuler
                                            </button>
                                            <button
                                                type="submit"
                                                class="px-4 py-2 bg-led-green text-white rounded-md hover:bg-led-green/80 transition-all"
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

                <!-- Distribution des avis -->
                <div class="mb-8">
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red/60 transition-all duration-300 rounded-lg shadow-lg p-6"
                    >
                        <h3
                            class="text-lg font-play font-semibold text-white mb-4"
                        >
                            Distribution des avis par note
                        </h3>
                        <div class="h-64 flex items-center justify-center">
                            <Doughnut
                                :data="reviewsChartData"
                                :options="pieChartOptions"
                            />
                        </div>
                    </div>
                </div>

                <!-- Activité récente -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Derniers avis clients -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red/60 transition-all duration-300 rounded-lg shadow-lg p-6"
                    >
                        <div class="flex justify-between items-center mb-4">
                            <h3
                                class="text-lg font-play font-semibold text-white"
                            >
                                Derniers avis clients
                            </h3>
                            <Link
                                :href="route('admin.reviews')"
                                class="text-sm text-led-green hover:text-led-green/80"
                            >
                                Voir tout
                            </Link>
                        </div>

                        <div
                            v-if="recent_reviews && recent_reviews.length"
                            class="space-y-4"
                        >
                            <div
                                v-for="review in recent_reviews"
                                :key="review.id"
                                class="border-b border-gaming-red/20 pb-3 last:border-0"
                            >
                                <div class="flex items-start gap-2">
                                    <div
                                        class="bg-gaming-red/20 rounded-full p-2 mt-1"
                                    >
                                        <StarIcon
                                            class="h-5 w-5 text-gaming-red"
                                        />
                                    </div>
                                    <div>
                                        <div class="flex items-center">
                                            <span
                                                class="font-medium text-white"
                                                >{{ review.name }}</span
                                            >
                                            <span class="flex ml-2">
                                                <span
                                                    v-for="(
                                                        isFilled, index
                                                    ) in renderStars(
                                                        review.rating
                                                    )"
                                                    :key="index"
                                                >
                                                    <StarIcon
                                                        class="h-4 w-4"
                                                        :class="
                                                            isFilled
                                                                ? 'text-gaming-red'
                                                                : 'text-white/20'
                                                        "
                                                    />
                                                </span>
                                            </span>
                                        </div>
                                        <p class="text-sm text-white/70 mt-1">
                                            {{ truncateText(review.comment) }}
                                        </p>
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
                        <div v-else class="text-center text-white/50 py-4">
                            Aucun avis récent
                        </div>
                    </div>

                    <!-- Derniers projets -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red/60 transition-all duration-300 rounded-lg shadow-lg p-6"
                    >
                        <div class="flex justify-between items-center mb-4">
                            <h3
                                class="text-lg font-play font-semibold text-white"
                            >
                                Derniers projets
                            </h3>
                            <Link
                                :href="route('admin.portfolio')"
                                class="text-sm text-led-green hover:text-led-green/80"
                            >
                                Voir tout
                            </Link>
                        </div>

                        <div
                            v-if="recent_projects && recent_projects.length"
                            class="space-y-4"
                        >
                            <div
                                v-for="project in recent_projects"
                                :key="project.id"
                                class="border-b border-gaming-red/20 pb-3 last:border-0"
                            >
                                <div class="flex items-start gap-2">
                                    <div
                                        class="bg-gaming-red/20 rounded-full p-2 mt-1"
                                    >
                                        <WrenchScrewdriverIcon
                                            class="h-5 w-5 text-gaming-red"
                                        />
                                    </div>
                                    <div>
                                        <div class="flex items-center">
                                            <span
                                                class="font-medium text-white"
                                                >{{ project.title }}</span
                                            >
                                            <span
                                                v-if="project.is_featured"
                                                class="ml-2 text-xs text-led-green bg-led-green/10 px-2 py-0.5 rounded"
                                            >
                                                Mis en avant
                                            </span>
                                        </div>
                                        <p class="text-sm text-white/70 mt-1">
                                            {{
                                                truncateText(
                                                    project.description,
                                                    80
                                                )
                                            }}
                                        </p>
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
                        <div v-else class="text-center text-white/50 py-4">
                            Aucun projet récent
                        </div>
                    </div>

                    <!-- Dernières activités blog/commentaires -->
                    <div
                        class="bg-deep-black border border-gaming-red/30 hover:border-gaming-red/60 transition-all duration-300 rounded-lg shadow-lg p-6"
                    >
                        <div class="flex justify-between items-center mb-4">
                            <h3
                                class="text-lg font-play font-semibold text-white"
                            >
                                Activité récente
                            </h3>
                            <Link
                                :href="route('admin.blog')"
                                class="text-sm text-led-green hover:text-led-green/80"
                            >
                                Voir le blog
                            </Link>
                        </div>

                        <div class="space-y-4">
                            <!-- Articles récents -->
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
                                        <div
                                            class="bg-gaming-red/20 rounded-full p-2 mt-1"
                                        >
                                            <DocumentTextIcon
                                                class="h-5 w-5 text-gaming-red"
                                            />
                                        </div>
                                        <div>
                                            <div class="flex items-center">
                                                <span
                                                    class="font-medium text-white"
                                                    >{{ post.title }}</span
                                                >
                                            </div>
                                            <p
                                                class="text-sm text-white/70 mt-1"
                                            >
                                                {{
                                                    truncateText(
                                                        post.excerpt ||
                                                            post.content,
                                                        80
                                                    )
                                                }}
                                            </p>
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
                                        <div
                                            class="bg-gaming-red/20 rounded-full p-2 mt-1"
                                        >
                                            <ChatBubbleLeftRightIcon
                                                class="h-5 w-5 text-gaming-red"
                                            />
                                        </div>
                                        <div>
                                            <div class="flex items-center">
                                                <span
                                                    class="font-medium text-white"
                                                    >{{ comment.name }}</span
                                                >
                                                <span
                                                    class="ml-2 text-xs text-white/50"
                                                    >sur "{{
                                                        comment.blog_post.title
                                                    }}"</span
                                                >
                                            </div>
                                            <p
                                                class="text-sm text-white/70 mt-1"
                                            >
                                                {{
                                                    truncateText(
                                                        comment.content,
                                                        80
                                                    )
                                                }}
                                            </p>
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
.bg-deep-black {
    background-color: #121212;
}

.border-gaming-red {
    border-color: #ef4444;
}

.text-gaming-red {
    color: #ef4444;
}

.text-led-green {
    color: #10b981;
}

.bg-led-green {
    background-color: #10b981;
}

.font-play {
    font-family: "Play", sans-serif;
}
</style>
