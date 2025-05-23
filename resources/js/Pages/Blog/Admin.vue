<script setup>
import { ref, onMounted, watch, computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm, router } from "@inertiajs/vue3";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
} from "@headlessui/vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline";
import AdminSearchHeader from "@/Components/Admin/AdminSearchHeader.vue";
import AdminSortHeader from "@/Components/Admin/AdminSortHeader.vue";
import ConfirmDeleteModal from "@/Components/Admin/ConfirmDeleteModal.vue";
import StatusToast from "@/Components/Admin/StatusToast.vue";
import AdminFormButtons from "@/Components/Admin/AdminFormButtons.vue";

const props = defineProps({
    posts: Array,
    tags: Array,
});

// État pour l'éditeur
const editing = ref(null);
const showTagForm = ref(false);
const previewImage = ref(null);
const editorContent = ref("");
const showDeleteModal = ref(false);
const postToDelete = ref(null);
const searchQuery = ref("");
const sortBy = ref("created_at");
const sortDirection = ref("desc");
const updateMessage = ref("");
const updateStatus = ref(null); // null, 'success' ou 'error'

// Définir les colonnes pour le tri
const sortColumns = [
    { key: "title", label: "Titre", colSpan: 4 },
    { key: "created_at", label: "Date", colSpan: 2 },
    { key: "is_published", label: "Statut", colSpan: 2 },
    { key: "order", label: "Ordre", colSpan: 2 },
    { key: "actions", label: "Actions", colSpan: 2 },
];

// Filtrage et tri des articles
const filteredPosts = computed(() => {
    let filtered = [...props.posts];

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (post) =>
                post.title.toLowerCase().includes(query) ||
                post.excerpt?.toLowerCase().includes(query) ||
                post.content?.toLowerCase().includes(query) ||
                post.tags.some((tag) => tag.name.toLowerCase().includes(query))
        );
    }

    filtered.sort((a, b) => {
        let comparison = 0;
        let aValue = a[sortBy.value];
        let bValue = b[sortBy.value];

        // Gérer les dates
        if (sortBy.value === "published_at" || sortBy.value === "created_at") {
            aValue = aValue ? new Date(aValue) : new Date(0);
            bValue = bValue ? new Date(bValue) : new Date(0);
        }

        if (aValue > bValue) {
            comparison = 1;
        } else if (aValue < bValue) {
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
        sortDirection.value = "desc";
    }
};

// Configurer CKEditor
let editor = null;
onMounted(() => {
    if (typeof ClassicEditor !== "undefined") {
        ClassicEditor.create(document.querySelector("#editor"), {
            toolbar: {
                items: [
                    "heading",
                    "|",
                    "bold",
                    "italic",
                    "link",
                    "|",
                    "bulletedList",
                    "numberedList",
                    "|",
                    "blockQuote",
                    "insertTable",
                    "|",
                    "undo",
                    "redo",
                ],
                // Configuration pour responsive
                shouldNotGroupWhenFull: true,
                // Permet de définir comment les éléments seront répartis
                viewportTopOffset: 60,
            },
        })
            .then((newEditor) => {
                editor = newEditor;

                // Mettre à jour le contenu du formulaire lorsque l'éditeur change
                editor.model.document.on("change:data", () => {
                    form.content = editor.getData();
                });

                // Si nous sommes en mode édition, initialiser l'éditeur
                if (editing.value) {
                    editor.setData(editing.value.content);
                }
            })
            .catch((error) => {
                console.error(error);
            });
    }
});

// Observer les changements sur editing pour mettre à jour l'éditeur
watch(editing, (newValue) => {
    if (editor && newValue) {
        editor.setData(newValue.content);
    } else if (editor) {
        editor.setData("");
    }
});

// Formulaire pour les articles
const form = useForm({
    title: "",
    slug: "",
    excerpt: "",
    content: "",
    featured_image: null,
    is_published: false,
    order: 0,
    tags: [],
    delete_image: false,
});

// Formulaire pour les tags
const tagForm = useForm({
    name: "",
    slug: "",
});

// Soumettre le formulaire d'article
const submit = () => {
    if (editing.value) {
        form.post(route("admin.blog.update", editing.value.id), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                resetForm();
                updateStatus.value = "success";
                updateMessage.value = "Article mis à jour avec succès !";
                setTimeout(() => {
                    updateStatus.value = null;
                    updateMessage.value = "";
                }, 3000);
            },
        });
    } else {
        form.post(route("admin.blog.store"), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                resetForm();
                updateStatus.value = "success";
                updateMessage.value = "Article ajouté avec succès !";
                setTimeout(() => {
                    updateStatus.value = null;
                    updateMessage.value = "";
                }, 3000);
            },
        });
    }
};

