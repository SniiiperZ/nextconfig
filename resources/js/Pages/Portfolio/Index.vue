<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
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
    <PublicLayout title="Réalisations">
        <!-- Hero Section pour la page Portfolio -->
        <div class="portfolio-hero-section">
            <div class="hero-content z-10 relative text-center">
                <h1
                    class="text-6xl md:text-7xl font-play text-gaming-red mb-6 hero-title"
                >
                    Nos Réalisations
                </h1>
                <p
                    class="text-2xl text-white mb-12 max-w-3xl mx-auto font-medium text-shadow-lg"
                >
                    Découvrez nos configurations PC gaming sur mesure
                </p>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Projets mis en avant -->
                <div
                    v-if="projects.some((project) => project.is_featured)"
                    class="mb-16"
                >
                    <div class="flex justify-center mb-12">
                        <h2
                            class="text-4xl font-play text-gaming-red section-title"
                        >
                            Projets Phares
                        </h2>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                    >
                        <div
                            v-for="project in projects.filter(
                                (p) => p.is_featured
                            )"
                            :key="project.id"
                            class="bg-deep-black border border-gaming-red rounded-lg overflow-hidden project-card"
                        >
                            <div
                                class="h-56 overflow-hidden relative card-image-container"
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
                                        class="px-4 py-2 bg-gaming-red text-white rounded-md transform translate-y-4 opacity-0 transition-all duration-300 view-details-btn"
                                    >
                                        Voir les détails
                                    </button>
                                </div>
                            </div>

                            <div class="p-6">
                                <h3
                                    class="text-2xl font-play text-led-green mb-3"
                                >
                                    {{ project.title }}
                                </h3>
                                <p class="text-white/80 mb-4 line-clamp-2">
                                    {{ project.description }}
                                </p>
                                <button
                                    @click="showModal(project.id)"
                                    class="inline-flex items-center text-gaming-red hover:text-white transition group"
                                >
                                    <span class="mr-2">Voir les détails</span>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 transform group-hover:translate-x-1 transition-transform duration-200"
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
                                    <div class="p-6">
                                        <div
                                            class="flex justify-between items-center mb-6"
                                        >
                                            <h3
                                                class="text-3xl font-play text-led-green glow-text"
                                            >
                                                {{ project.title }}
                                            </h3>
                                            <button
                                                @click="closeModal(project.id)"
                                                class="text-white hover:text-gaming-red transition-colors"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-7 w-7"
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
                                            class="flex flex-col lg:flex-row gap-8"
                                        >
                                            <!-- Carousel d'images -->
                                            <div class="lg:w-1/2">
                                                <div class="relative">
                                                    <!-- Image principale -->
                                                    <div
                                                        class="w-full h-80 overflow-hidden rounded-lg bg-gray-800 border border-gaming-red/50 shadow-glow"
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
                                                            class="carousel-btn left-2 bg-deep-black/90 hover:bg-gaming-red text-white p-2 rounded-full transition-colors duration-200 focus:outline-none"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="h-6 w-6"
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
                                                            class="carousel-btn right-2 bg-deep-black/90 hover:bg-gaming-red text-white p-2 rounded-full transition-colors duration-200 focus:outline-none"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="h-6 w-6"
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
                                                    class="flex mt-4 space-x-3 overflow-x-auto py-2 custom-scrollbar"
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
                                                        class="thumbnail-btn w-16 h-16 flex-shrink-0 rounded-md overflow-hidden focus:outline-none transition-transform duration-200"
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

                                            <div class="lg:w-1/2">
                                                <div
                                                    class="mb-6 bg-deep-black/50 rounded-lg p-4 border border-gaming-red/30 animate-fade-in"
                                                >
                                                    <h4
                                                        class="text-xl font-play text-led-green mb-3"
                                                    >
                                                        Description
                                                    </h4>
                                                    <p
                                                        class="text-white/90 leading-relaxed"
                                                    >
                                                        {{
                                                            project.description
                                                        }}
                                                    </p>
                                                </div>

                                                <div
                                                    v-if="project.specs"
                                                    class="bg-deep-black/50 rounded-lg p-4 border border-gaming-red/30 animate-fade-in"
                                                >
                                                    <h4
                                                        class="text-xl font-play text-led-green mb-3"
                                                    >
                                                        Caractéristiques
                                                        techniques
                                                    </h4>
                                                    <div
                                                        class="text-white/90 whitespace-pre-line leading-relaxed specs-content"
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
                    <div class="flex justify-center mb-12">
                        <h2
                            class="text-4xl font-play text-gaming-red section-title"
                        >
                            Toutes nos réalisations
                        </h2>
                    </div>

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
                    >
                        <div
                            v-for="project in projects"
                            :key="project.id"
                            class="bg-deep-black border border-gaming-red rounded-lg overflow-hidden project-card"
                        >
                            <div
                                class="h-44 overflow-hidden relative card-image-container"
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
                                        class="px-3 py-1 bg-gaming-red text-white rounded-md transform translate-y-4 opacity-0 transition-all duration-300 view-details-btn text-sm"
                                    >
                                        Voir les détails
                                    </button>
                                </div>
                            </div>

                            <div class="p-4">
                                <h3
                                    class="text-lg font-play text-led-green mb-2"
                                >
                                    {{ project.title }}
                                </h3>
                                <p
                                    class="text-white/80 text-sm mb-3 line-clamp-2"
                                >
                                    {{ project.description }}
                                </p>
                                <button
                                    @click="showModal(project.id)"
                                    class="inline-flex items-center text-sm text-gaming-red hover:text-white transition group"
                                >
                                    <span class="mr-1">Détails</span>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 transform group-hover:translate-x-1 transition-transform duration-200"
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
                                    <div class="p-6">
                                        <div
                                            class="flex justify-between items-center mb-6"
                                        >
                                            <h3
                                                class="text-3xl font-play text-led-green glow-text"
                                            >
                                                {{ project.title }}
                                            </h3>
                                            <button
                                                @click="closeModal(project.id)"
                                                class="text-white hover:text-gaming-red transition-colors"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-7 w-7"
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
                                            class="flex flex-col lg:flex-row gap-8"
                                        >
                                            <!-- Carousel d'images -->
                                            <div class="lg:w-1/2">
                                                <div class="relative">
                                                    <!-- Image principale -->
                                                    <div
                                                        class="w-full h-80 overflow-hidden rounded-lg bg-gray-800 border border-gaming-red/50 shadow-glow"
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
                                                            class="carousel-btn left-2 bg-deep-black/90 hover:bg-gaming-red text-white p-2 rounded-full transition-colors duration-200 focus:outline-none"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="h-6 w-6"
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
                                                            class="carousel-btn right-2 bg-deep-black/90 hover:bg-gaming-red text-white p-2 rounded-full transition-colors duration-200 focus:outline-none"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="h-6 w-6"
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
                                                    class="flex mt-4 space-x-3 overflow-x-auto py-2 custom-scrollbar"
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
                                                        class="thumbnail-btn w-16 h-16 flex-shrink-0 rounded-md overflow-hidden focus:outline-none transition-transform duration-200"
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

                                            <div class="lg:w-1/2">
                                                <div
                                                    class="mb-6 bg-deep-black/50 rounded-lg p-4 border border-gaming-red/30 animate-fade-in"
                                                >
                                                    <h4
                                                        class="text-xl font-play text-led-green mb-3"
                                                    >
                                                        Description
                                                    </h4>
                                                    <p
                                                        class="text-white/90 leading-relaxed"
                                                    >
                                                        {{
                                                            project.description
                                                        }}
                                                    </p>
                                                </div>

                                                <div
                                                    v-if="project.specs"
                                                    class="bg-deep-black/50 rounded-lg p-4 border border-gaming-red/30 animate-fade-in"
                                                >
                                                    <h4
                                                        class="text-xl font-play text-led-green mb-3"
                                                    >
                                                        Caractéristiques
                                                        techniques
                                                    </h4>
                                                    <div
                                                        class="text-white/90 whitespace-pre-line leading-relaxed specs-content"
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
                <div v-if="!projects.length" class="text-center py-16">
                    <div
                        class="inline-block p-8 border border-gaming-red/50 rounded-lg bg-deep-black/50 shadow-glow"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-12 w-12 text-gaming-red/70 mx-auto mb-4"
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
                        <p class="text-white/70 text-lg">
                            Aucune réalisation n'est disponible pour le moment.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
/* Hero section pour la page portfolio */
.portfolio-hero-section {
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

/* Hero title avec animation glow */
.hero-title {
    text-shadow: 0 0 15px rgba(236, 64, 122, 0.7), 0 3px 10px rgba(0, 0, 0, 0.8);
    letter-spacing: 1px;
    font-weight: bold;
    animation: glow 2s ease-in-out infinite alternate;
}

/* Animation pour le titre */
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

/* Styles pour les titres de section */
.section-title {
    position: relative;
    display: inline-block;
    padding-bottom: 10px;
    margin-left: auto;
    margin-right: auto;
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

/* Adaptation pour les écrans mobiles */
@media (max-width: 768px) {
    .hero-title {
        font-size: 3rem;
    }

    .section-title {
        font-size: 2rem;
    }
}
</style>
