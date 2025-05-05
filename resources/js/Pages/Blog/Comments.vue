<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router } from "@inertiajs/vue3";

const props = defineProps({
    pendingComments: Array,
    approvedComments: Array,
});

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
        }
    );
};

// Supprimer/rejeter un commentaire
const deleteComment = (comment, isRejection = false) => {
    const message = isRejection
        ? `Êtes-vous sûr de vouloir rejeter ce commentaire de ${comment.name} ?`
        : `Êtes-vous sûr de vouloir supprimer ce commentaire de ${comment.name} ?`;

    if (confirm(message)) {
        router.delete(route("admin.comments.destroy", comment.id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <AdminLayout title="Gestion des commentaires">
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Modération des commentaires
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Message de succès -->
                <div
                    v-if="$page.props.flash.success"
                    class="bg-led-green/20 border border-led-green text-white p-4 rounded mb-6"
                >
                    {{ $page.props.flash.success }}
                </div>

                <!-- Section des commentaires en attente -->
                <div
                    class="bg-deep-black border border-gaming-red rounded-lg p-6 mb-8"
                >
                    <h3 class="text-xl text-gaming-red mb-6 font-play">
                        Commentaires en attente de modération ({{
                            pendingComments.length
                        }})
                    </h3>

                    <div
                        v-if="pendingComments.length === 0"
                        class="text-white/60 text-center py-4"
                    >
                        Aucun commentaire en attente de modération.
                    </div>

                    <div v-else class="space-y-6">
                        <div
                            v-for="comment in pendingComments"
                            :key="comment.id"
                            class="bg-deep-black/30 border border-gaming-red/30 rounded-lg p-4"
                        >
                            <div class="flex justify-between mb-2">
                                <div>
                                    <span class="text-led-green font-medium">{{
                                        comment.name
                                    }}</span>
                                    <span class="text-white/60 text-sm ml-2"
                                        >({{ comment.email }})</span
                                    >
                                </div>
                                <span class="text-white/60 text-sm">{{
                                    formatDate(comment.created_at)
                                }}</span>
                            </div>

                            <div class="mb-4">
                                <Link
                                    :href="
                                        route(
                                            'blog.show',
                                            comment.blog_post.slug
                                        )
                                    "
                                    class="text-sm text-gaming-red hover:text-white"
                                >
                                    Sur : {{ comment.blog_post.title }}
                                </Link>
                            </div>

                            <p
                                class="text-white/90 bg-deep-black/50 p-3 rounded-lg border border-gaming-red/20 mb-4"
                            >
                                {{ comment.content }}
                            </p>

                            <div class="flex space-x-4">
                                <button
                                    @click="approveComment(comment)"
                                    class="bg-led-green text-deep-black px-3 py-1 rounded hover:bg-opacity-90 transition"
                                >
                                    Approuver
                                </button>
                                <button
                                    @click="deleteComment(comment, true)"
                                    class="bg-gaming-red text-white px-3 py-1 rounded hover:bg-opacity-90 transition"
                                >
                                    Rejeter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section des commentaires approuvés -->
                <div
                    class="bg-deep-black border border-gaming-red rounded-lg p-6"
                >
                    <h3 class="text-xl text-led-green mb-6 font-play">
                        Commentaires approuvés ({{ approvedComments.length }})
                    </h3>

                    <div
                        v-if="approvedComments.length === 0"
                        class="text-white/60 text-center py-4"
                    >
                        Aucun commentaire approuvé.
                    </div>

                    <div v-else class="space-y-4">
                        <div
                            v-for="comment in approvedComments"
                            :key="comment.id"
                            class="bg-deep-black/30 border border-led-green/30 rounded-lg p-4"
                        >
                            <div class="flex justify-between mb-2">
                                <div>
                                    <span class="text-led-green font-medium">{{
                                        comment.name
                                    }}</span>
                                    <span class="text-white/60 text-sm ml-2"
                                        >({{ comment.email }})</span
                                    >
                                </div>
                                <span class="text-white/60 text-sm">{{
                                    formatDate(comment.created_at)
                                }}</span>
                            </div>

                            <div class="mb-2">
                                <Link
                                    :href="
                                        route(
                                            'blog.show',
                                            comment.blog_post.slug
                                        )
                                    "
                                    class="text-sm text-gaming-red hover:text-white"
                                >
                                    Sur : {{ comment.blog_post.title }}
                                </Link>
                            </div>

                            <p class="text-white/90 mb-2">
                                {{ comment.content }}
                            </p>

                            <button
                                @click="deleteComment(comment)"
                                class="text-sm text-gaming-red hover:text-white transition"
                            >
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
