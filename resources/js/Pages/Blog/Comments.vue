<script setup>
import { ref, computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import AdminSearchHeader from "@/Components/Admin/AdminSearchHeader.vue";
import AdminSortHeader from "@/Components/Admin/AdminSortHeader.vue";
import ConfirmDeleteModal from "@/Components/Admin/ConfirmDeleteModal.vue";
import StatusToast from "@/Components/Admin/StatusToast.vue";

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

// Définir les colonnes pour le tri
const pendingColumns = [
    { key: "name", label: "Auteur", colSpan: 2 },
    { key: "post_title", label: "Article", colSpan: 3 },
    { key: "content", label: "Commentaire", colSpan: 4 },
    { key: "created_at", label: "Date", colSpan: 2 },
    { key: "actions", label: "Actions", colSpan: 1 },
];

const approvedColumns = [
    { key: "name", label: "Auteur", colSpan: 2 },
    { key: "post_title", label: "Article", colSpan: 3 },
    { key: "content", label: "Commentaire", colSpan: 4 },
    { key: "created_at", label: "Date", colSpan: 2 },
    { key: "actions", label: "Actions", colSpan: 1 },
];

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
            <AdminSearchHeader
                title="Modération des commentaires"
                v-model:searchQuery="searchQuery"
            />
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
                        <AdminSortHeader
                            :sort-by="sortBy"
                            :sort-direction="sortDirection"
                            :columns="pendingColumns"
                            @sort="toggleSort"
                        />

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
                        <AdminSortHeader
                            :sort-by="sortBy"
                            :sort-direction="sortDirection"
                            :columns="approvedColumns"
                            @sort="toggleSort"
                        />

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
        <ConfirmDeleteModal
            :show="showDeleteModal"
            :item-name="
                isRejection
                    ? `Commentaire de ${commentToDelete?.name}`
                    : `Commentaire de ${commentToDelete?.name}`
            "
            :item-label="isRejection ? 'rejeter' : 'supprimer'"
            @close="showDeleteModal = false"
            @confirm="deleteComment"
        >
            <template #default>
                <div
                    class="bg-deep-black/50 border border-gaming-red/20 p-3 rounded mb-4 text-left"
                >
                    <p class="text-white/90 text-sm">
                        {{ commentToDelete?.content }}
                    </p>
                </div>
            </template>
        </ConfirmDeleteModal>

        <!-- Toast de notification -->
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
