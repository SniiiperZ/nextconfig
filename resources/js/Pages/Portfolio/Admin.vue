<script setup>
import { ref, onMounted, computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm, router } from "@inertiajs/vue3";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
} from "@headlessui/vue";
import {
    ExclamationTriangleIcon,
    MagnifyingGlassIcon,
    ArrowUpIcon,
    ArrowDownIcon,
} from "@heroicons/vue/24/outline";
import axios from "axios";

const props = defineProps({
    projects: Array,
});

// Récupérer le token CSRF de manière fiable
const csrfToken = ref(
    document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content") || ""
);

// Filtrage et tri des projets
const searchQuery = ref("");
const sortBy = ref("order");
const sortDirection = ref("asc");

const filteredProjects = computed(() => {
    let filtered = [...props.projects];

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (project) =>
                project.title.toLowerCase().includes(query) ||
                project.description.toLowerCase().includes(query) ||
                project.specs?.toLowerCase().includes(query)
        );
    }

    filtered.sort((a, b) => {
        let comparison = 0;
        if (a[sortBy.value] > b[sortBy.value]) {
            comparison = 1;
        } else if (a[sortBy.value] < b[sortBy.value]) {
            comparison = -1;
        }
        return sortDirection.value === "asc" ? comparison : -comparison;
    });

    return filtered;
});

const toggleSort = (column) => {
    if (sortBy.value === column) {
        sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
    } else {
        sortBy.value = column;
        sortDirection.value = "asc";
    }
};

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
const showDeleteModal = ref(false);
const projectToDelete = ref(null);

