<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Link } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUpdate } from "vue";

defineProps({
    projects: Array,
});

const modalRefs = ref({});
const currentImageIndex = ref({}); // Pour suivre l'image active pour chaque projet

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
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h1 class="text-4xl font-play text-gaming-red mb-8 text-center">
                    Nos Réalisations
                </h1>

                <!-- Projets mis en avant -->
                <div
                    v-if="projects.some((project) => project.is_featured)"
                    class="mb-16"
                >
                    <h2 class="text-2xl font-play text-led-green mb-6">
                        Projets Phares
                    </h2>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                    >
                        <div
                            v-for="project in projects.filter(
                                (p) => p.is_featured
                            )"
                            :key="project.id"
                            class="bg-deep-black border border-gaming-red rounded-lg overflow-hidden transition-transform hover:scale-105"
                        >
                            <div class="h-48 overflow-hidden">
                                <!-- Utiliser la première image du projet si disponible -->
                                <img
                                    v-if="
                                        project.images &&
                                        project.images.length > 0
                                    "
                                    :src="`/storage/${project.images[0].image_path}`"
                                    :alt="project.title"
                                    class="w-full h-full object-cover"
                                />
                                <img
                                    v-else-if="project.image_path"
                                    :src="`/storage/${project.image_path}`"
                                    :alt="project.title"
                                    class="w-full h-full object-cover"
                                />
                                <div
                                    v-else
                                    class="w-full h-full bg-gray-800 flex items-center justify-center"
                                >
                                    <span class="text-gray-500"
                                        >Pas d'image</span
                                    >
                                </div>
                            </div>
                            <div class="p-6">
                                <h3
                                    class="text-xl font-play text-led-green mb-2"
                                >
                                    {{ project.title }}
                                </h3>
                                <p class="text-white/80 mb-4 line-clamp-2">
                                    {{ project.description }}
                                </p>
                                <button
                                    @click="showModal(project.id)"
                                    class="text-gaming-red hover:text-white transition"
                                >
                                    Voir les détails
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
                                            class="flex justify-between items-center mb-4"
                                        >
                                            <h3
                                                class="text-2xl font-play text-led-green"
                                            >
                                                {{ project.title }}
                                            </h3>
                                            <button
                                                @click="closeModal(project.id)"
                                                class="text-white hover:text-gaming-red"
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
                                                        d="M6 18L18 6M6 6l12 12"
                                                    />
                                                </svg>
                                            </button>
                                        </div>
                                        <div
                                            class="flex flex-col md:flex-row gap-6"
                                        >
                                            <!-- Carousel d'images -->
                                            <div class="md:w-1/2">
                                                <div class="relative">
                                                    <!-- Image principale -->
                                                    <div
                                                        class="w-full h-80 overflow-hidden rounded-lg bg-gray-800"
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
                                                            class="bg-deep-black/80 hover:bg-gaming-red/80 text-white p-2 rounded-full ml-2"
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
                                                            class="bg-deep-black/80 hover:bg-gaming-red/80 text-white p-2 rounded-full mr-2"
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

                                                <!-- Miniatures -->
                                                <div
                                                    v-if="
                                                        project.images &&
                                                        project.images.length >
                                                            1
                                                    "
                                                    class="flex mt-3 space-x-2 overflow-x-auto py-2"
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
                                                        class="w-16 h-16 flex-shrink-0 rounded-md overflow-hidden focus:outline-none"
                                                        :class="
                                                            currentImageIndex[
                                                                project.id
                                                            ] === index
                                                                ? 'border-2 border-led-green'
                                                                : 'border border-gray-700'
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

                                            <div class="md:w-1/2">
                                                <div class="mb-4">
                                                    <h4
                                                        class="text-lg font-play text-white mb-2"
                                                    >
                                                        Description
                                                    </h4>
                                                    <p class="text-white/80">
                                                        {{
                                                            project.description
                                                        }}
                                                    </p>
                                                </div>
                                                <div v-if="project.specs">
                                                    <h4
                                                        class="text-lg font-play text-white mb-2"
                                                    >
                                                        Caractéristiques
                                                        techniques
                                                    </h4>
                                                    <div
                                                        class="text-white/80 whitespace-pre-line"
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
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
                >
                    <div
                        v-for="project in projects"
                        :key="project.id"
                        class="bg-deep-black border border-gaming-red rounded-lg overflow-hidden transition-transform hover:scale-105"
                    >
                        <div class="h-40 overflow-hidden">
                            <!-- Utiliser la première image du projet si disponible -->
                            <img
                                v-if="
                                    project.images && project.images.length > 0
                                "
                                :src="`/storage/${project.images[0].image_path}`"
                                :alt="project.title"
                                class="w-full h-full object-cover"
                            />
                            <img
                                v-else-if="project.image_path"
                                :src="`/storage/${project.image_path}`"
                                :alt="project.title"
                                class="w-full h-full object-cover"
                            />
                            <div
                                v-else
                                class="w-full h-full bg-gray-800 flex items-center justify-center"
                            >
                                <span class="text-gray-500">Pas d'image</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-play text-led-green mb-1">
                                {{ project.title }}
                            </h3>
                            <p class="text-white/80 text-sm mb-3 line-clamp-2">
                                {{ project.description }}
                            </p>
                            <button
                                @click="showModal(project.id)"
                                class="text-sm text-gaming-red hover:text-white transition"
                            >
                                Voir les détails
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
                                        class="flex justify-between items-center mb-4"
                                    >
                                        <h3
                                            class="text-2xl font-play text-led-green"
                                        >
                                            {{ project.title }}
                                        </h3>
                                        <button
                                            @click="closeModal(project.id)"
                                            class="text-white hover:text-gaming-red"
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
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row gap-6"
                                    >
                                        <!-- Carousel d'images -->
                                        <div class="md:w-1/2">
                                            <div class="relative">
                                                <!-- Image principale -->
                                                <div
                                                    class="w-full h-80 overflow-hidden rounded-lg bg-gray-800"
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
                                                            >Pas d'image</span
                                                        >
                                                    </div>
                                                </div>

                                                <!-- Boutons de navigation (uniquement si plusieurs images) -->
                                                <div
                                                    v-if="
                                                        project.images &&
                                                        project.images.length >
                                                            1
                                                    "
                                                    class="absolute inset-y-0 left-0 flex items-center"
                                                >
                                                    <button
                                                        @click="
                                                            prevImage(project)
                                                        "
                                                        class="bg-deep-black/80 hover:bg-gaming-red/80 text-white p-2 rounded-full ml-2"
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
                                                        project.images.length >
                                                            1
                                                    "
                                                    class="absolute inset-y-0 right-0 flex items-center"
                                                >
                                                    <button
                                                        @click="
                                                            nextImage(project)
                                                        "
                                                        class="bg-deep-black/80 hover:bg-gaming-red/80 text-white p-2 rounded-full mr-2"
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

                                            <!-- Miniatures -->
                                            <div
                                                v-if="
                                                    project.images &&
                                                    project.images.length > 1
                                                "
                                                class="flex mt-3 space-x-2 overflow-x-auto py-2"
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
                                                    class="w-16 h-16 flex-shrink-0 rounded-md overflow-hidden focus:outline-none"
                                                    :class="
                                                        currentImageIndex[
                                                            project.id
                                                        ] === index
                                                            ? 'border-2 border-led-green'
                                                            : 'border border-gray-700'
                                                    "
                                                >
                                                    <img
                                                        :src="`/storage/${image.image_path}`"
                                                        :alt="`${
                                                            project.title
                                                        } - Image ${index + 1}`"
                                                        class="w-full h-full object-cover"
                                                    />
                                                </button>
                                            </div>
                                        </div>

                                        <div class="md:w-1/2">
                                            <div class="mb-4">
                                                <h4
                                                    class="text-lg font-play text-white mb-2"
                                                >
                                                    Description
                                                </h4>
                                                <p class="text-white/80">
                                                    {{ project.description }}
                                                </p>
                                            </div>
                                            <div v-if="project.specs">
                                                <h4
                                                    class="text-lg font-play text-white mb-2"
                                                >
                                                    Caractéristiques techniques
                                                </h4>
                                                <div
                                                    class="text-white/80 whitespace-pre-line"
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

                <!-- Message si aucun projet n'est disponible -->
                <div v-if="!projects.length" class="text-center py-12">
                    <p class="text-white/70 text-lg">
                        Aucune réalisation n'est disponible pour le moment.
                    </p>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
dialog::backdrop {
    background-color: rgba(0, 0, 0, 0.7);
}

dialog {
    max-height: 90vh;
    overflow-y: auto;
}

dialog::backdrop {
    cursor: pointer;
}

/* Animation de transition pour les modales */
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

/* Animation pour le changement d'image */
.carousel-image-enter-active,
.carousel-image-leave-active {
    transition: opacity 0.3s ease;
}

.carousel-image-enter-from,
.carousel-image-leave-to {
    opacity: 0;
}
</style>
