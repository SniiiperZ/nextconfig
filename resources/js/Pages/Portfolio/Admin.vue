<script setup>
import { ref, onMounted } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import axios from "axios"; // Assurez-vous qu'axios est installé

const props = defineProps({
    projects: Array,
});

// Récupérer le token CSRF de manière fiable
const csrfToken = ref(
    document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content") || ""
);

// Configurer axios pour qu'il inclue toujours le token CSRF
onMounted(() => {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken.value;
    axios.defaults.withCredentials = true; // Important pour les requêtes cross-domain
});

const form = useForm({
    title: "",
    description: "",
    specs: "",
    images: [], // Modifié pour accepter plusieurs images
    order: 0,
    is_featured: false,
    is_visible: true,
    delete_images: [], // Pour marquer les images à supprimer
});

const editing = ref(null);
const imagePreview = ref([]);
const draggedImageIndex = ref(null);
const updateMessage = ref("");
const updateStatus = ref(null); // null, 'success' ou 'error'

const submit = () => {
    if (editing.value) {
        form.post(route("admin.portfolio.update", editing.value.id), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                editing.value = null;
                form.reset();
                imagePreview.value = [];
            },
        });
    } else {
        form.post(route("admin.portfolio.store"), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                form.reset();
                imagePreview.value = [];
            },
        });
    }
};

const editProject = (project) => {
    editing.value = project;
    form.title = project.title;
    form.description = project.description;
    form.specs = project.specs || "";
    form.order = project.order;
    form.is_featured = project.is_featured;
    form.is_visible = project.is_visible;
    form.images = [];
    form.delete_images = [];

    // Prévisualisations des images existantes
    imagePreview.value = project.images.map((img) => ({
        id: img.id,
        path: `/storage/${img.image_path}`,
        isMain: img.is_main,
    }));
};

const cancelEdit = () => {
    editing.value = null;
    form.reset();
    imagePreview.value = [];
};

const deleteProject = (project) => {
    if (confirm(`Êtes-vous sûr de vouloir supprimer "${project.title}" ?`)) {
        form.delete(route("admin.portfolio.destroy", project.id), {
            preserveScroll: true,
        });
    }
};

const handleImagesChange = (event) => {
    const files = event.target.files;
    if (!files.length) return;

    // Ajouter les fichiers au formulaire
    form.images = [...form.images, ...Array.from(files)];

    // Générer des prévisualisations pour les nouvelles images
    Array.from(files).forEach((file) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value.push({
                id: null, // null pour les nouvelles images
                path: e.target.result,
                isMain: false, // La première image deviendra automatiquement principale côté serveur
                file: file, // référence au fichier pour pouvoir le supprimer
            });
        };
        reader.readAsDataURL(file);
    });
};

const removeImage = (index) => {
    const image = imagePreview.value[index];

    // Si c'est une image existante, la marquer pour suppression
    if (image.id) {
        form.delete_images.push(image.id);
    } else {
        // Si c'est une nouvelle image, la supprimer du formulaire
        const fileIndex = form.images.findIndex((f) => f === image.file);
        if (fileIndex !== -1) {
            form.images.splice(fileIndex, 1);
        }
    }

    // Supprimer la prévisualisation
    imagePreview.value.splice(index, 1);
};

// Gestion du drag & drop pour réorganiser les images
const onDragStart = (index) => {
    draggedImageIndex.value = index;
};

const onDragOver = (event) => {
    event.preventDefault();
};

const onDrop = (event, index) => {
    event.preventDefault();

    if (draggedImageIndex.value !== null && draggedImageIndex.value !== index) {
        const draggedIndex = draggedImageIndex.value;

        // Réorganiser les prévisualisations
        const temp = imagePreview.value[draggedIndex];
        imagePreview.value.splice(draggedIndex, 1);
        imagePreview.value.splice(index, 0, temp);

        // Réorganiser également les images dans le formulaire si ce sont des nouvelles images
        if (!temp.id && form.images.length > 0) {
            const tempFile = form.images[draggedIndex];
            if (tempFile) {
                form.images.splice(draggedIndex, 1);
                form.images.splice(index, 0, tempFile);
            }
        }

        // Si nous éditons un projet existant, mettre à jour l'ordre des images
        if (editing.value) {
            const imagesWithOrder = imagePreview.value
                .filter((img) => img.id) // Seulement les images existantes
                .map((img, idx) => ({
                    id: img.id,
                    order: idx,
                }));

            if (imagesWithOrder.length > 0) {
                updateStatus.value = "loading";
                updateMessage.value = "Mise à jour en cours...";

                // Utiliser axios au lieu de fetch pour une meilleure gestion des CSRF tokens
                axios
                    .post(
                        route("admin.portfolio.images.order", editing.value.id),
                        { images: imagesWithOrder }
                    )
                    .then((response) => {
                        updateStatus.value = "success";
                        updateMessage.value =
                            "Ordre des images mis à jour avec succès";
                        setTimeout(() => {
                            updateStatus.value = null;
                            updateMessage.value = "";
                        }, 3000);
                    })
                    .catch((error) => {
                        updateStatus.value = "error";
                        updateMessage.value = `Erreur: ${
                            error.response?.data?.message || error.message
                        }`;
                        console.error("Erreur lors de la mise à jour:", error);
                    });
            }
        }
    }

    draggedImageIndex.value = null;
};