// Réinitialiser le formulaire
const resetForm = () => {
    editing.value = null;
    previewImage.value = null;
    form.reset();
    if (editor) {
        editor.setData("");
    }
};

// Éditer un article existant
const editPost = (post) => {
    editing.value = post;
    form.title = post.title;
    form.slug = post.slug;
    form.excerpt = post.excerpt || "";
    form.content = post.content;
    form.is_published = post.is_published;
    form.order = post.order;
    form.tags = post.tags.map((tag) => tag.id);
    form.delete_image = false;

    // Prévisualisation de l'image
    if (post.featured_image) {
        previewImage.value = `/storage/${post.featured_image}`;
    } else {
        previewImage.value = null;
    }

    // Scroll to form
    setTimeout(() => {
        document
            .querySelector("#blog-form")
            .scrollIntoView({ behavior: "smooth" });
    }, 100);
};

// Annuler l'édition
const cancelEdit = () => {
    resetForm();
};

// Confirmer la suppression
const confirmDelete = (post) => {
    postToDelete.value = post;
    showDeleteModal.value = true;
};

// Supprimer un article
const deletePost = () => {
    router.delete(route("admin.blog.destroy", postToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            showDeleteModal.value = false;
            postToDelete.value = null;
            updateStatus.value = "success";
            updateMessage.value = "Article supprimé avec succès !";
            setTimeout(() => {
                updateStatus.value = null;
                updateMessage.value = "";
            }, 3000);
        },
    });
};

