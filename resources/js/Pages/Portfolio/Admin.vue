<script setup>
import { ref, onMounted, computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm, router } from "@inertiajs/vue3";
import AdminSearchHeader from "@/Components/Admin/AdminSearchHeader.vue";
import AdminSortHeader from "@/Components/Admin/AdminSortHeader.vue";
import ConfirmDeleteModal from "@/Components/Admin/ConfirmDeleteModal.vue";
import StatusToast from "@/Components/Admin/StatusToast.vue";
import AdminFormButtons from "@/Components/Admin/AdminFormButtons.vue";
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

// Définir les colonnes pour le tri
const sortColumns = [
    { key: "title", label: "Titre", colSpan: 3 },
    { key: "images", label: "Images", colSpan: 4 },
    { key: "order", label: "Ordre", colSpan: 1 },
    { key: "is_visible", label: "Statut", colSpan: 2 },
    { key: "actions", label: "Actions", colSpan: 2 },
];

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
            <AdminSearchHeader
                title="Gestion du Portfolio"
                v-model:searchQuery="searchQuery"
            />
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Formulaire -->
                <div
                    id="portfolio-form"
                    class="bg-deep-black p-4 sm:p-6 rounded-lg shadow mb-8 border border-gaming-red/30 hover:border-gaming-red/70 transition-all duration-300"
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
                                    class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6"
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

                        <AdminFormButtons
                            :processing="form.processing"
                            :is-editing="!!editing"
                            @cancel="cancelEdit"
                        />
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
                    <AdminSortHeader
                        :sort-by="sortBy"
                        :sort-direction="sortDirection"
                        :columns="sortColumns"
                        @sort="toggleSort"
                    />

                    <transition-group name="list" tag="div" class="space-y-4">
                        <div
                            v-for="project in filteredProjects"
                            :key="project.id"
                            class="bg-deep-black p-4 sm:p-6 rounded-lg shadow border border-gaming-red/10 hover:border-gaming-red/40 transition-all duration-300"
                        >
                            <!-- Vue mobile/tablette (structure en carte) -->
                            <div class="lg:hidden">
                                <h3
                                    class="text-xl font-play font-semibold text-led-green mb-2"
                                >
                                    {{ project.title }}
                                </h3>
                                <p class="text-white/80 mb-3">
                                    {{ project.description }}
                                </p>

                                <!-- Images en version mobile -->
                                <div
                                    class="flex space-x-2 overflow-x-auto pb-2 mb-3"
                                >
                                    <div
                                        v-for="(
                                            image, idx
                                        ) in project.images.slice(0, 4)"
                                        :key="image.id"
                                        class="w-20 h-20 flex-shrink-0 overflow-hidden rounded"
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
                                        class="w-20 h-20 bg-deep-black border border-gaming-red/50 flex-shrink-0 flex items-center justify-center rounded text-white"
                                    >
                                        +{{ project.images.length - 4 }}
                                    </div>
                                    <div
                                        v-if="!project.images.length"
                                        class="w-20 h-20 bg-deep-black border border-gaming-red/50 flex-shrink-0 flex items-center justify-center rounded"
                                    >
                                        <span class="text-white/50 text-xs"
                                            >Aucune image</span
                                        >
                                    </div>
                                </div>

                                <div
                                    class="flex flex-wrap gap-4 mt-4 justify-between items-center"
                                >
                                    <div class="flex flex-wrap gap-3">
                                        <div>
                                            <span
                                                class="text-white text-sm mr-2"
                                                >Ordre:</span
                                            >
                                            <span
                                                class="bg-deep-black border border-gaming-red/50 px-3 py-1 rounded-full text-white"
                                            >
                                                {{ project.order }}
                                            </span>
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            <span
                                                v-if="project.is_visible"
                                                class="bg-led-green/20 text-led-green px-3 py-1 rounded-full"
                                            >
                                                Visible
                                            </span>
                                            <span
                                                v-else
                                                class="bg-gaming-red/20 text-gaming-red px-3 py-1 rounded-full"
                                            >
                                                Masqué
                                            </span>
                                            <span
                                                v-if="project.is_featured"
                                                class="bg-gaming-red/20 text-gaming-red px-3 py-1 rounded-full"
                                            >
                                                Phare
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex gap-2 mt-2 sm:mt-0">
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

                            <!-- Vue desktop (structure en grille) -->
                            <div
                                class="hidden lg:grid grid-cols-12 gap-4 items-start"
                            >
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

        <ConfirmDeleteModal
            :show="showDeleteModal"
            :item-name="projectToDelete?.title"
            item-label="le projet"
            @close="showDeleteModal = false"
            @confirm="deleteProject"
        />

        <StatusToast :status="updateStatus" :message="updateMessage" />
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
