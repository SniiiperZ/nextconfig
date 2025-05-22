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
import {
    ExclamationTriangleIcon,
    MagnifyingGlassIcon,
    ArrowUpIcon,
    ArrowDownIcon,
} from "@heroicons/vue/24/outline";

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
            toolbar: [
                "heading",
                "|",
                "bold",
                "italic",
                "link",
                "bulletedList",
                "numberedList",
                "blockQuote",
                "insertTable",
                "undo",
                "redo",
            ],
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
            <div
                class="flex flex-col sm:flex-row justify-between items-center gap-4"
            >
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Gestion du Blog
                </h2>
                <div class="relative w-full sm:w-auto">
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
                        <div class="flex flex-wrap gap-4 pt-4">
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

                <!-- Liste des articles -->
                <div
                    v-if="filteredPosts.length === 0"
                    class="bg-deep-black p-6 rounded-lg shadow text-center"
                >
                    <p class="text-white/80">Aucun article trouvé</p>
                </div>

                <div v-else class="space-y-4">
                    <!-- En-têtes du tableau - visibles uniquement sur desktop -->
                    <div
                        class="hidden lg:grid bg-gaming-red/20 p-4 rounded-lg mb-4 grid-cols-12 gap-4 items-center font-semibold text-white"
                    >
                        <div
                            class="col-span-4 flex items-center cursor-pointer"
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
                        <div
                            class="col-span-2 cursor-pointer"
                            @click="toggleSort('created_at')"
                        >
                            Date
                            <ArrowUpIcon
                                v-if="
                                    sortBy === 'created_at' &&
                                    sortDirection === 'asc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                            <ArrowDownIcon
                                v-else-if="
                                    sortBy === 'created_at' &&
                                    sortDirection === 'desc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                        </div>
                        <div
                            class="col-span-2 text-center cursor-pointer"
                            @click="toggleSort('is_published')"
                        >
                            Statut
                            <ArrowUpIcon
                                v-if="
                                    sortBy === 'is_published' &&
                                    sortDirection === 'asc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                            <ArrowDownIcon
                                v-else-if="
                                    sortBy === 'is_published' &&
                                    sortDirection === 'desc'
                                "
                                class="h-4 w-4 ml-1 inline"
                            />
                        </div>
                        <div
                            class="col-span-2 cursor-pointer"
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
                        <div class="col-span-2 text-center">Actions</div>
                    </div>

                    <!-- Barre de tri mobile visible uniquement sur petit écran -->
                    <div
                        class="lg:hidden bg-gaming-red/20 p-3 rounded-lg mb-4 text-white"
                    >
                        <div class="flex justify-between items-center">
                            <div class="text-sm font-medium">Trier par:</div>
                            <div class="flex gap-2 flex-wrap">
                                <button
                                    @click="toggleSort('title')"
                                    class="px-2 py-1 rounded text-sm flex items-center"
                                    :class="{
                                        'bg-led-green text-deep-black':
                                            sortBy === 'title',
                                        'bg-deep-black/30': sortBy !== 'title',
                                    }"
                                >
                                    Titre
                                    <ArrowUpIcon
                                        v-if="
                                            sortBy === 'title' &&
                                            sortDirection === 'asc'
                                        "
                                        class="h-3 w-3 ml-1"
                                    />
                                    <ArrowDownIcon
                                        v-else-if="
                                            sortBy === 'title' &&
                                            sortDirection === 'desc'
                                        "
                                        class="h-3 w-3 ml-1"
                                    />
                                </button>
                                <button
                                    @click="toggleSort('created_at')"
                                    class="px-2 py-1 rounded text-sm flex items-center"
                                    :class="{
                                        'bg-led-green text-deep-black':
                                            sortBy === 'created_at',
                                        'bg-deep-black/30':
                                            sortBy !== 'created_at',
                                    }"
                                >
                                    Date
                                    <ArrowUpIcon
                                        v-if="
                                            sortBy === 'created_at' &&
                                            sortDirection === 'asc'
                                        "
                                        class="h-3 w-3 ml-1"
                                    />
                                    <ArrowDownIcon
                                        v-else-if="
                                            sortBy === 'created_at' &&
                                            sortDirection === 'desc'
                                        "
                                        class="h-3 w-3 ml-1"
                                    />
                                </button>
                                <button
                                    @click="toggleSort('is_published')"
                                    class="px-2 py-1 rounded text-sm flex items-center"
                                    :class="{
                                        'bg-led-green text-deep-black':
                                            sortBy === 'is_published',
                                        'bg-deep-black/30':
                                            sortBy !== 'is_published',
                                    }"
                                >
                                    Statut
                                    <ArrowUpIcon
                                        v-if="
                                            sortBy === 'is_published' &&
                                            sortDirection === 'asc'
                                        "
                                        class="h-3 w-3 ml-1"
                                    />
                                    <ArrowDownIcon
                                        v-else-if="
                                            sortBy === 'is_published' &&
                                            sortDirection === 'desc'
                                        "
                                        class="h-3 w-3 ml-1"
                                    />
                                </button>
                                <button
                                    @click="toggleSort('order')"
                                    class="px-2 py-1 rounded text-sm flex items-center"
                                    :class="{
                                        'bg-led-green text-deep-black':
                                            sortBy === 'order',
                                        'bg-deep-black/30': sortBy !== 'order',
                                    }"
                                >
                                    Ordre
                                    <ArrowUpIcon
                                        v-if="
                                            sortBy === 'order' &&
                                            sortDirection === 'asc'
                                        "
                                        class="h-3 w-3 ml-1"
                                    />
                                    <ArrowDownIcon
                                        v-else-if="
                                            sortBy === 'order' &&
                                            sortDirection === 'desc'
                                        "
                                        class="h-3 w-3 ml-1"
                                    />
                                </button>
                            </div>
                        </div>
                    </div>

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
                                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-deep-black border border-gaming-red p-4 sm:p-6 text-left align-middle shadow-xl transition-all"
                            >
                                <div
                                    class="flex items-center justify-center mb-5 text-gaming-red"
                                >
                                    <ExclamationTriangleIcon
                                        class="h-10 w-10 sm:h-12 sm:w-12"
                                    />
                                </div>

                                <div class="text-center">
                                    <h3
                                        class="text-lg sm:text-xl font-medium text-white mb-4"
                                    >
                                        Confirmer la suppression
                                    </h3>
                                    <p
                                        class="text-white/70 mb-6 text-sm sm:text-base"
                                    >
                                        Êtes-vous sûr de vouloir supprimer
                                        l'article : <br />
                                        <span
                                            class="font-semibold text-led-green break-words"
                                            >{{ postToDelete?.title }}</span
                                        >
                                    </p>
                                </div>

                                <div class="flex justify-center gap-4 mt-6">
                                    <button
                                        @click="showDeleteModal = false"
                                        class="inline-flex justify-center rounded-md border border-gaming-red bg-deep-black px-3 py-2 sm:px-4 sm:py-2 text-sm sm:text-base text-white hover:bg-gaming-red/10 transition-colors"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        @click="deletePost"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-gaming-red px-3 py-2 sm:px-4 sm:py-2 text-sm sm:text-base text-white hover:bg-gaming-red/90 transition-colors"
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
    }
}

/* Animations pour la liste */
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