const submit = () => {
    if (editing.value) {
        form.post(route("admin.portfolio.update", editing.value.id), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                editing.value = null;
                form.reset();
                imagePreview.value = [];
                updateStatus.value = "success";
                updateMessage.value = "Projet mis à jour avec succès !";
                setTimeout(() => {
                    updateStatus.value = null;
                    updateMessage.value = "";
                }, 3000);
            },
        });
    } else {
        form.post(route("admin.portfolio.store"), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                form.reset();
                imagePreview.value = [];
                updateStatus.value = "success";
                updateMessage.value = "Projet ajouté avec succès !";
                setTimeout(() => {
                    updateStatus.value = null;
                    updateMessage.value = "";
                }, 3000);
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

    // Scroll to form
    setTimeout(() => {
        document
            .querySelector("#portfolio-form")
            .scrollIntoView({ behavior: "smooth" });
    }, 100);
};

const cancelEdit = () => {
    editing.value = null;
    form.reset();
    imagePreview.value = [];
};

const confirmDelete = (project) => {
    projectToDelete.value = project;
    showDeleteModal.value = true;
};

const deleteProject = () => {
    router.delete(route("admin.portfolio.destroy", projectToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            showDeleteModal.value = false;
            projectToDelete.value = null;
            updateStatus.value = "success";
            updateMessage.value = "Projet supprimé avec succès !";
            setTimeout(() => {
                updateStatus.value = null;
                updateMessage.value = "";
            }, 3000);
        },
    });
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
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Gestion du Portfolio
                </h2>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                    >
                        <MagnifyingGlassIcon
                            class="h-5 w-5 text-gray-400"
                            aria-hidden="true"
                        />
                    </div>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Rechercher..."
                        class="block w-full pl-10 pr-3 py-2 border border-gaming-red rounded-md bg-deep-black/50 text-white focus:outline-none focus:ring-2 focus:ring-led-green"
                    />
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Formulaire -->
                <div
                    id="portfolio-form"
                    class="bg-deep-black p-6 rounded-lg shadow mb-8 border border-gaming-red/30 hover:border-gaming-red/70 transition-all duration-300"
                >
                    <h3
                        class="text-xl font-play font-semibold text-led-green mb-4"
                    >
                        {{
                            editing
                                ? "Modifier un projet"
                                : "Ajouter un nouveau projet"
                        }}
                    </h3>

                    <form @submit.prevent="submit" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div>
                                    <label
                                        class="block text-white mb-2 font-medium"
                                        >Titre</label
                                    >
                                    <input
                                        v-model="form.title"
                                        type="text"
                                        class="w-full bg-deep-black border border-gaming-red rounded-md px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-200"
                                        :class="{
                                            'border-red-500': form.errors.title,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.title"
                                        class="text-red-500 mt-1 text-sm"
                                    >
                                        {{ form.errors.title }}
                                    </p>
                                </div>

                                <div class="mt-4">
                                    <label
                                        class="block text-white mb-2 font-medium"
                                        >Description</label
                                    >
                                    <textarea
                                        v-model="form.description"
                                        rows="4"
                                        class="w-full bg-deep-black border border-gaming-red rounded-md px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-200"
                                        :class="{
                                            'border-red-500':
                                                form.errors.description,
                                        }"
                                    ></textarea>
                                    <p
                                        v-if="form.errors.description"
                                        class="text-red-500 mt-1 text-sm"
                                    >
                                        {{ form.errors.description }}
                                    </p>
                                </div>

                                <div class="mt-4">
                                    <label
                                        class="block text-white mb-2 font-medium"
                                        >Caractéristiques techniques</label
                                    >
                                    <textarea
                                        v-model="form.specs"
                                        rows="4"
                                        class="w-full bg-deep-black border border-gaming-red rounded-md px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-200"
                                        placeholder="Processeur: Intel i7-14700K&#10;Carte graphique: RTX 4080&#10;RAM: 32GB DDR5&#10;..."
                                        :class="{
                                            'border-red-500': form.errors.specs,
                                        }"
                                    ></textarea>
                                    <p
                                        v-if="form.errors.specs"
                                        class="text-red-500 mt-1 text-sm"
                                    >
                                        {{ form.errors.specs }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <label
                                        class="block text-white mb-2 font-medium"
                                        >Images (glisser-déposer pour
                                        réorganiser)</label
                                    >
                                    <input
                                        type="file"
                                        @change="handleImagesChange"
                                        accept="image/*"
                                        multiple
                                        class="w-full bg-deep-black border border-gaming-red rounded-md px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-200"
                                        :class="{
                                            'border-red-500':
                                                form.errors.images,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.images"
                                        class="text-red-500 mt-1 text-sm"
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
                                                    class="bg-gaming-red text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors duration-200"
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

                                <div
                                    class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6"
                                >
                                    <div>
                                        <label
                                            class="block text-white mb-2 font-medium"
                                            >Ordre d'affichage</label
                                        >
                                        <input
                                            v-model="form.order"
                                            type="number"
                                            class="w-full bg-deep-black border border-gaming-red rounded-md px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-200"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.order,
                                            }"
                                        />
                                        <p
                                            v-if="form.errors.order"
                                            class="text-red-500 mt-1 text-sm"
                                        >
                                            {{ form.errors.order }}
                                        </p>
                                    </div>

                                    <div class="space-y-3">
                                        <div>
                                            <label
                                                class="block text-white mb-2 font-medium"
                                                >Options</label
                                            >
                                        </div>

                                        <div
                                            class="flex items-center space-x-2"
                                        >
                                            <div
                                                @click="
                                                    form.is_featured =
                                                        !form.is_featured
                                                "
                                                :class="[
                                                    'px-4 py-2 rounded-md cursor-pointer transition-colors duration-200',
                                                    form.is_featured
                                                        ? 'bg-led-green text-deep-black font-medium'
                                                        : 'bg-deep-black border border-gaming-red text-white',
                                                ]"
                                            >
                                                Projet phare
                                            </div>
                                        </div>

                                        <div
                                            class="flex items-center space-x-2"
                                        >
                                            <div
                                                @click="
                                                    form.is_visible =
                                                        !form.is_visible
                                                "
                                                :class="[
                                                    'px-4 py-2 rounded-md cursor-pointer transition-colors duration-200',
                                                    form.is_visible
                                                        ? 'bg-led-green text-deep-black font-medium'
                                                        : 'bg-gaming-red text-white font-medium',
                                                ]"
                                            >
                                                {{
                                                    form.is_visible
                                                        ? "Visible"
                                                        : "Masqué"
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-2">
                            <button
                                type="submit"
                                class="bg-gaming-red text-white px-6 py-3 rounded-md hover:bg-gaming-red/90 transition duration-150 flex items-center"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing" class="mr-2">
                                    <svg
                                        class="animate-spin h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            class="opacity-25"
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                        ></circle>
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                        ></path>
                                    </svg>
                                </span>
                                {{ editing ? "Mettre à jour" : "Ajouter" }}
                            </button>
                            <button
                                v-if="editing"
                                @click="cancelEdit"
                                type="button"
                                class="bg-deep-black border border-gaming-red text-white px-6 py-3 rounded-md hover:bg-gaming-red/10 transition duration-150"
                            >
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Liste des projets -->
                <div
                    v-if="filteredProjects.length === 0"
                    class="bg-deep-black p-6 rounded-lg shadow text-center"
                >
                    <p class="text-white/80">Aucun projet trouvé</p>
                </div>

                <div v-else class="space-y-4">
                    <div
                        class="bg-gaming-red/20 p-4 rounded-lg mb-4 grid grid-cols-12 gap-4 items-center font-semibold text-white"
                    >
                        <div
                            class="col-span-3 flex items-center cursor-pointer"
                            @click="toggleSort('title')"
                        >
                            Titre
                            <ArrowUpIcon
                                v-if="
                                    sortBy === 'title' &&
                                    sortDirection === 'asc'
                                "
                                class="h-4 w-4 ml-1"
                            />
                            <ArrowDownIcon
                                v-else-if="
                                    sortBy === 'title' &&
                                    sortDirection === 'desc'
                                "
                                class="h-4 w-4 ml-1"
                            />
                        </div>
                        <div class="col-span-4">Images</div>
                        <div
                            class="col-span-1 text-center cursor-pointer"
                            @click="toggleSort('order')"
                        >
                            Ordre
                            <ArrowUpIcon
                                v-if="
                                    sortBy === 'order' &&
                                    sortDirection === 'asc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                            <ArrowDownIcon
                                v-else-if="
                                    sortBy === 'order' &&
                                    sortDirection === 'desc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                        </div>
                        <div
                            class="col-span-2 text-center cursor-pointer"
                            @click="toggleSort('is_visible')"
                        >
                            Statut
                            <ArrowUpIcon
                                v-if="
                                    sortBy === 'is_visible' &&
                                    sortDirection === 'asc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                            <ArrowDownIcon
                                v-else-if="
                                    sortBy === 'is_visible' &&
                                    sortDirection === 'desc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                        </div>
                        <div class="col-span-2 text-center">Actions</div>
                    </div>

                    <transition-group name="list" tag="div" class="space-y-4">
                        <div
                            v-for="project in filteredProjects"
                            :key="project.id"
                            class="bg-deep-black p-6 rounded-lg shadow border border-gaming-red/10 hover:border-gaming-red/40 transition-all duration-300"
                        >
                            <div class="grid grid-cols-12 gap-4 items-start">
                                <div class="col-span-3">
                                    <h3
                                        class="text-xl font-play font-semibold text-led-green mb-2"
                                    >
                                        {{ project.title }}
                                    </h3>
                                    <p class="text-white/80 line-clamp-2">
                                        {{ project.description }}
                                    </p>
                                </div>

                                <div class="col-span-4">
                                    <div
                                        class="flex space-x-2 overflow-x-auto pb-2"
                                    >
                                        <div
                                            v-for="(
                                                image, idx
                                            ) in project.images.slice(0, 4)"
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
                                            v-if="project.images.length > 4"
                                            class="w-16 h-16 bg-deep-black border border-gaming-red/50 flex-shrink-0 flex items-center justify-center rounded text-white"
                                        >
                                            +{{ project.images.length - 4 }}
                                        </div>
                                        <div
                                            v-if="!project.images.length"
                                            class="w-16 h-16 bg-deep-black border border-gaming-red/50 flex-shrink-0 flex items-center justify-center rounded"
                                        >
                                            <span class="text-white/50 text-xs"
                                                >Aucune image</span
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-1 text-center">
                                    <span
                                        class="bg-deep-black border border-gaming-red/50 px-3 py-1 rounded-full text-white"
                                    >
                                        {{ project.order }}
                                    </span>
                                </div>

                                <div
                                    class="col-span-2 text-center flex flex-col items-center justify-center space-y-2"
                                >
                                    <span
                                        v-if="project.is_visible"
                                        class="bg-led-green/20 text-led-green px-3 py-1 rounded-full w-full max-w-24"
                                    >
                                        Visible
                                    </span>
                                    <span
                                        v-else
                                        class="bg-gaming-red/20 text-gaming-red px-3 py-1 rounded-full w-full max-w-24"
                                    >
                                        Masqué
                                    </span>
                                    <span
                                        v-if="project.is_featured"
                                        class="bg-gaming-red/20 text-gaming-red px-3 py-1 rounded-full w-full max-w-24"
                                    >
                                        Phare
                                    </span>
                                </div>

                                <div
                                    class="col-span-2 flex justify-center gap-2"
                                >
                                    <button
                                        @click="editProject(project)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                    >
                                        Éditer
                                    </button>
                                    <button
                                        @click="confirmDelete(project)"
                                        class="bg-gaming-red hover:bg-red-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                    >
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </transition-group>
                </div>
            </div>
        </div>

        <!-- Modal de confirmation de suppression -->
        <TransitionRoot appear :show="showDeleteModal" as="template">
            <Dialog
                as="div"
                @close="showDeleteModal = false"
                class="relative z-10"
            >
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/75 transition-opacity" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="flex min-h-full items-center justify-center p-4 text-center"
                    >
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel
                                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-deep-black border border-gaming-red p-6 text-left align-middle shadow-xl transition-all"
                            >
                                <div
                                    class="flex items-center justify-center mb-5 text-gaming-red"
                                >
                                    <ExclamationTriangleIcon
                                        class="h-12 w-12"
                                    />
                                </div>

                                <div class="text-center">
                                    <h3
                                        class="text-xl font-medium text-white mb-4"
                                    >
                                        Confirmer la suppression
                                    </h3>
                                    <p class="text-white/70 mb-6">
                                        Êtes-vous sûr de vouloir supprimer le
                                        projet : <br />
                                        <span
                                            class="font-semibold text-led-green"
                                            >{{ projectToDelete?.title }}</span
                                        >
                                    </p>
                                </div>

                                <div class="flex justify-center gap-4 mt-6">
                                    <button
                                        @click="showDeleteModal = false"
                                        class="inline-flex justify-center rounded-md border border-gaming-red bg-deep-black px-4 py-2 text-white hover:bg-gaming-red/10 transition-colors"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        @click="deleteProject"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-gaming-red px-4 py-2 text-white hover:bg-gaming-red/90 transition-colors"
                                    >
                                        Supprimer
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Toast de notification -->
        <div
            v-if="updateStatus"
            class="fixed bottom-4 right-4 p-4 rounded-lg shadow-lg transition-all duration-300 z-50"
            :class="{
                'bg-led-green text-white': updateStatus === 'success',
                'bg-gaming-red text-white': updateStatus === 'error',
                'bg-blue-500 text-white': updateStatus === 'loading',
            }"
        >
            <div class="flex items-center space-x-2">
                <svg
                    v-if="updateStatus === 'success'"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"
                    />
                </svg>
                <svg
                    v-if="updateStatus === 'error'"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"
                    />
                </svg>
                <svg
                    v-if="updateStatus === 'loading'"
                    class="animate-spin h-5 w-5"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
                <span>{{ updateMessage }}</span>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.list-enter-active,
.list-leave-active {
    transition: all 0.3s ease;
}
.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(20px);
}
</style>
