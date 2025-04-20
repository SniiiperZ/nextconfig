<script setup>
import { ref, onMounted, watch } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    posts: Array,
    tags: Array,
});

// État pour l'éditeur
const editing = ref(null);
const showTagForm = ref(false);
const previewImage = ref(null);
const editorContent = ref("");

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
    published_at: null,
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
            },
        });
    } else {
        form.post(route("admin.blog.store"), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                resetForm();
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
    form.published_at = post.published_at;
    form.order = post.order;
    form.tags = post.tags.map((tag) => tag.id);
    form.delete_image = false;

    // Prévisualisation de l'image
    if (post.featured_image) {
        previewImage.value = `/storage/${post.featured_image}`;
    } else {
        previewImage.value = null;
    }
};

// Annuler l'édition
const cancelEdit = () => {
    resetForm();
};

// Supprimer un article
const deletePost = (post) => {
    if (
        confirm(
            `Êtes-vous sûr de vouloir supprimer l'article "${post.title}" ?`
        )
    ) {
        form.delete(route("admin.blog.destroy", post.id), {
            preserveScroll: true,
        });
    }
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
        },
    });
};

// Supprimer un tag
const deleteTag = (tag) => {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le tag "${tag.name}" ?`)) {
        tagForm.delete(route("admin.tags.destroy", tag.id), {
            preserveScroll: true,
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion du Blog
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Formulaire de l'article -->
                <div class="bg-deep-black p-6 rounded-lg shadow mb-6">
                    <form @submit.prevent="submit" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <!-- Titre -->
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

                                <!-- Slug -->
                                <div>
                                    <label class="block text-white mb-2">
                                        Slug
                                        <span class="text-xs text-white/60"
                                            >(optionnel, généré
                                            automatiquement)</span
                                        >
                                    </label>
                                    <input
                                        v-model="form.slug"
                                        type="text"
                                        class="w-full bg-gray-800 text-white rounded px-3 py-2"
                                    />
                                    <p
                                        v-if="form.errors.slug"
                                        class="text-red-500 text-sm mt-1"
                                    >
                                        {{ form.errors.slug }}
                                    </p>
                                </div>

                                <!-- Extrait -->
                                <div>
                                    <label class="block text-white mb-2">
                                        Extrait
                                        <span class="text-xs text-white/60"
                                            >(optionnel)</span
                                        >
                                    </label>
                                    <textarea
                                        v-model="form.excerpt"
                                        rows="3"
                                        class="w-full bg-gray-800 text-white rounded px-3 py-2"
                                    ></textarea>
                                </div>

                                <!-- Date de publication -->
                                <div>
                                    <label class="block text-white mb-2">
                                        Date de publication
                                        <span class="text-xs text-white/60"
                                            >(optionnel)</span
                                        >
                                    </label>
                                    <input
                                        v-model="form.published_at"
                                        type="datetime-local"
                                        class="w-full bg-gray-800 text-white rounded px-3 py-2"
                                    />
                                </div>

                                <!-- Options de l'article -->
                                <div class="flex flex-wrap gap-6">
                                    <div>
                                        <label class="block text-white mb-2"
                                            >Ordre</label
                                        >
                                        <input
                                            v-model="form.order"
                                            type="number"
                                            class="bg-gray-800 text-white rounded px-3 py-2 w-24"
                                        />
                                    </div>

                                    <div class="flex items-center">
                                        <label class="flex items-center">
                                            <input
                                                v-model="form.is_published"
                                                type="checkbox"
                                                class="bg-gray-800 text-white rounded mr-2"
                                            />
                                            <span class="text-white"
                                                >Publié</span
                                            >
                                        </label>
                                    </div>
                                </div>

                                <!-- Sélection des tags -->
                                <div>
                                    <div
                                        class="flex justify-between items-center mb-2"
                                    >
                                        <label class="block text-white"
                                            >Tags</label
                                        >
                                        <button
                                            type="button"
                                            @click="showTagForm = !showTagForm"
                                            class="text-sm text-led-green hover:text-gaming-red"
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
                                        class="mb-4 bg-gray-800/50 p-3 rounded"
                                    >
                                        <div class="flex gap-3">
                                            <div class="flex-grow">
                                                <input
                                                    v-model="tagForm.name"
                                                    type="text"
                                                    placeholder="Nom du tag"
                                                    class="w-full bg-gray-800 text-white rounded px-3 py-2 text-sm"
                                                />
                                            </div>
                                            <button
                                                type="button"
                                                @click="submitTag"
                                                class="bg-led-green text-white px-3 py-1 rounded text-sm"
                                            >
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
                                    <div class="bg-gray-800 p-3 rounded">
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
                                                    class="flex items-center bg-gray-700 rounded-md p-1 cursor-pointer hover:bg-gray-600"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        :value="tag.id"
                                                        v-model="form.tags"
                                                        class="mr-1"
                                                    />
                                                    <span
                                                        class="text-white text-sm"
                                                        >{{ tag.name }}</span
                                                    >
                                                    <button
                                                        @click.prevent="
                                                            deleteTag(tag)
                                                        "
                                                        class="ml-1 text-red-400 hover:text-red-500"
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
                                    <label class="block text-white mb-2"
                                        >Image mise en avant</label
                                    >
                                    <input
                                        type="file"
                                        @change="handleImageChange"
                                        accept="image/*"
                                        class="w-full bg-gray-800 text-white rounded px-3 py-2"
                                    />
                                    <p
                                        v-if="form.errors.featured_image"
                                        class="text-red-500 text-sm mt-1"
                                    >
                                        {{ form.errors.featured_image }}
                                    </p>
                                </div>

                                <!-- Prévisualisation de l'image -->
                                <div v-if="previewImage" class="relative">
                                    <img
                                        :src="previewImage"
                                        alt="Prévisualisation"
                                        class="max-h-64 rounded border border-gray-700"
                                    />
                                    <button
                                        @click.prevent="removeImage"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1"
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
                                    <label class="block text-white mb-2"
                                        >Contenu</label
                                    >
                                    <div class="rounded">
                                        <textarea id="editor"></textarea>
                                    </div>
                                    <p
                                        v-if="form.errors.content"
                                        class="text-red-500 text-sm mt-1"
                                    >
                                        {{ form.errors.content }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons du formulaire -->
                        <div class="flex gap-4 mt-6">
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

                <!-- Liste des articles -->
                <div class="bg-deep-black p-6 rounded-lg shadow">
                    <h3 class="text-xl text-led-green mb-6">
                        Liste des articles
                    </h3>

                    <div
                        v-if="!posts.length"
                        class="text-white text-center py-4"
                    >
                        Aucun article à afficher pour le moment.
                    </div>

                    <div v-else class="space-y-6">
                        <div
                            v-for="post in posts"
                            :key="post.id"
                            class="border border-gaming-red rounded-lg p-4"
                        >
                            <div class="flex justify-between mb-4">
                                <div>
                                    <h4 class="text-lg text-led-green">
                                        {{ post.title }}
                                    </h4>
                                    <div class="text-sm text-white/60">
                                        {{
                                            formatDate(
                                                post.published_at ||
                                                    post.created_at
                                            )
                                        }}
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <span
                                        v-if="!post.is_published"
                                        class="bg-gray-600 text-white px-2 py-0.5 text-xs rounded"
                                    >
                                        Brouillon
                                    </span>
                                    <span
                                        v-else-if="
                                            post.published_at &&
                                            new Date(post.published_at) >
                                                new Date()
                                        "
                                        class="bg-yellow-600 text-white px-2 py-0.5 text-xs rounded"
                                    >
                                        Programmé
                                    </span>
                                    <span
                                        v-else
                                        class="bg-green-600 text-white px-2 py-0.5 text-xs rounded"
                                    >
                                        Publié
                                    </span>
                                </div>
                            </div>

                            <div
                                class="flex md:flex-row flex-col md:items-center gap-4"
                            >
                                <!-- Image du post -->
                                <div class="w-20 h-20 flex-shrink-0">
                                    <img
                                        v-if="post.featured_image"
                                        :src="`/storage/${post.featured_image}`"
                                        :alt="post.title"
                                        class="w-full h-full object-cover rounded"
                                    />
                                    <div
                                        v-else
                                        class="w-full h-full bg-gray-800 flex items-center justify-center rounded"
                                    >
                                        <span class="text-gray-500 text-xs"
                                            >Pas d'image</span
                                        >
                                    </div>
                                </div>

                                <!-- Tags -->
                                <div class="flex-grow">
                                    <div
                                        v-if="post.tags.length"
                                        class="flex flex-wrap gap-2 mb-2"
                                    >
                                        <span
                                            v-for="tag in post.tags"
                                            :key="tag.id"
                                            class="text-xs bg-gray-700 text-white px-2 py-0.5 rounded"
                                        >
                                            {{ tag.name }}
                                        </span>
                                    </div>
                                    <p
                                        class="text-white/80 text-sm line-clamp-2"
                                    >
                                        {{
                                            post.excerpt ||
                                            post.content
                                                .replace(/<[^>]*>?/gm, "")
                                                .substring(0, 150)
                                        }}...
                                    </p>
                                </div>

                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    <button
                                        @click="editPost(post)"
                                        class="bg-blue-500 text-white px-3 py-1 rounded"
                                    >
                                        Modifier
                                    </button>
                                    <button
                                        @click="deletePost(post)"
                                        class="bg-red-500 text-white px-3 py-1 rounded"
                                    >
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
</style>
