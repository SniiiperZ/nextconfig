<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import HeroSection from "@/Components/HeroSection.vue";
import HeroTitle from "@/Components/HeroTitle.vue";
import { Link } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUpdate } from "vue";

defineProps({
    projects: Array,
});

const modalRefs = ref({});
const currentImageIndex = ref({});

const showModal = (projectId) => {
    if (modalRefs.value[projectId]) {
        // Réinitialiser l'index d'image à 0 quand on ouvre la modal
        currentImageIndex.value[projectId] = 0;
        modalRefs.value[projectId].showModal();
    }
};

const closeModal = (projectId) => {
    if (modalRefs.value[projectId]) {
        modalRefs.value[projectId].close();
    }
};

// Navigation dans le carousel
const prevImage = (project) => {
    if (currentImageIndex.value[project.id] > 0) {
        currentImageIndex.value[project.id]--;
    } else {
        // Boucler à la dernière image
        currentImageIndex.value[project.id] = project.images.length - 1;
    }
};

const nextImage = (project) => {
    if (currentImageIndex.value[project.id] < project.images.length - 1) {
        currentImageIndex.value[project.id]++;
    } else {
        // Boucler à la première image
        currentImageIndex.value[project.id] = 0;
    }
};

const setImage = (projectId, index) => {
    currentImageIndex.value[projectId] = index;
};

// Fonction pour configurer les écouteurs d'événements de clic à l'extérieur
const setupOutsideClickListeners = () => {
    Object.keys(modalRefs.value).forEach((projectId) => {
        const modal = modalRefs.value[projectId];
        if (modal) {
            // Supprimer les anciens écouteurs pour éviter les doublons
            modal.removeEventListener("click", modal._clickHandler);

            // Définir un nouveau gestionnaire d'événements
            modal._clickHandler = (e) => {
                if (e.target === modal) {
                    closeModal(projectId);
                }
            };

            // Ajouter l'écouteur d'événements
            modal.addEventListener("click", modal._clickHandler);
        }
    });
};

// Ajouter les écouteurs après le montage du composant
onMounted(() => {
    setupOutsideClickListeners();
});

// Mettre à jour les écouteurs avant chaque mise à jour du DOM
onBeforeUpdate(() => {
    // Nettoyer les références existantes pour éviter les fuites de mémoire
    Object.keys(modalRefs.value).forEach((key) => {
        const modal = modalRefs.value[key];
        if (modal && modal._clickHandler) {
            modal.removeEventListener("click", modal._clickHandler);
        }
    });
});

// Fonction pour obtenir l'image à afficher
const getImageToShow = (project) => {
    // Si le projet a des images dans le tableau images
    if (project.images && project.images.length > 0) {
        const imageIndex = currentImageIndex.value[project.id] || 0;
        return `/storage/${project.images[imageIndex].image_path}`;
    }
    // Retour à l'ancienne image_path pour compatibilité
    else if (project.image_path) {
        return `/storage/${project.image_path}`;
    }
    // Pas d'image
    return null;
};
</script>

