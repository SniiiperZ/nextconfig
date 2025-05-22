<script setup>
import { ref, computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router, useForm } from "@inertiajs/vue3";
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
    pendingComments: Array,
    approvedComments: Array,
});

const showDeleteModal = ref(false);
const commentToDelete = ref(null);
const isRejection = ref(false);
const searchQuery = ref("");
const sortBy = ref("created_at");
const sortDirection = ref("desc");
const updateMessage = ref("");
const updateStatus = ref(null); // null, 'success' ou 'error'
const activeTab = ref("pending"); // 'pending' ou 'approved'

// Filtrage et tri des commentaires
const filteredPendingComments = computed(() => {
    return filterAndSortComments(props.pendingComments);
});

const filteredApprovedComments = computed(() => {
    return filterAndSortComments(props.approvedComments);
});

// Fonction de filtre et tri générique
function filterAndSortComments(comments) {
    let filtered = [...comments];

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (comment) =>
                comment.name.toLowerCase().includes(query) ||
                comment.email.toLowerCase().includes(query) ||
                comment.content.toLowerCase().includes(query) ||
                comment.blog_post.title.toLowerCase().includes(query)
        );
    }

    filtered.sort((a, b) => {
        let comparison = 0;
        let aValue = a[sortBy.value];
        let bValue = b[sortBy.value];

        // Gérer les dates
        if (sortBy.value === "created_at" || sortBy.value === "updated_at") {
            aValue = aValue ? new Date(aValue) : new Date(0);
            bValue = bValue ? new Date(bValue) : new Date(0);
        }

        // Gérer le tri par titre d'article
        if (sortBy.value === "post_title") {
            aValue = a.blog_post.title;
            bValue = b.blog_post.title;
        }

        if (aValue > bValue) {
            comparison = 1;
        } else if (aValue < bValue) {
            comparison = -1;
        }
        return sortDirection.value === "asc" ? comparison : -comparison;
    });

    return filtered;
}

const toggleSort = (column) => {
    if (sortBy.value === column) {
        sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
    } else {
        sortBy.value = column;
        sortDirection.value = "desc";
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
        hour: "2-digit",
        minute: "2-digit",
    });
};

// Approuver un commentaire
const approveComment = (comment) => {
    router.patch(
        route("admin.comments.approve", comment.id),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                updateStatus.value = "success";
                updateMessage.value = "Commentaire approuvé avec succès !";
                setTimeout(() => {
                    updateStatus.value = null;
                    updateMessage.value = "";
                }, 3000);
            },
        }
    );
};

// Confirmer la suppression d'un commentaire
const confirmDelete = (comment, rejection = false) => {
    commentToDelete.value = comment;
    isRejection.value = rejection;
    showDeleteModal.value = true;
};

// Supprimer/rejeter un commentaire
const deleteComment = () => {
    router.delete(route("admin.comments.destroy", commentToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            showDeleteModal.value = false;
            const action = isRejection.value ? "rejeté" : "supprimé";
            updateStatus.value = "success";
            updateMessage.value = `Commentaire ${action} avec succès !`;
            setTimeout(() => {
                updateStatus.value = null;
                updateMessage.value = "";
                commentToDelete.value = null;
            }, 3000);
        },
    });
};

// Affiche le nombre total de commentaires dans chaque catégorie
const totalPending = computed(() => props.pendingComments.length);
const totalApproved = computed(() => props.approvedComments.length);

// Tronquer le texte pour avoir un aperçu
const truncateText = (text, length = 100) => {
    if (!text) return "";
    return text.length > length ? text.substring(0, length) + "..." : text;
};
</script>

