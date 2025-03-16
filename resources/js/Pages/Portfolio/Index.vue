<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Link } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUpdate } from "vue";

defineProps({
    projects: Array,
});

const modalRefs = ref({});

const showModal = (projectId) => {
    if (modalRefs.value[projectId]) {
        modalRefs.value[projectId].showModal();
    }
};

const closeModal = (projectId) => {
    if (modalRefs.value[projectId]) {
        modalRefs.value[projectId].close();
    }
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
                                <img
                                    v-if="project.image_path"
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
                                    class="modal rounded-lg bg-deep-black border border-gaming-red p-0 w-full max-w-3xl"
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
                                            <div class="md:w-1/2">
                                                <img
                                                    v-if="project.image_path"
                                                    :src="`/storage/${project.image_path}`"
                                                    :alt="project.title"
                                                    class="w-full rounded-lg"
                                                />
                                                <div
                                                    v-else
                                                    class="w-full h-48 bg-gray-800 rounded-lg flex items-center justify-center"
                                                >
                                                    <span class="text-gray-500"
                                                        >Pas d'image</span
                                                    >
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
                            <img
                                v-if="project.image_path"
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
                                class="modal rounded-lg bg-deep-black border border-gaming-red p-0 w-full max-w-3xl"
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
                                        <div class="md:w-1/2">
                                            <img
                                                v-if="project.image_path"
                                                :src="`/storage/${project.image_path}`"
                                                :alt="project.title"
                                                class="w-full rounded-lg"
                                            />
                                            <div
                                                v-else
                                                class="w-full h-48 bg-gray-800 rounded-lg flex items-center justify-center"
                                            >
                                                <span class="text-gray-500"
                                                    >Pas d'image</span
                                                >
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
</style>