<template>
    <PublicLayout
        title="Réalisations"
        description="Découvrez nos réalisations de PC Gaming sur mesure. Configurations personnalisées, watercooling, RGB et performances optimales pour les joueurs exigeants."
        keywords="portfolio PC gaming, réalisations PC sur mesure, projets assemblage PC, configuration gaming personnalisée, watercooling, RGB"
    >
        <!-- Hero Section pour la page Portfolio avec le composant HeroSection -->
        <HeroSection
            title="Nos Réalisations"
            subtitle="Découvrez nos configurations PC gaming sur mesure"
            backgroundImage="/images/background.jpg"
            height="min-h-50vh"
        />

        <div class="py-8 sm:py-10 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Projets mis en avant -->
                <div
                    v-if="projects.some((project) => project.is_featured)"
                    class="mb-8 sm:mb-12 md:mb-16"
                >
                    <!-- Utilisation du composant HeroTitle -->
                    <HeroTitle
                        title="Projets Phares"
                        :centered="true"
                        marginBottom="mb-8 sm:mb-10 md:mb-12"
                    />

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8"
                    >
                        <div
                            v-for="project in projects.filter(
                                (p) => p.is_featured
                            )"
                            :key="project.id"
                            class="bg-deep-black border border-gaming-red rounded-lg overflow-hidden project-card"
                        >
                            <div
                                class="h-40 sm:h-48 md:h-56 overflow-hidden relative card-image-container"
                            >
                                <!-- Utiliser la première image du projet si disponible -->
                                <img
                                    v-if="
                                        project.images &&
                                        project.images.length > 0
                                    "
                                    :src="`/storage/${project.images[0].image_path}`"
                                    :alt="project.title"
                                    class="w-full h-full object-cover transition-transform duration-500"
                                />
                                <img
                                    v-else-if="project.image_path"
                                    :src="`/storage/${project.image_path}`"
                                    :alt="project.title"
                                    class="w-full h-full object-cover transition-transform duration-500"
                                />
                                <div
                                    v-else
                                    class="w-full h-full bg-gray-800 flex items-center justify-center"
                                >
                                    <span class="text-gray-500"
                                        >Pas d'image</span
                                    >
                                </div>

                                <!-- Overlay avec effet de brillance -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-deep-black to-transparent opacity-0 hover:opacity-70 transition-opacity duration-300 flex items-end justify-center p-4"
                                >
                                    <button
                                        @click="showModal(project.id)"
                                        class="px-3 py-1.5 sm:px-4 sm:py-2 bg-gaming-red text-white text-xs sm:text-sm rounded-md transform translate-y-4 opacity-0 transition-all duration-300 view-details-btn"
                                    >
                                        Voir les détails
                                    </button>
                                </div>
                            </div>

                            <div class="p-4 sm:p-6">
                                <h3
                                    class="text-xl sm:text-2xl font-play text-led-green mb-2 sm:mb-3"
                                >
                                    {{ project.title }}
                                </h3>
                                <p
                                    class="text-white/80 text-sm sm:text-base mb-3 sm:mb-4 line-clamp-2"
                                >
                                    {{ project.description }}
                                </p>
                                <button
                                    @click="showModal(project.id)"
                                    class="inline-flex items-center text-sm sm:text-base text-gaming-red hover:text-white transition group"
                                >
                                    <span class="mr-1 sm:mr-2"
                                        >Voir les détails</span
                                    >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 sm:h-5 sm:w-5 transform group-hover:translate-x-1 transition-transform duration-200"
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
                                </button>

                                <!-- Modal pour les détails -->
                                <dialog
                                    :ref="
                                        (el) => {
                                            if (el) modalRefs[project.id] = el;
                                            else delete modalRefs[project.id];
                                        }
                                    "
                                    class="modal rounded-lg bg-deep-black border border-gaming-red p-0 w-full max-w-5xl"
                                >
                                    <div class="p-4 sm:p-6">
                                        <div
                                            class="flex justify-between items-center mb-4 sm:mb-6"
                                        >
                                            <h3
                                                class="text-xl sm:text-2xl md:text-3xl font-play text-led-green glow-text pr-4"
                                            >
                                                {{ project.title }}
                                            </h3>
                                            <button
                                                @click="closeModal(project.id)"
                                                class="text-white hover:text-gaming-red transition-colors"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 sm:h-6 sm:w-6 md:h-7 md:w-7"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"
                                                    />
                                                </svg>
                                            </button>
                                        </div>
                                        <div
                                            class="flex flex-col lg:flex-row gap-4 sm:gap-6 md:gap-8"
                                        >
                                            <!-- Carousel d'images -->
                                            <div class="lg:w-1/2">
                                                <div class="relative">
                                                    <!-- Image principale -->
                                                    <div
                                                        class="w-full h-60 sm:h-72 md:h-80 overflow-hidden rounded-lg bg-gray-800 border border-gaming-red/50 shadow-glow"
                                                    >
                                                        <img
                                                            v-if="
                                                                getImageToShow(
                                                                    project
                                                                )
                                                            "
                                                            :src="
                                                                getImageToShow(
                                                                    project
                                                                )
                                                            "
                                                            :alt="project.title"
                                                            class="w-full h-full object-contain transition-opacity duration-300"
                                                        />
                                                        <div
                                                            v-else
                                                            class="w-full h-full flex items-center justify-center"
                                                        >
                                                            <span
                                                                class="text-gray-500"
                                                                >Pas
                                                                d'image</span
                                                            >
                                                        </div>
                                                    </div>

                                                    <!-- Boutons de navigation (uniquement si plusieurs images) -->
                                                    <div
                                                        v-if="
                                                            project.images &&
                                                            project.images
                                                                .length > 1
                                                        "
                                                        class="absolute inset-y-0 left-0 flex items-center"
                                                    >
                                                        <button
                                                            @click="
                                                                prevImage(
                                                                    project
                                                                )
                                                            "
                                                            class="carousel-btn left-2 bg-deep-black/90 hover:bg-gaming-red text-white p-1.5 sm:p-2 rounded-full transition-colors duration-200 focus:outline-none"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 sm:h-5 sm:w-5 md:h-6 md:w-6"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 19l-7-7 7-7"
                                                                />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <div
                                                        v-if="
                                                            project.images &&
                                                            project.images
                                                                .length > 1
                                                        "
                                                        class="absolute inset-y-0 right-0 flex items-center"
                                                    >
                                                        <button
                                                            @click="
                                                                nextImage(
                                                                    project
                                                                )
                                                            "
                                                            class="carousel-btn right-2 bg-deep-black/90 hover:bg-gaming-red text-white p-1.5 sm:p-2 rounded-full transition-colors duration-200 focus:outline-none"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 sm:h-5 sm:w-5 md:h-6 md:w-6"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 5l7 7-7 7"
                                                                />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Miniatures améliorées -->
                                                <div
                                                    v-if="
                                                        project.images &&
                                                        project.images.length >
                                                            1
                                                    "
                                                    class="flex mt-3 sm:mt-4 space-x-2 sm:space-x-3 overflow-x-auto py-2 px-1 custom-scrollbar"
                                                >
                                                    <button
                                                        v-for="(
                                                            image, index
                                                        ) in project.images"
                                                        :key="index"
                                                        @click="
                                                            setImage(
                                                                project.id,
                                                                index
                                                            )
                                                        "
                                                        class="thumbnail-btn w-12 h-12 sm:w-16 sm:h-16 flex-shrink-0 rounded-md overflow-hidden focus:outline-none transition-transform duration-200"
                                                        :class="
                                                            currentImageIndex[
                                                                project.id
                                                            ] === index
                                                                ? 'border-2 border-led-green scale-110 shadow-glow-sm'
                                                                : 'border border-gray-700 hover:border-gaming-red'
                                                        "
                                                    >
                                                        <img
                                                            :src="`/storage/${image.image_path}`"
                                                            :alt="`${
                                                                project.title
                                                            } - Image ${
                                                                index + 1
                                                            }`"
                                                            class="w-full h-full object-cover"
                                                        />
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="lg:w-1/2 mt-4 lg:mt-0">
                                                <div
                                                    class="mb-4 sm:mb-6 bg-deep-black/50 rounded-lg p-3 sm:p-4 border border-gaming-red/30 animate-fade-in"
                                                >
                                                    <h4
                                                        class="text-lg sm:text-xl font-play text-led-green mb-2 sm:mb-3"
                                                    >
                                                        Description
                                                    </h4>
                                                    <p
                                                        class="text-white/90 text-sm sm:text-base leading-relaxed"
                                                    >
                                                        {{
                                                            project.description
                                                        }}
                                                    </p>
                                                </div>

                                                <div
                                                    v-if="project.specs"
                                                    class="bg-deep-black/50 rounded-lg p-3 sm:p-4 border border-gaming-red/30 animate-fade-in"
                                                >
                                                    <h4
                                                        class="text-lg sm:text-xl font-play text-led-green mb-2 sm:mb-3"
                                                    >
                                                        Caractéristiques
                                                        techniques
                                                    </h4>
                                                    <div
                                                        class="text-white/90 text-sm sm:text-base whitespace-pre-line leading-relaxed specs-content"
                                                    >
                                                        {{ project.specs }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </dialog>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tous les projets -->
                <div class="mb-8">
                    <!-- Utilisation du composant HeroTitle -->
                    <HeroTitle
                        title="Toutes nos réalisations"
                        :centered="true"
                        marginBottom="mb-8 sm:mb-10 md:mb-12"
                    />

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 md:gap-6"
                    >
                        <div
                            v-for="project in projects"
                            :key="project.id"
                            class="bg-deep-black border border-gaming-red rounded-lg overflow-hidden project-card"
                        >
                            <div
                                class="h-36 sm:h-40 md:h-44 overflow-hidden relative card-image-container"
                            >
                                <!-- Image principale -->
                                <img
                                    v-if="
                                        project.images &&
                                        project.images.length > 0
                                    "
                                    :src="`/storage/${project.images[0].image_path}`"
                                    :alt="project.title"
                                    class="w-full h-full object-cover transition-transform duration-500"
                                />
                                <img
                                    v-else-if="project.image_path"
                                    :src="`/storage/${project.image_path}`"
                                    :alt="project.title"
                                    class="w-full h-full object-cover transition-transform duration-500"
                                />
                                <div
                                    v-else
                                    class="w-full h-full bg-gray-800 flex items-center justify-center"
                                >
                                    <span class="text-gray-500"
                                        >Pas d'image</span
                                    >
                                </div>

                                <!-- Overlay avec effet de brillance -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-deep-black to-transparent opacity-0 hover:opacity-70 transition-opacity duration-300 flex items-end justify-center p-3"
                                >
                                    <button
                                        @click="showModal(project.id)"
                                        class="px-2 py-1 sm:px-3 sm:py-1.5 bg-gaming-red text-white rounded-md transform translate-y-4 opacity-0 transition-all duration-300 view-details-btn text-xs sm:text-sm"
                                    >
                                        Voir les détails
                                    </button>
                                </div>
                            </div>

                            <div class="p-3 sm:p-4">
                                <h3
                                    class="text-base sm:text-lg font-play text-led-green mb-1 sm:mb-2"
                                >
                                    {{ project.title }}
                                </h3>
                                <p
                                    class="text-white/80 text-xs sm:text-sm mb-2 sm:mb-3 line-clamp-2"
                                >
                                    {{ project.description }}
                                </p>
                                <button
                                    @click="showModal(project.id)"
                                    class="inline-flex items-center text-xs sm:text-sm text-gaming-red hover:text-white transition group"
                                >
                                    <span class="mr-1">Détails</span>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-3 w-3 sm:h-4 sm:w-4 transform group-hover:translate-x-1 transition-transform duration-200"
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
                                </button>

                                <!-- Modal pour les détails (même contenu que ci-dessus) -->
                                <dialog
                                    :ref="
                                        (el) => {
                                            if (el) modalRefs[project.id] = el;
                                            else delete modalRefs[project.id];
                                        }
                                    "
                                    class="modal rounded-lg bg-deep-black border border-gaming-red p-0 w-full max-w-5xl"
                                >
                                    <div class="p-4 sm:p-6">
                                        <div
                                            class="flex justify-between items-center mb-4 sm:mb-6"
                                        >
                                            <h3
                                                class="text-xl sm:text-2xl md:text-3xl font-play text-led-green glow-text pr-4"
                                            >
                                                {{ project.title }}
                                            </h3>
                                            <button
                                                @click="closeModal(project.id)"
                                                class="text-white hover:text-gaming-red transition-colors"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 sm:h-6 sm:w-6 md:h-7 md:w-7"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"
                                                    />
                                                </svg>
                                            </button>
                                        </div>
                                        <div
                                            class="flex flex-col lg:flex-row gap-4 sm:gap-6 md:gap-8"
                                        >
                                            <!-- Carousel d'images -->
                                            <div class="lg:w-1/2">
                                                <div class="relative">
                                                    <!-- Image principale -->
                                                    <div
                                                        class="w-full h-60 sm:h-72 md:h-80 overflow-hidden rounded-lg bg-gray-800 border border-gaming-red/50 shadow-glow"
                                                    >
                                                        <img
                                                            v-if="
                                                                getImageToShow(
                                                                    project
                                                                )
                                                            "
                                                            :src="
                                                                getImageToShow(
                                                                    project
                                                                )
                                                            "
                                                            :alt="project.title"
                                                            class="w-full h-full object-contain transition-opacity duration-300"
                                                        />
                                                        <div
                                                            v-else
                                                            class="w-full h-full flex items-center justify-center"
                                                        >
                                                            <span
                                                                class="text-gray-500"
                                                                >Pas
                                                                d'image</span
                                                            >
                                                        </div>
                                                    </div>

                                                    <!-- Boutons de navigation (uniquement si plusieurs images) -->
                                                    <div
                                                        v-if="
                                                            project.images &&
                                                            project.images
                                                                .length > 1
                                                        "
                                                        class="absolute inset-y-0 left-0 flex items-center"
                                                    >
                                                        <button
                                                            @click="
                                                                prevImage(
                                                                    project
                                                                )
                                                            "
                                                            class="carousel-btn left-2 bg-deep-black/90 hover:bg-gaming-red text-white p-1.5 sm:p-2 rounded-full transition-colors duration-200 focus:outline-none"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 sm:h-5 sm:w-5 md:h-6 md:w-6"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 19l-7-7 7-7"
                                                                />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <div
                                                        v-if="
                                                            project.images &&
                                                            project.images
                                                                .length > 1
                                                        "
                                                        class="absolute inset-y-0 right-0 flex items-center"
                                                    >
                                                        <button
                                                            @click="
                                                                nextImage(
                                                                    project
                                                                )
                                                            "
                                                            class="carousel-btn right-2 bg-deep-black/90 hover:bg-gaming-red text-white p-1.5 sm:p-2 rounded-full transition-colors duration-200 focus:outline-none"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 sm:h-5 sm:w-5 md:h-6 md:w-6"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 5l7 7-7 7"
                                                                />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Miniatures améliorées -->
                                                <div
                                                    v-if="
                                                        project.images &&
                                                        project.images.length >
                                                            1
                                                    "
                                                    class="flex mt-3 sm:mt-4 space-x-2 sm:space-x-3 overflow-x-auto py-2 px-1 custom-scrollbar"
                                                >
                                                    <button
                                                        v-for="(
                                                            image, index
                                                        ) in project.images"
                                                        :key="index"
                                                        @click="
                                                            setImage(
                                                                project.id,
                                                                index
                                                            )
                                                        "
                                                        class="thumbnail-btn w-12 h-12 sm:w-16 sm:h-16 flex-shrink-0 rounded-md overflow-hidden focus:outline-none transition-transform duration-200"
                                                        :class="
                                                            currentImageIndex[
                                                                project.id
                                                            ] === index
                                                                ? 'border-2 border-led-green scale-110 shadow-glow-sm'
                                                                : 'border border-gray-700 hover:border-gaming-red'
                                                        "
                                                    >
                                                        <img
                                                            :src="`/storage/${image.image_path}`"
                                                            :alt="`${
                                                                project.title
                                                            } - Image ${
                                                                index + 1
                                                            }`"
                                                            class="w-full h-full object-cover"
                                                        />
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="lg:w-1/2 mt-4 lg:mt-0">
                                                <div
                                                    class="mb-4 sm:mb-6 bg-deep-black/50 rounded-lg p-3 sm:p-4 border border-gaming-red/30 animate-fade-in"
                                                >
                                                    <h4
                                                        class="text-lg sm:text-xl font-play text-led-green mb-2 sm:mb-3"
                                                    >
                                                        Description
                                                    </h4>
                                                    <p
                                                        class="text-white/90 text-sm sm:text-base leading-relaxed"
                                                    >
                                                        {{
                                                            project.description
                                                        }}
                                                    </p>
                                                </div>

                                                <div
                                                    v-if="project.specs"
                                                    class="bg-deep-black/50 rounded-lg p-3 sm:p-4 border border-gaming-red/30 animate-fade-in"
                                                >
                                                    <h4
                                                        class="text-lg sm:text-xl font-play text-led-green mb-2 sm:mb-3"
                                                    >
                                                        Caractéristiques
                                                        techniques
                                                    </h4>
                                                    <div
                                                        class="text-white/90 text-sm sm:text-base whitespace-pre-line leading-relaxed specs-content"
                                                    >
                                                        {{ project.specs }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </dialog>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message si aucun projet n'est disponible -->
                <div
                    v-if="!projects.length"
                    class="text-center py-8 sm:py-12 md:py-16"
                >
                    <div
                        class="inline-block p-6 sm:p-8 border border-gaming-red/50 rounded-lg bg-deep-black/50 shadow-glow"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10 sm:h-12 sm:w-12 text-gaming-red/70 mx-auto mb-3 sm:mb-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                            />
                        </svg>
                        <p
                            class="text-white/70 text-sm sm:text-base md:text-lg"
                        >
                            Aucune réalisation n'est disponible pour le moment.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
/* Dialog style */
dialog::backdrop {
    background-color: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(3px);
}

dialog {
    max-height: 90vh;
    overflow-y: auto;
}

dialog[open] {
    animation: modal-fade-in 0.3s ease-out;
}

@keyframes modal-fade-in {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Cartes projets avec hover effect */
.project-card {
    transition: all 0.3s ease;
    transform: translateY(0);
}

.project-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2), 0 0 15px rgba(236, 64, 122, 0.3);
    border-color: rgba(236, 64, 122, 0.8);
}

/* Adaptation pour mobile */
@media (max-width: 640px) {
    .project-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2),
            0 0 10px rgba(236, 64, 122, 0.3);
    }
}

.card-image-container:hover img {
    transform: scale(1.05);
}

.project-card:hover .view-details-btn {
    transform: translateY(0);
    opacity: 1;
}

/* Effet glow pour les éléments importants */
.glow-text {
    text-shadow: 0 0 10px rgba(236, 64, 122, 0.5);
}

.shadow-glow {
    box-shadow: 0 0 20px rgba(236, 64, 122, 0.4);
}

.shadow-glow-sm {
    box-shadow: 0 0 10px rgba(236, 64, 122, 0.4);
}

/* Réduire l'effet glow sur mobile */
@media (max-width: 640px) {
    .shadow-glow {
        box-shadow: 0 0 10px rgba(236, 64, 122, 0.3);
    }

    .shadow-glow-sm {
        box-shadow: 0 0 5px rgba(236, 64, 122, 0.3);
    }

    .glow-text {
        text-shadow: 0 0 5px rgba(236, 64, 122, 0.4);
    }
}

/* Custom scrollbar pour les miniatures */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgba(236, 64, 122, 0.5) rgba(0, 0, 0, 0.2);
}

.custom-scrollbar::-webkit-scrollbar {
    height: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(236, 64, 122, 0.5);
    border-radius: 10px;
}

/* Animation d'entrée pour les blocs de contenu */
.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
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

/* Animation optimisée pour mobile */
@media (max-width: 640px) {
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
}

/* Adaptation pour les écrans mobiles */
@media (max-width: 768px) {
    .section-title {
        font-size: 2rem;
    }

    /* Améliorer la vitesse de scrolling sur mobile */
    dialog {
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }
}
</style>