// Pour améliorer le rendu visuel de l'image principale lors de la réorganisation
const isMainImage = (index) => {
    return index === 0;
};
</script>

<template>
    <AdminLayout title="Gestion du Portfolio">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion du Portfolio
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Formulaire -->
                <div class="bg-deep-black p-6 rounded-lg shadow mb-6">
                    <form @submit.prevent="submit" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div>
                                    <label class="block text-white mb-2"
                                        >Titre</label
                                    >
                                    <input
                                        v-model="form.title"
                                        type="text"
                                        class="w-full bg-gray-800 text-white rounded px-3 py-2"
                                    />
                                    <p
                                        v-if="form.errors.title"
                                        class="text-red-500 text-sm mt-1"
                                    >
                                        {{ form.errors.title }}
                                    </p>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-white mb-2"
                                        >Description</label
                                    >
                                    <textarea
                                        v-model="form.description"
                                        rows="4"
                                        class="w-full bg-gray-800 text-white rounded px-3 py-2"
                                    ></textarea>
                                    <p
                                        v-if="form.errors.description"
                                        class="text-red-500 text-sm mt-1"
                                    >
                                        {{ form.errors.description }}
                                    </p>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-white mb-2"
                                        >Caractéristiques techniques</label
                                    >
                                    <textarea
                                        v-model="form.specs"
                                        rows="4"
                                        class="w-full bg-gray-800 text-white rounded px-3 py-2"
                                        placeholder="Processeur: Intel i7-14700K&#10;Carte graphique: RTX 4080&#10;RAM: 32GB DDR5&#10;..."
                                    ></textarea>
                                </div>

                                <div class="flex gap-4 mt-4">
                                    <div>
                                        <label class="block text-white mb-2"
                                            >Ordre</label
                                        >
                                        <input
                                            v-model="form.order"
                                            type="number"
                                            class="bg-gray-800 text-white rounded px-3 py-2"
                                        />
                                    </div>

                                    <div class="flex items-center">
                                        <label class="flex items-center">
                                            <input
                                                v-model="form.is_featured"
                                                type="checkbox"
                                                class="bg-gray-800 text-white rounded mr-2"
                                            />
                                            <span class="text-white"
                                                >Projet phare</span
                                            >
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <label class="flex items-center">
                                            <input
                                                v-model="form.is_visible"
                                                type="checkbox"
                                                class="bg-gray-800 text-white rounded mr-2"
                                            />
                                            <span class="text-white"
                                                >Visible</span
                                            >
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <label class="block text-white mb-2"
                                        >Images (glisser-déposer pour
                                        réorganiser)</label
                                    >
                                    <input
                                        type="file"
                                        @change="handleImagesChange"
                                        accept="image/*"
                                        multiple
                                        class="w-full bg-gray-800 text-white rounded px-3 py-2"
                                    />
                                    <p
                                        v-if="form.errors.images"
                                        class="text-red-500 text-sm mt-1"
                                    >
                                        {{ form.errors.images }}
                                    </p>
                                </div>

                                <!-- Prévisualisation et gestion des images -->
                                <div
                                    v-if="imagePreview.length > 0"
                                    class="mt-4"
                                >
                                    <p class="text-white text-sm mb-2">
                                        Faites glisser pour réorganiser.
                                        <span class="text-led-green">
                                            La première image sera utilisée
                                            comme image principale.
                                        </span>
                                    </p>
                                    <div
                                        class="grid grid-cols-2 sm:grid-cols-3 gap-3"
                                    >
                                        <div
                                            v-for="(
                                                image, index
                                            ) in imagePreview"
                                            :key="index"
                                            class="relative border-2 rounded overflow-hidden"
                                            :class="
                                                isMainImage(index)
                                                    ? 'border-led-green ring-2 ring-led-green'
                                                    : 'border-gray-700'
                                            "
                                            draggable="true"
                                            @dragstart="onDragStart(index)"
                                            @dragover="onDragOver"
                                            @drop="onDrop($event, index)"
                                        >
                                            <img
                                                :src="image.path"
                                                alt="Aperçu"
                                                class="h-24 w-full object-cover"
                                            />
                                            <div
                                                class="absolute top-0 right-0 p-1"
                                            >
                                                <button
                                                    @click.prevent="
                                                        removeImage(index)
                                                    "
                                                    type="button"
                                                    class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center"
                                                    title="Supprimer cette image"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4"
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
                                                v-if="isMainImage(index)"
                                                class="absolute bottom-0 left-0 right-0 bg-led-green/70 px-1 py-0.5"
                                            >
                                                <span
                                                    class="text-white text-xs font-medium"
                                                >
                                                    Image principale
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button
                                type="submit"
                                class="bg-gaming-red text-white px-4 py-2 rounded"
                                :disabled="form.processing"
                            >
                                {{ editing ? "Mettre à jour" : "Ajouter" }}
                            </button>
                            <button
                                v-if="editing"
                                @click="cancelEdit"
                                type="button"
                                class="bg-gray-600 text-white px-4 py-2 rounded"
                            >
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Liste des projets -->
                <div class="bg-deep-black p-6 rounded-lg shadow">
                    <h3 class="text-xl text-led-green mb-4">
                        Liste des projets
                    </h3>

                    <div
                        v-if="!projects.length"
                        class="text-white text-center py-4"
                    >
                        Aucun projet à afficher pour le moment.
                    </div>

                    <div v-else class="space-y-4">
                        <div
                            v-for="project in projects"
                            :key="project.id"
                            class="border border-gaming-red rounded-lg p-4"
                        >
                            <div class="flex justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <h4 class="text-lg text-led-green">
                                        {{ project.title }}
                                    </h4>
                                    <span
                                        v-if="project.is_featured"
                                        class="bg-gaming-red text-white px-2 py-0.5 text-xs rounded"
                                        >Phare</span
                                    >
                                    <span
                                        v-if="!project.is_visible"
                                        class="bg-gray-600 text-white px-2 py-0.5 text-xs rounded"
                                        >Masqué</span
                                    >
                                </div>
                                <div class="flex space-x-2">
                                    <button
                                        @click="editProject(project)"
                                        class="bg-blue-500 text-white px-3 py-1 rounded"
                                    >
                                        Modifier
                                    </button>
                                    <button
                                        @click="deleteProject(project)"
                                        class="bg-red-500 text-white px-3 py-1 rounded"
                                    >
                                        Supprimer
                                    </button>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <!-- Affichage des miniatures d'images -->
                                <div
                                    class="flex space-x-2 overflow-x-auto pb-2"
                                    style="max-width: 300px"
                                >
                                    <div
                                        v-for="(image, idx) in project.images"
                                        :key="image.id"
                                        class="w-16 h-16 flex-shrink-0 overflow-hidden rounded"
                                        :class="
                                            idx === 0
                                                ? 'border-2 border-led-green'
                                                : ''
                                        "
                                    >
                                        <img
                                            :src="`/storage/${image.image_path}`"
                                            :alt="project.title"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>
                                    <div
                                        v-if="!project.images.length"
                                        class="w-16 h-16 bg-gray-800 flex-shrink-0 flex items-center justify-center rounded"
                                    >
                                        <span class="text-gray-500 text-xs"
                                            >Aucune image</span
                                        >
                                    </div>
                                </div>
                                <div>
                                    <p
                                        class="text-white/80 text-sm line-clamp-2"
                                    >
                                        {{ project.description }}
                                    </p>
                                    <p class="text-white/60 text-xs mt-1">
                                        Ordre: {{ project.order }} |
                                        {{ project.images.length }} image(s)
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Ajouter un message de statut de mise à jour -->
        <div
            v-if="updateStatus"
            class="fixed bottom-4 right-4 p-3 rounded shadow-lg transition-opacity duration-300"
            :class="{
                'bg-green-500 text-white': updateStatus === 'success',
                'bg-red-500 text-white': updateStatus === 'error',
                'bg-blue-500 text-white': updateStatus === 'loading',
            }"
        >
            {{ updateMessage }}
        </div>
    </AdminLayout>
</template>