// Gérer le changement d'image
const handleImageChange = (event) => {
    const file = event.target.files[0];
    if (!file) {
        return;
    }

    form.featured_image = file;

    // Prévisualisation
    const reader = new FileReader();
    reader.onload = (e) => {
        previewImage.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

// Supprimer l'image
const removeImage = () => {
    form.featured_image = null;
    form.delete_image = true;
    previewImage.value = null;
};

// Soumettre le formulaire de tag
const submitTag = () => {
    tagForm.post(route("admin.tags.store"), {
        preserveScroll: true,
        onSuccess: () => {
            tagForm.reset();
            showTagForm.value = false;
            updateStatus.value = "success";
            updateMessage.value = "Tag ajouté avec succès !";
            setTimeout(() => {
                updateStatus.value = null;
                updateMessage.value = "";
            }, 3000);
        },
    });
};

// Confirmer la suppression d'un tag
const confirmDeleteTag = (tag) => {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le tag "${tag.name}" ?`)) {
        tagForm.delete(route("admin.tags.destroy", tag.id), {
            preserveScroll: true,
            onSuccess: () => {
                updateStatus.value = "success";
                updateMessage.value = "Tag supprimé avec succès !";
                setTimeout(() => {
                    updateStatus.value = null;
                    updateMessage.value = "";
                }, 3000);
            },
        });
    }
};

// Formater la date pour l'affichage
const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};
</script>

<template>
    <AdminLayout title="Gestion du Blog">
        <template #header>
            <AdminSearchHeader
                title="Gestion du Blog"
                v-model:searchQuery="searchQuery"
            />
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Formulaire de l'article -->
                <div
                    id="blog-form"
                    class="bg-deep-black p-4 sm:p-6 rounded-lg shadow mb-8 border border-gaming-red/30 hover:border-gaming-red/70 transition-all duration-300"
                >
                    <h3
                        class="text-xl font-play font-semibold text-led-green mb-4"
                    >
                        {{
                            editing
                                ? "Modifier un article"
                                : "Ajouter un nouvel article"
                        }}
                    </h3>

                    <form @submit.prevent="submit" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <!-- Titre -->
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

                                <!-- Slug -->
                                <div>
                                    <label
                                        class="block text-white mb-2 font-medium"
                                    >
                                        Slug
                                        <span class="text-xs text-white/60"
                                            >(optionnel, généré
                                            automatiquement)</span
                                        >
                                    </label>
                                    <input
                                        v-model="form.slug"
                                        type="text"
                                        class="w-full bg-deep-black border border-gaming-red rounded-md px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-200"
                                        :class="{
                                            'border-red-500': form.errors.slug,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.slug"
                                        class="text-red-500 mt-1 text-sm"
                                    >
                                        {{ form.errors.slug }}
                                    </p>
                                </div>

                                <!-- Extrait -->
                                <div>
                                    <label
                                        class="block text-white mb-2 font-medium"
                                    >
                                        Extrait
                                        <span class="text-xs text-white/60"
                                            >(optionnel)</span
                                        >
                                    </label>
                                    <textarea
                                        v-model="form.excerpt"
                                        rows="3"
                                        class="w-full bg-deep-black border border-gaming-red rounded-md px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-200"
                                        :class="{
                                            'border-red-500':
                                                form.errors.excerpt,
                                        }"
                                    ></textarea>
                                    <p
                                        v-if="form.errors.excerpt"
                                        class="text-red-500 mt-1 text-sm"
                                    >
                                        {{ form.errors.excerpt }}
                                    </p>
                                </div>

                                <!-- Options de l'article -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6"
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
                                                    form.is_published =
                                                        !form.is_published
                                                "
                                                :class="[
                                                    'px-4 py-2 rounded-md cursor-pointer transition-colors duration-200',
                                                    form.is_published
                                                        ? 'bg-led-green text-deep-black font-medium'
                                                        : 'bg-gaming-red text-white font-medium',
                                                ]"
                                            >
                                                {{
                                                    form.is_published
                                                        ? "Publié"
                                                        : "Brouillon"
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sélection des tags -->
                                <div>
                                    <div
                                        class="flex justify-between items-center mb-2"
                                    >
                                        <label
                                            class="block text-white font-medium"
                                            >Tags</label
                                        >
                                        <button
                                            type="button"
                                            @click="showTagForm = !showTagForm"
                                            class="text-sm text-led-green hover:text-gaming-red transition-colors duration-200"
                                        >
                                            {{
                                                showTagForm
                                                    ? "Annuler"
                                                    : "Ajouter un tag"
                                            }}
                                        </button>
                                    </div>

                                    <!-- Formulaire d'ajout de tag -->
                                    <div
                                        v-if="showTagForm"
                                        class="mb-4 bg-deep-black/50 p-3 rounded border border-gaming-red/30"
                                    >
                                        <div class="flex gap-3">
                                            <div class="flex-grow">
                                                <input
                                                    v-model="tagForm.name"
                                                    type="text"
                                                    placeholder="Nom du tag"
                                                    class="w-full bg-deep-black border border-gaming-red rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-200 text-sm"
                                                />
                                            </div>
                                            <button
                                                type="button"
                                                @click="submitTag"
                                                class="bg-led-green text-deep-black px-3 py-1 rounded font-medium text-sm transition-colors duration-200 hover:bg-led-green/90"
                                                :disabled="tagForm.processing"
                                            >
                                                <span
                                                    v-if="tagForm.processing"
                                                    class="mr-2"
                                                >
                                                    <svg
                                                        class="animate-spin h-4 w-4 text-white inline"
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
                                                Ajouter
                                            </button>
                                        </div>
                                        <p
                                            v-if="tagForm.errors.name"
                                            class="text-red-500 text-xs mt-1"
                                        >
                                            {{ tagForm.errors.name }}
                                        </p>
                                    </div>

                                    <!-- Liste des tags disponibles -->
                                    <div
                                        class="bg-deep-black/50 p-3 rounded border border-gaming-red/30"
                                    >
                                        <div
                                            v-if="tags.length === 0"
                                            class="text-white/60 text-sm"
                                        >
                                            Aucun tag disponible. Créez-en un
                                            nouveau.
                                        </div>
                                        <div
                                            v-else
                                            class="flex flex-wrap gap-2"
                                        >
                                            <div
                                                v-for="tag in tags"
                                                :key="tag.id"
                                                class="inline-flex items-center"
                                            >
                                                <label
                                                    class="flex items-center bg-deep-black/70 rounded-md p-1 cursor-pointer hover:bg-gaming-red/10 border border-gaming-red/30 transition-all duration-200"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        :value="tag.id"
                                                        v-model="form.tags"
                                                        class="rounded border-gaming-red bg-deep-black text-led-green shadow-sm focus:ring-led-green mr-1"
                                                    />
                                                    <span
                                                        class="text-white text-sm"
                                                        >{{ tag.name }}</span
                                                    >
                                                    <button
                                                        @click.prevent="
                                                            confirmDeleteTag(
                                                                tag
                                                            )
                                                        "
                                                        class="ml-1 text-gaming-red hover:text-red-500 transition-colors duration-200"
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="h-3 w-3"
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
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <!-- Image mise en avant -->
                                <div>
                                    <label
                                        class="block text-white mb-2 font-medium"
                                        >Image mise en avant</label
                                    >
                                    <input
                                        type="file"
                                        @change="handleImageChange"
                                        accept="image/*"
                                        class="w-full bg-deep-black border border-gaming-red rounded-md px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-led-green transition-all duration-200"
                                        :class="{
                                            'border-red-500':
                                                form.errors.featured_image,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.featured_image"
                                        class="text-red-500 mt-1 text-sm"
                                    >
                                        {{ form.errors.featured_image }}
                                    </p>
                                </div>

                                <!-- Prévisualisation de l'image -->
                                <div v-if="previewImage" class="relative">
                                    <img
                                        :src="previewImage"
                                        alt="Prévisualisation"
                                        class="max-h-64 rounded border border-gaming-red object-cover"
                                    />
                                    <button
                                        @click.prevent="removeImage"
                                        class="absolute top-2 right-2 bg-gaming-red text-white rounded-full p-1 hover:bg-red-600 transition-colors duration-200"
                                        title="Supprimer l'image"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
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

                                <!-- Contenu de l'article avec CKEditor -->
                                <div>
                                    <label
                                        class="block text-white mb-2 font-medium"
                                        >Contenu</label
                                    >
                                    <div class="rounded">
                                        <textarea id="editor"></textarea>
                                    </div>
                                    <p
                                        v-if="form.errors.content"
                                        class="text-red-500 mt-1 text-sm"
                                    >
                                        {{ form.errors.content }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons du formulaire -->
                        <AdminFormButtons
                            :processing="form.processing"
                            :is-editing="!!editing"
                            @cancel="cancelEdit"
                        />
                    </form>
                </div>

                <!-- Liste des articles -->
                <div
                    v-if="filteredPosts.length === 0"
                    class="bg-deep-black p-6 rounded-lg shadow text-center"
                >
                    <p class="text-white/80">Aucun article trouvé</p>
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
                            v-for="post in filteredPosts"
                            :key="post.id"
                            class="bg-deep-black p-4 sm:p-6 rounded-lg shadow border border-gaming-red/10 hover:border-gaming-red/40 transition-all duration-300"
                        >
                            <!-- Vue mobile/tablette (structure en carte) -->
                            <div class="lg:hidden">
                                <div class="flex items-start space-x-3 mb-3">
                                    <!-- Image en miniature -->
                                    <div class="w-20 h-20 flex-shrink-0">
                                        <img
                                            v-if="post.featured_image"
                                            :src="`/storage/${post.featured_image}`"
                                            :alt="post.title"
                                            class="w-full h-full object-cover rounded border border-gaming-red/50"
                                        />
                                        <div
                                            v-else
                                            class="w-full h-full bg-deep-black/50 flex items-center justify-center rounded border border-gaming-red/50"
                                        >
                                            <span class="text-gray-500 text-xs"
                                                >Pas d'image</span
                                            >
                                        </div>
                                    </div>

                                    <div class="flex-grow">
                                        <h3
                                            class="text-lg font-play font-semibold text-led-green"
                                        >
                                            {{ post.title }}
                                        </h3>
                                        <p
                                            class="text-white/80 text-sm line-clamp-2 mt-1"
                                        >
                                            {{
                                                post.excerpt ||
                                                post.content
                                                    .replace(/<[^>]*>?/gm, "")
                                                    .substring(0, 80) + "..."
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-y-2">
                                    <!-- Date -->
                                    <div class="w-full">
                                        <span class="text-white/70 text-sm">
                                            Date:
                                            {{
                                                formatDate(
                                                    post.published_at ||
                                                        post.created_at
                                                )
                                            }}
                                        </span>
                                    </div>

                                    <!-- Tags -->
                                    <div v-if="post.tags.length" class="w-full">
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            <span
                                                v-for="tag in post.tags.slice(
                                                    0,
                                                    3
                                                )"
                                                :key="tag.id"
                                                class="text-xs bg-deep-black text-white px-2 py-0.5 rounded border border-gaming-red/50"
                                            >
                                                {{ tag.name }}
                                            </span>
                                            <span
                                                v-if="post.tags.length > 3"
                                                class="text-xs bg-deep-black text-white px-2 py-0.5 rounded border border-gaming-red/50"
                                            >
                                                +{{ post.tags.length - 3 }}
                                            </span>
                                        </div>
                                    </div>

                                    <div
                                        class="flex flex-wrap justify-between w-full mt-2 items-center gap-y-2"
                                    >
                                        <div class="flex gap-2">
                                            <!-- Statut -->
                                            <span
                                                v-if="!post.is_published"
                                                class="bg-gaming-red/20 text-gaming-red px-3 py-1 rounded-full"
                                            >
                                                Brouillon
                                            </span>
                                            <span
                                                v-else-if="
                                                    post.published_at &&
                                                    new Date(
                                                        post.published_at
                                                    ) > new Date()
                                                "
                                                class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full"
                                            >
                                                Programmé
                                            </span>
                                            <span
                                                v-else
                                                class="bg-led-green/20 text-led-green px-3 py-1 rounded-full"
                                            >
                                                Publié
                                            </span>

                                            <!-- Ordre -->
                                            <span
                                                class="bg-deep-black border border-gaming-red/50 px-3 py-1 rounded-full text-white"
                                            >
                                                {{ post.order }}
                                            </span>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex gap-2">
                                            <button
                                                @click="editPost(post)"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                            >
                                                Éditer
                                            </button>
                                            <button
                                                @click="confirmDelete(post)"
                                                class="bg-gaming-red hover:bg-red-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                            >
                                                Supprimer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Vue desktop (structure en grille) -->
                            <div
                                class="hidden lg:grid grid-cols-12 gap-4 items-start"
                            >
                                <div class="col-span-4">
                                    <div class="flex items-start space-x-3">
                                        <!-- Image en miniature -->
                                        <div class="w-20 h-20 flex-shrink-0">
                                            <img
                                                v-if="post.featured_image"
                                                :src="`/storage/${post.featured_image}`"
                                                :alt="post.title"
                                                class="w-full h-full object-cover rounded border border-gaming-red/50"
                                            />
                                            <div
                                                v-else
                                                class="w-full h-full bg-deep-black/50 flex items-center justify-center rounded border border-gaming-red/50"
                                            >
                                                <span
                                                    class="text-gray-500 text-xs"
                                                    >Pas d'image</span
                                                >
                                            </div>
                                        </div>

                                        <div>
                                            <h4
                                                class="text-lg font-play font-semibold text-led-green"
                                            >
                                                {{ post.title }}
                                            </h4>
                                            <p
                                                class="text-white/80 text-sm line-clamp-2 mt-1"
                                            >
                                                {{
                                                    post.excerpt ||
                                                    post.content
                                                        .replace(
                                                            /<[^>]*>?/gm,
                                                            ""
                                                        )
                                                        .substring(0, 80) +
                                                        "..."
                                                }}
                                            </p>

                                            <!-- Tags -->
                                            <div
                                                v-if="post.tags.length"
                                                class="flex flex-wrap gap-1 mt-2"
                                            >
                                                <span
                                                    v-for="tag in post.tags.slice(
                                                        0,
                                                        2
                                                    )"
                                                    :key="tag.id"
                                                    class="text-xs bg-deep-black text-white px-2 py-0.5 rounded border border-gaming-red/50"
                                                >
                                                    {{ tag.name }}
                                                </span>
                                                <span
                                                    v-if="post.tags.length > 2"
                                                    class="text-xs bg-deep-black text-white px-2 py-0.5 rounded border border-gaming-red/50"
                                                >
                                                    +{{ post.tags.length - 2 }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-2 text-white/80">
                                    {{
                                        formatDate(
                                            post.published_at || post.created_at
                                        )
                                    }}
                                </div>

                                <div class="col-span-2 text-center">
                                    <span
                                        v-if="!post.is_published"
                                        class="bg-gaming-red/20 text-gaming-red px-3 py-1 rounded-full max-w-24 inline-block"
                                    >
                                        Brouillon
                                    </span>
                                    <span
                                        v-else-if="
                                            post.published_at &&
                                            new Date(post.published_at) >
                                                new Date()
                                        "
                                        class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full max-w-24 inline-block"
                                    >
                                        Programmé
                                    </span>
                                    <span
                                        v-else
                                        class="bg-led-green/20 text-led-green px-3 py-1 rounded-full max-w-24 inline-block"
                                    >
                                        Publié
                                    </span>
                                </div>

                                <div class="col-span-2 text-center">
                                    <span
                                        class="bg-deep-black border border-gaming-red/50 px-3 py-1 rounded-full text-white"
                                    >
                                        {{ post.order }}
                                    </span>
                                </div>

                                <div
                                    class="col-span-2 flex justify-center gap-2"
                                >
                                    <button
                                        @click="editPost(post)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                    >
                                        Éditer
                                    </button>
                                    <button
                                        @click="confirmDelete(post)"
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
        <ConfirmDeleteModal
            :show="showDeleteModal"
            :item-name="postToDelete?.title"
            item-label="l'article"
            @close="showDeleteModal = false"
            @confirm="deletePost"
        />

        <!-- Toast de notification -->
        <StatusToast :status="updateStatus" :message="updateMessage" />
    </AdminLayout>
</template>

<style>
/* Styles pour l'éditeur CKEditor */
.ck-editor__editable {
    min-height: 300px;
    color: black !important; /* Force la couleur du texte en noir */
}

ol,
ul,
menu {
    padding: 0 1.5em;
}

/* Styles pour assurer la lisibilité dans l'éditeur */
.ck.ck-editor__main > .ck-editor__editable {
    background-color: white !important;
    color: black !important;
}

/* Styles pour les contrôles de la barre d'outils */
.ck.ck-toolbar {
    background-color: #f0f0f0 !important;
    border-color: #ccc !important;
}

.ck.ck-button {
    color: #333 !important;
}

.ck.ck-button.ck-on {
    background-color: #e0e0e0 !important;
}

/* Styles pour améliorer l'expérience sur mobile */
@media (max-width: 640px) {
    .ck-editor__editable {
        min-height: 200px;
    }

    .ck.ck-toolbar {
        flex-wrap: wrap;
        padding: 8px;
        gap: 4px;
    }

    /* Forcer les groupes d'outils à occuper toute la largeur sur mobile */
    .ck.ck-toolbar > .ck-toolbar__items {
        flex-wrap: wrap;
        width: 100%;
        gap: 6px;
    }

    /* Donner plus d'espace à chaque bouton sur mobile */
    .ck.ck-toolbar .ck-button {
        padding: 8px;
        margin: 2px;
    }

    /* Organiser les séparateurs pour les sauts de ligne */
    .ck.ck-toolbar .ck-toolbar__separator {
        width: 100%;
        height: 1px;
        margin: 4px 0;
    }
}

/* Animation plus fluide pour les transitions */
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