<template>
    <AdminLayout title="Gestion des commentaires">
        <template #header>
            <div
                class="flex flex-col sm:flex-row justify-between items-center gap-4"
            >
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Modération des commentaires
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
                <!-- Onglets de navigation -->
                <div
                    class="flex border-b border-gaming-red mb-6 overflow-x-auto"
                >
                    <button
                        @click="activeTab = 'pending'"
                        :class="[
                            'py-2 px-4 font-medium text-sm focus:outline-none whitespace-nowrap',
                            activeTab === 'pending'
                                ? 'border-b-2 border-gaming-red text-gaming-red'
                                : 'text-white/70 hover:text-white hover:border-gaming-red/50 hover:border-b',
                        ]"
                    >
                        En attente
                        <span
                            class="ml-1 px-1.5 py-0.5 bg-gaming-red text-white text-xs rounded-full"
                            v-if="totalPending > 0"
                        >
                            {{ totalPending }}
                        </span>
                    </button>
                    <button
                        @click="activeTab = 'approved'"
                        :class="[
                            'py-2 px-4 font-medium text-sm focus:outline-none whitespace-nowrap',
                            activeTab === 'approved'
                                ? 'border-b-2 border-led-green text-led-green'
                                : 'text-white/70 hover:text-white hover:border-led-green/50 hover:border-b',
                        ]"
                    >
                        Approuvés
                        <span
                            class="ml-1 px-1.5 py-0.5 bg-led-green text-deep-black text-xs rounded-full"
                            v-if="totalApproved > 0"
                        >
                            {{ totalApproved }}
                        </span>
                    </button>
                </div>

                <!-- Message de succès flash -->
                <div
                    v-if="$page.props.flash.success"
                    class="bg-led-green/20 border border-led-green text-white p-4 rounded mb-6"
                >
                    {{ $page.props.flash.success }}
                </div>

                <!-- Section des commentaires en attente -->
                <div v-if="activeTab === 'pending'">
                    <div
                        v-if="filteredPendingComments.length === 0"
                        class="bg-deep-black p-6 rounded-lg shadow text-center"
                    >
                        <p class="text-white/80">
                            Aucun commentaire en attente trouvé
                        </p>
                    </div>

                    <div v-else class="space-y-4">
                        <!-- En-têtes du tableau - visibles uniquement sur desktop -->
                        <div
                            class="hidden lg:grid bg-gaming-red/20 p-4 rounded-lg mb-4 grid-cols-12 gap-4 items-center font-semibold text-white"
                        >
                            <div
                                class="col-span-2 flex items-center cursor-pointer"
                                @click="toggleSort('name')"
                            >
                                Auteur
                                <ArrowUpIcon
                                    v-if="
                                        sortBy === 'name' &&
                                        sortDirection === 'asc'
                                    "
                                    class="h-4 w-4 ml-1"
                                />
                                <ArrowDownIcon
                                    v-else-if="
                                        sortBy === 'name' &&
                                        sortDirection === 'desc'
                                    "
                                    class="h-4 w-4 ml-1"
                                />
                            </div>
                            <div
                                class="col-span-3 cursor-pointer"
                                @click="toggleSort('post_title')"
                            >
                                Article
                                <ArrowUpIcon
                                    v-if="
                                        sortBy === 'post_title' &&
                                        sortDirection === 'asc'
                                    "
                                    class="h-4 w-4 ml-1 inline"
                                />
                                <ArrowDownIcon
                                    v-else-if="
                                        sortBy === 'post_title' &&
                                        sortDirection === 'desc'
                                    "
                                    class="h-4 w-4 ml-1 inline"
                                />
                            </div>
                            <div
                                class="col-span-4 cursor-pointer"
                                @click="toggleSort('content')"
                            >
                                Commentaire
                                <ArrowUpIcon
                                    v-if="
                                        sortBy === 'content' &&
                                        sortDirection === 'asc'
                                    "
                                    class="h-4 w-4 ml-1 inline"
                                />
                                <ArrowDownIcon
                                    v-else-if="
                                        sortBy === 'content' &&
                                        sortDirection === 'desc'
                                    "
                                    class="h-4 w-4 ml-1 inline"
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
                            <div class="col-span-1 text-center">Actions</div>
                        </div>

                        <!-- Barre de tri mobile visible uniquement sur petit écran -->
                        <div
                            class="lg:hidden bg-gaming-red/20 p-3 rounded-lg mb-4 text-white"
                        >
                            <div class="flex justify-between items-center">
                                <div class="text-sm font-medium">
                                    Trier par:
                                </div>
                                <div class="flex gap-2 flex-wrap">
                                    <button
                                        @click="toggleSort('name')"
                                        class="px-2 py-1 rounded text-sm flex items-center"
                                        :class="{
                                            'bg-led-green text-deep-black':
                                                sortBy === 'name',
                                            'bg-deep-black/30':
                                                sortBy !== 'name',
                                        }"
                                    >
                                        Auteur
                                        <ArrowUpIcon
                                            v-if="
                                                sortBy === 'name' &&
                                                sortDirection === 'asc'
                                            "
                                            class="h-3 w-3 ml-1"
                                        />
                                        <ArrowDownIcon
                                            v-else-if="
                                                sortBy === 'name' &&
                                                sortDirection === 'desc'
                                            "
                                            class="h-3 w-3 ml-1"
                                        />
                                    </button>
                                    <button
                                        @click="toggleSort('post_title')"
                                        class="px-2 py-1 rounded text-sm flex items-center"
                                        :class="{
                                            'bg-led-green text-deep-black':
                                                sortBy === 'post_title',
                                            'bg-deep-black/30':
                                                sortBy !== 'post_title',
                                        }"
                                    >
                                        Article
                                        <ArrowUpIcon
                                            v-if="
                                                sortBy === 'post_title' &&
                                                sortDirection === 'asc'
                                            "
                                            class="h-3 w-3 ml-1"
                                        />
                                        <ArrowDownIcon
                                            v-else-if="
                                                sortBy === 'post_title' &&
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
                                </div>
                            </div>
                        </div>

                        <transition-group
                            name="list"
                            tag="div"
                            class="space-y-4"
                        >
                            <div
                                v-for="comment in filteredPendingComments"
                                :key="comment.id"
                                class="bg-deep-black p-4 sm:p-6 rounded-lg shadow border border-gaming-red/10 hover:border-gaming-red/40 transition-all duration-300"
                            >
                                <!-- Vue mobile/tablette (structure en carte) -->
                                <div class="lg:hidden space-y-3">
                                    <div>
                                        <div
                                            class="flex justify-between items-start"
                                        >
                                            <h4
                                                class="text-base font-play font-semibold text-led-green"
                                            >
                                                {{ comment.name }}
                                            </h4>
                                            <div class="text-white/60 text-xs">
                                                {{
                                                    formatDate(
                                                        comment.created_at
                                                    )
                                                }}
                                            </div>
                                        </div>
                                        <p
                                            class="text-white/60 text-xs mt-1 break-all"
                                        >
                                            {{ comment.email }}
                                        </p>
                                    </div>

                                    <div
                                        class="border-t border-b border-gaming-red/20 py-3"
                                    >
                                        <div class="mb-3">
                                            <Link
                                                :href="
                                                    route(
                                                        'blog.show',
                                                        comment.blog_post.slug
                                                    )
                                                "
                                                class="text-sm text-gaming-red hover:text-white transition-colors duration-200"
                                            >
                                                {{ comment.blog_post.title }}
                                            </Link>
                                        </div>

                                        <p class="text-white/90 text-sm">
                                            {{ comment.content }}
                                        </p>
                                    </div>

                                    <div class="flex justify-center gap-2 pt-2">
                                        <button
                                            @click="approveComment(comment)"
                                            class="bg-led-green hover:bg-led-green/90 text-deep-black px-4 py-2 rounded transition-colors duration-200 flex-1"
                                        >
                                            Approuver
                                        </button>
                                        <button
                                            @click="
                                                confirmDelete(comment, true)
                                            "
                                            class="bg-gaming-red hover:bg-red-600 text-white px-4 py-2 rounded transition-colors duration-200 flex-1"
                                        >
                                            Rejeter
                                        </button>
                                    </div>
                                </div>

                                <!-- Vue desktop (structure en grille) -->
                                <div
                                    class="hidden lg:grid grid-cols-12 gap-4 items-start"
                                >
                                    <div class="col-span-2">
                                        <h4
                                            class="text-base font-play font-semibold text-led-green"
                                        >
                                            {{ comment.name }}
                                        </h4>
                                        <p
                                            class="text-white/60 text-xs mt-1 break-all"
                                        >
                                            {{ comment.email }}
                                        </p>
                                    </div>

                                    <div class="col-span-3">
                                        <Link
                                            :href="
                                                route(
                                                    'blog.show',
                                                    comment.blog_post.slug
                                                )
                                            "
                                            class="text-sm text-gaming-red hover:text-white transition-colors duration-200"
                                        >
                                            {{ comment.blog_post.title }}
                                        </Link>
                                    </div>

                                    <div class="col-span-4 relative">
                                        <p
                                            class="text-white/90 text-sm line-clamp-2"
                                        >
                                            {{ comment.content }}
                                        </p>
                                        <button
                                            v-if="comment.content.length > 120"
                                            class="text-xs text-led-green hover:text-led-green/80 mt-1"
                                            @click="
                                                $event.target.parentNode
                                                    .querySelector('p')
                                                    .classList.toggle(
                                                        'line-clamp-2'
                                                    )
                                            "
                                        >
                                            Voir plus
                                        </button>
                                    </div>

                                    <div
                                        class="col-span-2 text-white/60 text-xs"
                                    >
                                        {{ formatDate(comment.created_at) }}
                                    </div>

                                    <div
                                        class="col-span-1 flex justify-center gap-2"
                                    >
                                        <button
                                            @click="approveComment(comment)"
                                            class="bg-led-green hover:bg-led-green/90 text-deep-black px-3 py-1 rounded transition-colors duration-200"
                                            title="Approuver"
                                        >
                                            ✓
                                        </button>
                                        <button
                                            @click="
                                                confirmDelete(comment, true)
                                            "
                                            class="bg-gaming-red hover:bg-red-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                            title="Rejeter"
                                        >
                                            ✕
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </transition-group>
                    </div>
                </div>

                <!-- Section des commentaires approuvés -->
                <div v-if="activeTab === 'approved'">
                    <div
                        v-if="filteredApprovedComments.length === 0"
                        class="bg-deep-black p-6 rounded-lg shadow text-center"
                    >
                        <p class="text-white/80">
                            Aucun commentaire approuvé trouvé
                        </p>
                    </div>

                    <div v-else class="space-y-4">
                        <!-- En-têtes du tableau - visibles uniquement sur desktop -->
                        <div
                            class="hidden lg:grid bg-gaming-red/20 p-4 rounded-lg mb-4 grid-cols-12 gap-4 items-center font-semibold text-white"
                        >
                            <div
                                class="col-span-2 flex items-center cursor-pointer"
                                @click="toggleSort('name')"
                            >
                                Auteur
                                <ArrowUpIcon
                                    v-if="
                                        sortBy === 'name' &&
                                        sortDirection === 'asc'
                                    "
                                    class="h-4 w-4 ml-1"
                                />
                                <ArrowDownIcon
                                    v-else-if="
                                        sortBy === 'name' &&
                                        sortDirection === 'desc'
                                    "
                                    class="h-4 w-4 ml-1"
                                />
                            </div>
                            <div
                                class="col-span-3 cursor-pointer"
                                @click="toggleSort('post_title')"
                            >
                                Article
                                <ArrowUpIcon
                                    v-if="
                                        sortBy === 'post_title' &&
                                        sortDirection === 'asc'
                                    "
                                    class="h-4 w-4 ml-1 inline"
                                />
                                <ArrowDownIcon
                                    v-else-if="
                                        sortBy === 'post_title' &&
                                        sortDirection === 'desc'
                                    "
                                    class="h-4 w-4 ml-1 inline"
                                />
                            </div>
                            <div
                                class="col-span-4 cursor-pointer"
                                @click="toggleSort('content')"
                            >
                                Commentaire
                                <ArrowUpIcon
                                    v-if="
                                        sortBy === 'content' &&
                                        sortDirection === 'asc'
                                    "
                                    class="h-4 w-4 ml-1 inline"
                                />
                                <ArrowDownIcon
                                    v-else-if="
                                        sortBy === 'content' &&
                                        sortDirection === 'desc'
                                    "
                                    class="h-4 w-4 ml-1 inline"
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
                            <div class="col-span-1 text-center">Actions</div>
                        </div>

                        <!-- Barre de tri mobile visible uniquement sur petit écran -->
                        <div
                            class="lg:hidden bg-gaming-red/20 p-3 rounded-lg mb-4 text-white"
                        >
                            <div class="flex justify-between items-center">
                                <div class="text-sm font-medium">
                                    Trier par:
                                </div>
                                <div class="flex gap-2 flex-wrap">
                                    <button
                                        @click="toggleSort('name')"
                                        class="px-2 py-1 rounded text-sm flex items-center"
                                        :class="{
                                            'bg-led-green text-deep-black':
                                                sortBy === 'name',
                                            'bg-deep-black/30':
                                                sortBy !== 'name',
                                        }"
                                    >
                                        Auteur
                                        <ArrowUpIcon
                                            v-if="
                                                sortBy === 'name' &&
                                                sortDirection === 'asc'
                                            "
                                            class="h-3 w-3 ml-1"
                                        />
                                        <ArrowDownIcon
                                            v-else-if="
                                                sortBy === 'name' &&
                                                sortDirection === 'desc'
                                            "
                                            class="h-3 w-3 ml-1"
                                        />
                                    </button>
                                    <button
                                        @click="toggleSort('post_title')"
                                        class="px-2 py-1 rounded text-sm flex items-center"
                                        :class="{
                                            'bg-led-green text-deep-black':
                                                sortBy === 'post_title',
                                            'bg-deep-black/30':
                                                sortBy !== 'post_title',
                                        }"
                                    >
                                        Article
                                        <ArrowUpIcon
                                            v-if="
                                                sortBy === 'post_title' &&
                                                sortDirection === 'asc'
                                            "
                                            class="h-3 w-3 ml-1"
                                        />
                                        <ArrowDownIcon
                                            v-else-if="
                                                sortBy === 'post_title' &&
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
                                </div>
                            </div>
                        </div>

                        <transition-group
                            name="list"
                            tag="div"
                            class="space-y-4"
                        >
                            <div
                                v-for="comment in filteredApprovedComments"
                                :key="comment.id"
                                class="bg-deep-black p-4 sm:p-6 rounded-lg shadow border border-led-green/10 hover:border-led-green/40 transition-all duration-300"
                            >
                                <!-- Vue mobile/tablette (structure en carte) -->
                                <div class="lg:hidden space-y-3">
                                    <div>
                                        <div
                                            class="flex justify-between items-start"
                                        >
                                            <h4
                                                class="text-base font-play font-semibold text-led-green"
                                            >
                                                {{ comment.name }}
                                            </h4>
                                            <div class="text-white/60 text-xs">
                                                {{
                                                    formatDate(
                                                        comment.created_at
                                                    )
                                                }}
                                            </div>
                                        </div>
                                        <p
                                            class="text-white/60 text-xs mt-1 break-all"
                                        >
                                            {{ comment.email }}
                                        </p>
                                    </div>

                                    <div
                                        class="border-t border-b border-led-green/20 py-3"
                                    >
                                        <div class="mb-3">
                                            <Link
                                                :href="
                                                    route(
                                                        'blog.show',
                                                        comment.blog_post.slug
                                                    )
                                                "
                                                class="text-sm text-gaming-red hover:text-white transition-colors duration-200"
                                            >
                                                {{ comment.blog_post.title }}
                                            </Link>
                                        </div>

                                        <p class="text-white/90 text-sm">
                                            {{ comment.content }}
                                        </p>
                                    </div>

                                    <div class="flex justify-center gap-2 pt-2">
                                        <button
                                            @click="confirmDelete(comment)"
                                            class="bg-gaming-red hover:bg-red-600 text-white px-4 py-2 rounded transition-colors duration-200 w-full sm:w-auto"
                                        >
                                            Supprimer
                                        </button>
                                    </div>
                                </div>

                                <!-- Vue desktop (structure en grille) -->
                                <div
                                    class="hidden lg:grid grid-cols-12 gap-4 items-start"
                                >
                                    <div class="col-span-2">
                                        <h4
                                            class="text-base font-play font-semibold text-led-green"
                                        >
                                            {{ comment.name }}
                                        </h4>
                                        <p
                                            class="text-white/60 text-xs mt-1 break-all"
                                        >
                                            {{ comment.email }}
                                        </p>
                                    </div>

                                    <div class="col-span-3">
                                        <Link
                                            :href="
                                                route(
                                                    'blog.show',
                                                    comment.blog_post.slug
                                                )
                                            "
                                            class="text-sm text-gaming-red hover:text-white transition-colors duration-200"
                                        >
                                            {{ comment.blog_post.title }}
                                        </Link>
                                    </div>

                                    <div class="col-span-4 relative">
                                        <p
                                            class="text-white/90 text-sm line-clamp-2"
                                        >
                                            {{ comment.content }}
                                        </p>
                                        <button
                                            v-if="comment.content.length > 120"
                                            class="text-xs text-led-green hover:text-led-green/80 mt-1"
                                            @click="
                                                $event.target.parentNode
                                                    .querySelector('p')
                                                    .classList.toggle(
                                                        'line-clamp-2'
                                                    )
                                            "
                                        >
                                            Voir plus
                                        </button>
                                    </div>

                                    <div
                                        class="col-span-2 text-white/60 text-xs"
                                    >
                                        {{ formatDate(comment.created_at) }}
                                    </div>

                                    <div class="col-span-1 flex justify-center">
                                        <button
                                            @click="confirmDelete(comment)"
                                            class="bg-gaming-red hover:bg-red-600 text-white px-3 py-1 rounded transition-colors duration-200"
                                            title="Supprimer"
                                        >
                                            ✕
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </transition-group>
                    </div>
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
                                        Confirmer
                                        {{
                                            isRejection
                                                ? "le rejet"
                                                : "la suppression"
                                        }}
                                    </h3>
                                    <p
                                        class="text-white/70 mb-6 text-sm sm:text-base"
                                    >
                                        Êtes-vous sûr de vouloir
                                        {{
                                            isRejection
                                                ? "rejeter"
                                                : "supprimer"
                                        }}
                                        ce commentaire de : <br />
                                        <span
                                            class="font-semibold text-led-green break-words"
                                            >{{ commentToDelete?.name }}</span
                                        >
                                    </p>

                                    <div
                                        class="bg-deep-black/50 border border-gaming-red/20 p-3 rounded mb-4 text-left"
                                    >
                                        <p class="text-white/90 text-sm">
                                            {{ commentToDelete?.content }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex justify-center gap-4 mt-6">
                                    <button
                                        @click="showDeleteModal = false"
                                        class="inline-flex justify-center rounded-md border border-gaming-red bg-deep-black px-3 py-2 sm:px-4 sm:py-2 text-sm sm:text-base text-white hover:bg-gaming-red/10 transition-colors"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        @click="deleteComment"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-gaming-red px-3 py-2 sm:px-4 sm:py-2 text-sm sm:text-base text-white hover:bg-gaming-red/90 transition-colors"
                                    >
                                        {{
                                            isRejection
                                                ? "Rejeter"
                                                : "Supprimer"
                                        }}
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
