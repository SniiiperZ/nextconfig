<script setup>
import { ref } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    projects: Array,
});

const form = useForm({
    title: "",
    description: "",
    specs: "",
    image: null,
    order: 0,
    is_featured: false,
    is_visible: true,
});

const editing = ref(null);
const imagePreview = ref(null);

const submit = () => {
    if (editing.value) {
        form.post(route("admin.portfolio.update", editing.value.id), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                editing.value = null;
                form.reset();
                imagePreview.value = null;
            },
        });
    } else {
        form.post(route("admin.portfolio.store"), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                form.reset();
                imagePreview.value = null;
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
    form.image = null;
    imagePreview.value = project.image_path
        ? `/storage/${project.image_path}`
        : null;
};

const cancelEdit = () => {
    editing.value = null;
    form.reset();
    imagePreview.value = null;
};

const deleteProject = (project) => {
    if (confirm(`Êtes-vous sûr de vouloir supprimer "${project.title}" ?`)) {
        form.delete(route("admin.portfolio.destroy", project.id), {
            preserveScroll: true,
        });
    }
};

const handleImageChange = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    form.image = file;

    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
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
                            </div>

                            <div>
                                <div>
                                    <label class="block text-white mb-2"
                                        >Image</label
                                    >
                                    <input
                                        type="file"
                                        @change="handleImageChange"
                                        accept="image/*"
                                        class="w-full bg-gray-800 text-white rounded px-3 py-2"
                                    />
                                    <p
                                        v-if="form.errors.image"
                                        class="text-red-500 text-sm mt-1"
                                    >
                                        {{ form.errors.image }}
                                    </p>
                                </div>

                                <!-- Aperçu de l'image -->
                                <div v-if="imagePreview" class="mt-4">
                                    <img
                                        :src="imagePreview"
                                        alt="Aperçu"
                                        class="h-40 object-contain rounded"
                                    />
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
                                <div class="w-24 h-24 overflow-hidden rounded">
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
                                        <span class="text-gray-500 text-xs"
                                            >Pas d'image</span
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
                                        Ordre: {{ project.order }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
